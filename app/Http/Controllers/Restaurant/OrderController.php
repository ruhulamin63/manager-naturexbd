<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\AreaCoverage;
use App\Models\Backend\SMSHistory;
use App\Models\Grocery\Admin;
use App\Models\Grocery\City;
use App\Models\Grocery\Order;
use App\Restaurant\resOrder;
use App\Models\Grocery\Users;
use App\Models\PromoOrders;
use App\Models\UserDevice;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{

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
        $newSMSHistory->campaign = "Admin Message";
        $newSMSHistory->sendTo = $sendTo;
        $newSMSHistory->message = $message;
        $newSMSHistory->totalSMS = $response['Total Valid Numbers'];
        $newSMSHistory->totalCost = $response['Total Cost'];
        $newSMSHistory->save();
    }

    private function getDeliveryCharge($city, $price)
    {
        $deliveryCharge = 0;
        if ($city == "Dhaka" || $city == "Rangpur") {
            if ($price <= 999) {
                $deliveryCharge = 45;
            } else if ($price <= 1999) {
                $deliveryCharge = 65;
            } else {
                $deliveryCharge = 80;
            }
            // if ($price <= 2499) {
            //     $deliveryCharge = 78;
            // } else if ($price <= 4000) {
            //     $deliveryCharge = 92;
            // } else {
            //     $deliveryCharge = 110;
            // }
            // if ($price >= 1400) {
            //     $deliveryCharge = 7;
            // } else {
            //     if ($price <= 2499) {
            //         $deliveryCharge = 78;
            //     } else if ($price <= 4000) {
            //         $deliveryCharge = 92;
            //     } else {
            //         $deliveryCharge = 110;
            //     }
            // }
        } else {
            if ($price <= 999) {
                $deliveryCharge = 48;
            } else if ($price <= 1999) {
                $deliveryCharge = 58;
            } else if ($price <= 2999) {
                $deliveryCharge = 68;
            } else if ($price <= 4999) {
                $deliveryCharge = 85;
            } else {
                $deliveryCharge = 100;
            }
            // if ($price >= 700) {
            //     $deliveryCharge = 7;
            // } else {
            //     if ($price <= 999) {
            //         $deliveryCharge = 48;
            //     } else if ($price <= 1999) {
            //         $deliveryCharge = 58;
            //     } else if ($price <= 2999) {
            //         $deliveryCharge = 68;
            //     } else if ($price <= 4999) {
            //         $deliveryCharge = 85;
            //     } else {
            //         $deliveryCharge = 100;
            //     }
            // }
        }
        return $deliveryCharge;
    }

    public function calculateDeliveryCharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required',
            'total_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $city = City::select('*')->where('id', $request->input('city_id'))->get();
            $city = $city[0]->city_name;
            $price = $request->input('total_amount');
            $deliveryCharge = $this->getDeliveryCharge($city, $price);

            $areaList = array();

            if ($city == "Dhaka") {
                $areaList[0]["area_name"] = "Adabor";
                $areaList[1]["area_name"] = "Badda";
                $areaList[2]["area_name"] = "Bandar";
                $areaList[3]["area_name"] = "Bangshal";
                $areaList[4]["area_name"] = "Biman Bandar (Airport)";
                $areaList[5]["area_name"] = "Cantonment";
                $areaList[6]["area_name"] = "Chawkbazar";
                $areaList[7]["area_name"] = "Dakshinkhan";
                $areaList[8]["area_name"] = "Darus Salam";
                $areaList[9]["area_name"] = "Demra";
                $areaList[10]["area_name"] = "Dhanmondi";
                $areaList[11]["area_name"] = "Gazipur Sadar";
                $areaList[12]["area_name"] = "Gendaria";
                $areaList[13]["area_name"] = "Gulshan";
                $areaList[14]["area_name"] = "Hazaribagh";
                $areaList[15]["area_name"] = "Jatrabari";
                $areaList[16]["area_name"] = "Kadamtali";
                $areaList[17]["area_name"] = "Kafrul";
                $areaList[18]["area_name"] = "Kalabagan";
                $areaList[19]["area_name"] = "Kamrangirchar";
                $areaList[20]["area_name"] = "Keraniganj";
                $areaList[21]["area_name"] = "Khilgaon";
                $areaList[22]["area_name"] = "Khilkhet";
                $areaList[23]["area_name"] = "Kotwali";
                $areaList[24]["area_name"] = "Lalbagh";
                $areaList[25]["area_name"] = "Mirpur";
                $areaList[26]["area_name"] = "Mohammadpur";
                $areaList[27]["area_name"] = "Motijheel";
                $areaList[28]["area_name"] = "Narayanganj Sadar";
                $areaList[29]["area_name"] = "New Market";
                $areaList[30]["area_name"] = "Pallabi";
                $areaList[31]["area_name"] = "Paltan";
                $areaList[32]["area_name"] = "Ramna";
                $areaList[33]["area_name"] = "Rampura";
                $areaList[34]["area_name"] = "Sabujbagh";
                $areaList[35]["area_name"] = "Savar";
                $areaList[36]["area_name"] = "Shah Ali";
                $areaList[37]["area_name"] = "Shahbagh";
                $areaList[38]["area_name"] = "Sher-e-Bangla Nagar";
                $areaList[39]["area_name"] = "Shyampur";
                $areaList[40]["area_name"] = "Sutrapur";
                $areaList[41]["area_name"] = "Tejgaon";
                $areaList[42]["area_name"] = "Tejgaon Industrial Area";
                $areaList[43]["area_name"] = "Turag";
                $areaList[44]["area_name"] = "Uttara";
                $areaList[45]["area_name"] = "Uttar Khan";
            } else if ($city == "Rangpur") {
                $areaList[0]["area_name"] = "Radhabollov";
                $areaList[1]["area_name"] = "Purboget";
                $areaList[2]["area_name"] = "Medical Pakarmatha";
                $areaList[3]["area_name"] = "Circuit House";
                $areaList[4]["area_name"] = "Pasharipara";
                $areaList[5]["area_name"] = "Dc More";
                $areaList[6]["area_name"] = "Jamtola Mosjid";
                $areaList[7]["area_name"] = "Keranipara";
                $areaList[8]["area_name"] = "Keranipara Chourastar Mor";
                $areaList[9]["area_name"] = "Textile";
                $areaList[10]["area_name"] = "Ideal More";
                $areaList[11]["area_name"] = "Terminal";
                $areaList[12]["area_name"] = "Mulatol Pakar Matha";
                $areaList[13]["area_name"] = "Kamarpara";
                $areaList[14]["area_name"] = "Honumantola";
                $areaList[15]["area_name"] = "Dhap Engneerpara";
                $areaList[16]["area_name"] = "New Engneerpara";
                $areaList[17]["area_name"] = "Munsipara";
                $areaList[18]["area_name"] = "Gomostopara";
                $areaList[19]["area_name"] = "Dewanbari";
                $areaList[20]["area_name"] = "Indrarmor";
                $areaList[21]["area_name"] = "Jummapara";
                $areaList[22]["area_name"] = "Shapla";
                $areaList[23]["area_name"] = "Senpara";
                $areaList[24]["area_name"] = "Guptopara";
                $areaList[25]["area_name"] = "Grand Hotel More";
                $areaList[26]["area_name"] = "Salekpump";
                $areaList[27]["area_name"] = "Kotowali Thana";
                $areaList[28]["area_name"] = "Chartolar Mor";
                $areaList[29]["area_name"] = "Thikadarpara";
                $areaList[30]["area_name"] = "Palpara";
                $areaList[31]["area_name"] = "Bikon More";
                $areaList[32]["area_name"] = "Adorshopara";
                $areaList[33]["area_name"] = "Master Para";
                $areaList[34]["area_name"] = "Hajipara";
                $areaList[35]["area_name"] = "CEO bazar";
                $areaList[36]["area_name"] = "Kamal Kasna";
                $areaList[37]["area_name"] = "Cheakpost";
                $areaList[38]["area_name"] = "Dhap Police Fary";
                $areaList[39]["area_name"] = "islambag";
                $areaList[40]["area_name"] = "popular";
                $areaList[41]["area_name"] = "Shamolilane";
                $areaList[42]["area_name"] = "Dhap Bazar";
            } else if ($city == "Khulna") {
                $city = City::select('*')->where('city_name', 'Khulna')->get();
                $areaLists = AreaCoverage::select('*')->where('city_id', $city[0]->id)->orderBy('area_name', 'ASC')->get();
                foreach ($areaLists as $key => $item) {
                    $areaList[$key]["area_name"] = $item->area_name;
                }
            } else if ($city == "Chattagram") {
                // $areaList[0]["area_name"] = "Halishohor";
                // $areaList[1]["area_name"] = "Bondor Area";
                // $areaList[2]["area_name"] = "Free Port";
                // $areaList[3]["area_name"] = "Noyabazar";
                // $areaList[4]["area_name"] = "Agrabad";
                // $areaList[5]["area_name"] = "Dewanhat";
                // $areaList[6]["area_name"] = "Alonkar";
                // $areaList[7]["area_name"] = "GEC";
                // $areaList[8]["area_name"] = "Khulsi";
                // $areaList[9]["area_name"] = "Muradpur";
            } else if ($city == "Rajshahi") {
                // $areaList[0]["area_name"] = "Halishohor";
                // $areaList[1]["area_name"] = "Bondor Area";
                // $areaList[2]["area_name"] = "Free Port";
                // $areaList[3]["area_name"] = "Noyabazar";
                // $areaList[4]["area_name"] = "Agrabad";
                // $areaList[5]["area_name"] = "Dewanhat";
                // $areaList[6]["area_name"] = "Alonkar";
                // $areaList[7]["area_name"] = "GEC";
                // $areaList[8]["area_name"] = "Khulsi";
                // $areaList[9]["area_name"] = "Muradpur";
            } else if ($city == "Mymensingh") {
                $city = City::select('*')->where('city_name', 'Mymensingh')->get();
                $areaLists = AreaCoverage::select('*')->where('city_id', $city[0]->id)->orderBy('area_name', 'ASC')->get();
                foreach ($areaLists as $key => $item) {
                    $areaList[$key]["area_name"] = $item->area_name;
                }
            } else if ($city == "Barishal") {
                $city = City::select('*')->where('city_name', 'Barishal')->get();
                $areaLists = AreaCoverage::select('*')->where('city_id', $city[0]->id)->orderBy('area_name', 'ASC')->get();
                foreach ($areaLists as $key => $item) {
                    $areaList[$key]["area_name"] = $item->area_name;
                }
            }

            return response()->json([
                'error' => false,
                'message' => 'Calculated delivery charge',
                'deliveryCharge' => $deliveryCharge,
                'areaList' => $areaList,
                'campaign' => false
            ]);
        }
    }

    public function changeSchedule(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'change_schedule')) {
                $orderID = $request->input('order_id');
                $schedule = $request->input('schedule');
                $update = resOrder::where('order_id', $orderID)->update([
                    'scheduled_date' => $schedule
                ]);

                return redirect()->back()->with([
                    'error' => false,
                    'message' => 'Order schedule updated.'
                ]);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_order')) {
                $validator = Validator::make($request->all(), [
                    'order_id' => 'required',
                    'city_id' => 'required',
                    'division' => 'required',
                    'customer_name' => 'required',
                    'customer_mobile' => 'required',
                    'delivery_address' => 'required',
                    'delivery_note' => 'required',
                    'order_data' => 'required',
                    'discount' => 'required',
                    'total_amount' => 'required',
                    'scheduled_date' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityName = City::select('*')->where('id', $request->input('city_id'))->get();
                    $cityName = $cityName[0]->city_name;

                    if (strlen($request->input('customer_mobile')) == 11) {
                        $contact_number = "+88" . $request->input('customer_mobile');
                    } else {
                        $contact_number = $request->input('customer_mobile');
                    }

                    $total_amount = 0;

                    foreach (json_decode($request->input('order_data'), true) as $item) {
                        $productID = $item["a"];
                        $productName = $item["b"];
                        $productPrice = $item["c"];
                        $productQuantity = $item["d"];
                        $productDescription = $item["e"];
                        $productImage = $item["f"];

                        $total_amount += ($productPrice * $productQuantity);
                    }

                    $deliveryCharge = $this->getDeliveryCharge($cityName, $total_amount);

                    $orderID = $request->input('order_id');
                    resOrder::where('order_id', $orderID)->update([
                        'order_data' => $request->input('order_data'),
                        'delivery_note' => $request->input('delivery_note'),
                        'contact_number' => $contact_number,
                        'delivery_address' => $request->input('delivery_address'),
                        'customer_name' => $request->input('customer_name'),
                        'product_total' => $total_amount,
                        'delivery_charge' => $deliveryCharge,
                        'discount' => $request->input('discount'),
                        'total_amount' => $total_amount + $deliveryCharge - $request->input('discount'),
                        'scheduled_date' => $request->input('scheduled_date')
                    ]);

                    $userCheck = Users::select('*')->where('mobile', $contact_number)->get();
                    if (count($userCheck) == 0) {
                        $newUser = new Users();
                        $newUser->name = $request->input('customer_name');
                        $newUser->division = $request->input('division');
                        $newUser->mobile = $contact_number;
                        $newUser->device_token = "Website Order";
                        $newUser->save();
                    }

                    $title = "Admin Message";
                    $message = "Customer order updated. Order ID: #" . $request->input('order_id') . ". City: " . $cityName . "\n\nPlease check the details on dahsboard.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $userInfo = Users::select('*')->where('mobile', '+8801722850218')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801704005054')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    if ($cityName == "Rangpur") {
                        $userInfo = Users::select('*')->where('mobile', '+8801821078274')->get();
                        $this->sendNotification($data_array, $userInfo[0]->device_token);
                    }

                    return response()->json([
                        'error' => false,
                        'message' => 'Your order has been updated. Order ID: #' . $orderID
                    ]);
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function confirmOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'confirm_order')) {
                if ($request->has('id')) {
                    $orderID = $request->input('id');
                    $update = resOrder::where('order_id', $orderID)->update([
                        'order_status' => 'Ongoing'
                    ]);

                    $title = "Order Update #" . $orderID;
                    $message = "Your order has been confirmed and the status is set to ongoing. We will deliver your order within 24 hours.\n\nThanks for being with Naturex.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $usermobile = resOrder::select('*')->where('order_id', $orderID)->get();
                    $usermobile = $usermobile[0]->contact_number;

                    $userInfo = Users::select('*')->where('mobile', $usermobile)->get();
                    if (count($userInfo) == 0) {
                        $userInfo = UserInfo::select('*')->where('user_phone', $usermobile)->get();
                        if (count($userInfo) != 0) {
                            $userID = $userInfo[0]->user_id;
                            $userDevice = UserDevice::select('*')->where('user_id', $userID)->get();
                            if (count($userDevice) != 0) {
                                $this->sendNotification($data_array, $userDevice[0]->device_token);
                            }
                        }
                    } else {
                        $this->sendNotification($data_array, $userInfo[0]->device_token);
                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Order confirmed.'
                    ]);
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function completeOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'confirm_order')) {
                if ($request->has('id')) {
                    $orderID = $request->input('id');
                    $update = resOrder::where('order_id', $orderID)->update([
                        'order_status' => 'Delivered'
                    ]);

                    $title = "Order Update #" . $orderID;
                    $message = "Your order has been delivered.\n\nThanks for being with Naturex.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $usermobile = resOrder::select('*')->where('order_id', $orderID)->get();
                    $usermobile = $usermobile[0]->contact_number;

                    $userInfo = Users::select('*')->where('mobile', $usermobile)->get();
                    if (count($userInfo) == 0) {
                        $userInfo = UserInfo::select('*')->where('user_phone', $usermobile)->get();
                        if (count($userInfo) != 0) {
                            $userID = $userInfo[0]->user_id;
                            $userDevice = UserDevice::select('*')->where('user_id', $userID)->get();
                            if (count($userDevice) != 0) {
                                $this->sendNotification($data_array, $userDevice[0]->device_token);
                            }
                        }
                    } else {
                        $this->sendNotification($data_array, $userInfo[0]->device_token);
                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Order delivered.'
                    ]);
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function pendingOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'confirm_order')) {
                if ($request->has('id')) {
                    $orderID = $request->input('id');
                    $update = resOrder::where('order_id', $orderID)->update([
                        'order_status' => 'Pending'
                    ]);

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Order set to pending.'
                    ]);
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function cancelOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'cancel_order')) {
                if ($request->has('id')) {
                    $orderID = $request->input('id');
                    $remarks = $request->input('remarks');
                    $update = resOrder::where('order_id', $orderID)->update([
                        'order_status' => 'Cancelled',
                        'order_remarks' => $remarks
                    ]);

                    $title = "Order Update #" . $orderID;
                    $message = "Your order has been cancelled. We are sorry for the inconvenience.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $usermobile = resOrder::select('*')->where('order_id', $orderID)->get();
                    $usermobile = $usermobile[0]->contact_number;

                    $userInfo = Users::select('*')->where('mobile', $usermobile)->get();
                    if (count($userInfo) == 0) {
                        $userInfo = UserInfo::select('*')->where('user_phone', $usermobile)->get();
                        if (count($userInfo) != 0) {
                            $userID = $userInfo[0]->user_id;
                            $userDevice = UserDevice::select('*')->where('user_id', $userID)->get();
                            if (count($userDevice) != 0) {
                                $this->sendNotification($data_array, $userDevice[0]->device_token);
                            }
                        }
                    } else {
                        $this->sendNotification($data_array, $userInfo[0]->device_token);
                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Order cancelled.'
                    ]);
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function deleteOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'delete_order')) {
                if ($request->has('id')) {
                    $orderID = $request->input('id');
                    resOrder::where('order_id', $orderID)->delete();

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Order deleted.'
                    ]);
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function applyDiscount(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'apply_discount')) {
                $validator = Validator::make($request->all(), [
                    'order_id' => 'required',
                    'discount' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $orderID = $request->input('order_id');
                    $discount = $request->input('discount');

                    $current = resOrder::select('*')->where('order_id', $orderID)->get();
                    $current = $current[0]->total_amount;

                    $updatedTotal = $current - $discount;

                    $update = resOrder::where('order_id', $orderID)->update(['discount' => $discount, 'total_amount' => $updatedTotal]);
                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Discount applied successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
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

}
