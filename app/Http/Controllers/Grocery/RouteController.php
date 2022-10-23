<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\AreaCoverage;
use App\Models\Backend\Notification;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Category;
use App\Models\Grocery\City;
use App\Models\Grocery\Leads;
use App\Models\Grocery\LoginReport;
use App\Models\Grocery\Order;
use App\Models\grocery\ProductMultiImage;
use App\Models\Grocery\Products;
use App\Models\Grocery\Users;
use App\Models\MangoLeads;
use App\Models\MangoOrder;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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


    //=======================Ruhul==========================
//    public function addBlog(Request $request)
//    {
//        if ($this->isLoggedIn($request)) {
//            if ($this->hasPermission($request, 'add_product')) {
//                $category = Category::all();
//                $categoryList = $category->unique('category');
//                $cityList = City::all();
//                return view('Grocery.AddProduct')
//                    ->with('title', 'Products | Grocery')
//                    ->with('date', date('d-M-Y'))
//                    ->with('cityList', $cityList)
//                    ->with('categoryList', $categoryList);
//            } else {
//                return redirect(url('/dashboard/page/unauthorized'));
//            }
//        } else {
//            return redirect(url('/dashboard/signin'));
//        }
//    }

    //=======================Ruhul==========================


    private function getDeliveryCharge($city, $price)
    {
        $deliveryCharge = 0;
        if ($city == "Dhaka") {
            if ($price <= 2499) {
                $deliveryCharge = 78;
            } else if ($price <= 4000) {
                $deliveryCharge = 92;
            } else {
                $deliveryCharge = 110;
            }
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
        }
        return $deliveryCharge;
    }

    public function dashboard(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'home')) {
                $cityList = City::all();
                $todaysOrder[0] = count(DB::table('grocery_orders')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->get());
                $todaysPendingOrder[0] = count(DB::table('grocery_orders')->where('order_status', 'Pending')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->get());
                $todaysDeliverOrder[0] = count(DB::table('grocery_orders')->where('order_status', 'Delivered')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->get());
                $todaysCancelOrder[0] = count(DB::table('grocery_orders')->where('order_status', 'Cancelled')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->get());
                $todaysOrderAmount = DB::table('grocery_orders')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('total_amount');
                $todaysPendingOrderAmount = DB::table('grocery_orders')->where('order_status', 'Pending')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('total_amount');
                $todaysDeliverOrderAmount = DB::table('grocery_orders')->where('order_status', 'Delivered')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('total_amount');
                $todaysCancelOrderAmount = DB::table('grocery_orders')->where('order_status', 'Cancelled')->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('total_amount');

                $admin=DB::table('grocery_admin')->get();
                $totalAdmin=count($admin);
                $user=DB::table('grocery_users')->get();
                $totalUser=count($user);

                return view('Grocery.Home')
                    ->with('title', 'Home | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('todaysOrder', $todaysOrder[0])
                    ->with('todaysPendingOrder', $todaysPendingOrder[0])
                    ->with('todaysDeliverOrder', $todaysDeliverOrder[0])
                    ->with('todaysCancelOrder', $todaysCancelOrder[0])
                    ->with('todaysOrderAmount', $todaysOrderAmount)
                    ->with('todaysPendingOrderAmount', $todaysPendingOrderAmount)
                    ->with('todaysDeliverOrderAmount', $todaysDeliverOrderAmount)
                    ->with('todaysCancelOrderAmount', $todaysCancelOrderAmount)
                    ->with('totalAdmin', $totalAdmin)
                    ->with('totalUser', $totalUser)
                    ->with('cityList', $cityList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function city(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'city')) {
                $cityList = City::all();
                return view('Grocery.City')
                    ->with('title', 'City | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function areaCoverage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'area_coverage')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $cityList = City::all();
                        $areaList = AreaCoverage::select('*')->where('city_id', $request->input('city'))->orderBy('area_name', 'ASC')->get();
                        return view('City.AreaCoverage')
                            ->with('title', 'Area Coverage | Grocery')
                            ->with('date', date('d-M-Y'))
                            ->with('areaList', $areaList)
                            ->with('cityList', $cityList)
                            ->with('cityID', $request->input('city'));
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function category(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'category')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $cityList = City::all();
                        $categoryList = Category::select('*')->where('cityID', $request->input('city'))->get();
                        return view('Grocery.CategoryManager')
                            ->with('title', 'Category | Grocery')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('categoryList', $categoryList)
                            ->with('cityID', $request->input('city'));
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function addCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'add_category')) {
                $cityList = City::all();
                return view('Grocery.AddCategory')
                    ->with('title', 'Category | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function editCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'edit_category')) {
                if ($request->has('id')) {
                    $categoryID = $request->input('id');
                    $categoryDetails = Category::select('*')->where('id', $categoryID)->get();
                    $cityList = City::all();
                    return view('Grocery.EditCategory')
                        ->with('title', 'Category | Grocery')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('categoryID', $categoryID)
                        ->with('categoryDetails', $categoryDetails[0]);
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

    public function addProduct(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'add_product')) {
                $category = Category::all();
                $categoryList = $category->unique('category');
                $cityList = City::all();
                return view('Grocery.AddProduct')
                    ->with('title', 'Products | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('categoryList', $categoryList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }


    public function addSeasonalProduct(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $category = Category::all();
                $categoryList = $category->unique('category');
                $cityList = City::all();
                $seasonalProductList=DB::table('grocery_seasonal_products')->where('status',1)->get();
                return view('Grocery.addSeasonalProduct')
                    ->with('title', 'Products | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('categoryList', $categoryList)
                    ->with('seasonalProductList', $seasonalProductList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addSeasonalCampain(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $category = Category::all();
                $categoryList = $category->unique('category');
                $cityList = City::all();
                $seasonalProductList=DB::table('grocery_seasonal_products')->get();
                return view('Grocery.addSeasonalCampain')
                    ->with('title', 'Products | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addMarketingBanner(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $category = Category::all();
                $categoryList = $category->unique('category');
                $cityList = City::all();
                $seasonalProductList=DB::table('grocery_seasonal_products')->get();
                return view('Grocery.addMarketingBanner')
                    ->with('title', 'Products | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function createMarketingBanner(Request $request)
    {
        if ($this->isLoggedIn($request)) {
                $validator = Validator::make($request->all(), [
                    'banner_title' => 'required|max:200',
                    'banner_image' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {

                    $imageURL = "";

                    $existing = DB::table('grocery_marketing_banners')->where('banner_title', $request->input('banner_title'))->get();

                        if (count($existing) == 0) {

                            if($imageURL == ""){
                                $imageID = strtoupper(Str::random(6));
                                $extension = request()->banner_image->getClientOriginalExtension();
                                $request->banner_image->storeAs('public/temp', $imageID . '.' . $extension);
                                $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                                Storage::disk('grocery_banners')->put($imageID . '.' . $extension, Storage::get($imageURL));
                                Storage::delete($imageURL);
                                $imageURL = '/app/grocery/banners/' . $imageID . '.' . $extension;
                            }

                            $data1=array();
                            //$data1['cityID']= $city->id;
                            $data1['banner_title']= $request->banner_title;
                            $data1['banner_subtitle']= $request->banner_subtitle;
                            $data1['banner_image']=$imageURL;
                            $data1['created_at']=date('Y-m-d H:i:s');

                            // if (in_array($city->id, $cityCoverage)) {
                                $data1['status']='1';
                            // } else {
                            //     $data1['status']='0';
                            // }

                            DB::table('grocery_marketing_banners')->insert($data1);

                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Banner Title already esxists.'
                            ]);
                        }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Banner added successfully.'
                    ]);
                }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addHomepageBanner(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $category = Category::all();
                $categoryList = $category->unique('category');
                $cityList = City::all();
                $seasonalProductList=DB::table('grocery_seasonal_products')->get();
                return view('Grocery.addHomepageBanner')
                    ->with('title', 'Products | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function createHomepageBanner(Request $request)
    {
        //dd($request);
        if ($this->isLoggedIn($request)) {
                $validator = Validator::make($request->all(), [
                    'banner_title' => 'required|max:200',
                    'banner_image' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {

                    $imageURL = "";

                    $existing = DB::table('grocery_homepage_banners')->where('banner_title', $request->input('banner_title'))->get();

                        if (count($existing) == 0) {

                            if($imageURL == ""){
                                $imageID = strtoupper(Str::random(6));
                                $extension = request()->banner_image->getClientOriginalExtension();
                                $request->banner_image->storeAs('public/temp', $imageID . '.' . $extension);
                                $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                                Storage::disk('grocery_homepage_banners')->put($imageID . '.' . $extension, Storage::get($imageURL));
                                Storage::delete($imageURL);
                                $imageURL = '/app/grocery/homepage-banners/' . $imageID . '.' . $extension;
                            }

                            $data1=array();
                            //$data1['cityID']= $city->id;
                            $data1['banner_title']= $request->banner_title;
                            $data1['banner_subtitle']= $request->banner_subtitle;
                            $data1['banner_image']=$imageURL;
                            $data1['created_at']=date('Y-m-d H:i:s');

                            // if (in_array($city->id, $cityCoverage)) {
                                $data1['status']='1';
                            // } else {
                            //     $data1['status']='0';
                            // }

                            DB::table('grocery_homepage_banners')->insert($data1);

                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Banner Title already esxists.'
                            ]);
                        }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Banner added successfully.'
                    ]);
                }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }


    public function products(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'product')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $cityList = City::all();
                        $categoryList = Category::all();
                        $category = array();
                        $checker = array();
                        $index = 0;
                        foreach ($categoryList as $item) {
                            if (!(in_array($item->category, $checker))) {
                                $checker[$index] = $item->category;
                                $category[$index]["category"] = $item->category;
                                $index++;
                            }
                        }
//                        $productList = Products::select('*')
//                            ->where('cityID', $request->input('city'))->get();
                        $productList = Products::orderBy('id', 'desc')->get();
                        $productListImages = ProductMultiImage::orderBy('id', 'desc')->get();

                        return view('Grocery.ProductManager')
                            ->with('title', 'Products | Grocery')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('productList', $productList)
                            ->with('productListImages', $productListImages)
//                            ->with('cityID', $request->input('city'))
                            ->with('cityID', 1)
                            ->with('categoryList', $category);
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function manageSeasonalCampain(Request $request)
    {

        $cityList = City::all();
        $categoryList = Category::all();
        $category = array();
        $checker = array();

        $campainList = DB::select("SELECT * FROM grocery_seasonal_products");

        return view('Grocery.ManageSeasonalCampain')
            ->with('title', 'Products | Grocery')
            ->with('date', date('d-M-Y'))
            ->with('cityList', $cityList)
            ->with('campainList', $campainList)
            ->with('categoryList', $category);
    }

    public function editProduct(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'edit_product')) {
                if ($request->has('id')) {
                    $productID = $request->input('id');
                    $productDetails = Products::select('*')->where('id', $productID)->get();
                    $cityList = City::all();
                    return view('Grocery.EditProduct')
                        ->with('title', 'Products | Grocery')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('productID', $productID)
                        ->with('productDetails', $productDetails[0]);
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

    public function SeasonalCampainEdit(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_product')) {
                if ($request->has('id')) {
                    $campaignID = $request->input('id');
                    $campainDetails = DB::select("SELECT * FROM grocery_seasonal_products WHERE id = '$campaignID'");
                    // $productDetails = Products::select('*')->where('id', $campaignID)->get();
                    $cityList = City::all();
                    return view('Grocery.EditSeasonalCampaign')
                        ->with('title', 'Products | Grocery')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('campaignID', $campaignID)
                        ->with('campainDetails', $campainDetails[0]);
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

    public function orders(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'order')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $cityList = City::all();
                        $orderList = Order::select('*')
                            ->where('city_id', $request->input('city'))->orderBy('created_at', 'DESC')->limit(100)->get();
                        if ($request->has('schedule')) {
                            $orderList = Order::select('*')
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
                                Order::where('id', $item->id)->update([
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
                        return view('Grocery.OrderManager')
                            ->with('title', 'Orders | Grocery')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('orderList', $orderList)
                            ->with('cityID', $request->input('city'));
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function mangoOrders(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                $orderList = MangoOrder::select('*')->orderBy('created_at', 'DESC')->get();
                $cityList = City::all();
                return view('MangoOrder.MangoOrderManager')
                    ->with('title', 'Mango Orders | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('orderList', $orderList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function manageMangoOrders(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_order')) {
                if ($request->has('orderID')) {
                    $orderDetails = MangoOrder::select('*')->where('order_id', $request->input('orderID'))->get();
                    $cityList = City::all();
                    return view('MangoOrder.EditMangoOrder')
                        ->with('title', 'Mango Orders | Grocery')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('orderDetails', $orderDetails);
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

    public function userManager(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'user_manager')) {
                if ($request->has('city')) {
                    $cityList = City::all();
                    $cityName = City::select('*')->where('id', $request->input('city'))->get();
                    $cityName = $cityName[0]->city_name;
//                    $userList = Users::select('*')
//                        ->where('division', $cityName)->get();
//                        ->where('division', $cityName)->get();

                    $userList = Users::select('*')->get();


                    return view('Grocery.UserManager')
                        ->with('title', 'Users | Grocery')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('userList', $userList)
                        ->with('cityID', $request->input('city'));
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

    public function riderManager(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'rider')) {
                if ($request->has('city')) {
                    $cityList = City::all();
                    $riderList = Rider::select('*')
                        ->where('city_id', $request->input('city'))->get();
                    return view('Grocery.RiderManager')
                        ->with('title', 'Rider | Grocery')
                        ->with('date', date('d-M-Y'))
                        ->with('cityList', $cityList)
                        ->with('riderList', $riderList)
                        ->with('cityID', $request->input('city'));
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

    public function adminManager(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'admin')) {
                $cityList = City::all();
                $adminList = Admin::all();
                return view('Grocery.AdminManager')
                    ->with('title', 'Admins | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('adminList', $adminList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function changePassword(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'password_change')) {
                $cityList = City::all();
                return view('Grocery.UpdatePassword')
                    ->with('title', 'Change Password | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function loginLog(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'login_log')) {
                $cityList = City::all();
                $loginLog = LoginReport::select('*')->orderBy('created_at', 'DESC')->get();
                return view('Grocery.LoginLog')
                    ->with('title', 'Login Log | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('loginLog', $loginLog);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function dealerInvoice(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'print_dealer_invoice')) {
                $cityList = City::all();
                return view('Grocery.DealerInvoicePrint')
                    ->with('title', 'Delaer Invoice Print | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function leadsData(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'leads_data')) {
                $cityList = City::all();
                $leadsData = Leads::select('*')->orderBy('status', 'ASC')->get();
                return view('Grocery.LeadsData')
                    ->with('title', 'Leads Data | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('leadsData', $leadsData);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function mangoLeadsData(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mango_leads_data')) {
                $cityList = City::all();
                $leadsData = MangoLeads::select('*')->orderBy('status', 'ASC')->get();
                return view('MangoOrder.MangoLeads')
                    ->with('title', 'Mango Leads Data | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('leadsData', $leadsData);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function createManualOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'create_order')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $allProducts = Products::select('*')
//                            ->where('cityID', $request->input('city'))
                            ->where('status', 'Active')
                            ->get();
                        foreach ($allProducts as $key => $product) {
                            $allProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
                        }
                        $cityList = City::all();
                        return view('Grocery.CreateNewOrder')
                            ->with('title', 'Create New Order | Grocery')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('cityID', $request->input('city'))
                            ->with('productList', $allProducts);
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function editOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'edit_order')) {
                if ($request->has('id') && $request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $allProducts = Products::select('*')
                            ->where('cityID', $request->input('city'))
                            ->where('status', 'Active')
                            ->get();
                        foreach ($allProducts as $key => $product) {
                            $allProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
                        }

                        $orderID = $request->input('id');
                        $orders = Order::select('*')->where('order_id', $orderID)->get();
                        $orderData = $orders[0]->order_data;

                        $customerMobile = substr($orders[0]->contact_number, 3);

                        $cityList = City::all();
                        return view('Grocery.EditExistingOrder')
                            ->with('title', 'Edit Order | Grocery')
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
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function saleAnalysis(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'product_analysis')) {
                if ($request->has('city')) {
                    $cityCheck = City::select('*')->where('id', $request->input('city'))->get();
                    $cityCheck = $cityCheck[0]->city_name;
                    if ($this->hasPermission($request, strtolower($cityCheck))) {
                        $cityID = $request->input('city');
                        $productList = array();
                        $index = 0;
                        $orders = Order::select('*')
                            ->where('city_id', $cityID)
                            ->where('order_status', 'Delivered')
                            ->get();
                        foreach ($orders as $key => $item) {
                            foreach (json_decode($item->order_data, true) as $itemProd) {
                                $productID = $itemProd["a"];
                                $productName = $itemProd["b"];
                                $productPrice = $itemProd["c"];
                                $productQuantity = $itemProd["d"];
                                $productDescription = $itemProd["e"];
                                $productImage = $itemProd["f"];

                                $matched = false;
                                foreach ($productList as $pKey => $pItem) {
                                    if ($pItem["productID"] == $productID) {
                                        $currentQuantity = $productList[$pKey]["productQuantity"];
                                        $productList[$pKey]["productQuantity"] = intval($productQuantity) + intval($currentQuantity);
                                        $matched = true;
                                        break;
                                    }
                                }

                                if (!$matched) {
                                    $productList[$index]["productID"] = $productID;
                                    $productList[$index]["productName"] = $productName;
                                    $productList[$index]["productDescription"] = $productDescription;
                                    $productList[$index]["productImage"] = $productImage;
                                    $productList[$index]["productQuantity"] = $productQuantity;
                                    $index++;
                                }
                            }
                        }

                        for ($i = 0; $i < count($productList); $i++) {
                            for ($j = $i + 1; $j < count($productList); $j++) {
                                if (intval($productList[$j]["productQuantity"]) > intval($productList[$i]["productQuantity"])) {
                                    $temp = $productList[$j];
                                    $productList[$j] = $productList[$i];
                                    $productList[$i] = $temp;
                                }
                            }
                        }

                        $cityList = City::all();
                        return view('Grocery.ProductSaleAnalysis')
                            ->with('title', 'Product Analysis | Grocery')
                            ->with('date', date('d-M-Y'))
                            ->with('cityList', $cityList)
                            ->with('cityID', $request->input('city'))
                            ->with('productList', $productList);
                    } else {
                        return redirect(url('/dashboard/page/unauthorized'));
                    }
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

    public function push_notification(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'push_notification')) {
                $cityList = City::all();
                $notificationList = Notification::select('*')->orderBy('created_at', 'DESC')->get();
                return view('Grocery.NotificationManager')
                    ->with('title', 'Push Notification | Grocery')
                    ->with('cityList', $cityList)
                    ->with('notificationList', $notificationList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function signout(Request $request)
    {
        $request->session()->flush();
        return redirect(url('/dashboard/signin'));
    }

    public function addPromo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                return view('Grocery.addPromo')
                    ->with('title', 'Add Promo | Grocery')
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
                $promoList = DB::select("SELECT * FROM grocery_promo WHERE activeSatus=1;");
                return view('Grocery.managePromo')
                    ->with('title', 'Manage Promo | Grocery')
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

    public function invoiceManage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $invoiceImageList = DB::select("SELECT * FROM grocery_invoice_picture ");

                return view('Grocery.ManageInvoiceImage')
                    ->with('title', 'Add Invoice Image | Grocery')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('invoiceImageList', $invoiceImageList);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
