<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\MenuManager;
use App\Models\Backend\RestaurantManager;
use App\Models\OrderDetails;
use App\Models\OrderRaw;
use App\Models\Rider;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
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

    private function sendToRider($data, $target)
    {
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';

        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAtVpBXy4:APA91bE_oBziWEgT7L5bk7ySPkVmC27f4Uq7NddHvyVX5gGfQFdWMAv2YkXVU6xCXqveQTQrdHh9eccxYnVh04om4V22-fKwOZsiq8ccDtA7yRFGhguRyMd9yChcjifVmorA3hHoAs6X';

        $fields = array();

        if (is_array($target)) {
            $fields['registration_ids'] = $target;
        } else {
            $fields['to'] = $target;
        }
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
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }

    private function getItemDetails($items)
    {
        $item_exp_2 = explode("_", $items);
        $restaurantID = $item_exp_2[0];
        $item_id = $item_exp_2[1];
        $itemQuantity = $item_exp_2[2];

        $res_query = RestaurantManager::select('*')->where('restaurant_id', $restaurantID)->get();
        $res_data = $res_query[0];

        $restaurant_name = $res_data->restaurant_name;

        $get_item = MenuManager::select('*')->where('item_id', $item_id)->where('restaurant_id', $restaurantID)->get();

        $item_data = $get_item[0];
        $item = $item_data->item_name;
        $price = $item_data->price;

        return $restaurant_name . "_" . $item . "_" . $price . "_" . $itemQuantity;
    }

    public function placeOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'order' => 'required',
                'info' => 'required',
                'orderNote' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $cart = $request->input('order');
                $info = $request->input('info');

                $orderId = strtoupper(Str::random(6));

                $date = date('Y-m-d');

                $orderRaw = new OrderRaw();
                $orderRaw->order_id = $orderId;
                $orderRaw->orders = $cart;
                $orderRaw->info = $info;
                $orderRaw->status = "Pending";
                $orderRaw->order_date = $date;

                $details = json_decode($cart, true);
                $info = json_decode($info, true);

                $item_details = "RESID_ITEMID_QUANTITY";

                for ($i = 0; $i < count($details); $i++) {
                    $item_details = $item_details . "," . $details[$i]["restaurantID"] . "_" . $details[$i]["itemID"] . "_" . $details[$i]["itemQuantity"];
                }

                $user_id = $info['0']["id"];
                $delivery = $info['0']["delivery"];
                $mres_fee = $info['0']["multi_res_fee"];
                $gift = $info['0']["gift"];
                $total_bill = $info['0']["total_amount"];
                $payment = $info['0']["payment"];
                $name = $info['0']["name"];
                $to_add = $info['0']["to_address"];
                $contact = $info['0']["mobile"];
                $city = $info['0']["selectedCity"];

                $stts = "Pending";

                $orderNote = $request->input('orderNote');

                $newOrderDetails = new OrderDetails();
                $newOrderDetails->user_id = $user_id;
                $newOrderDetails->order_id = $orderId;
                $newOrderDetails->item_details = $item_details;
                $newOrderDetails->delivery_fee = $delivery;
                $newOrderDetails->multi_res_fee = $mres_fee;
                $newOrderDetails->total_bill = $total_bill;
                $newOrderDetails->type = $gift;
                $newOrderDetails->payment = $payment;
                $newOrderDetails->receiver_name = $name;
                $newOrderDetails->to_addr = $to_add;
                $newOrderDetails->city = $city;
                $newOrderDetails->contact = $contact;
                $newOrderDetails->status = $stts;
                $newOrderDetails->rider_id = '';
                $newOrderDetails->order_date = $date;
                $newOrderDetails->orderNote = $orderNote;

                $device_token = UserDevice::select('*')->where('user_id', $user_id)->get();
                $device_token = $device_token[0]->device_token;

                $title = "Order Update #" . $orderId;
                $message = "Your order has been placed successfully. Sit back and relax. Our rider will call you soon.";

                $data_array = array('title' => $title, 'message' => $message);

                $this->sendNotification($data_array, $device_token);

                $riderInfo = Rider::select('*')->where('mobile', '01821078274')->get();
                if (count($riderInfo) == 1) {
                    $token = $riderInfo[0]->device_token;
                    $title = "New order received!";
                    $message = "Tap to view details.";

                    $siren = "ON";

                    $data_array = array('title' => $title, 'message' => $message, 'siren' => $siren);
                    $this->sendToRider($data_array, $token);
                }

                if ($orderRaw->save() && $newOrderDetails->save()) {
                    return response()->json([
                        'error' => false,
                        'code' => "KTS100",
                        'order_id' => $orderId,
                        'message' => 'Your order has been placed successfully. Sit back and relax. Our rider will call you soon.'
                    ]);
                } else {
                    return response()->json([
                        'error' => false,
                        'code' => "KTS200",
                        'message' => 'Your order has been placed successfully. Sit back and relax. Our rider will call you soon.'
                    ]);
                }
            }
        }
    }

    public function getOrderList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $userID = $request->input('user_id');
            $orderList = OrderDetails::select('*')->where('user_id', $userID)->orderBy('created_at', 'DESC')->get();

            $order = array();

            foreach ($orderList as $key => $orderDetails) {
                $order[$key]['order_id'] = $orderDetails->order_id;
                $order[$key]['order_type'] = $orderDetails->type;
                $order[$key]['order_status'] = $orderDetails->status;
                $order[$key]['order_amount'] = $orderDetails->total_bill;

                $rider_id = $orderDetails->rider_id;

                $order[$key]['riderName'] = "";
                $order[$key]['riderNumber'] = "";

                if ($rider_id != "") {
                    $riderInfo = Rider::select('*')->where('rider_id', $rider_id)->get();
                    if (count($riderInfo) == 1) {
                        $order[$key]['riderName'] = $riderInfo[0]->name;
                        $order[$key]['riderNumber'] = $riderInfo[0]->mobile;
                    }
                }
            }

            return response()->json([
                'error' => false,
                'message' => $order
            ]);
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
                'message' => 'Data validation failed!'
            ]);
        } else {
            $order_id = $request->input('orderID');

            $orderDetails = OrderDetails::select('*')->where('order_id', $order_id)->get();

            $order = array();

            $item_details = "";

            foreach ($orderDetails as $key => $details) {
                $order[$key]['item_details'] = $details->item_details;
                $order[$key]['delivery_fee'] = number_format((float) $details->delivery_fee, 2, '.', '');
                $order[$key]['mres_fee'] = $details->multi_res_fee;
                $order[$key]['total_bill'] = $details->total_bill;
                $order[$key]['type'] = $details->type;
                $order[$key]['payment'] = $details->payment;
                $order[$key]['receiver'] = $details->receiver_name;
                $order[$key]['toAddr'] = $details->to_addr;
                $order[$key]['contact'] = $details->contact;
                $order[$key]['order_note'] = $details->orderNote;
                $order[$key]['status'] = $details->status;
                $item_details = $details->item_details;
            }

            $itemParse = "";

            $item_exp = explode(",", $item_details);

            for ($i = 1; $i < count($item_exp); $i++) {
                if ($i == count($item_exp) - 1) {
                    $itemParse = $itemParse . $this->getItemDetails($item_exp[$i]);
                } else {
                    $itemParse = $itemParse . $this->getItemDetails($item_exp[$i]) . ":";
                }
            }

            $order[0]['item_details'] = $itemParse;

            return response()->json([
                'error' => false,
                'message' => $order
            ]);
        }
    }

    public function getAllOrderList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'riderID' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $date = date('Y-m-d');
            $riderID = $request->input('riderID');
            $orderList = OrderDetails::select('*')->where('created_at', 'LIKE', '%' . $date . '%')->orderBy('created_at', 'DESC')->get();
            if ($riderID == "ODZFCX" || $riderID == "PO7OHA" || $riderID == "MMJCZW") {
                $orderList = OrderDetails::select('*')
                    ->where('rider_id', $riderID)
                    ->where('created_at', 'LIKE', '%' . $date . '%')
                    ->orderBy('created_at', 'DESC')->get();
            }
            // if($riderID != 'WHWCUY' || $riderID != 'SRNI1D' || $riderID != 'KLDTMD'){
            //     $orderList = OrderDetails::select('*')->where('rider_id', $riderID)->where('created_at', 'LIKE', '%' . $date . '%')->orderBy('created_at', 'DESC')->get();
            // }

            $order = array();

            $riderList = Rider::select('*')
                ->where('mobile', '!=', '01704005054')
                ->where('mobile', '!=', '01722850218')
                ->get();

            foreach ($orderList as $key => $orderDetails) {
                $order[$key]['order_id'] = $orderDetails->order_id;
                $order[$key]['receiver_name'] = $orderDetails->receiver_name;
                $order[$key]['order_status'] = $orderDetails->status;
                $order[$key]['order_amount'] = $orderDetails->total_bill;
                $order[$key]['riderID'] = $orderDetails->rider_id;
                $order[$key]['orderTime'] = date('d M Y h:i:s A', strtotime($orderDetails->created_at));
            }

            return response()->json([
                'error' => false,
                'message' => $order,
                'riderList' => $riderList,
                'versionName' => 'beta-1.9'
            ]);
        }
    }

    public function assignRider(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'orderID' => 'required',
                'riderID' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $orderID = $request->input('orderID');
                $riderID = $request->input('riderID');

                OrderDetails::where('order_id', $orderID)->update([
                    'rider_id' => $riderID,
                    'status' => 'Ongoing'
                ]);

                $userInfo = OrderDetails::select('*')->where('order_id', $orderID)->get();
                if (count($userInfo) == 1) {
                    $riderInfo = Rider::select('*')->where('rider_id', $riderID)->get();
                    $device_token = UserDevice::select('*')->where('user_id', $userInfo[0]->user_id)->get();
                    $device_token = $device_token[0]->device_token;

                    $title = "Order Update #" . $orderID;
                    $message = "Your order has been assigned to " . $riderInfo[0]->name . ". Rider is on the way to restaurant.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $this->sendNotification($data_array, $device_token);

                    if (count($riderInfo) == 1) {
                        $token = $riderInfo[0]->device_token;
                        $title = "New order received!";
                        $message = "Tap to view details.";

                        $siren = "ON";

                        $data_array = array('title' => $title, 'message' => $message, 'siren' => $siren);
                        $this->sendToRider($data_array, $token);
                    }
                }

                return response()->json([
                    'error' => false,
                    'message' => 'Assigned!'
                ]);
            }
        }
    }

    public function cancelOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'orderID' => 'required',
                'riderID' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $orderID = $request->input('orderID');
                $riderID = $request->input('riderID');

                OrderDetails::where('order_id', $orderID)->update([
                    'rider_id' => $riderID,
                    'status' => 'Cancelled'
                ]);

                $userInfo = OrderDetails::select('*')->where('order_id', $orderID)->get();
                if (count($userInfo) == 1) {
                    $device_token = UserDevice::select('*')->where('user_id', $userInfo[0]->user_id)->get();
                    $device_token = $device_token[0]->device_token;

                    $title = "Order Update #" . $orderID;
                    $message = "Your order has been cancelled.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $this->sendNotification($data_array, $device_token);
                }

                return response()->json([
                    'error' => false,
                    'message' => 'Cancelled!'
                ]);
            }
        }
    }

    public function markDelivered(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'orderID' => 'required',
                'riderID' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $orderID = $request->input('orderID');

                OrderDetails::where('order_id', $orderID)->update([
                    'status' => 'Delivered'
                ]);

                $userInfo = OrderDetails::select('*')->where('order_id', $orderID)->get();
                if (count($userInfo) == 1) {
                    $device_token = UserDevice::select('*')->where('user_id', $userInfo[0]->user_id)->get();
                    $device_token = $device_token[0]->device_token;

                    $title = "Order Update #" . $orderID;
                    $message = "Your order has been delivered. Enjoy your meal.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $this->sendNotification($data_array, $device_token);
                }

                return response()->json([
                    'error' => false,
                    'message' => 'Delivered!'
                ]);
            }
        }
    }
}
