<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\SMSHistory;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RiderController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if (($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) ||
            ($request->session()->has('GR_LOGGED_IN') && $request->session()->get('GR_LOGGED_IN'))
        ) {
            return true;
        } else {
            return false;
        }
    }

    private function sendSMS($to, $message)
    {
        $message = urldecode($message);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://gosms.xyz/api/v1/sendSms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'username' => 'rafathossain',
                'password' => 'rafat1234',
                'number' => $to,
                'sms_content' => $message,
                'sms_type' => '1',
                'masking' => 'non-masking'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);

        $newSMSHistory = new SMSHistory();
        $newSMSHistory->campaign = "-";
        $newSMSHistory->sendTo = $to;
        $newSMSHistory->message = $message;
        $newSMSHistory->totalSMS = $response['Total Valid Numbers'];
        $newSMSHistory->totalCost = $response['Total Cost'];
        $newSMSHistory->save();
    }

    public function addNewRider(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'cityID' => 'required',
                    'name' => 'required',
                    'mobile' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! Please fill up all the fields.'
                    ]);
                } else {
                    $rider_id = strtoupper(Str::random(6));
                    $password = Str::random(8);

                    $new_rider = new Rider();
                    $new_rider->rider_id = $rider_id;
                    $new_rider->city_id = $request->input('cityID');
                    $new_rider->name = $request->input('name');
                    $new_rider->mobile = $request->input('mobile');
                    $new_rider->password = Hash::make($password);
                    $new_rider->device_token = "-";

                    $message = "Dear " . $request->input('name') . ", welcome to KT Captain.\n\nPassword: " . $password . "\n\n- Admin";
                    $this->sendSMS($request->input('mobile'), $message);

                    if ($new_rider->save()) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Success! New rider added successfully!'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Error! Something went wrong.'
                        ]);
                    }
                }
            }
        } else {
            return redirect('/dashboard');
        }
    }

    public function riderLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required',
                'password' => 'required',
                'device_token' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $mobile = $request->input('mobile');
                $password = $request->input('password');

                $riderInfo = Rider::select('*')->where('mobile', $mobile)->get();
                if (count($riderInfo) == 1) {
                    if ($mobile == "01821078274" || $mobile == "01704005054" || $mobile == "01722850218" || $mobile == "01791865233") {
                        Rider::where('mobile', $mobile)->update(['device_token' => $request->input('device_token')]);
                        $riderList = Rider::select('*')
                            ->where('mobile', '!=', '01704005054')
                            ->where('mobile', '!=', '01722850218')
                            ->where('mobile', '!=', '01791865233')
                            ->get();
                        if (Hash::check($password, $riderInfo[0]->password)) {
                            return response()->json([
                                'error' => false,
                                'message' => 'Logged in!',
                                'riderID' => $riderInfo[0]->rider_id,
                                'riderRole' => "ADMIN",
                                'riderList' => $riderList
                            ]);
                        } else {
                            return response()->json([
                                'error' => true,
                                'message' => 'Your password didn\'t match!'
                            ]);
                        }
                    } else {
                        Rider::where('mobile', $mobile)->update(['device_token' => $request->input('device_token')]);
                        $riderList = Rider::select('*')
                            ->where('mobile', '!=', '01704005054')
                            ->where('mobile', '!=', '01722850218')
                            ->where('mobile', '!=', '01791865233')
                            ->get();
                        if (Hash::check($password, $riderInfo[0]->password)) {
                            return response()->json([
                                'error' => false,
                                'message' => 'Logged in!',
                                'riderID' => $riderInfo[0]->rider_id,
                                'riderRole' => "RIDER",
                                'riderList' => $riderList
                            ]);
                        } else {
                            return response()->json([
                                'error' => true,
                                'message' => 'Your password didn\'t match!'
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'No rider found!'
                    ]);
                }
            }
        }
    }

    public function updateRiderPassword(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'riderID' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Error! Please fill up all the fields.'
                    ]);
                } else {
                    $newPassword = rand(100000, 999999);
                    $riderID = $request->input('riderID');
                    $update = Rider::where('rider_id', $riderID)->update([
                        'password' => Hash::make($newPassword)
                    ]);
                    if ($update) {
                        $riderInfo = Rider::select('*')->where('rider_id', $riderID)->get();
                        $riderMobile = $riderInfo[0]->mobile;
                        $riderName = $riderInfo[0]->name;
                        $message = "Dear " . $riderName . ",\n\nYour password for captain app is updated.\n\nNew Password: " . $newPassword . "\n\nPlease login with your new password.\n\n- Admin";
                        $this->sendSMS($riderMobile, $message);
                        return response()->json([
                            'error' => false,
                            'message' => 'Password updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Error! Something went wrong.'
                        ]);
                    }
                }
            }
        }
    }
}
