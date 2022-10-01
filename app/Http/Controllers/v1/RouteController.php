<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\AccessControl;
use App\Models\Backend\APIManager;
use App\Models\Backend\CityManager;
use App\Models\Backend\FeaturedRestaurantManager;
use App\Models\Backend\ManagerInfo;
use App\Models\Backend\MenuManager;
use App\Models\Backend\Notification;
use App\Models\Backend\OldUser;
use App\Models\Backend\PageManager;
use App\Models\Backend\RestaurantCategory;
use App\Models\Backend\RestaurantManager;
use App\Models\Backend\RestaurantProperty;
use App\Models\Backend\SMSHistory;
use App\Models\OrderDetails;
use App\Models\Rider;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RouteController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function signin(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            return redirect(url('/dashboard'));
        } else {
            if ($request->session()->has('GR_LOGGED_IN') && $request->session()->get('GR_LOGGED_IN')) {
                return redirect(url('/grocery/dashboard'));
            } else {
                return view('Signin')
                    ->with('id', 0)
                    ->with('title', 'Sign In');
            }
        }
    }

    public function dashboard(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $cityList = CityManager::all();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.vultr.com/v1/account/info",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "API-Key: N7GJPX2C2CUZ33F5S22OKAPZCSG6YDC32JIA"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://gosms.xyz/api/v1/getBalance?username=rafathossain&password=rafat1234",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $smsresponse = curl_exec($curl);
            curl_close($curl);
            $smsresponse = json_decode($smsresponse, true);

            $chargedThisMonth = $response['pending_charges'];
            $remainingBalance = ($response['balance'] + $chargedThisMonth) * (-1);
            $lastPaymentAmount = $response['last_payment_amount'] * (-1);
            $lastPaymentDate = date('d M Y', strtotime($response['last_payment_date']));
            $smsBalance = $smsresponse['balance'];
            $smsBalanceExpiry = date('d M Y', strtotime($smsresponse['balance_expire_date']));

            $todayDate = date('Y-m-d');

            $totalOrders = OrderDetails::select('*')->where('order_date', $todayDate)->orderBy('status', 'DESC')->get();
            $deliveredOrders = OrderDetails::select('*')->where('order_date', $todayDate)->where('status', 'Delivered')->get();
            $cancelledOrders = OrderDetails::select('*')->where('order_date', $todayDate)->where('status', 'Cancelled')->get();

            $totalBill = 0;
            $deliveredBill = 0;
            $cancelledBill = 0;
            $multiRestaurantBill = 0;
            $orderArray = array();
            foreach ($totalOrders as $key => $tOrders) {
                $orderArray[$key] = $tOrders->contact;
                $totalBill += $tOrders->total_bill;
                $multiRestaurantBill += $tOrders->multi_res_fee;
            }

            foreach ($deliveredOrders as $tOrders) {
                $deliveredBill += $tOrders->total_bill;
            }

            foreach ($cancelledOrders as $tOrders) {
                $cancelledBill += $tOrders->total_bill;
            }

            $diskSpace = disk_total_space("/");
            $freeDiskSpace = disk_free_space("/");
            $usedDiskSpace = $diskSpace - $freeDiskSpace;

            return view('Home')
                ->with('id', 0)
                ->with('title', 'Home')
                ->with('cityList', $cityList)
                ->with('remainingCredit', $remainingBalance)
                ->with('chargedThisMonth', $chargedThisMonth)
                ->with('lastPaymentAmount', $lastPaymentAmount)
                ->with('lastPaymentDate', $lastPaymentDate)
                ->with('smsBalance', $smsBalance)
                ->with('smsBalanceExpiry', $smsBalanceExpiry)
                ->with('date', $todayDate)
                ->with('allOrders', $totalOrders)
                ->with('totalOrders', count($totalOrders))
                ->with('deliveredOrders', count($deliveredOrders))
                ->with('cancelledOrders', count($cancelledOrders))
                ->with('totalBill', $totalBill)
                ->with('deliveredBill', $deliveredBill)
                ->with('cancelledBill', $cancelledBill)
                ->with('diskSpace', $diskSpace)
                ->with('freeDiskSpace', $freeDiskSpace)
                ->with('usedDiskSpace', $usedDiskSpace);
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function signout(Request $request)
    {
        $request->session()->flush();
        return redirect(url('/dashboard/signin'));
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

        $item = "Removed/Not Available";

        if (count($get_item) != 0) {
            $item_data = $get_item[0];
            $item = $item_data->item_name;
            $price = $item_data->price;
        }

        return $item . " X " . $itemQuantity . "<br><small><b>Restaurant:</b> " . $restaurant_name . "</small>";
    }

    public function pageManager(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $id = $request->input('id');
                $pageList = PageManager::select('*')->where('page_id', $id)->get();
                if (count($pageList) == 1) {
                    $accessGranted = false;
                    $readWrite = "000";
                    $managerRoleID = $request->session()->get('MANAGER_ROLE');
                    $managerPermissions = AccessControl::select('*')->where('role_id', $managerRoleID)->get();
                    $managerPermissions = explode(',', $managerPermissions[0]->role_permissions);
                    foreach ($managerPermissions as $permission) {
                        $pageID = explode('_', $permission)[0];
                        if ($pageID == $id) {
                            $accessGranted = true;
                            $readWrite = explode('_', $permission)[1];
                            break;
                        }
                    }
                    if ($accessGranted) {
                        if ($request->has('city')) {
                            if ($request->has('restaurant')) {
                                $cityID = $request->input('city');
                                $restaurantID = $request->input('restaurant');
                                return $this->menuManager(
                                    $id,
                                    $pageList[0]->page_title,
                                    $pageList[0]->page_view,
                                    $readWrite,
                                    $cityID,
                                    $restaurantID
                                );
                            } else if ($request->has('type')) {
                                $cityID = $request->input('city');
                                return $this->featureManager(
                                    $id,
                                    $pageList[0]->page_title,
                                    $pageList[0]->page_view,
                                    $readWrite,
                                    $cityID
                                );
                            } else {
                                $cityID = $request->input('city');
                                return $this->restaurantManager(
                                    $id,
                                    $pageList[0]->page_title,
                                    $pageList[0]->page_view,
                                    $readWrite,
                                    $cityID
                                );
                            }
                        } else {
                            $todayDate = date('Y-m-d');
                            if ($request->has('date')) {
                                $todayDate = $request->input('date');
                            } else {
                                $todayDate = "NONE";
                            }
                            return $this->viewController(
                                $id,
                                $pageList[0]->page_title,
                                $pageList[0]->page_view,
                                $readWrite,
                                $todayDate
                            );
                        }
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
                } else {
                    return redirect(url('/dashboard/page/unauthorized'));
                }
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    private function viewController($pageID, $pageTitle, $viewName, $readWrite, $todayDate)
    {
        $cityList = CityManager::all();
        if ($viewName == 'PageManager') {
            $viewList = array();
            $files = File::files(resource_path('views'));
            foreach ($files as $key => $file) {
                $fileName = explode('views', $file);
                $fileName = substr($fileName[count($fileName) - 1], 1);
                $fileName = explode('.', $fileName)[0];
                $viewList[$key] = $fileName;
            }
            $pageList = PageManager::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('viewList', $viewList)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('pageList', $pageList);
        } else if ($viewName == 'AccessControl') {
            $accessList = AccessControl::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('accessList', $accessList);
        } else if ($viewName == 'CityManager') {
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList);
        } else if ($viewName == 'OldUserManager') {
            $oldUsers = OldUser::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('oldUserList', $oldUsers);
        } else if ($viewName == 'UserManager') {
            $allUsers = UserInfo::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('userList', $allUsers);
        } else if ($viewName == 'APIManager') {
            $APIs = APIManager::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('APIList', $APIs);
        } else if ($viewName == 'CategoryManager') {
            $categoryList = RestaurantCategory::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('categoryList', $categoryList);
        } else if ($viewName == 'NotificationManager') {
            $notificationList = Notification::select('*')->orderBy('created_at', 'DESC')->get();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('notificationList', $notificationList);
        } else if ($viewName == 'RiderManager') {
            $riderList = Rider::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('riderList', $riderList);
        } else if ($viewName == 'RiderNotificationManager') {
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList);
        } else if ($viewName == 'SalesReport') {
            $totalOrders = OrderDetails::select('*')->where('order_date', $todayDate)->orderBy('status', 'DESC')->get();
            $deliveredOrders = OrderDetails::select('*')->where('order_date', $todayDate)->where('status', 'Delivered')->get();
            $cancelledOrders = OrderDetails::select('*')->where('order_date', $todayDate)->where('status', 'Cancelled')->get();

            $totalBill = 0;
            $deliveredBill = 0;
            $cancelledBill = 0;
            $multiRestaurantBill = 0;
            $orderArray = array();
            foreach ($totalOrders as $key => $tOrders) {
                $orderArray[$key] = $tOrders->contact;
                $totalBill += $tOrders->total_bill;
                $multiRestaurantBill += $tOrders->multi_res_fee;
            }

            $uniqueOrders = array_unique($orderArray);

            foreach ($deliveredOrders as $tOrders) {
                $deliveredBill += $tOrders->total_bill;
            }

            foreach ($cancelledOrders as $tOrders) {
                $cancelledBill += $tOrders->total_bill;
            }

            $restaurantOrders = array();
            $restaurantIDs = array();
            $index = 0;
            foreach ($totalOrders as $key => $tOrders) {
                $details = $tOrders->item_details;
                $details = explode(",", $details);
                $tempID = array();
                for ($i = 1; $i < count($details); $i++) {
                    $item = $details[$i];
                    $item = explode("_", $item)[0];
                    if (!in_array($item, $tempID)) {
                        array_push($tempID, $item);
                        if (!in_array($item, $restaurantIDs)) {
                            array_push($restaurantIDs, $item);
                            $restaurantInfo = RestaurantManager::select('*')->where('restaurant_id', $item)->get();
                            $restaurantOrders[$index]['id'] = $item;
                            $restaurantOrders[$index]['name'] = $restaurantInfo[0]->restaurant_name;
                            $restaurantOrders[$index]['orders'] = 1;
                            $index++;
                        } else {
                            foreach ($restaurantOrders as $rKey => $tempOrder) {
                                if ($tempOrder['id'] == $item) {
                                    $restaurantOrders[$rKey]['orders'] = $tempOrder['orders'] + 1;
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $mResOrders = ((int) $multiRestaurantBill) / 10;
            $orderLists = array();

            foreach ($totalOrders as $key => $order) {
                $orderLists[$key]['orderID'] = $order->order_id;
                $orderLists[$key]['name'] = $order->receiver_name;
                $orderLists[$key]['contact'] = $order->contact;
                $orderLists[$key]['address'] = $order->to_addr;
                $orderLists[$key]['amount'] = $order->total_bill;
                $orderLists[$key]['status'] = $order->status;
                $orderLists[$key]['created_at'] = date('d-M-Y h:i:s A', strtotime($order->created_at));
                $item_details = $order->item_details;
                $itemParse = "";
                $item_exp = explode(",", $item_details);
                for ($i = 1; $i < count($item_exp); $i++) {
                    if ($i == count($item_exp) - 1) {
                        $itemParse = $itemParse . $this->getItemDetails($item_exp[$i]);
                    } else {
                        $itemParse = $itemParse . $this->getItemDetails($item_exp[$i]) . "<br><br>";
                    }
                }
                $orderLists[$key]['itemDetails'] = $itemParse;
            }

            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('date', $todayDate)
                ->with('allOrders', $orderLists)
                ->with('totalOrders', count($totalOrders))
                ->with('deliveredOrders', count($deliveredOrders))
                ->with('cancelledOrders', count($cancelledOrders))
                ->with('multiRestaurantOrder', $mResOrders)
                ->with('totalBill', $totalBill)
                ->with('deliveredBill', $deliveredBill)
                ->with('cancelledBill', $cancelledBill)
                ->with('uniqueOrders', count($uniqueOrders))
                ->with('restaurantOrders', $restaurantOrders);
        } else if ($viewName == 'UpdatePassword') {
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList);
        } else if ($viewName == 'SendSMS') {
            $riderList = Rider::all();
            $riderContact = "";
            foreach ($riderList as $key => $rider) {
                if ($key == count($riderList) - 1) {
                    $riderContact = $riderContact . $rider->mobile;
                } else {
                    $riderContact = $riderContact . $rider->mobile . ", ";
                }
            }

            $adminList = ManagerInfo::all();
            $adminContact = "";
            foreach ($adminList as $key => $admin) {
                if ($key == count($adminList) - 1) {
                    $adminContact = $adminContact . $admin->mobile;
                } else {
                    $adminContact = $adminContact . $admin->mobile . ", ";
                }
            }

            $smsList = SMSHistory::select('*')->orderBy('id', 'DESC')->get();
            $smsCount = 0;
            foreach ($smsList as $sms) {
                $smsCount += $sms->totalSMS;
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://gosms.xyz/api/v1/getBalance?username=rafathossain&password=rafat1234",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $smsresponse = curl_exec($curl);
            curl_close($curl);
            $smsresponse = json_decode($smsresponse, true);

            $smsBalance = $smsresponse['balance'];
            $smsBalanceExpiry = date('d M Y', strtotime($smsresponse['balance_expire_date']));

            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('riderContact', $riderContact)
                ->with('adminContact', $adminContact)
                ->with('smsHistory', $smsList)
                ->with('smsCount', $smsCount)
                ->with('smsBalance', $smsBalance)
                ->with('smsBalanceExpiry', $smsBalanceExpiry);
        }
    }

    private function restaurantManager($pageID, $pageTitle, $viewName, $readWrite, $cityID)
    {
        $cityList = CityManager::all();
        if ($viewName == 'RestaurantManager') {
            $allRestaurantList = array();
            $cityName = CityManager::select('*')->where('city_id', $cityID)->get();
            $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->get();
            foreach ($restaurantList as $key => $restaurant_temp) {
                $allRestaurantList[$key] = $restaurant_temp;
                $discountProperty = RestaurantProperty::select('*')->where('restaurant_id', $restaurant_temp->restaurant_id)->get();
                $allRestaurantList[$key]['discount'] = $discountProperty[0]->discount_percentage;
            }
            $restaurantCategory = RestaurantCategory::all();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('cityID', $cityID)
                ->with('cityName', $cityName[0]->city_name)
                ->with('restaurantCategory', $restaurantCategory)
                ->with('restaurantList', $allRestaurantList);
        }
    }

    private function menuManager($pageID, $pageTitle, $viewName, $readWrite, $cityID, $restaurantID)
    {
        $cityList = CityManager::all();
        if ($viewName == 'MenuManager') {
            $restaurantDetails = RestaurantManager::select('*')->where('restaurant_id', $restaurantID)->get();
            $restaurantCategory = RestaurantCategory::all();
            $restaurantMenu = MenuManager::select('*')->where('restaurant_id', $restaurantID)->get();
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('cityID', $cityID)
                ->with('restaurantID', $restaurantID)
                ->with('restaurantCategory', $restaurantCategory)
                ->with('restaurantDetails', $restaurantDetails)
                ->with('restaurantMenu', $restaurantMenu);
        }
    }

    private function featureManager($pageID, $pageTitle, $viewName, $readWrite, $cityID)
    {
        $cityList = CityManager::all();
        if ($viewName == 'FeaturedRestaurants') {
            $cityName = CityManager::select('*')->where('city_id', $cityID)->get();
            $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->get();
            $fastFoodFeature = FeaturedRestaurantManager::select('*')
                ->where('city_id', $cityID)
                ->where('category', 'Fast Food')->get();
            $banglaFoodFeature = FeaturedRestaurantManager::select('*')
                ->where('city_id', $cityID)
                ->where('category', 'Bangla')->get();

            $featureList = FeaturedRestaurantManager::select('*')->where('city_id', $cityID)->orderBy('sequence', 'ASC')->get();

            $fastFoodList = array();
            $banglaFoodList = array();
            $fastindex = 0;
            $banglaindex = 0;
            foreach ($restaurantList as $restaurant) {
                if (Str::contains($restaurant->restaurant_category, 'Fast Food')) {
                    $fastFoodList[$fastindex] = $restaurant;
                    if (count($fastFoodList) != 0) {
                        if (Str::contains($fastFoodFeature[0]->featured_restaurants, $restaurant->restaurant_id)) {
                            $fastFoodList[$fastindex]['featured'] = true;
                        } else {
                            $fastFoodList[$fastindex]['featured'] = false;
                        }
                    } else {
                        $fastFoodList[$fastindex]['featured'] = false;
                    }
                    $fastindex += 1;
                }

                if (Str::contains($restaurant->restaurant_category, 'Bangla')) {
                    $banglaFoodList[$banglaindex] = $restaurant;
                    if (count($banglaFoodFeature) != 0) {
                        if (Str::contains($banglaFoodFeature[0]->featured_restaurants, $restaurant->restaurant_id)) {
                            $banglaFoodList[$banglaindex]['featured'] = true;
                        } else {
                            $banglaFoodList[$banglaindex]['featured'] = false;
                        }
                    } else {
                        $banglaFoodList[$banglaindex]['featured'] = false;
                    }
                    $banglaindex += 1;
                }
            }

            $featuredFastFoodRestaurant = array();
            if (count($fastFoodFeature) != 0) {
                $featuredFastFood = explode(',', $fastFoodFeature[0]->featured_restaurants);
                foreach ($featuredFastFood as $key => $fastFood) {
                    $restaurantInfo = RestaurantManager::select('*')->where('restaurant_id', $fastFood)->get();
                    if (count($restaurantInfo) != 0) {
                        $featuredFastFoodRestaurant[$key] = $restaurantInfo[0];
                    }
                }
            }

            $featuredBanglaFoodRestaurant = array();
            if (count($banglaFoodFeature) != 0) {
                $featuredBanglaFood = explode(',', $banglaFoodFeature[0]->featured_restaurants);
                foreach ($featuredBanglaFood as $key => $banglaFood) {
                    $restaurantInfo = RestaurantManager::select('*')->where('restaurant_id', $banglaFood)->get();
                    if (count($restaurantInfo) != 0) {
                        $featuredBanglaFoodRestaurant[$key] = $restaurantInfo[0];
                    }
                }
            }
            return view($viewName)
                ->with('title', $pageTitle)
                ->with('id', $pageID)
                ->with('readWrite', $readWrite)
                ->with('cityList', $cityList)
                ->with('cityID', $cityID)
                ->with('type', 'featured')
                ->with('cityName', $cityName[0]->city_name)
                ->with('restaurantList', $restaurantList)
                ->with('featureList', $featureList)
                ->with('fastfoodtList', $fastFoodList)
                ->with('featuredFastFoodRestaurant', $featuredFastFoodRestaurant)
                ->with('banglafoodtList', $banglaFoodList)
                ->with('featuredBanglaFoodRestaurant', $featuredBanglaFoodRestaurant);
        }
    }

    public function unauthorized()
    {
        return view('Unauthorized')->with('title', 'Unauthorized')->with('id', '0');
    }
}
