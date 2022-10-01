<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'division' => 'required',
            'mobile' => 'required',
            'device_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $existing = Users::select('*')->where('mobile', $request->input('mobile'))->get();
            if (count($existing) == 1) {
                $update = Users::where('mobile', $request->input('mobile'))->update(['device_token' => $request->input('device_token')]);
                if ($update) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Account created successfully.'
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Something went wrong.'
                    ]);
                }
            } else {
                $newUser = new Users();
                $newUser->name = $request->input('name');
                $newUser->division = $request->input('division');
                $newUser->mobile = $request->input('mobile');
                $newUser->device_token = $request->input('device_token');
                if ($newUser->save()) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Account created successfully.'
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Something went wrong.'
                    ]);
                }
            }
        }
    }
}
