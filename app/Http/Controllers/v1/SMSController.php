<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\SMSHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SMSController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function sendSMS(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'sendTo' => 'required',
                    'message' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! Please fill up all the fields.'
                    ]);
                } else {
                    $sendTo = $request->input('sendTo');
                    $message = urldecode($request->input('message'));

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
                            'number' => $sendTo,
                            'sms_content' => $message,
                            'sms_type' => '1',
                            'masking' => 'non-masking'
                        ),
                    ));
        
                    $response = curl_exec($curl);
                    $response = json_decode($response, true);
                    curl_close($curl);

                    $campaign = "-";
                    if($request->has('campaignName') && $request->input('campaignName') != ""){
                        $campaign = $request->input('campaignName');
                    }

                    $newSMSHistory = new SMSHistory();
                    $newSMSHistory->campaign = $campaign;
                    $newSMSHistory->sendTo = $sendTo;
                    $newSMSHistory->message = $message;
                    $newSMSHistory->totalSMS = $response['Total Valid Numbers'];
                    $newSMSHistory->totalCost = $response['Total Cost'];
                    $newSMSHistory->save();

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => "Your messages has been send.\n\nTotal SMS: " . $response['Total Valid Numbers'] . "\nTotal Cost: " . $response['Total Cost']
                    ]);
                }
            }
        }
    }
}
