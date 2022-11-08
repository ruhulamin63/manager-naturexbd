<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Category;
use App\Models\Grocery\City;
use App\Models\Grocery\ProductMultiImage;
use App\Models\Grocery\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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


    //add image to the storage disk.
    public function uploadImageViaAjax(Request $request)
    {
        $name = [];
        $original_name = [];
        foreach ($request->file('file') as $key => $value) {
            $destinationPath = '/multiple-product-image/images/' . uniqid() . '.' . $value->extension();
            $value->storePubliclyAs('public', $destinationPath);
            $name[] = $destinationPath;
        }

//        dd($name);
        return response()->json(['name' => $name, 'original_name' => $original_name]);
    }

    public function createProduct(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'add_product')) {

//                dd($request->all());

                $validator = Validator::make($request->all(), [
                    'city_coverage' => 'required',
                    'product_name' => 'required',
                    'product_category' => 'required',
                    'trade_price' => 'required',
                    'product_price' => 'required',
                    'product_description' => 'required',
                    'product_type' => 'required',
                    'measuring_unit_new'=>'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityCoverage = $request->input('city_coverage');
                    $cityList = City::all();
//                    dd($cityList[0]->id);

//                    $imageURL = "";
//                    foreach ($cityList as $city) {
                        $existing = Products::select('*')
                            ->where('cityID', $cityList[0]->id)
                            ->where('product_name', $request->input('product_name'))
                            ->get();

//                        dd(count($existing));

                        if (count($existing) == 0) {
//                            if($imageURL == ""){
//                                $imageID = strtoupper(Str::random(6));
//                                $extension = request()->product_thumbnail->getClientOriginalExtension();
//                                $request->product_thumbnail->storeAs('storage/temp', $imageID . '.' . $extension);
//                                $imageURL = 'public/temp/' . $imageID . '.' . $extension;
//                                Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
//                                Storage::delete($imageURL);
//                                $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;
//                            }

                            $newProduct = new Products();

                            if(file($request->product_thumbnail)){
                                $destinationPath = '/base-product/' . uniqid() . '.' . $request->product_thumbnail->extension();
                                $request->product_thumbnail->storePubliclyAs('public', $destinationPath);
                                $newProduct->product_thumbnail = $destinationPath;
                            }

//                            dd($newProduct->product_thumbnail);


                            $newProduct->cityID = $cityList[0]->id;
                            $newProduct->category = $request->input('product_category');
                            $newProduct->product_name = $request->input('product_name');

                            $newProduct->stock = $request->input('stock');

                            $newProduct->url = $request->input('url');
                            $newProduct->short_description = $request->input('short_description');

                            $newProduct->trade_price = $request->input('trade_price');
                            $newProduct->product_price = $request->input('product_price');
                            $newProduct->measuring_unit = "N/A";
                            $newProduct->product_type = $request->input('product_type');

                            $newProduct->meta_title = $request->input('meta_title');
                            $newProduct->meta_description = $request->input('meta_description');
                            $newProduct->meta_keywords = $request->input('meta_keywords');

                            $newProduct->product_description = $request->input('product_description');
                            $newProduct->offer_old_price = $request->input('product_old_price');
//                            $newProduct->product_thumbnail = $imageURL;
                            $newProduct->measuring_unit_new = $request->input('measuring_unit_new');;

                            if (in_array($cityList[0]->id, $cityCoverage)) {
                                $newProduct->status = 'Active';
                            } else {
                                $newProduct->status = 'Inactive';
                            }
                            $newProduct->save();

                            //======================================
//                            $messages = array(
//                                'images.required' => 'Image is Required.'
//                            );
//                            $this->validate($request, array(
//                                'images' => 'required|array|min:1',
//                            ),$messages);


//                            dd($request->images);

                            if($request->images != null){
                                foreach ($request->images as $image) {
                                    $productMultiImage = new ProductMultiImage();
                                    $productMultiImage->product_id = $newProduct->id;
                                    $productMultiImage->image_path = $image;
                                    $productMultiImage->status = 'Active';
                                    $productMultiImage->save();
//                                dd($productMultiImage);
                                }
                            }

                            //======================================
//                            dd('ok');
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Product already esxists under current city.'
                            ]);
                        }
//                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Product added successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function createSeasonalProducts(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'seasonal_offer_id' => 'required',
                    'city_coverage' => 'required',
                    'product_name' => 'required',
                    'product_category' => 'required',
                    'trade_price' => 'required',
                    'product_price' => 'required',
                    'product_description' => 'required',
                    'product_thumbnail' => 'required',
                    'product_type' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityCoverage = $request->input('city_coverage');
                    $cityList = City::all();
                    $imageURL = "";
                    foreach ($cityList as $city) {
                        $existing = Products::select('*')
                            ->where('cityID', $city->id)
                            ->where('product_name', $request->input('product_name'))
                            ->get();
                        if (count($existing) == 0) {

                            if($imageURL == ""){
                                $imageID = strtoupper(Str::random(6));
                                $extension = request()->product_thumbnail->getClientOriginalExtension();
                                $request->product_thumbnail->storeAs('public/temp', $imageID . '.' . $extension);
                                $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                                Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                                Storage::delete($imageURL);
                                $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;
                            }


                            $newProduct = new Products();
                            $newProduct->cityID = $city->id;
                            $newProduct->category = $request->input('product_category');
                            $newProduct->product_name = $request->input('product_name');
                            $newProduct->trade_price = $request->input('trade_price');
                            $newProduct->product_price = $request->input('product_price');
                            $newProduct->measuring_unit = "N/A";
                            $newProduct->product_type = $request->input('product_type');
                            $newProduct->seasonal_id = $request->input('seasonal_offer_id');
                            $newProduct->product_description = $request->input('product_description');
                            $newProduct->product_thumbnail = $imageURL;
                            if (in_array($city->id, $cityCoverage)) {
                                $newProduct->status = 'Active';
                            } else {
                                $newProduct->status = 'Inactive';
                            }
                            $newProduct->save();
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Product already esxists under current city.'
                            ]);
                        }
                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Product added successfully.'
                    ]);
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function createSeasonalProductsCampain(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'campain_title' => 'required',
                    'campain_SubTitle' => 'required',
                    'description' => 'required',
                    'campain_banner' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    //$cityCoverage = $request->input('city_coverage');
                    //$cityList = City::all();
                    $imageURL = "";
                    $imageURL_s1 = "";
                    $imageURL_s2 = "";
                    $imageURL_s3 = "";
                    $imageURL_s4 = "";
                    // foreach ($cityList as $city) {

                        $existing = DB::table('grocery_seasonal_products')->where('title', $request->input('campain_title'))->get();

                        if (count($existing) == 0) {

                            if($imageURL == ""){
                                $imageID = strtoupper(Str::random(6));
                                $extension = request()->campain_banner->getClientOriginalExtension();
                                $request->campain_banner->storeAs('public/temp', $imageID . '.' . $extension);
                                $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                                Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                                Storage::delete($imageURL);
                                $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;
                            }

                            if($request->input('slider1')!=""){
                                if($imageURL_s1 == ""){
                                    $imageID_s1 = strtoupper(Str::random(6));
                                    $extension_s1 = request()->slider1->getClientOriginalExtension();
                                    $request->slider1->storeAs('public/temp', $imageID_s1 . '.' . $extension_s1);
                                    $imageURL_s1 = 'public/temp/' . $imageID_s1 . '.' . $extension_s1;
                                    Storage::disk('grocery_products')->put($imageID_s1 . '.' . $extension_s1, Storage::get($imageURL_s1));
                                    Storage::delete($imageURL_s1);
                                    $imageURL_s1 = '/app/grocery/products/' . $imageID_s1 . '.' . $extension_s1;
                                }
                            }else{
                                $imageURL_s1 ="N/A";
                            }

                            if($request->input('slider2')!=""){
                                if($imageURL_s2 == ""){
                                    $imageID_s2 = strtoupper(Str::random(6));
                                    $extension_s2 = request()->slider2->getClientOriginalExtension();
                                    $request->slider2->storeAs('public/temp', $imageID_s2 . '.' . $extension_s2);
                                    $imageURL_s2 = 'public/temp/' . $imageID_s2 . '.' . $extension_s2;
                                    Storage::disk('grocery_products')->put($imageID_s2 . '.' . $extension_s2, Storage::get($imageURL_s2));
                                    Storage::delete($imageURL_s2);
                                    $imageURL_s2 = '/app/grocery/products/' . $imageID_s2 . '.' . $extension_s2;
                                }
                            }else{
                                $imageURL_s2 ="N/A";
                            }

                            if($request->input('slider3')!=""){
                                if($imageURL_s3 == ""){
                                    $imageID_s3 = strtoupper(Str::random(6));
                                    $extension_s3 = request()->slider3->getClientOriginalExtension();
                                    $request->slider3->storeAs('public/temp', $imageID_s3 . '.' . $extension_s3);
                                    $imageURL_s3 = 'public/temp/' . $imageID_s3 . '.' . $extension_s3;
                                    Storage::disk('grocery_products')->put($imageID_s3 . '.' . $extension_s3, Storage::get($imageURL_s3));
                                    Storage::delete($imageURL_s3);
                                    $imageURL_s3 = '/app/grocery/products/' . $imageID_s3 . '.' . $extension_s3;
                                }
                            }else{
                                $imageURL_s3 ="N/A";
                            }


                            if($request->input('slider4')!=""){
                                if($imageURL_s4 == ""){
                                    $imageID_s4 = strtoupper(Str::random(6));
                                    $extension_s4 = request()->slider4->getClientOriginalExtension();
                                    $request->slider4->storeAs('public/temp', $imageID_s4 . '.' . $extension_s4);
                                    $imageURL_s4 = 'public/temp/' . $imageID_s4 . '.' . $extension_s4;
                                    Storage::disk('grocery_products')->put($imageID_s4 . '.' . $extension_s4, Storage::get($imageURL_s4));
                                    Storage::delete($imageURL_s4);
                                    $imageURL_s4 = '/app/grocery/products/' . $imageID_s4 . '.' . $extension_s4;
                                }
                            }else{
                                $imageURL_s4 ="N/A";
                            }

                            $data1=array();
                            //$data1['cityID']= $city->id;
                            $data1['title']= $request->campain_title;
                            $data1['subtitle']= $request->campain_SubTitle;
                            $data1['details']=$request->description;
                            $data1['banner']=$imageURL;
                            $data1['slider_1']=$imageURL_s1;
                            $data1['slider_2']=$imageURL_s2;
                            $data1['slider_3']=$imageURL_s3;
                            $data1['slider_4']=$imageURL_s4;
                            $data1['meta_tag']=$request->campain_metaTag;
                            $data1['meta_decs']=$request->campain_metaDes;
                            $data1['timestamp']=date('Y-m-d H:i:s');

                            // if (in_array($city->id, $cityCoverage)) {
                                $data1['status']='1';
                            // } else {
                            //     $data1['status']='0';
                            // }

                            DB::table('grocery_seasonal_products')->insert($data1);

                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Campain already esxists under current city.'
                            ]);
                        }
                    // }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Campain added successfully.'
                    ]);
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function SeasonalCampainEditImages(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_product')) {
                $validator = Validator::make($request->all(), [
                    'edit_img_campaign_id' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $id =$input['edit_img_campaign_id'];
                    $curinfo=DB::table('grocery_seasonal_products')
                        ->where('id',$id)
                        ->first();

                    $totalCount=0;

                        if($request->edit_campain_banner !=""){
                            $imageID = strtoupper(Str::random(6));
                            $extension = request()->edit_campain_banner->getClientOriginalExtension();
                            $request->edit_campain_banner->storeAs('public/temp', $imageID . '.' . $extension);
                            $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                            Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                            Storage::delete($imageURL);
                            $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;

                            $data=DB::update("UPDATE grocery_seasonal_products SET banner= '$imageURL' WHERE id = $id;");
                            $totalCount++;
                        }else{
                            $data=null;
                        }

                        if($request->edit_slider1 !=""){

                            $imageID_s1 = strtoupper(Str::random(6));
                            $extension_s1 = request()->edit_slider1->getClientOriginalExtension();
                            $request->edit_slider1->storeAs('public/temp', $imageID_s1 . '.' . $extension_s1);
                            $imageURL_s1 = 'public/temp/' . $imageID_s1 . '.' . $extension_s1;
                            Storage::disk('grocery_products')->put($imageID_s1 . '.' . $extension_s1, Storage::get($imageURL_s1));
                            Storage::delete($imageURL_s1);
                            $imageURL_s1 = '/app/grocery/products/' . $imageID_s1 . '.' . $extension_s1;

                            $data1=DB::update("UPDATE grocery_seasonal_products SET slider_1= '$imageURL_s1' WHERE id = $id;");
                            $totalCount++;
                        }else{
                            $data1=null;
                        }

                        if($request->edit_slider2 !=""){

                            $imageID_s2 = strtoupper(Str::random(6));
                            $extension_s2 = request()->edit_slider2->getClientOriginalExtension();
                            $request->edit_slider2->storeAs('public/temp', $imageID_s2 . '.' . $extension_s2);
                            $imageURL_s2 = 'public/temp/' . $imageID_s2 . '.' . $extension_s2;
                            Storage::disk('grocery_products')->put($imageID_s2 . '.' . $extension_s2, Storage::get($imageURL_s2));
                            Storage::delete($imageURL_s2);
                            $imageURL_s2 = '/app/grocery/products/' . $imageID_s2 . '.' . $extension_s2;

                            $data2=DB::update("UPDATE grocery_seasonal_products SET slider_2= '$imageURL_s2' WHERE id = $id;");
                            $totalCount++;
                        }else{
                            $data2=null;
                        }

                        if($request->edit_slider3 !=""){

                            $imageID_s3 = strtoupper(Str::random(6));
                            $extension_s3 = request()->edit_slider3->getClientOriginalExtension();
                            $request->edit_slider3->storeAs('public/temp', $imageID_s3 . '.' . $extension_s3);
                            $imageURL_s3 = 'public/temp/' . $imageID_s3 . '.' . $extension_s3;
                            Storage::disk('grocery_products')->put($imageID_s3 . '.' . $extension_s3, Storage::get($imageURL_s3));
                            Storage::delete($imageURL_s3);
                            $imageURL_s3 = '/app/grocery/products/' . $imageID_s3 . '.' . $extension_s3;

                            $data3=DB::update("UPDATE grocery_seasonal_products SET slider_3= '$imageURL_s3' WHERE id = $id;");
                            $totalCount++;
                        }else{
                            $data3=null;
                        }

                        if($request->edit_slider4 !=""){

                            $imageID_s4 = strtoupper(Str::random(6));
                            $extension_s4 = request()->edit_slider4->getClientOriginalExtension();
                            $request->edit_slider4->storeAs('public/temp', $imageID_s4 . '.' . $extension_s4);
                            $imageURL_s4 = 'public/temp/' . $imageID_s4 . '.' . $extension_s4;
                            Storage::disk('grocery_products')->put($imageID_s4 . '.' . $extension_s4, Storage::get($imageURL_s4));
                            Storage::delete($imageURL_s4);
                            $imageURL_s4 = '/app/grocery/products/' . $imageID_s4 . '.' . $extension_s4;

                            $data4=DB::update("UPDATE grocery_seasonal_products SET slider_4= '$imageURL_s4' WHERE id = $id;");
                            $totalCount++;
                        }else{
                            $data4=null;
                        }

                    if($totalCount == 5){
                        if($data==null && $data1==null && $data2==null && $data3==null && $data4==null){
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'No Image Update! Something went wrong'
                            ]);
                        }else if($data==null || $data1==null || $data2==null || $data3==null || $data4==null){
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Maybe one or more image not update! Something went wrong'
                            ]);
                        }else{
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'Campaign Image Update successfully.'
                            ]);
                        }
                    }else{
                        if($data==null && $data1==null && $data2==null && $data3==null && $data4==null){
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'No Image Selected'
                            ]);
                        }else{
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'Campaign Image Update successfully.'
                            ]);
                        }
                    }
                }
            // } else {
            //     return redirect()->back()->with([
            //         'error' => true,
            //         'message' => 'You don\'t have permission to edit data.'
            //     ]);
            // }
        } else {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function SeasonalCampainEdit(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'edit_product')) {
                $validator = Validator::make($request->all(), [
                    'campaign_id' => 'required',
                    'cam_title' => 'required',
                    'cam_Sub_title' => 'required',
                    'cam_description' => 'required',
                    'cam_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->campaign_id;
                    $title = $request->cam_title;
                    $subTitle = $request->cam_Sub_title;
                    $Des = $request->cam_description;
                    $metaTag = $request->cam_meta_tag;
                    $metaDes = $request->cam_meta_des;
                    $status = $request->cam_status;

                    $data = array();
                    $data['title']= $request->cam_title;

                    //$update = DB::table('grocery_seasonal_products')->where('id',$id)->update($data);
                    $update = DB::update("UPDATE grocery_seasonal_products SET title='$title', subtitle='$subTitle', details='$Des', meta_tag='$metaTag', meta_decs='$metaDes', status= '$status' WHERE id = '$id';");

                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Campaign updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'You have to change something or Something went wrong.'
                        ]);
                    }
                }
            // } else {
            //     return redirect()->back()->with([
            //         'error' => true,
            //         'message' => 'You don\'t have permission to edit data.'
            //     ]);
            // }
        } else {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function productStatusUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'update_product')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'product_id' => 'required',
                    'product_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $update = Products::where('cityID', $request->input('city_id'))
                        ->where('id', $request->input('product_id'))
                        ->update(['status' => $request->input('product_status')]);

                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Product updated successfully.'
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
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function SeasonalCampainStatusUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
                $validator = Validator::make($request->all(), [
                    'campaign_id' => 'required',
                    'campaign_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('campaign_id');
                    $status = $request->input('campaign_status');
                    $update = DB::update("UPDATE grocery_seasonal_products SET status= '$status' WHERE id = '$id';");

                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Campaign Status updated successfully.'
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

    public function editProduct(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'edit_product')) {
                $validator = Validator::make($request->all(), [
                    'product_id' => 'required',
                    'product_name' => 'required',
                    'trade_price' => 'required',
                    'product_price' => 'required',
                    'product_description' => 'required',
                    'measuring_unit_new' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {

//                    dd($request->all());

                    $currentData = Products::where('id', $request->input('product_id'))->first();
//                    if ($request->has('product_thumbnail')) {
//                        $path = public_path() . $currentData[0]->product_thumbnail;
//                        if (file_exists($path)){
//                            unlink($path);
//                        }
//                        $imageID = strtoupper(Str::random(6));
//                        $extension = request()->product_thumbnail->getClientOriginalExtension();
//                        $request->product_thumbnail->storeAs('public/temp', $imageID . '.' . $extension);
//                        $imageURL = 'public/temp/' . $imageID . '.' . $extension;
//                        Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
//                        Storage::delete($imageURL);
//                        $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;

                        if($request->product_thumbnail != "") {
                            if (file($request->product_thumbnail)) {
                                $destinationPath = '/base-product/' . uniqid() . '.' . $request->product_thumbnail->extension();
                                $request->product_thumbnail->storePubliclyAs('public', $destinationPath);
                                $currentData->product_thumbnail = $destinationPath;
                            }
                        }
                        $currentData->save();
    //                        dd($MultipleImage);
//                        foreach ($MultipleImage as $image) {
//                            $path = public_path() . $image->image_path;
//                            if (file_exists($path)){
//                                unlink($path);
//                            }
//                        }
//                    dd($request->input('product_id'));
                    $MultipleImage = ProductMultiImage::where('product_id', $request->input('product_id'))->first();
//                    dd($MultipleImage);
                    if($MultipleImage != null){
                        if($request->images != null){
                            foreach ($request->images as $image) {
                                $MultipleImage->product_id = $request->input('product_id');
                                $MultipleImage->image_path = $image;
                                $MultipleImage->status = 'Active';
//                                dd($productMultiImage);
                            }
                            $MultipleImage->save();
                        }
                    }else{
                        if($request->images != null){
                            foreach ($request->images as $image) {
                                $productMultiImage = new ProductMultiImage();
                                $productMultiImage->product_id = $request->input('product_id');
                                $productMultiImage->image_path = $image;
                                $productMultiImage->status = 'Active';
                                $productMultiImage->save();
                            }
                        }
                    }


                        Products::where('product_name', $currentData->product_name)->update([
                            'product_name' => $request->input('product_name'),
                            'product_description' => $request->input('product_description'),
                        ]);

                        Products::where('id', $request->input('product_id'))->update([
                            'trade_price' => $request->input('trade_price'),
                            'stock' => $request->input('stock'),
                            'url' => $request->input('url'),
                            'short_description' => $request->input('short_description'),
                            'product_price' => $request->input('product_price'),
                            'measuring_unit_new' => $request->input('measuring_unit_new'),
                            'meta_title' => $request->input('meta_title'),
                            'meta_description' => $request->input('meta_description'),
                            'meta_keywords' => $request->input('meta_keywords')
                        ]);
//                    } else {
//
//                        $imageURL = "";
//                        if($request->product_thumbnail != ""){
//                            if(file($request->product_thumbnail)){
//                                $destinationPath = '/base-product/' . uniqid() . '.' . $request->product_thumbnail->extension();
//                                $request->product_thumbnail->storePubliclyAs('public', $destinationPath);
//                                $imageURL->product_thumbnail = $destinationPath;
//                            }
//                        }
//
//
//                        $MultipleImage = ProductMultiImage::where('product_id', $request->input('product_id'))->get();
////                        dd($MultipleImage);
//                        foreach ($MultipleImage as $image) {
//                            $path = public_path() . $image->image_path;
//                            if (file_exists($path)){
//                                unlink($path);
//                            }
//                        }
                        //update multi image model
//                        ProductMultiImage::where('product_id', $request->input('product_id'))->update([
//                            'product_name' => $request->input('product_name'),
//                            'product_description' => $request->input('product_description')
//                        ]);
//
//                        Products::where('product_name', $currentData[0]->product_name)->update([
//                            'product_name' => $request->input('product_name'),
//                            'product_description' => $request->input('product_description')
//                        ]);
//
//                        Products::where('id', $request->input('product_id'))->update([
//                            'trade_price' => $request->input('trade_price'),
//                            'url' => $request->input('url'),
//                            'short_description' => $request->input('short_description'),
//                            'product_price' => $request->input('product_price'),
//                            'measuring_unit_new' => $request->input('measuring_unit_new'),
//                            'meta_title' => $request->input('meta_title'),
//                            'meta_description' => $request->input('meta_description'),
//                            'meta_keywords' => $request->input('meta_keywords')
//                        ]);
//                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Product updated successfully.'
                    ]);
                }
            } else {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function editCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'update_product_category')) {
                $validator = Validator::make($request->all(), [
                    'product_name' => 'required',
                    'current_category' => 'required',
                    'new_category' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $productName = $request->input('product_name');
                    $currentCategory = $request->input('current_category');
                    $newCategory = $request->input('new_category');

                    $update = Products::where('product_name', $productName)
                        ->where('category', $currentCategory)
                        ->update(['category' => $newCategory]);

                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Product category updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function getProductList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'category' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            if ($request->input('category') == "None") {
                $allProducts = Products::select('*')
                    ->where('cityID', $request->input('city'))
                    ->where('status', 'Active')
                    ->get();
                foreach ($allProducts as $key => $product) {
                    $allProducts[$key]['product_name'] = ucwords($product->product_name);
                    $allProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
                }
                return response()->json([
                    'error' => false,
                    'message' => 'All Active Product List',
                    'products' => $allProducts
                ]);
            } else {
                $allProducts = Products::select('*')
                    ->where('cityID', $request->input('city'))
                    ->where('category', $request->input('category'))
                    ->where('status', 'Active')
                    ->get();
                foreach ($allProducts as $key => $product) {
                    $allProducts[$key]['product_name'] = ucwords($product->product_name);
                    $allProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
                }
                return response()->json([
                    'error' => false,
                    'message' => 'All Active Product List',
                    'products' => $allProducts
                ]);
            }
        }
    }

    public function getHomeProductList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $allProducts = Products::select('*')
                ->where('cityID', $request->input('city'))
                ->where('status', 'Active')
                ->inRandomOrder()
                ->LIMIT('15')
                ->get();
            foreach ($allProducts as $key => $product) {
                $allProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
            }
            return response()->json([
                'error' => false,
                'message' => 'All Active Product List',
                'products' => $allProducts
            ]);
        }
    }

    public function regenerateProducts(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'regenerate_product')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityID = $request->input('id');
                    $productList = Products::select('*')->where('cityID', '1')->get();
                    $regenCounter = 0;
                    foreach ($productList as $products) {
                        $existing = Products::select('*')
                            ->where('product_name', $products->product_name)
                            ->where('cityID', $cityID)
                            ->get();
                        if (count($existing) == 0) {
                            $newProduct = new Products();
                            $newProduct->cityID = $cityID;
                            $newProduct->category = $products->category;
                            $newProduct->product_name = $products->product_name;
                            $newProduct->product_price = $products->product_price;
                            $newProduct->measuring_unit = $products->measuring_unit;
                            $newProduct->product_description = $products->product_description;
                            $newProduct->product_thumbnail = $products->product_thumbnail;
                            $newProduct->measuring_unit_new = isset($products->measuring_unit_new)?$products->measuring_unit_new:null;
                            $newProduct->status = $products->status;
                            $newProduct->save();

                            $regenCounter++;
                        }
                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => $regenCounter . ' product regenerated successfully.'
                    ]);
                }
            } else {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function searchProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'required',
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $city = $request->input('city');
            $keyword = $request->input('keyword');
            $allProducts = Products::select('*')
                ->where([
                    ['cityID', '=', $city],
                    ['product_name', 'LIKE', '%' . $keyword . '%'],
                    ['status', '=', 'Active']
                ])
                ->orWhere([
                    ['cityID', '=', $city],
                    ['product_description', 'LIKE', '%' . $keyword . '%'],
                    ['status', '=', 'Active']
                ])->get();
            foreach ($allProducts as $key => $product) {
                $allProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
            }
            return response()->json([
                'error' => false,
                'message' => 'Product Search Result',
                'products' => $allProducts
            ]);
        }
    }

    public function getProductDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category' => 'required',
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $id = $request->input('id');
            $category = $request->input('category');
            $productDetails = Products::select('*')->where('id', $id)->get();

            dd($productDetails);

            foreach ($productDetails as $key => $product) {
                $productDetails[$key]['product_thumbnail'] = url($product->product_thumbnail);
            }
            $relatedProducts = Products::select('*')
                ->where('category', $category)
                ->where('id', '!=', $id)
                ->where('cityID', $request->input('city'))
                ->LIMIT('10')->get();
            foreach ($relatedProducts as $key => $product) {
                $relatedProducts[$key]['product_thumbnail'] = url($product->product_thumbnail);
            }
            $relatedProducts = collect($relatedProducts)->toArray();
            shuffle($relatedProducts);
            return response()->json([
                'error' => false,
                'message' => 'Product Search Result',
                'details' => $productDetails,
                'related' => $relatedProducts
            ]);
        }
    }
}
