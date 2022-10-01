<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Leads;
use App\Models\Grocery\Users;
use App\Models\MangoLeads;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LeadsController extends Controller
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

    private function sendNotification($data, $target)
    {
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';

        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAaZVge1s:APA91bFagv6aSs_1uVg-9lBwtnWM_AdBrm9d9cBjPJJm-N2mH1R77iNyF2emPWLf00vDkFXJ-xAU-o6V2l5-9IraWnHxbp9DadIjkzwvhv2yUl9Nz8D4YuDWWEOFCfVhZz67KqEyW5sZ';

        $fields = array();

        $fields['to'] = $target;
        $fields['data'] = $data;

        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);
    }

    public function uploadLeads(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'upload_leads')) {
                $validator = Validator::make($request->all(), [
                    'leads_csv' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $csvID = strtoupper(Str::random(6));
                    $extension = request()->leads_csv->getClientOriginalExtension();
                    $request->leads_csv->storeAs('public/temp', $csvID . '.' . $extension);
                    $csvURL = 'public/temp/' . $csvID . '.' . $extension;
                    Storage::disk('grocery_leads')->put($csvID . '.' . $extension, Storage::get($csvURL));
                    Storage::delete($csvURL);
                    $csvURL = '/leads/' . $csvID . '.' . $extension;

                    $leadCount = 0;
                    $row = 0;
                    $header = array();
                    if (($h = fopen(public_path($csvURL), "r")) !== FALSE) {
                        // Convert each line into the local $data variable
                        while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                            // Read the data from a single line
                            // print_r($data);
                            $num = count($data);
                            $row++;
                            $headID = 0;
                            $details = "";
                            if ($row == 1) {
                                for ($c = 14; $c < $num; $c++) {
                                    $str = trim(strtolower(str_replace("_", " ", $data[$c])));
                                    $str = trim(strtolower(str_replace("?", "", $str)));
                                    $header[$headID] = ucwords($str);
                                    $headID++;
                                }
                            } else {
                                $headID = 0;
                                for ($c = 14; $c < $num; $c++) {
                                    $str = trim(strtolower(str_replace("_", " ", $data[$c])));
                                    $str = trim(strtolower(str_replace("?", "", $str)));
                                    $details = $details . $header[$headID] . ": " . ucwords($str) . "\n";
                                    $headID++;
                                }
                                $details = $details . "Timestamp: " . $data[1];

                                $existing = Leads::select('*')->where('details', $details)->get();
                                if (count($existing) == 0 && $details != "") {
                                    $name = trim(strtolower(str_replace("_", " ", $data[12])));
                                    $name = trim(strtolower(str_replace("?", "", $name)));
                                    $name = ucwords($name);

                                    $mobile = trim(strtolower(str_replace("_", " ", $data[13])));
                                    $mobile = trim(strtolower(str_replace("?", "", $mobile)));

                                    $leadCount++;
                                    $newLead = new Leads();
                                    $newLead->source = explode(': ', $data[3])[1];
                                    $newLead->name = $name;
                                    $newLead->mobile = $mobile;
                                    $newLead->details = $details;
                                    $newLead->remarks = "";
                                    $newLead->status = "Pending";
                                    $newLead->save();
                                }
                            }
                        }

                        // Close the file
                        fclose($h);
                    }

                    unlink(public_path($csvURL));

                    $title = "Admin Message";
                    $message = $leadCount . " new leads has been added to dashboard. Please check for the updates.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $userInfo = Users::select('*')->where('mobile', '+8801722850218')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801704005054')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801821078274')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => $leadCount . ' leads collected successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function resolveLead(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'leads_data')) {
                $validator = Validator::make($request->all(), [
                    'remarks' => 'required',
                    'leadID' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $leadID = $request->input('leadID');
                    $remarks = $request->input('remarks');

                    $update = Leads::where('id', $leadID)->update(['remarks' => $remarks, 'status' => 'Resolved']);

                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Leads updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function uploadMangoLeads(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'upload_leads')) {
                $validator = Validator::make($request->all(), [
                    'leads_csv' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $csvID = strtoupper(Str::random(6));
                    $extension = request()->leads_csv->getClientOriginalExtension();
                    $request->leads_csv->storeAs('public/temp', $csvID . '.' . $extension);
                    $csvURL = 'public/temp/' . $csvID . '.' . $extension;
                    Storage::disk('grocery_leads')->put($csvID . '.' . $extension, Storage::get($csvURL));
                    Storage::delete($csvURL);
                    $csvURL = '/leads/' . $csvID . '.' . $extension;

                    $leadCount = 0;
                    $row = 0;
                    $header = array();
                    if (($h = fopen(public_path($csvURL), "r")) !== FALSE) {
                        // Convert each line into the local $data variable
                        while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                            // Read the data from a single line
                            // print_r($data);
                            $num = count($data);
                            $row++;
                            $headID = 0;
                            $details = "";
                            if ($row == 1) {
                                for ($c = 14; $c < $num; $c++) {
                                    $str = trim(strtolower(str_replace("_", " ", $data[$c])));
                                    $str = trim(strtolower(str_replace("?", "", $str)));
                                    $header[$headID] = ucwords($str);
                                    $headID++;
                                }
                            } else {
                                $headID = 0;
                                for ($c = 14; $c < $num; $c++) {
                                    $str = trim(strtolower(str_replace("_", " ", $data[$c])));
                                    $str = trim(strtolower(str_replace("?", "", $str)));
                                    $details = $details . $header[$headID] . ": " . ucwords($str) . "\n";
                                    $headID++;
                                }
                                $details = $details . "Timestamp: " . $data[1];

                                $existing = MangoLeads::select('*')->where('details', $details)->get();
                                if (count($existing) == 0 && $details != "") {
                                    $name = trim(strtolower(str_replace("_", " ", $data[12])));
                                    $name = trim(strtolower(str_replace("?", "", $name)));
                                    $name = ucwords($name);

                                    $mobile = trim(strtolower(str_replace("_", " ", $data[13])));
                                    $mobile = trim(strtolower(str_replace("?", "", $mobile)));

                                    $leadCount++;
                                    $newLead = new MangoLeads();
                                    $newLead->source = explode(': ', $data[3])[1];
                                    $newLead->name = $name;
                                    $newLead->mobile = $mobile;
                                    $newLead->details = $details;
                                    $newLead->remarks = "";
                                    $newLead->status = "Pending";
                                    $newLead->save();
                                }
                            }
                        }

                        // Close the file
                        fclose($h);
                    }

                    unlink(public_path($csvURL));

                    $title = "Admin Message";
                    $message = $leadCount . " new leads has been added to dashboard. Please check for the updates.";

                    $data_array = array('title' => $title, 'message' => $message);

                    $userInfo = Users::select('*')->where('mobile', '+8801722850218')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801704005054')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801821078274')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
                    $this->sendNotification($data_array, $userInfo[0]->device_token);

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => $leadCount . ' leads collected successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function resolveMangoLead(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'leads_data')) {
                $validator = Validator::make($request->all(), [
                    'remarks' => 'required',
                    'leadID' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $leadID = $request->input('leadID');
                    $remarks = $request->input('remarks');

                    $update = MangoLeads::where('id', $leadID)->update(['remarks' => $remarks, 'status' => 'Resolved']);

                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Leads updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
