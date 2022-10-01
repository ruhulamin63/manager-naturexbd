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
use App\Models\Grocery\Products;
use App\Models\Grocery\Users;
use App\Models\MangoLeads;
use App\Models\MangoOrder;
use App\Models\Rider;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
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

    public function AddProductCreate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'city_Id' => 'required',
                    'restaurant_id' => 'required',
                    'product_name' => 'required',
                    'category_id' => 'required',
                    'product_price' => 'required',
                    'product_type' => 'required',
                    'product_thumbnail' => 'required',
                    'product_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                
                    $input = $request->all();
                    $cityId= $input['city_Id'];
                    $restaurantId= $input['restaurant_id'];
                    // $branchId= $input['branch_id'];
                    $productName= $input['product_name'];
                    $categoryId= $input['category_id'];
                    $price= $input['product_price'];
                    $type= $input['product_type'];
                    $status= $input['product_status'];

                    $imageID = strtoupper(Str::random(10));
                    $extension = request()->product_thumbnail->getClientOriginalExtension();
                    $request->product_thumbnail->storeAs('public/temp', $imageID . '.' . $extension);
                    $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                    Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension; 

                    $data=DB::insert("INSERT INTO restaurant_products (cityID, resId,name,image,category,price,type,status) VALUES ('$cityId', '$restaurantId','$productName','$imageURL','$categoryId','$price','$type','$status');");
                    
                    if($data==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'New Category Added Successfully.'
                        ]);
                    }

                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'product_id' => 'required',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('product_id');
                    $Status = $request->input('status');
                    $update = DB::update("UPDATE restaurant_products SET status= '$Status' WHERE id = '$id';");
                    
                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Status updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function DiscountUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'editProductId' => 'required',
                    'editNewPrice' => 'required',
                    'edit_dis_type' => 'required',
                    'editOldPrice' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('editProductId');
                    $newPrice = $request->input('editNewPrice');
                    $type = $request->input('edit_dis_type');
                    $oldPrice = $request->input('editOldPrice');
                    $update = DB::update("UPDATE restaurant_products SET price= '$newPrice',discount_type= '$type',oldPrice= '$oldPrice' WHERE id = '$id';");
                    
                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Discount Updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function ProdcutInfoUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'editProId' => 'required',
                    'editProductName' => 'required',
                    'edit_restaurant' => 'required',
                    'edit_city' => 'required',
                    'edit_menu' => 'required',
                    'editProduct_type' => 'required',
                    'editPrice' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('editProId');
                    $name = $request->input('editProductName');
                    $restaurnt = $request->input('edit_restaurant');
                    $city = $request->input('edit_city');
                    $menu = $request->input('edit_menu');
                    $type = $request->input('editProduct_type');
                    $price = $request->input('editPrice');

                    $update = DB::update("UPDATE restaurant_products SET name= '$name',resId= '$restaurnt',cityID= '$city',category= '$menu',type= '$type',price= '$price' WHERE id = '$id';");
                    
                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Product Info Updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateImage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    
                    'productImageId' => 'required',
                    'editImage' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $id =$input['productImageId'];
                    

                    $imageID = strtoupper(Str::random(10));
                    $extension = request()->editImage->getClientOriginalExtension();
                    $request->editImage->storeAs('public/temp', $imageID . '.' . $extension);
                    $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                    Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension; 

                    $data=DB::update("UPDATE restaurant_products SET image= '$imageURL' WHERE id = $id;");
                    if($data==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong!'
                         ]);
                    }
                    else{
                        return redirect()->back()->with([
                           'error' => false,
                            'message' => 'New Image added Successfully.'
                        ]);
                    }
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
