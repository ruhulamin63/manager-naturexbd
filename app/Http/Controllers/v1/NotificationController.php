<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\Notification;
use App\Models\Grocery\Users;
use App\Models\Rider;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function sendNotification(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nf_title' => 'required',
            'nf_message' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(url('/dashboard/page/unauthorized'));
        } else {
            $data_array = "";
            $redirect = "-";
            $image = "-";
            $randomID = strtoupper(Str::random(6));
            if ($request->has('nf_redirect') && $request->input('nf_redirect') != "") {
                if ($request->has('nf_preview')) {
                    $extension = request()->nf_preview->getClientOriginalExtension();
                    $request->nf_preview->storeAs('public/temp', $randomID . '.' . $extension);
                    $imageURL = 'public/temp/' . $randomID . '.' . $extension;
                    Storage::disk('notification')->put($randomID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/notifications/' . $randomID . '.' . $extension;

                    $data_array = array(
                        'title' => $request->input('nf_title'),
                        'message' => $request->input('nf_message'),
                        'image' => asset($imageURL),
                        'redirect' => $request->input('nf_redirect')
                    );

                    $image = asset($imageURL);
                } else {
                    $imageURL = 'storage/defaults/images/notification.jpg';

                    $data_array = array(
                        'title' => $request->input('nf_title'),
                        'message' => $request->input('nf_message'),
                        'redirect' => $request->input('nf_redirect')
                    );

                    $image = asset($imageURL);
                }

                $redirect = $request->input('nf_redirect');
            } else if ($request->has('nf_preview')) {
                $extension = request()->nf_preview->getClientOriginalExtension();
                $request->nf_preview->storeAs('public/temp', $randomID . '.' . $extension);
                $imageURL = 'public/temp/' . $randomID . '.' . $extension;
                Storage::disk('notification')->put($randomID . '.' . $extension, Storage::get($imageURL));
                Storage::delete($imageURL);
                $imageURL = '/app/notifications/' . $randomID . '.' . $extension;

                $data_array = array(
                    'title' => $request->input('nf_title'),
                    'message' => $request->input('nf_message'),
                    'image' => asset($imageURL)
                );

                $image = asset($imageURL);
            } else {
                $imageURL = 'storage/defaults/images/notification.jpg';

                $data_array = array(
                    'title' => $request->input('nf_title'),
                    'message' => $request->input('nf_message')
                );

                $image = asset($imageURL);
            }

            //FCM api URL
            $url = 'https://fcm.googleapis.com/fcm/send';

            //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key = 'AAAAaZVge1s:APA91bFagv6aSs_1uVg-9lBwtnWM_AdBrm9d9cBjPJJm-N2mH1R77iNyF2emPWLf00vDkFXJ-xAU-o6V2l5-9IraWnHxbp9DadIjkzwvhv2yUl9Nz8D4YuDWWEOFCfVhZz67KqEyW5sZ';

            $fields = array();

            $fields['data'] = $data_array;

            $target = array();

            $success = 0;
            $failed = 0;

            if ($request->input('city') == 'All') {
                $userDevice = UserDevice::all();
                foreach ($userDevice as $key => $userDvc) {
                    $target[count($target)] = $userDvc->device_token;
                }

                $userNew = Users::where('device_token', '!=', 'Website Order')->get();
                foreach ($userNew as $key => $userItem) {
                    $target[count($target)] = $userItem->device_token;
                }
            } else {
                $cityName = $request->input('city');

                $userNew = Users::where('division', $cityName)->where('device_token', '!=', 'Website Order')->get();
                foreach ($userNew as $key => $userItem) {
                    $target[count($target)] = $userItem->device_token;
                }
            }

            $beginIndex = 0;
            $endIndex = 999;

            for ($i = 0; $i < ceil(count($target) / 999); $i++) {
                $chunks = array();
                $innerIndex = 0;
                for ($j = $beginIndex; $j < $endIndex; $j++) {
                    $chunks[$innerIndex] = $target[$j];
                    $innerIndex++;
                }

                $beginIndex = $endIndex;
                if (($endIndex + 999) > count($target)) {
                    $endIndex = count($target);
                } else {
                    $endIndex = $endIndex + 999;
                }

                if (is_array($chunks)) {
                    $fields['registration_ids'] = $chunks;
                } else {
                    $fields['to'] = $chunks;
                }

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
                $result = curl_exec($ch);
                curl_close($ch);
                $result = json_decode($result, true);

                $success +=  $result["success"];
                $failed += $result["failure"];
            }

            $new_notification = new Notification();
            $new_notification->notification_id = $randomID;
            $new_notification->title = $request->input('nf_title');
            $new_notification->message = $request->input('nf_message');
            $new_notification->image = $image;
            $new_notification->redirect = $redirect;
            $new_notification->success = $success;
            $new_notification->failed = $failed;
            $new_notification->status = "Sent";
            if ($new_notification->save()) {
                return redirect()->back()->with([
                    'error' => false,
                    'message' => "Notification sent successfully!"
                ]);
            } else {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => "Something went wrong!"
                ]);
            }
        }
    }

    public function getNotificationList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $notificationList = Notification::select('*')->orderBy('created_at', 'DESC')->get();

            return response()->json([
                'error' => false,
                'message' => $notificationList
            ]);
        }
    }

    public function sendNotificationToRider(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'nf_title' => 'required',
                'nf_message' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $data_array = array(
                    'title' => $request->input('nf_title'),
                    'message' => $request->input('nf_message')
                );

                //FCM api URL
                $url = 'https://fcm.googleapis.com/fcm/send';

                //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
                $server_key = 'AAAAtVpBXy4:APA91bE_oBziWEgT7L5bk7ySPkVmC27f4Uq7NddHvyVX5gGfQFdWMAv2YkXVU6xCXqveQTQrdHh9eccxYnVh04om4V22-fKwOZsiq8ccDtA7yRFGhguRyMd9yChcjifVmorA3hHoAs6X';

                $fields = array();

                $fields['data'] = $data_array;
                // $fields['notification'] = $data_array;

                $target = array();

                $userDevice = Rider::all();
                foreach ($userDevice as $key => $userDvc) {
                    $target[$key] = $userDvc->device_token;
                }

                if (is_array($target)) {
                    $fields['registration_ids'] = $target;
                } else {
                    $fields['to'] = $target;
                }

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
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);

                $result = json_decode($result, true);

                return redirect()->back()->with([
                    'error' => false,
                    'message' => "Notification sent to " . $result['success'] . " riders successfully!"
                ]);
            }
        } else {
            return redirect('/dashboard');
        }
    }
}
