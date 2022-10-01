<?php

namespace App\Http\Controllers;

use App\Models\Grocery\Users;
use Illuminate\Http\Request;

class FacebookChatController extends Controller
{
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

    public function notify(Request $request)
    {
        if ($request->has('message')) {
            $title = "ফেসবুক পেজ মেসেজ";
            $message = "ফেসবুক পেজে নতুন মেসেজ এসেছে। কেউ রিপ্লাই করুন এখনি।\n\nনামঃ " . $request->input('fname') . " " . $request->input('lname') . "\nমেসেজঃ " . $request->input('message');

            $data_array = array('title' => $title, 'message' => $message);

            $userInfo = Users::select('*')->where('mobile', '+8801797718470')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $userInfo = Users::select('*')->where('mobile', '+8801746742421')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $text = array();
            $text[0]['text'] = "আমরা খুব দ্রুত আপনার সমস্যা সমাধান করার চেষ্টা করছি। কিছুটা সময় দিন।";

            return response()->json([
                "messages" => $text
            ]);
        } else if ($request->has('problem')) {
            $title = "ফেসবুক পেজ কমপ্লেইন";
            $message = "";
            if($request->has('order')){
                $message = "ফেসবুক পেজে নতুন কমপ্লেইন এসেছে। কেউ রিপ্লাই করুন এখনি।\n\nনামঃ " . $request->input('fname') . " " . $request->input('lname') . "\nঅর্ডার আইডিঃ " . $request->input('order') . "\nকমপ্লেইনঃ " . $request->input('problem');
            } else {
                $message = "ফেসবুক পেজে নতুন কমপ্লেইন এসেছে। কেউ রিপ্লাই করুন এখনি।\n\nনামঃ " . $request->input('fname') . " " . $request->input('lname') . "\nকমপ্লেইনঃ " . $request->input('problem');
            }

            $data_array = array('title' => $title, 'message' => $message);

            $userInfo = Users::select('*')->where('mobile', '+8801797718470')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $userInfo = Users::select('*')->where('mobile', '+8801746742421')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $text = array();
            $text[0]['text'] = "আমরা খুব দ্রুত আপনার সমস্যা সমাধান করার চেষ্টা করছি। কিছুটা সময় দিন।";

            return response()->json([
                "messages" => $text
            ]);
        } else {
            $title = "ফেসবুক পেজ মেসেজ";
            $message = "ফেসবুক পেজে নতুন মেসেজ এসেছে। চেক করুন পেজে। অটো রিপ্লাই না হলে, ম্যানুয়েলি রিপ্লাই করবেন।";

            $data_array = array('title' => $title, 'message' => $message);

            $userInfo = Users::select('*')->where('mobile', '+8801797718470')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $userInfo = Users::select('*')->where('mobile', '+8801701034237')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);

            $userInfo = Users::select('*')->where('mobile', '+8801746742421')->get();
            $this->sendNotification($data_array, $userInfo[0]->device_token);
        }
    }
}
