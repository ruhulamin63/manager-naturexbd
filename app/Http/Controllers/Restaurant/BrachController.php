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

class BrachController extends Controller
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

    public function Create(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'city_Id' => 'required',
                    'restaurant_id' => 'required',
                    'branch_name' => 'required',
                    'branch_type' => 'required',
                    'branch_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                
                    $input = $request->all();
                    $cityId= $input['city_Id'];
                    $resId= $input['restaurant_id'];
                    $name= $input['branch_name'];
                    $type= $input['branch_type'];
                    $status= $input['branch_status'];

                    $data=DB::insert("INSERT INTO restaurant_branch (resId, cityID,branchName,type,status) VALUES ('$resId','$cityId','$name','$type','$status');");
                    
                    if($data==null){
                        return redirect('/restaurant/addBranch')->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect('/restaurant/addBranch')->with([
                            'error' => false,
                            'message' => 'New Branch Added Successfully.'
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

    public function addBranchUpdateStatus(Request $request)
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
                    $update = DB::update("UPDATE restaurant_branch SET status= '$Status' WHERE id = '$id';");
                    
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

    public function updateBranchName(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'editBranch_id' => 'required',
                    'editBranchName' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $input = $request->all();
                    $branchId =$input['editBranch_id'];
                    $branchName =$input['editBranchName'];

                    $data=DB::update("UPDATE restaurant_branch SET branchName= '$branchName' WHERE id = $branchId;");
                    if($data==null){
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Update Name Successfully.'
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

    public function DeleteBranch(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
            
                $id = $request->input('branch_id');
                $deleted = DB::delete("DELETE FROM restaurant_branch WHERE id = $id;");
                if ($deleted) {
                        
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
                
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
