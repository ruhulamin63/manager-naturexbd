<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        // if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
        //     return true;
        // } else {
        //     return false;
        // }
        return true;
    }

    public function createGroup(Request $request){
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'group_name' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
            }
        }
    }
}
