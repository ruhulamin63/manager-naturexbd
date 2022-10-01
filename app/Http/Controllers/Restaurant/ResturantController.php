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


class ResturantController extends Controller
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

    public function create(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'cityId' => 'required',
                    'restaurant_name' => 'required',
                    'res_type' => 'required',
                    'category_coverage' => 'required',
                    'res_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $name = $request->input('restaurant_name');
                    $cityId = $request->input('cityId');
                    $exisitng=DB::select("SELECT * FROM restaurant_restaurantList WHERE name='$name' AND cityID='$cityId' ;");
                    if (count($exisitng) == 0) {
                        $input = $request->all();
                        $category_coverage= $input['category_coverage'];
                        $res_type= $input['res_type'];
                        $res_status= $input['res_status'];

                        $data=DB::insert("INSERT INTO restaurant_restaurantList (cityID, name,type,status) VALUES ('$cityId', '$name','$res_type','$res_status');");
                                                
                        $getResId=DB::table('restaurant_restaurantList')->where('name',$name)->where('cityID',$cityId)->where('type',$res_type)->where('status',$res_status)->orderBy('id','DESC')->first();
                        
                        $resId = $getResId->id;
                        foreach ($category_coverage as $category){
                            $catInserData=DB::insert("INSERT INTO restrurent_to_category (res_id, cat_id) VALUES ('$resId', '$category');");
                        }
                        
                        if($data==null){
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Something went wrong.'
                            ]);
                        }
                        else{
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'Restaurant added successfully.'
                            ]);
                        }
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Restaurant Name already exists.'
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

    public function RestaurantStatusUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
                $validator = Validator::make($request->all(), [
                    'res_id' => 'required',
                    'res_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('res_id');
                    $status = $request->input('res_status');
                    $update = DB::update("UPDATE restaurant_restaurantList SET status= '$status' WHERE id = '$id';");
        
                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Restaurant Status updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function restaurantUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'editRes_id' => 'required',
                    'edit_cityList' => 'required',
                    'editResName' => 'required',
                    'edit_typeList' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $id =$input['editRes_id'];
                    $name =$input['editResName'];
                    $city =$input['edit_cityList'];
                    $type =$input['edit_typeList'];

                    $updated=DB::update("UPDATE restaurant_restaurantList SET cityID= '$city',name= '$name',type= '$type' WHERE id = $id;");
                    if($updated==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Update Successfully.'
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

    public function restaurantCategoryUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'editResCat_id' => 'required',
                    'editCategory_coverage' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $id =$input['editResCat_id'];
                    $category_coverage =$input['editCategory_coverage'];

                    $removed_temp_cart=DB::delete("DELETE FROM restrurent_to_category WHERE res_id = $id");

                    foreach ($category_coverage as $category){
                        $updated=DB::insert("INSERT INTO restrurent_to_category (res_id, cat_id) VALUES ('$id', '$category');");
                    }

                    if($updated==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Update Successfully.'
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

    public function ProductCategoryCreate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'category_name' => 'required',
                    'category_img' => 'required',
                    'category_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                
                    $input = $request->all();
                    $name= $input['category_name'];
                    $status= $input['category_status'];

                    $imageID = strtoupper(Str::random(6));
                    $extension = request()->category_img->getClientOriginalExtension();
                    $request->category_img->storeAs('public/temp', $imageID . '.' . $extension);
                    $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                    Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension; 

                    $data=DB::insert("INSERT INTO restaurant_product_category (category, image, status) VALUES ('$name', '$imageURL','$status');");
                    
                    if($data==null){
                        return redirect('/restaurant/ProductCategory')->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect('/restaurant/ProductCategory')->with([
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

    public function ProductCategoryUpdateStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('id');
                    $Status = $request->input('status');
                    $update = DB::update("UPDATE restaurant_product_category SET status= '$Status' WHERE id = '$id';");
                    
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

    public function RestaurantCategoryCreate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'category_name' => 'required',
                    'category_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                
                    $input = $request->all();
                    $name= $input['category_name'];
                    $status= $input['category_status'];

                    $data=DB::insert("INSERT INTO restaurant_res_category (name, status) VALUES ('$name','$status');");
                    
                    if($data==null){
                        return redirect('/restaurant/RestaurantCategory')->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect('/restaurant/RestaurantCategory')->with([
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

    public function RestaurantCategoryUpdateStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('id');
                    $Status = $request->input('status');
                    $update = DB::update("UPDATE restaurant_res_category SET status= '$Status' WHERE id = '$id';");
                    
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

    public function RestaurantCategorydeleteCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('id');
                    $delete = DB::delete("DELETE FROM restaurant_res_category WHERE id = '$id';");
                    
                    if ($delete) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Delete successfully.'
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

    public function ProductCategoryDeleteCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('id');
                    $delete = DB::delete("DELETE FROM restaurant_product_category WHERE id = '$id';");
                    
                    if ($delete) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Delete successfully.'
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

    public function addProductShowRestaurantList(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {

                    $id = $request->cityId;
                    $restaurantList = DB::select("SELECT * FROM restaurant_restaurantList WHERE cityID = '$id' AND status =1 ORDER BY name ASC");
                    
                    if ($restaurantList) {
                        return response()->json($restaurantList);
                    }else{
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function addProductShowBranchList(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {

                    $id = $request->resId;
                    $data = DB::select("SELECT * FROM restaurant_branch WHERE resId = '$id' AND status =1 ORDER BY branchName ASC");
                    
                    if ($data) {
                        return response()->json($data);
                    }else{
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function PropertyCreate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'res_id' => 'required',
                    'open_time' => 'required',
                    'close_time' => 'required',
                    'address' => 'required',
                    'restaurant_logo' => 'required',
                    'restaurant_cover' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                
                    $input = $request->all();
                    $res_id= $input['res_id'];
                    $open_time= $input['open_time'];
                    $close_time= $input['close_time'];
                    $rating= $input['rating'];
                    $phone_num= $input['phone_num'];
                    $address= $input['address'];
                    $lat= $input['lat'];
                    $lon= $input['lon'];

                    $imageID = strtoupper(Str::random(10));
                    $extension = request()->restaurant_logo->getClientOriginalExtension();
                    $request->restaurant_logo->storeAs('public/temp', $imageID . '.' . $extension);
                    $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                    Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension; 


                    $coverImageID = strtoupper(Str::random(10));
                    $coverExtension = request()->restaurant_cover->getClientOriginalExtension();
                    $request->restaurant_cover->storeAs('public/temp', $coverImageID . '.' . $coverExtension);
                    $coverImageURL = 'public/temp/' . $coverImageID . '.' . $coverExtension;
                    Storage::disk('grocery_products')->put($coverImageID . '.' . $coverExtension, Storage::get($coverImageURL));
                    Storage::delete($coverImageURL);
                    $coverImageURL = '/app/grocery/products/' . $coverImageID . '.' . $coverExtension; 
                    
                    $data=DB::insert("INSERT INTO restaurant_property (resId, opening_time,closing_time,rating,logo,coverImage,phone,address,lat,lon) VALUES ('$res_id','$open_time','$close_time','$rating','$imageURL','$coverImageURL','$phone_num','$address','$lat','$lon');");
                    
                    if($data==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect('/restaurant/RestaurantList')->with([
                            'error' => false,
                            'message' => 'Property Added Successfully.'
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

    public function PropertyDiscountUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'edit_Dis_Res_id' => 'required',
                    'editDis' => 'required',
                    'edit_dis_type' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('edit_Dis_Res_id');
                    $dis = $request->input('editDis');
                    $type = $request->input('edit_dis_type');
                    $update = DB::update("UPDATE restaurant_property SET discount= '$dis',discount_type= '$type' WHERE id = '$id';");
                    
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

    public function PropertyDiscountRemove(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {

                if ($request->has('id')) {
                    $id = $request->input('id');
                    $update = DB::update("UPDATE restaurant_property SET discount= '0',discount_type= '0' WHERE id = '$id';");
                    
                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Discount Removed successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
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

    public function PropertyUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'edit_res_id' => 'required',
                    'edit_open_time' => 'required',
                    'edit_close_time' => 'required',
                    'edit_address' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('edit_res_id');
                    $open = $request->input('edit_open_time');
                    $close = $request->input('edit_close_time');
                    $address = $request->input('edit_address');
                    $phone = $request->input('edit_phone_num');
                    $lat = $request->input('edit_lat');
                    $lon = $request->input('edit_lon');
                    $rating = $request->input('edit_rating');


                    $data=array();
                    $data['opening_time']=$open;
                    $data['closing_time']=$close;
                    $data['rating']=$rating;
                    $data['phone']=$phone;
                    $data['address']=$address;
                    $data['lat']=$lat;
                    $data['lon']=$lon;

                    if($request->edit_restaurant_logo !=""){
                        $imageID = strtoupper(Str::random(6));
                        $extension = request()->edit_restaurant_logo->getClientOriginalExtension();
                        $request->edit_restaurant_logo->storeAs('public/temp', $imageID . '.' . $extension);
                        $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                        Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                        Storage::delete($imageURL);
                        $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;

                        $data['logo']=$imageURL;
                    }

                    if($request->edit_restaurant_cover !=""){
                            
                        $imageID_s1 = strtoupper(Str::random(6));
                        $extension_s1 = request()->edit_restaurant_cover->getClientOriginalExtension();
                        $request->edit_restaurant_cover->storeAs('public/temp', $imageID_s1 . '.' . $extension_s1);
                        $imageURL_s1 = 'public/temp/' . $imageID_s1 . '.' . $extension_s1;
                        Storage::disk('grocery_products')->put($imageID_s1 . '.' . $extension_s1, Storage::get($imageURL_s1));
                        Storage::delete($imageURL_s1);
                        $imageURL_s1 = '/app/grocery/products/' . $imageID_s1 . '.' . $extension_s1;
                        
                        $data['coverImage']=$imageURL_s1;
                    }

                    DB::table('restaurant_property')->where('resId',$id)->update($data);
                    
                    return redirect('/restaurant/RestaurantList')->with([
                        'error' => false,
                        'message' => 'Property Updated Successfully.'
                    ]);
                    
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
