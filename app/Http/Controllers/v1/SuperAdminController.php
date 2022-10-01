<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function createSuperAdmin(Request $request){
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $clientIP = FacadeRequest::ip();
                if($clientIP == "103.198.136.1"){
                    $name = $request->input('name');
                    $email = $request->input('email');
                    $mobile = $request->input('mobile');
                    $password = $request->input('password');
    
                    if(count(UserAuth::select('*')->where('email', $email)->get()) == 0){
                        $password = Hash::make($password);

                        $userID = strtoupper(substr($name,0,3)) . strtoupper(Str::random(3)) . substr($mobile,7,4);
                        
                        $new_super_admin = new SuperAdmin();
                        $new_super_admin->uid = $userID;
                        $new_super_admin->name = $name;
                        $new_super_admin->email = $email;
                        $new_super_admin->mobile = $mobile;
                        $new_super_admin->photo = url('/storage/defaults/images/avatar.png');
    
                        $new_user_auth = new UserAuth();
                        $new_user_auth->uid = $userID;
                        $new_user_auth->name = $name;
                        $new_user_auth->email = $email;
                        $new_user_auth->password = $password;
                        $new_user_auth->role_id = "9517";
                        $new_user_auth->permissions = "";
                        $new_user_auth->last_login = "None";
                        $new_user_auth->last_login_ip = "None";
    
                        if($new_super_admin->save() && $new_user_auth->save()){
                            return response()->json([
                                'error' => false,
                                'message' => "Super admin account created successfully."
                            ]);
                        } else {
                            return response()->json([
                                'error' => true,
                                'message' => "Failed to create super admin account."
                            ]);
                        }
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "User already exist with this email address."
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => "Requested IP not allowed!"
                    ]);
                }
            }
        }
    }
}
