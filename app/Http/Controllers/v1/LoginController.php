<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\AccessControl;
use App\Models\Backend\ManagerAuth;
use App\Models\Backend\ManagerInfo;
use App\Models\Grocery\Admin;
use App\Models\Grocery\LoginReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'dashboard' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'Error! Please fill up all the fields.'
                ]);
            } else {
                $email = $request->input('email');
                $password = $request->input('password');

                if ($request->input('dashboard') == 'grocery') {
                    $managerAuth = Admin::select('*')->where('email', $email)->get();

                    if (count($managerAuth) == 1) {
                        $hashedPassword = $managerAuth[0]->password;
                        if (Hash::check($password, $hashedPassword)) {
                            $managerID = $managerAuth[0]->user_id;
                            $managerRole = $managerAuth[0]->user_group;
                            $request->session()->regenerate();
                            $request->session()->put('GR_LOGGED_IN', true);
                            $request->session()->put('GR_UID', $managerID);
                            $request->session()->put('GR_MANAGER_NAME', $managerAuth[0]->name);
                            $request->session()->put('GR_MANAGER_EMAIL', $managerAuth[0]->email);

                            $newLogin = new LoginReport();
                            $newLogin->name = $managerAuth[0]->name;
                            $newLogin->email = $managerAuth[0]->email;
                            $newLogin->ip = $request->ip();
                            $newLogin->destination = "Grocery Dashboard";
                            $newLogin->save();

                            return redirect(url('/grocery/dashboard'));
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Error! Your password didn\'t match.'
                            ]);
                        }
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Error! No user exist with the provided email address.'
                        ]);
                    }
                } else {
                    $managerAuth = ManagerAuth::select('*')->where('email', $email)->get();

                    if (count($managerAuth) == 1) {
                        $hashedPassword = $managerAuth[0]->password;
                        if (Hash::check($password, $hashedPassword)) {
                            $managerID = $managerAuth[0]->uid;
                            $managerRole = $managerAuth[0]->role_id;
                            $managerInfo = ManagerInfo::select('*')->where('uid', $managerID)->get();
                            if (count($managerInfo) == 1) {
                                $accessControl = AccessControl::select('*')->where('role_id', $managerRole)->get();

                                $request->session()->regenerate();
                                $request->session()->put('LOGGED_IN', true);
                                $request->session()->put('UID', $managerID);
                                $request->session()->put('MANAGER_ROLE', $managerRole);
                                $request->session()->put('MANAGER_NAME', $managerInfo[0]->name);
                                $request->session()->put('MANAGER_EMAIL', $managerInfo[0]->email);
                                $request->session()->put('MANAGER_MOBILE', $managerInfo[0]->mobile);
                                $request->session()->put('MANAGER_PHOTO', $managerInfo[0]->photo);
                                $request->session()->put('MANAGER_ROLE_TITLE', $accessControl[0]->role_title);

                                ManagerAuth::where('email', $email)->update([
                                    'last_login' => Carbon::now()->toDateTimeString(),
                                    'last_login_ip' => $request->ip()
                                ]);

                                $newLogin = new LoginReport();
                                $newLogin->name = $managerAuth[0]->name;
                                $newLogin->email = $managerAuth[0]->email;
                                $newLogin->ip = $request->ip();
                                $newLogin->destination = "Restaurant Dashboard";
                                $newLogin->save();

                                return redirect(url('/dashboard'));
                            } else {
                                return redirect()->back()->with([
                                    'error' => true,
                                    'message' => 'Error! Something went wrong.'
                                ]);
                            }
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Error! Your password didn\'t match.'
                            ]);
                        }
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Error! No user exist with the provided email address.'
                        ]);
                    }
                }
            }
        }
    }

    public function createLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Error! Please fill up all the fields.'
            ]);
        } else {
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $password = $request->input('password');

            $managerAuth = ManagerAuth::select('*')->where('email', $email)->get();

            if (count($managerAuth) == 0) {
                // $newAccess = new AccessControl();
                // $newAccess->role_id = '4520';
                // $newAccess->role_title = 'Content Manager';
                // $newAccess->role_permissions = '2210_111,8119_111';
                // $newAccess->updated_by = 'System,0';
                // $newAccess->save();

                $hashedPassword = Hash::make($password);

                $UID = strtoupper(substr('Super Admin', 0, 3)) . strtoupper(Str::random(7));

                $newManagerInfo = new ManagerInfo();
                $newManagerInfo->uid = $UID;
                $newManagerInfo->name = $name;
                $newManagerInfo->email = $email;
                $newManagerInfo->mobile = $mobile;
                $newManagerInfo->photo = '/storage/defaults/images/avatar.png';
                $newManagerInfo->updated_by = 'System,0';
                $newManagerInfo->save();

                $newAuth = new ManagerAuth();
                $newAuth->uid = $UID;
                $newAuth->email = $email;
                $newAuth->password = $hashedPassword;
                $newAuth->role_id = '4520';
                $newAuth->last_login = '';
                $newAuth->last_login_ip = '';
                $newAuth->updated_by = 'System,0';
                $newAuth->save();

                return response()->json([
                    'error' => false,
                    'message' => 'Success! Account created.'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Error! User exist with the provided email address.'
                ]);
            }
        }
    }

    public function updatePassword(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'currentPassword' => 'required',
                'newPassword' => 'required',
                'confirmNewPassword' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $currentPassword = $request->input('currentPassword');
                $newPassword = $request->input('newPassword');
                $confirmNewPassword = $request->input('confirmNewPassword');

                $userEmail = $request->session()->get('MANAGER_EMAIL');
                $user = ManagerAuth::select('*')->where('email', $userEmail)->get();
                if (Hash::check($currentPassword, $user[0]->password)) {
                    if ($newPassword == $confirmNewPassword) {
                        $newPassword = Hash::make($newPassword);
                        $update = ManagerAuth::where('email', $userEmail)->update(['password' => $newPassword]);
                        if ($update) {
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'Password updated successfully!'
                            ]);
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Something went wrong!'
                            ]);
                        }
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Your new passwords didn\'t match!'
                        ]);
                    }
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Incorrect Password!'
                    ]);
                }
            }
        }
    }
}
