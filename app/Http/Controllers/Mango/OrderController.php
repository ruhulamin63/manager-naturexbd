<?php

namespace App\Http\Controllers\Mango;

use App\Http\Controllers\Controller;
use App\Models\Backend\SMSHistory;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Users;
use App\Models\MangoOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public static $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    public static $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('GR_LOGGED_IN') && $request->session()->get('GR_LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
        return true;
    }

    private function hasPermission(Request $request, $page)
    {
        if ($request->session()->has('GR_MANAGER_EMAIL')) {
            $email = $request->session()->get('GR_MANAGER_EMAIL');
            $permission = Admin::where('email', $email)->where('permissions', 'LIKE', '%' . $page . '%')->get();
            if (count($permission) == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        // return true;
    }

    private function en2bn($number)
    {
        return str_replace(self::$en, self::$bn, $number);
    }

    private function sendMessage($sendTo, $message)
    {
        $message = urldecode($message);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://gosms.xyz/api/v1/sendSms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'username' => 'rafathossain',
                'password' => 'rafat1234',
                'number' => $sendTo,
                'sms_content' => $message,
                'sms_type' => '1',
                'masking' => 'non-masking'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);

        $newSMSHistory = new SMSHistory();
        $newSMSHistory->campaign = "Mango";
        $newSMSHistory->sendTo = $sendTo;
        $newSMSHistory->message = $message;
        $newSMSHistory->totalSMS = $response['Total Valid Numbers'];
        $newSMSHistory->totalCost = $response['Total Cost'];
        $newSMSHistory->save();
    }

    private function sendNotification($data, $target)
    {
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';

        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAaZVge1s:APA91bFagv6aSs_1uVg-9lBwtnWM_AdBrm9d9cBjPJJm-N2mH1R77iNyF2emPWLf00vDkFXJ-xAU-o6V2l5-9IraWnHxbp9DadIjkzwvhv2yUl9Nz8D4YuDWWEOFCfVhZz67KqEyW5sZ';

        $fields = array();

        $fields['to'] = $target;
        $fields['data'] = $data;

        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);
    }

    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'thana' => 'required',
            'zilla' => 'required',
            'courier' => 'required',
            'quantity' => 'required',
            'delivery_note' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $type = "হাড়িভাংগা";
            $unit = "কেজি";

            $trade_price = "1500";
            $sell_price = "2100";

            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $thana = $request->input('thana');
            $zilla = $request->input('zilla');
            $courier = $request->input('courier');
            $quantity = $request->input('quantity');
            $delivery_note = $request->input('delivery_note');

            $order_id = "KT-M-HAR-" . time() . rand(1000, 9999);

            $quantity = intval($quantity) / 20;

            $newOrder = new MangoOrder();
            $newOrder->order_id = $order_id;
            $newOrder->type = $type;
            $newOrder->name = $name;
            $newOrder->mobile = $mobile;
            $newOrder->thana = $thana;
            $newOrder->zilla = $zilla;
            $newOrder->courier = $courier;
            $newOrder->quantity = $quantity * 20;
            $newOrder->unit = $unit;
            $newOrder->delivery_note = $delivery_note;
            $newOrder->trade_price = $trade_price * $quantity;
            $newOrder->sell_price = $sell_price * $quantity;
            $newOrder->profit = ($sell_price * $quantity) - ($quantity * $trade_price);
            $newOrder->tracking = "-";
            $newOrder->payment_method = "-";
            $newOrder->trx_id = "-";
            $newOrder->payment_status = "Pending";
            $newOrder->order_status = "Pending Confirmation";
            $newOrder->timeline = date('d-M-Y h:i:s A') . "_Order placed.";
            if ($newOrder->save()) {
                $message = "Dear guest, your order has been placed.\n\nMango type: Harivanga\nQuantity: " . $quantity * 20 . " Kg\n" . "Price: " . ($quantity) * $sell_price . "Tk. \n\nThanks for being with Naturex.";
                $this->sendMessage($mobile, $message);

                $exisitng = MangoOrder::select('*')->where('order_id', $order_id)->get();
                $timeline = $exisitng[0]->timeline . "," . date('d-M-Y h:i:s A') . "_Order sms sent to customer.";
                MangoOrder::where('order_id', $order_id)->update(['timeline' => $timeline]);

                $title = "রংপুরের হাড়িভাংগা আমের অর্ডার আইছে " . $this->en2bn($quantity * 20) . " কেজি";
                $message = "তারাতারি ড্যাশবোর্ডে চেক কইরা লন। " . $name . " আমের অর্ডার করছে " . $this->en2bn($quantity * 20) . " কেজি। অর্ডার করছে " . $thana . " থানা, " . $zilla . " জেলা থেইকা।";

                $data_array = array('title' => $title, 'message' => $message);

                $userInfo = Users::select('*')->where('mobile', '+8801722850218')->get();
                $this->sendNotification($data_array, $userInfo[0]->device_token);

                $userInfo = Users::select('*')->where('mobile', '+8801704005054')->get();
                $this->sendNotification($data_array, $userInfo[0]->device_token);

                $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
                $this->sendNotification($data_array, $userInfo[0]->device_token);

                return response()->json([
                    'error' => false,
                    'message' => 'Order Placed.',
                    'order_id' => $order_id
                ]);
            }
        }
    }

    public function getOrderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderID' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $orderID = $request->input('orderID');
            $orderDetails = MangoOrder::select('*')->where('order_id', $orderID)->get();
            if (count($orderDetails) == 1) {
                $paymentStatus = "Pending";
                if ($orderDetails[0]->payment_status == "Pending Verification") {
                    $paymentStatus = "Submitted";
                } else if ($orderDetails[0]->payment_status == "Verified") {
                    $paymentStatus = "Verified";
                } else if ($orderDetails[0]->payment_status == "Declined") {
                    $paymentStatus = "Declined";
                }

                return response()->json([
                    'error' => false,
                    'message' => 'Order Details.',
                    'status' => $paymentStatus,
                    'amount' => $orderDetails[0]->sell_price,
                    'trx_id' => $orderDetails[0]->trx_id,
                    'order_id' => $orderDetails[0]->order_id
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Something went wrong.'
                ]);
            }
        }
    }

    public function mangoPaymentSMS(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                $orderID = $request->input('orderID');
                $orderDetails = MangoOrder::select('*')->where('order_id', $orderID)->get();
                $message = "Dear guest, please follow the link to complete your payment.\n\nhttps://web.naturexbd.com/payment?orderID=" . $orderID;
                $this->sendMessage($orderDetails[0]->mobile, $message);

                $exisitng = MangoOrder::select('*')->where('order_id', $orderID)->get();
                $timeline = $exisitng[0]->timeline . "," . date('d-M-Y h:i:s A') . "_Payment URL has beent sent to customer by " . $request->session()->get('GR_MANAGER_NAME');
                MangoOrder::where('order_id', $orderID)->update(['timeline' => $timeline]);

                return redirect()->back()->with([
                    'error' => false,
                    'message' => "Payment URL sent successfully."
                ]);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateMangoOrders(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                $validator = Validator::make($request->all(), [
                    'orderID' => 'required',
                    'name' => 'required',
                    'mobile' => 'required',
                    'thana' => 'required',
                    'zilla' => 'required',
                    'quantity' => 'required',
                    'delivery_note' => 'required',
                    'payment_method' => 'required',
                    'trx_id' => 'required',
                    'courier' => 'required',
                    'tracking' => 'required',
                    'trade_price' => 'required',
                    'sell_price' => 'required',
                    'delivery_charge' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $trade_price = (intval($request->input('quantity')) / 20) * $request->input('trade_price');
                    $update = MangoOrder::where('order_id', $request->input('orderID'))->update([
                        'name' => $request->input('name'),
                        'mobile' => $request->input('mobile'),
                        'thana' => $request->input('thana'),
                        'zilla' => $request->input('zilla'),
                        'quantity' => $request->input('quantity'),
                        'trade_price' => $trade_price,
                        'sell_price' => $request->input('sell_price'),
                        'delivery_note' => $request->input('delivery_note'),
                        'payment_method' => $request->input('payment_method'),
                        'trx_id' => strtoupper($request->input('trx_id')),
                        'courier' => $request->input('courier'),
                        'tracking' => $request->input('tracking'),
                        'delivery' => $request->input('delivery_charge')
                    ]);

                    if ($update) {
                        $exisitng = MangoOrder::select('*')->where('order_id', $request->input('orderID'))->get();
                        $timeline = $exisitng[0]->timeline . "," . date('d-M-Y h:i:s A') . "_Order details updated by " . $request->session()->get('GR_MANAGER_NAME');
                        MangoOrder::where('order_id', $request->input('orderID'))->update(['timeline' => $timeline]);

                        return redirect()->back()->with([
                            'error' => false,
                            'message' => "Order updated successfully."
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => "Something went wrong."
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateMangoPayment(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                $validator = Validator::make($request->all(), [
                    'orderID' => 'required',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $update = MangoOrder::where('order_id', $request->input('orderID'))->update([
                        'payment_status' => $request->input('status')
                    ]);

                    if ($update) {
                        $exisitng = MangoOrder::select('*')->where('order_id', $request->input('orderID'))->get();
                        $timeline = $exisitng[0]->timeline . "," . date('d-M-Y h:i:s A') . "_Payment status updated (" . $request->input('status') . ") by " . $request->session()->get('GR_MANAGER_NAME');

                        if ($request->input('status') == "Verified") {
                            $message = "Dear guest, your payment has been verified.\n\nWe will update your order status thourgh sms.\n\nThanks for being with Naturex.";
                            $this->sendMessage($exisitng[0]->mobile, $message);

                            $timeline = $timeline . "," . date('d-M-Y h:i:s A') . "_Payment verification message sent to customer.";
                        }

                        MangoOrder::where('order_id', $request->input('orderID'))->update(['timeline' => $timeline]);

                        return response()->json([
                            'error' => false,
                            'message' => "Order updated successfully."
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Something went wrong."
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateMangoOrderStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                $validator = Validator::make($request->all(), [
                    'orderID' => 'required',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $update = MangoOrder::where('order_id', $request->input('orderID'))->update([
                        'order_status' => $request->input('status')
                    ]);

                    if ($update) {
                        $exisitng = MangoOrder::select('*')->where('order_id', $request->input('orderID'))->get();
                        $timeline = $exisitng[0]->timeline . "," . date('d-M-Y h:i:s A') . "_Order status updated (" . $request->input('status') . ") by " . $request->session()->get('GR_MANAGER_NAME');

                        if($request->input('status') == "Delivery Dispatched"){
                            $message = "Dear guest, your order will be delivered through " . $exisitng[0]->courier . ".\n\nTracking ID: " . $exisitng[0]->tracking . "\n\nYou will receive a call from courier when it arrives.";
                            $this->sendMessage($exisitng[0]->mobile, $message);

                            $timeline = $timeline . "," . date('d-M-Y h:i:s A') . "_Courier information (Ref.: " . $exisitng[0]->tracking . ") sent to customer.";
                        }

                        MangoOrder::where('order_id', $request->input('orderID'))->update(['timeline' => $timeline]);

                        return response()->json([
                            'error' => false,
                            'message' => "Order updated successfully."
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Something went wrong."
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function mangoPromoteSMS(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                $validator = Validator::make($request->all(), [
                    'mobile' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $message = "Dear guest, you can place order for Harivanga from Rangpur here: bit.ly/khabo_ami_harivanga\n\n20Kg @ 2100Tk\n\nFor more info, call +8801791865233";
                    $this->sendMessage($request->input('mobile'), $message);

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'SMS Sent Successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
