<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\City;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
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

    public function serverMaintenance(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'mango_order')) {
                $cityList = City::all();
                $MaintenanceInfo=DB::table('server_maintenance')->first();
                return view('ServerMaintenance')
                    ->with('title', 'Server Maintenance')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('MaintenanceInfo', $MaintenanceInfo);
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function update(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'sm_id' => 'required',
                    'sm_text' => 'required',
                    'sm_ip' => 'required',
                    'sm_status' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $sm_id =$input['sm_id'];
                    $sm_text =$input['sm_text'];
                    $sm_ip= $input['sm_ip'];
                    $sm_status= $input['sm_status'];
                    
                    $data=array();
                    $data['text']=$sm_text;
                    $data['ip']=$sm_ip;
                    $data['status']=$sm_status;

                    if($request->sm_image !=""){
                        $imageID = strtoupper(Str::random(10));
                        $extension = request()->sm_image->getClientOriginalExtension();
                        $request->sm_image->storeAs('public/temp', $imageID . '.' . $extension);
                        $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                        Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                        Storage::delete($imageURL);
                        $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension;

                        $data['image']=$imageURL;
                    }


                    $data=DB::table('server_maintenance')->where('id',$sm_id)->update($data);
                    
                    if($data==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Update successfully.'
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
