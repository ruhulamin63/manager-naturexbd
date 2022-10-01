<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\Backend\SMSHistory;
use App\Models\BkashPayments;
use App\Models\BkashRefunds;
use App\Models\bKashValidator;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Category;
use App\Models\Grocery\City;
use App\Models\Grocery\Order;
use App\Restaurant\resOrder;
use App\Models\PaymentAuthorization;
use App\Models\PaymentURL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PaymentController extends Controller
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

    private function sendMessage($sendTo, $message)
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
                'number' => $sendTo,
                'sms_content' => $message,
                'sms_type' => '1',
                'masking' => 'non-masking'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);

        $newSMSHistory = new SMSHistory();
        $newSMSHistory->campaign = "Payment Message";
        $newSMSHistory->sendTo = $sendTo;
        $newSMSHistory->message = $message;
        $newSMSHistory->totalSMS = $response['Total Valid Numbers'];
        $newSMSHistory->totalCost = $response['Total Cost'];
        $newSMSHistory->save();
    }

    private function generate_url($destination, $branding)
    {
        $domain_data["fullName"] = "rebrand.ly";
        if ($branding) {
            $domain_data["fullName"] = "pay.naturexbd.com";
        }
        $post_data["destination"] = $destination;
        $post_data["domain"] = $domain_data;
        $post_data["slashtag"] = strtoupper(Str::random(6));
        //$post_data["title"] = "Rebrandly YouTube channel";
        $ch = curl_init("https://api.rebrandly.com/v1/links");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "apikey: 7e84c39cd2d54059accda0d35b52f760",
            "Content-Type: application/json",
            "workspace: d5da8204d31b47a69518f2eae9bfe56d"
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        return $response;
    }

    private function delete_url($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.rebrandly.com/v1/links/' . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "apikey: 7e84c39cd2d54059accda0d35b52f760",
            "Content-Type: application/json",
            "workspace: d5da8204d31b47a69518f2eae9bfe56d"
        ));
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }

    public function tokens(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'transactions')) {
                $tokens = PaymentAuthorization::orderBy('created_at', 'DESC')->get();
                $cityList = City::all();
                return view('PaymentGateway.Tokens')
                    ->with('title', 'Payment Tokens')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('tokens', $tokens);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function generate_tokens(Request $request, $order_id)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'generate_trx_token')) {
                $validity_check = Order::where('order_id', $order_id)->get();
                if (count($validity_check) == 1) {
                    $existing_auth_check = PaymentAuthorization::where('order_id', $order_id)->get();
                    if (count($existing_auth_check) == 1) {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Payment token already exists. Please contact system admin to re-initiate token.'
                        ]);
                    } else {
                        $token = sha1($order_id . time() . rand());

                        $new_payment_auth = new PaymentAuthorization();
                        $new_payment_auth->order_id = $order_id;
                        $new_payment_auth->token = $token;
                        $new_payment_auth->init_time = time();
                        $new_payment_auth->timeline = time() . "_" . "Payment Inititated Successfully.\nIP: " . $request->ip();
                        $new_payment_auth->save();

                        $url_generator = $this->generate_url("https://wallet.naturexbd.com/payment?token=" . $token, true);
                        $payment_url = $url_generator["shortUrl"];
                        $message = "Please complete your payment by following the url: " . $payment_url . "\n\nThe url will expire in 30 minutes.";

                        $new_url = new PaymentURL();
                        $new_url->order_id = $order_id;
                        $new_url->destination = "https://wallet.naturexbd.com/payment?token=" . $token;
                        $new_url->short_url = $payment_url;
                        $new_url->uid = $url_generator["id"];
                        $new_url->status = "Active";
                        $new_url->save();

                        $customerNumber = $validity_check[0]->contact_number;
                        if (strlen($customerNumber) == 13) {
                            $customerNumber = "+" . $customerNumber;
                        } elseif (strlen($customerNumber) == 11) {
                            $customerNumber = "+88" . $customerNumber;
                        }

                        $this->sendMessage($customerNumber, $message);

                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Payment token initited successfully.'
                        ]);
                    }
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Invalid order id.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function generate_res_tokens(Request $request, $order_id)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'generate_trx_token')) {
                $validity_check = resOrder::where('order_id', $order_id)->get();
                if (count($validity_check) == 1) {
                    $existing_auth_check = PaymentAuthorization::where('order_id', $order_id)->get();
                    if (count($existing_auth_check) == 1) {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Payment token already exists. Please contact system admin to re-initiate token.'
                        ]);
                    } else {
                        $token = sha1($order_id . time() . rand());

                        $new_payment_auth = new PaymentAuthorization();
                        $new_payment_auth->order_id = $order_id;
                        $new_payment_auth->token = $token;
                        $new_payment_auth->init_time = time();
                        $new_payment_auth->timeline = time() . "_" . "Payment Inititated Successfully.\nIP: " . $request->ip();
                        $new_payment_auth->save();

                        $url_generator = $this->generate_url("https://wallet.naturexbd.com/payment?token=" . $token, true);
                        $payment_url = $url_generator["shortUrl"];
                        $message = "Please complete your payment by following the url: " . $payment_url . "\n\nThe url will expire in 30 minutes.";

                        $new_url = new PaymentURL();
                        $new_url->order_id = $order_id;
                        $new_url->destination = "https://wallet.naturexbd.com/payment?token=" . $token;
                        $new_url->short_url = $payment_url;
                        $new_url->uid = $url_generator["id"];
                        $new_url->status = "Active";
                        $new_url->save();

                        $customerNumber = $validity_check[0]->contact_number;
                        if (strlen($customerNumber) == 13) {
                            $customerNumber = "+" . $customerNumber;
                        } elseif (strlen($customerNumber) == 11) {
                            $customerNumber = "+88" . $customerNumber;
                        }

                        $this->sendMessage($customerNumber, $message);

                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Payment token initited successfully.'
                        ]);
                    }
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Invalid order id.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function generate_android_tokens(Request $request)
    {
        $order_id = $request->input('orderID');
        $validity_check = Order::where('order_id', $order_id)->get();
        if (count($validity_check) == 1) {
            $existing_auth_check = PaymentAuthorization::where('order_id', $order_id)->get();
            if (count($existing_auth_check) == 1) {
                return response()->json([
                    'error' => false,
                    'message' => 'Payment token already exists. Please contact system admin to re-initiate token.',
                    'token' => $existing_auth_check[0]->token
                ]);
            } else {
                $token = sha1($order_id . time() . rand());

                $new_payment_auth = new PaymentAuthorization();
                $new_payment_auth->order_id = $order_id;
                $new_payment_auth->token = $token;
                $new_payment_auth->init_time = time();
                $new_payment_auth->timeline = time() . "_" . "Payment Inititated Successfully.\nIP: " . $request->ip();
                $new_payment_auth->save();

                return response()->json([
                    'error' => false,
                    'message' => 'Payment token initited successfully.',
                    'token' => $token
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Invalid order id.'
            ]);
        }
    }

    public function walletHome(Request $request)
    {
        $hostname = $request->getHost();
        if ($hostname == "manage.naturexbd.com" || $hostname == "wallet.naturexbd.com") {
            if ($request->has('token')) {
                $token = $request->input('token');
                $payment_authorization = PaymentAuthorization::where('token', $token)->get();
                if (count($payment_authorization) == 1) {
                    $init_time = $payment_authorization[0]->init_time;
                    $current_time = time();
                    if (($current_time - $init_time) <= (60 * 60) && $payment_authorization[0]->current_status == "Pending") {
                    // if (($current_time - $init_time) <= (599760 * 60) && $payment_authorization[0]->current_status == "Pending") {
                        $order_id = $payment_authorization[0]->order_id;
                        $orderDetails = Order::where('order_id', $order_id)->get();
                        if (count($orderDetails) == 0) {
                            return view('PaymentGateway.Payment')->with([
                                'invalid' => true,
                                'name' => '',
                                'order_id' => $order_id,
                                'invoice_id' => 'Naturex',
                                'total' => 0.00,
                                'complete' => false
                            ]);
                        } else {
                            $name = $orderDetails[0]->customer_name;
                            $totalAmount = $orderDetails[0]->total_amount;
                            // $bkashFee = $orderDetails[0]->total_amount * 0.015;
                            // $total = $totalAmount + $bkashFee;
                            $invoice_id = $order_id . "-" . strtoupper(Str::random(4));
                            $bKashValidator = new bKashValidator();
                            $bKashValidator->invoice_id = $invoice_id;
                            $bKashValidator->amount = number_format($totalAmount, 2, '.', '');
                            $bKashValidator->save();

                            return view('PaymentGateway.Payment')->with([
                                'invalid' => false,
                                'name' => $name,
                                'order_id' => $order_id,
                                'invoice_id' => $invoice_id,
                                'total' => number_format($totalAmount, 2, '.', ''),
                                'complete' => false
                            ]);
                        }
                    } else if ($payment_authorization[0]->current_status == "Completed") {
                        if ($request->has('status') && $request->input('status') == "success") {
                            return view('PaymentGateway.Payment')->with([
                                'invalid' => false,
                                'name' => '',
                                'order_id' => 'Naturex',
                                'invoice_id' => 'Naturex',
                                'total' => 0.00,
                                'complete' => true
                            ]);
                        } else {
                            $url = "https://manage.naturexbd.com/payment?token=" . $token . "&status=success";
                            return redirect($url);
                        }
                    }
                    else {
                        $timeline = $payment_authorization[0]->timeline;
                        PaymentAuthorization::where('token', $token)->update([
                            'expired_on' => time(),
                            'current_status' => 'Expired',
                            'timeline' => $timeline . "," . time() . "_Payment Token Expired.\nIP:" . $request->ip()
                        ]);

                        $order_id = $payment_authorization[0]->order_id;
                        $url_id = PaymentURL::where('order_id', $order_id)->get();
                        $this->delete_url($url_id[0]->uid);

                        PaymentURL::where('order_id', $order_id)->delete();

                        return view('PaymentGateway.Unauthorized')
                            ->with('title', 'Naturex Wallet | Unauthorized');
                    }
                } else {
                    return view('PaymentGateway.Unauthorized')
                        ->with('title', 'Naturex Wallet | Unauthorized');
                }
            } else {
                return view('PaymentGateway.Unauthorized')
                    ->with('title', 'Naturex Wallet | Unauthorized');
            }
        } else {
            return view('PaymentGateway.Unauthorized')
                ->with('title', 'Naturex Wallet | Unauthorized');
        }
    }

    public function clear_payment_junk(Request $request)
    {
        $payment_authorization = PaymentAuthorization::all();
        foreach ($payment_authorization as $key => $item) {
            $current_time = time();
            $init_time = $item->init_time;
            if (($current_time - $init_time) > (60 * 60) && $item->current_status != "Expired") {
                PaymentAuthorization::where('token', $item->token)->update([
                    'expired_on' => time(),
                    'current_status' => 'Expired'
                ]);

                $order_id = $item->order_id;
                $url_id = PaymentURL::where('order_id', $order_id)->get();
                if (count($url_id) > 0) {
                    $this->delete_url($url_id[0]->uid);

                    PaymentURL::where('order_id', $order_id)->delete();
                }
            }
        }
    }

    public function bkash_payments(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'transactions')) {
                $payments = BkashPayments::orderBy('created_at', 'DESC')->get();
                $cityList = City::all();
                return view('PaymentGateway.bkashPayments')
                    ->with('title', 'bKash Payments')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('payments', $payments);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function bkash_refunds(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'transactions')) {
                $refunds = BkashRefunds::orderBy('created_at', 'DESC')->get();
                $cityList = City::all();
                return view('PaymentGateway.bkashRefunds')
                    ->with('title', 'bKash Payment Refunds')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('refunds', $refunds);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
