<?php

namespace App\Http\Controllers\Restaurant;


use App\Http\Controllers\Controller;
use App\Models\AreaCoverage;
use App\Models\Backend\Notification;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Category;
use App\Models\Grocery\City;
use App\Models\Grocery\Leads;
use App\Models\Grocery\LoginReport;
use App\Models\Grocery\Order;
use App\Restaurant\resOrder;
use App\Restaurant\resProducts;
use App\Models\Grocery\Products;
use App\Models\Grocery\Users;
use App\Models\MangoLeads;
use App\Models\MangoOrder;
use App\Models\Rider;
use Illuminate\Http\Request;
use DB;

class RouteController extends Controller
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
    
    public function addRestaurant(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $resCategoryList = DB::select("SELECT * FROM  restaurant_res_category Where status = 1 ORDER BY id DESC");
                return view('Restaurant.addRestaurant')
                    ->with('title', 'Add Reastaurant | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('categoryList', $resCategoryList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function RestaurantList(Request $request)
    {
        $cityList = City::all();
        $categoryList = DB::select("SELECT * FROM  restaurant_res_category");
        $restaurantInfo= DB::table('restaurant_restaurantList')->leftJoin('restaurant_property', 'restaurant_restaurantList.id', '=', 'restaurant_property.resId')->orderBy('restaurant_restaurantList.id','desc')->paginate(25);
        $resCategory=DB::select("SELECT * FROM restrurent_to_category WHERE status='1';");

        
        return view('Restaurant.RestaurantList')
            ->with('title', 'Products | Restaurant')
            ->with('date', date('d-M-Y'))
            ->with('cityList', $cityList)
            ->with('categoryList', $categoryList)
            ->with('restaurantInfo', $restaurantInfo)
            ->with('resCategory', $resCategory);
    }

    public function ProductCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $categoryList = DB::select("SELECT * FROM restaurant_product_category ORDER BY id DESC");

                return view('Restaurant.ResProductCategory')
                    ->with('title', 'Product Category | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('categoryList', $categoryList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function RestaurantCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $categoryList = DB::select("SELECT * FROM restaurant_res_category ORDER BY id DESC");

                return view('Restaurant.RestaurantCategory')
                    ->with('title', 'Restaurant Category | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('categoryList', $categoryList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addProduct(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $productCategory = DB::select("SELECT * FROM restaurant_product_category WHERE status=1 ORDER BY id DESC");

                return view('Restaurant.addRestaurantProduct')
                    ->with('title', 'Add Product | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('productCategory', $productCategory);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function AllProduct(Request $request)
    {
        $cityList = City::all();
        // $productList = DB::select("SELECT * FROM restaurant_products ORDER BY id DESC");
        $productList = DB::table('restaurant_products')->orderBy('id','DESC')->paginate(25);
        $restaurantList = DB::select("SELECT * FROM restaurant_restaurantList");
        $branchList = DB::select("SELECT * FROM restaurant_branch");
        $productCategory = DB::select("SELECT * FROM restaurant_product_category WHERE status=1 ORDER BY id DESC");

        return view('Restaurant.AllProductList')
            ->with('title', 'All Product | Restaurant')
            ->with('date', date('d-M-Y'))
            ->with('cityList', $cityList)
            ->with('productList', $productList)
            ->with('restaurantList', $restaurantList)
            ->with('productCategory', $productCategory)
            ->with('branchList', $branchList);
    }

    public function addBranch(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $restaurantList = DB::select("SELECT * FROM restaurant_restaurantList");
                $branchList = DB::select("SELECT * FROM restaurant_branch ORDER BY id DESC");

                return view('Restaurant.AddBranch')
                    ->with('title', 'Add Branch | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('restaurantList', $restaurantList)
                    ->with('branchList', $branchList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addProperty(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_product')) {
                if ($request->has('id')) {
                    $restaurantID = base64_decode($request->input('id'));
                    $cityList = City::all();

                    $restaurantInfo=DB::table('restaurant_restaurantList')->where('id',$restaurantID)->first();

                    return view('Restaurant.AddProperty')
                        ->with('title', 'Add Property | Restaurant')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('restaurantID', $restaurantID)
                        ->with('restaurantInfo', $restaurantInfo);
                } else {
                    return redirect()->back();
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function editProperty(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_product')) {
                if ($request->has('id')) {
                    $restaurantID = base64_decode($request->input('id'));
                    $cityList = City::all();
                    // $restaurantInfo=DB::table('restaurant_restaurantList')->where('id',$restaurantID)->first();

                    $restaurantInfo = DB::table('restaurant_restaurantList')
                    ->join('restaurant_property', 'restaurant_restaurantList.id', '=', 'restaurant_property.resId')
                    ->select('restaurant_restaurantList.*', 'restaurant_property.*')
                    ->where('restaurant_restaurantList.id',$restaurantID)
                    ->first();

                    return view('Restaurant.EditProperty')
                        ->with('title', 'Edit Property | Restaurant')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('restaurantID', $restaurantID)
                        ->with('restaurantInfo', $restaurantInfo);
                } else {
                    return redirect()->back();
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addPromo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                return view('Restaurant.addPromo')
                    ->with('title', 'Add Promo | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function managePromo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'city')) {
                $cityList = City::all();
                $promoList = DB::select("SELECT * FROM restaurant_promo WHERE activeSatus=1;");
                return view('Restaurant.managePromo')
                    ->with('title', 'Manage Promo | Restaurant')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('promoList', $promoList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function orders(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'order')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    // if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $cityList = City::all();
                        $orderList = resOrder::select('*')
                            ->where('city_id', $request->input('city'))->orderBy('created_at', 'DESC')->paginate(25);
                        if ($request->has('schedule')) {
                            $orderList = resOrder::select('*')
                                ->where('city_id', $request->input('city'))
                                ->where('scheduled_date', $request->input('schedule'))
                                ->where('order_status', '!=', 'Cancelled')
                                ->orderBy('created_at', 'DESC')->get();
                        }
                        foreach ($orderList as $key => $item) {
                            $city = City::select('*')->where('id', $item->city_id)->get();
                            $city = $city[0]->city_name;
                            $totalPrice = 0;
                            $totalProductPrice = 0;
                            if ($item->product_total == null || $item->product_total == "") {
                                foreach (json_decode($item->order_data, true) as $itemProd) {
                                    $productID = $itemProd["a"];
                                    $productName = $itemProd["b"];
                                    $productPrice = $itemProd["c"];
                                    $productQuantity = $itemProd["d"];
                                    $productDescription = $itemProd["e"];
                                    $productImage = $itemProd["f"];

                                    $totalProductPrice += ($productPrice * $productQuantity);
                                }
                                $deliveryCharge = $this->getDeliveryCharge($city, $totalProductPrice);
                                $totalPrice = $totalProductPrice + $deliveryCharge;
                                resOrder::where('id', $item->id)->update([
                                    'product_total' => $totalProductPrice,
                                    'delivery_charge' => $deliveryCharge,
                                    'discount' => '0',
                                    'total_amount' => $totalPrice
                                ]);
                            }
                            $orderList[$key]['product_total'] = $item->product_total;
                            $orderList[$key]['delivery_charge'] = $item->delivery_charge;
                            $orderList[$key]['discount'] = $item->discount;
                            $orderList[$key]['total_amount'] = $item->total_amount;
                        }
                        return view('Restaurant.OrderManager')
                            ->with('title', 'Orders | Restaurant')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('orderList', $orderList)
                            ->with('cityID', $request->input('city'));
                    // } else {
                    //     return redirect(url('/dashboard/page/unauthorized'));
                    // }
                } else {
                        $cityList = City::all();
                        $orderList = resOrder::select('*')
                            ->orderBy('created_at', 'DESC')->paginate(25);
                        if ($request->has('schedule')) {
                            $orderList = resOrder::select('*')
                                ->where('scheduled_date', $request->input('schedule'))
                                ->where('order_status', '!=', 'Cancelled')
                                ->orderBy('created_at', 'DESC')->get();
                        }
                        foreach ($orderList as $key => $item) {
                            $city = City::select('*')->where('id', $item->city_id)->get();
                            $city = $city[0]->city_name;
                            $totalPrice = 0;
                            $totalProductPrice = 0;
                            if ($item->product_total == null || $item->product_total == "") {
                                foreach (json_decode($item->order_data, true) as $itemProd) {
                                    $productID = $itemProd["a"];
                                    $productName = $itemProd["b"];
                                    $productPrice = $itemProd["c"];
                                    $productQuantity = $itemProd["d"];
                                    $productDescription = $itemProd["e"];
                                    $productImage = $itemProd["f"];

                                    $totalProductPrice += ($productPrice * $productQuantity);
                                }
                                $deliveryCharge = $this->getDeliveryCharge($city, $totalProductPrice);
                                $totalPrice = $totalProductPrice + $deliveryCharge;
                                resOrder::where('id', $item->id)->update([
                                    'product_total' => $totalProductPrice,
                                    'delivery_charge' => $deliveryCharge,
                                    'discount' => '0',
                                    'total_amount' => $totalPrice
                                ]);
                            }
                            $orderList[$key]['product_total'] = $item->product_total;
                            $orderList[$key]['delivery_charge'] = $item->delivery_charge;
                            $orderList[$key]['discount'] = $item->discount;
                            $orderList[$key]['total_amount'] = $item->total_amount;
                        }
                        return view('Restaurant.OrderManager')
                            ->with('title', 'Orders | Restaurant')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('orderList', $orderList)
                            ->with('cityID', $request->input('city'));
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function editOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_order')) {
                if ($request->has('id') && $request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    // if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $allProducts = resProducts::select('*')
                            ->where('cityID', $request->input('city'))
                            ->where('status', 1)
                            ->get();
                        foreach ($allProducts as $key => $product) {
                            $allProducts[$key]['product_thumbnail'] = url($product->image);
                        }
                        
                        // dd($allProducts);

                        $orderID = $request->input('id');
                        $orders = resOrder::select('*')->where('order_id', $orderID)->get();
                        $orderData = $orders[0]->order_data;

                        $customerMobile = substr($orders[0]->contact_number, 3);

                        $cityList = City::all();
                        return view('Restaurant.EditExistingOrder')
                            ->with('title', 'Edit Order | Restaurant')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('cityID', $request->input('city'))
                            ->with('orderData', $orderData)
                            ->with('productList', $allProducts)
                            ->with('discount', $orders[0]->discount)
                            ->with('name', $orders[0]->customer_name)
                            ->with('mobile', $customerMobile)
                            ->with('address', $orders[0]->delivery_address)
                            ->with('note', $orders[0]->delivery_note)
                            ->with('cityName', $orders[0]->city_name)
                            ->with('schedule', $orders[0]->scheduled_date);
                    // } else {
                    //     return redirect(url('/dashboard/page/unauthorized'));
                    // }
                } else {
                    return redirect()->back();
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

}
