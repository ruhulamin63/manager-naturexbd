<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\AreaCoverage;
use App\Models\Backend\Notification;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Category;
use App\Models\Grocery\City;
use App\Models\Grocery\LoginReport;
use App\Models\Grocery\Order;
use App\Models\Grocery\Products;
use App\Models\Grocery\Users;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
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

    public function creatPromo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'promo_code' => 'required',
                    'promo_count' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'promo_con' => 'required',
                    'promo_status' => 'required',
                    'discount_amount' => 'required',
                    'min_amount' => 'required',
                    'promo_type' => 'required',
                    'city_coverage' => 'required',
                    'promo_image' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityCoverage = $request->input('city_coverage');
                    $cityList = City::all();
                    $promoCode = $request->input('promo_code');
                    $imageData = "";
                    foreach ($cityList as $city) {
                        $exisitng = DB::table('restaurant_promo')
                            ->where('promo_code',$promoCode)
                            ->where('activeSatus',1)
                            ->where('cityID', $city->id)
                            ->get();
                        if (count($exisitng) == 0) {
                            $input = $request->all();
                            $promo_count= $input['promo_count'];
                            $start_date = date("Y-m-d H:i:s",strtotime($input['start_date']));
                            $end_date = date("Y-m-d H:i:s",strtotime($input['end_date']));
                            $promo_con= $input['promo_con'];
                            $promo_status= $input['promo_status'];
                            $discount_amount= $input['discount_amount'];
                            $min_amount= $input['min_amount'];
                            $promo_type= $input['promo_type'];
                            
                            $data=array();
                    
                            if($imageData == ""){
                                $image = $request->file('promo_image');
                                $image_name=$image->getClientOriginalName();
                                $image_ext=$image->getClientOriginalExtension();
                                //$image_new_name =hexdec(uniqid());
                                $image_new_name =strtoupper(Str::random(8));
                                $image_full_name=$image_new_name.'.'.$image_ext;
                                $upload_path='promo_banner/';
                                $image_url=$upload_path.$image_full_name;
                                $success=$image->move($upload_path,$image_full_name);
                                $imageData='/promo_banner/'.$image_full_name;
                            }
                            
                            if (in_array($city->id, $cityCoverage)) {
                                $data['status']=$promo_status;
                            } else {
                                $data['status']=0;
                            }
                            
                            $data['cityId']=$city->id;
                            $data['promo_code']=$promoCode;
                            $data['count']=$promo_count;
                            $data['start']=$start_date;
                            $data['end']=$end_date;
                            $data['conditions']=$promo_con;
                            $data['image']=$imageData;
                            $data['amount']=$discount_amount;
                            $data['promo_type']=$promo_type;
                            $data['conditions_amount']=$min_amount;
                            
                            $insert = DB::table('restaurant_promo')->insert($data);
                        }
                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Promo added successfully.'
                    ]);
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updatePromoStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'promo_id' => 'required',
                    'promo_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $promo_id = $request->input('promo_id');
                    $promoStatus = $request->input('promo_status');
                    $update = DB::update("UPDATE restaurant_promo SET status= '$promoStatus' WHERE id = '$promo_id';");
                    //$update = City::where('id', $cityID)->update(['status' => $request->input('city_status')]);
                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Promo Status updated successfully.'
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

    public function deletePromo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'promo_id' => 'required',
                    'promo_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $promo_id = $request->input('promo_id');
                    $promoStatus = $request->input('promo_status');
                    $update = DB::update("UPDATE restaurant_promo SET status= '$promoStatus',activeSatus= '$promoStatus' WHERE id = '$promo_id';");
                    //$update = City::where('id', $cityID)->update(['status' => $request->input('city_status')]);
                    if ($update) {
                        
                        return response()->json([
                            'error' => false,
                            'message' => 'Promo Delete successfully.'
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

    public function editPromo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'editPromo_id' => 'required',
                    'editPromoCode' => 'required',
                    'editPromoCount' => 'required',
                    'editStartDate' => 'required',
                    'editEndDate' => 'required',
                    'editPromoCon' => 'required',
                    'editAmount' => 'required',
                    'editMinAmount' => 'required',
                    'edit_promo_type' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $promoId =$input['editPromo_id'];
                    $promoCode =$input['editPromoCode'];
                    $promo_count= $input['editPromoCount'];
                    $start_date = date("Y-m-d H:i:s",strtotime($input['editStartDate']));
                    $end_date = date("Y-m-d H:i:s",strtotime($input['editEndDate']));
                    $promo_con= $input['editPromoCon'];
                    $Amount= $input['editAmount'];
                    $MinAmount= $input['editMinAmount'];
                    $promo_type= $input['edit_promo_type'];

                    $data=DB::update("UPDATE restaurant_promo SET promo_code= '$promoCode',count= '$promo_count',start= '$start_date',end= '$end_date',conditions= '$promo_con',amount='$Amount',promo_type='$promo_type',conditions_amount='$MinAmount' WHERE id = $promoId;");
                    if($data==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Promo Edit successfully.'
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

    public function updatePromoImage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    
                    'promo_id_img' => 'required',
                    'promo_preview' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $promoId =$input['promo_id_img'];
                    $promo_info=DB::table('restaurant_promo')
                        ->where('id',$promoId)
                        ->first();
                    $old_image= $promo_info->image;
                    $image = $request->file('promo_preview');
                    $imageCheck = '/promo_banner/'.$request->file('promo_preview');

                    if(($imageCheck)){

                        if(file_exists($old_image)){
                            unlink($old_image);
                        }

                    //$image = $request->file('promo_preview');
                    $image_name=$request->file('promo_preview')->getClientOriginalName();
                    $image_ext=$request->file('promo_preview')->getClientOriginalExtension();
                    $image_new_name =strtoupper(Str::random(8));
                    $image_full_name=$image_new_name.'.'.$image_ext;
                    $upload_path='promo_banner/';
                    $image_url=$upload_path.$image_full_name;
                    $success=$image->move($upload_path,$image_full_name);
                    $imageData='/promo_banner/'.$image_full_name;

                        $data=DB::update("UPDATE restaurant_promo SET image= '$imageData' WHERE id = $promoId;");
                        if($data==null){
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Something went wrong 1.'
                            ]);
                        }
                        else{
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'Promo Image added successfully.'
                            ]);
                        }
                    }else{
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong 2.'
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
