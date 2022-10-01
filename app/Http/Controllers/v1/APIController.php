<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\APIManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewApiProvider(Request $request){
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'service_name' => 'required',
                'service_identifier' => 'required',
                'api_key' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $new_api_provider = new APIManager();
                $new_api_provider->service_name = $request->input('service_name');
                $new_api_provider->service_identifier = $request->input('service_identifier');
                $new_api_provider->used_balance = '0.00';
                $new_api_provider->api_key = $request->input('api_key');
                $new_api_provider->updated_by = $request->session()->get('MANAGER_NAME') . ',' . $request->session()->get('UID');
                if($new_api_provider->save()){
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Success! New API provider added successfully!'
                    ]);
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! Something went wrong.'
                    ]);
                }
            }
        }
    }
}
