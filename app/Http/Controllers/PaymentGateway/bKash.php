<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\bKashHistory;
use App\Models\BkashPayments;
use App\Models\BkashRefunds;
use App\Models\bKashValidator;
use App\Models\Grocery\Order;
use App\Models\PaymentAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class bKash extends Controller
{
    // Sandbox
    // private $baseURL = "https://checkout.sandbox.bka.sh/v1.2.0-beta";

    // Production
    private $baseURL = "https://checkout.pay.bka.sh/v1.2.0-beta";

    //Stage 1
    // private $app_secret = "1vggbqd4hqk9g96o9rrrp2jftvek578v7d2bnerim12a87dbrrka";
    // private $app_key = "5tunt4masn6pv2hnvte1sb5n3j";

    // private $username = "sandboxTestUser";
    // private $password = "hWD@8vtzw0";

    // Stage 2
    // private $app_secret = "1honf6u1c56mqcivtc9ffl960slp4v2756jle5925nbooa46ch62";
    // private $app_key = "5nej5keguopj928ekcj3dne8p";

    // private $username = "testdemo";
    // private $password = "test%#de23@msdao";

    // Production
    private $app_secret = "o0mrnblvkl5haese7ut7ol8olfolk2lvhrt4i33h5oe0m35dsug";
    private $app_key = "3ip0o28liq91svr05h2js5oq7v";

    private $username = "KHAIDAITODAY";
    private $password = "Hk7@Ai9dt4yuC";

    private function grant_token()
    {
        $request_data = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );
        $url = curl_init($this->baseURL . '/checkout/token/grant');
        $request_data_json = json_encode($request_data);
        $header = array(
            'Content-Type:application/json',
            'username:' . $this->username,
            'password:' . $this->password
        );
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $response = curl_exec($url);
        curl_close($url);

        return json_decode($response, true);
    }

    private function refresh_token($refresh_token)
    {
        $request_data = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret,
            'refresh_token' => $refresh_token
        );
        $url = curl_init($this->baseURL . '/checkout/token/refresh');
        $request_data_json = json_encode($request_data);
        $header = array(
            'Content-Type:application/json',
            'username:' . $this->username,
            'password:' . $this->password
        );
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $response = curl_exec($url);
        curl_close($url);

        return json_decode($response, true);
    }

    public function create_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'invoice' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $amount = $request->input('amount');
            $invoice = $request->input('invoice');

            $bkashValidator = bKashValidator::where('invoice_id', $invoice)->get();

            if(count($bkashValidator) == 1){
                $orderID = explode('-', $invoice);
                $orderID = $orderID[0] . "-" . $orderID[1] . "-" . $orderID[2];
    
                $orderDetails = Order::where('order_id', $orderID)->get();
                $total_amount = $orderDetails[0]->total_amount;
                // $total_amount = $total_amount + $total_amount * 0.015;
                $total_amount = number_format($total_amount, 2, '.', '');
    
                if ($total_amount == $amount) {
                    $token = $this->grant_token();
    
                    $request_data = array(
                        'amount' => $amount,
                        'currency' => 'BDT',
                        'intent' => 'sale',
                        'merchantInvoiceNumber' => $invoice
                    );
                    $url = curl_init($this->baseURL . '/checkout/payment/create');
                    $request_data_json = json_encode($request_data);
                    $header = array(
                        'Content-Type:application/json',
                        'authorization:' . $token['id_token'],
                        'x-app-key:' . $this->app_key
                    );
    
                    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
                    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    
                    $response = curl_exec($url);
                    curl_close($url);
    
                    $create_payment_response = json_decode($response, true);
    
                    $message = "Grant Token => " . date('d-M-Y h:i:s A') . "\n";
    
                    $bkashHistory = new bKashHistory();
                    $bkashHistory->order_id = $orderID;
                    $bkashHistory->invoice_number = $invoice;
                    $bkashHistory->grant_token = $token['id_token'];
                    $bkashHistory->refresh_token = $token['refresh_token'];
                    $bkashHistory->token_time = time();
    
                    if (array_key_exists("paymentID", $create_payment_response)) {
                        $message = $message . "Create Payment => " . date('d-M-Y h:i:s A') . "\n";
                        $bkashHistory->payment_id = $create_payment_response['paymentID'];
                        $bkashHistory->amount = $amount;
                        $bkashHistory->history = $message;
                        $bkashHistory->trxID = "";
                        $bkashHistory->transactionStatus = "";
                    } else {
                        $message = $message . "Create Payment => Status Code: " . $create_payment_response['errorCode'] . ", Message: " . $create_payment_response['errorMessage'] . "\n";
                        $bkashHistory->payment_id = "N/A";
                        $bkashHistory->amount = "";
                        $bkashHistory->history = $message;
                        $bkashHistory->trxID = "";
                        $bkashHistory->transactionStatus = "Failed";
                    }
                    $bkashHistory->save();
    
                    return response()->json([
                        'data' => $response,
                        // 'token' => $token
                    ]);
                } else {
                    $response = array();
                    $response['errorCode'] = "000" . $total_amount . "_" . $amount;
                    $response['errorMessage'] = "Invalid invoice amount.";
                    return response()->json([
                        'data' => json_encode($response),
                        // 'token' => null
                    ]);
                }
            } else {
                $response = array();
                $response['errorCode'] = "999";
                $response['errorMessage'] = "Invalid invoice ID.";
                return response()->json([
                    'data' => json_encode($response),
                    // 'token' => null
                ]);
            }            
        }
    }

    public function execute_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paymentID' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = "";
            $paymentID = $request->input('paymentID');

            $paymentInfo = bKashHistory::select('*')->where('payment_id', $paymentID)->get();

            if (count($paymentInfo) == 1) {
                $currentTime = time();
                if (((($paymentInfo[0]->token_time) - $currentTime) / 60) > 50) {
                    $message = $paymentInfo[0]->history . "Refresh Token => " . date('d-M-Y h:i:s A') . "\n";

                    $token = $this->refresh_token($paymentInfo[0]->refresh_token);
                    $token = $token['id_token'];

                    bKashHistory::where('payment_id', $paymentID)->update([
                        'grant_token' => $token,
                        'refresh_token' => $token['refresh_token'],
                        'token_time' => time(),
                        'history' => $message
                    ]);
                } else {
                    $token = $paymentInfo[0]->grant_token;
                }
            }

            $url = curl_init($this->baseURL . '/checkout/payment/execute/' . $paymentID);
            $header = array(
                'Content-Type:application/json',
                'authorization:' . $token,
                'x-app-key:' . $this->app_key
            );

            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

            $response = curl_exec($url);
            curl_close($url);

            $trxDetails = json_decode($response, true);

            if (!isset($trxDetails['errorCode'])) {
                $message = $paymentInfo[0]->history . "Execute Payment => " . date('d-M-Y h:i:s A');

                bKashHistory::where('payment_id', $paymentID)->update([
                    'amount' => $trxDetails['amount'],
                    'trxID' => $trxDetails['trxID'],
                    'transactionStatus' => $trxDetails['transactionStatus'],
                    'history' => $message
                ]);

                $orderID = bKashHistory::where('payment_id', $paymentID)->get();
                $orderID = $orderID[0]->order_id;

                $auth_timeline = PaymentAuthorization::where('order_id', $orderID)->get();
                $timeline = $auth_timeline[0]->timeline;
                $timeline = $timeline . "," . time() . "_Payment Completed by Bkash.\nTrx. ID: " . $trxDetails['trxID'] . "\nIP:" . $request->ip();
                PaymentAuthorization::where('order_id', $orderID)->update([
                    'timeline' => $timeline,
                    'payment_channel' => 'bKash',
                    'current_status' => 'Completed'
                ]);
            } else {
                $message = $paymentInfo[0]->history . "Execute Payment Failed => " . date('d-M-Y h:i:s A');

                bKashHistory::where('payment_id', $paymentID)->update([
                    'trxID' => $trxDetails['errorMessage'],
                    'transactionStatus' => 'Error ' . $trxDetails['errorCode'],
                    'history' => $message
                ]);
            }

            return response()->json($response);
        }
    }

    public function query_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paymentID' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = "";
            $paymentID = $request->input('paymentID');

            $token = $this->grant_token();

            $url = curl_init($this->baseURL . '/checkout/payment/query/' . $paymentID);
            $header = array(
                'Content-Type:application/json',
                'authorization:' . $token['id_token'],
                'x-app-key:' . $this->app_key
            );

            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

            $response = curl_exec($url);
            curl_close($url);

            $trxDetails = json_decode($response, true);
            if (!isset($trxDetails['errorCode'])) {
                $paymentInfo = bKashHistory::select('*')->where('payment_id', $paymentID)->get();
                $message = $paymentInfo[0]->history . "Execute Payment => " . date('d-M-Y h:i:s A');

                bKashHistory::where('payment_id', $paymentID)->update([
                    'amount' => $trxDetails['amount'],
                    'trxID' => $trxDetails['trxID'],
                    'transactionStatus' => $trxDetails['transactionStatus'],
                    'history' => $message
                ]);

                $orderData = bKashHistory::where('payment_id', $paymentID)->get();
                $orderID = $orderData[0]->order_id;

                $auth_timeline = PaymentAuthorization::where('order_id', $orderID)->get();
                $timeline = $auth_timeline[0]->timeline;
                $timeline = $timeline . "," . time() . "_Payment Completed by Bkash.\nTrx. ID: " . $trxDetails['trxID'] . "\nIP:" . $request->ip();
                PaymentAuthorization::where('order_id', $orderID)->update([
                    'timeline' => $timeline,
                    'payment_channel' => 'bKash',
                    'current_status' => 'Completed'
                ]);
            } else {
                $paymentInfo = bKashHistory::select('*')->where('payment_id', $paymentID)->get();
                $message = $paymentInfo[0]->history . "Execute Payment Failed => " . date('d-M-Y h:i:s A');

                bKashHistory::where('payment_id', $paymentID)->update([
                    'trxID' => $trxDetails['errorMessage'],
                    'transactionStatus' => 'Error ' . $trxDetails['errorCode'],
                    'history' => $message
                ]);
            }

            return response()->json($response);
        }
    }

    public function search_transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trxID' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = "";
            $trxID = $request->input('trxID');

            $token = $this->grant_token();

            $url = curl_init($this->baseURL . '/checkout/payment/search/' . $trxID);
            $header = array(
                'Content-Type:application/json',
                'authorization:' . $token['id_token'],
                'x-app-key:' . $this->app_key
            );

            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

            $response = curl_exec($url);
            curl_close($url);

            return response()->json($response);
        }
    }

    public function refund_transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trxID' => 'required',
            'amount' => 'required',
            'reason' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = "";
            $trxID = $request->input('trxID');

            $existing = BkashRefunds::where('original_trxID', $trxID)->get();

            if (count($existing) > 0) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'Refund failed! Another refund already requested for this trx ID.'
                ]);
            } else {
                $paymentInfo = bKashHistory::select('*')->where('trxID', $trxID)->get();

                if (count($paymentInfo) == 0) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Refund failed! This payment was not completed through bKash API.'
                    ]);
                } else {
                    $trxAmount = $paymentInfo[0]->amount;

                    if ($request->input('amount') > $trxAmount) {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Refund failed! Refund amount can not be greater than the trx amount.'
                        ]);
                    } else {
                        $token = $this->grant_token();

                        $request_data = array(
                            'paymentID' => $paymentInfo[0]->payment_id,
                            'amount' => $request->input('amount'),
                            'trxID' => $trxID,
                            'sku' => $paymentInfo[0]->order_id,
                            'reason' => $request->input('reason')
                        );

                        $url = curl_init($this->baseURL . '/checkout/payment/refund');
                        $request_data_json = json_encode($request_data);
                        $header = array(
                            'Content-Type:application/json',
                            'authorization:' . $token['id_token'],
                            'x-app-key:' . $this->app_key
                        );

                        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
                        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

                        $response = curl_exec($url);
                        curl_close($url);

                        $trxDetails = json_decode($response, true);
                        if (!isset($trxDetails['errorCode'])) {
                            $newRefund = new BkashRefunds();
                            $newRefund->original_trxID = $trxDetails['originalTrxID'];
                            $newRefund->refund_trxID = $trxDetails['refundTrxID'];
                            $newRefund->amount = $trxDetails['amount'];
                            $newRefund->charge = $trxDetails['charge'];
                            $newRefund->status = $trxDetails['transactionStatus'];
                            $newRefund->timestamp = $trxDetails['completedTime'];
                            $newRefund->save();

                            if ($trxAmount == $trxDetails['amount']) {
                                BkashPayments::where('trxID', $trxDetails['originalTrxID'])->update([
                                    'trxStatus' => "Full Refund (BDT " . $trxDetails['amount'] . ")"
                                ]);
                            } else {
                                BkashPayments::where('trxID', $trxDetails['originalTrxID'])->update([
                                    'trxStatus' => "Partial Refund (BDT " . $trxDetails['amount'] . ")"
                                ]);
                            }

                            return redirect()->back()->with([
                                'error' => false,
                                'message' => "Refund complete! Amount: " . $trxDetails['amount'] . " BDT. Charge: " . $trxDetails['charge'] . " BDT. Find more details on bKash Refund page."
                            ]);
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => $trxDetails['errorMessage']
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function refund_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trxID' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = "";
            $trxID = $request->input('trxID');
            $paymentInfo = bKashHistory::select('*')->where('trxID', $trxID)->get();

            if (count($paymentInfo) == 0) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'No payment inititated from API.'
                ]);
            } else {
                $paymentID = $paymentInfo[0]->payment_id;
                $trxAmount = $paymentInfo[0]->amount;

                $paymentInfo = bKashHistory::select('*')->where('payment_id', $paymentID)->get();

                $token = $this->grant_token();

                $request_data = array(
                    'paymentID' => $paymentID,
                    'trxID' => $paymentInfo[0]->trxID
                );

                $url = curl_init($this->baseURL . '/checkout/payment/refund');
                $request_data_json = json_encode($request_data);
                $header = array(
                    'Content-Type:application/json',
                    'authorization:' . $token['id_token'],
                    'x-app-key:' . $this->app_key
                );

                curl_setopt($url, CURLOPT_HTTPHEADER, $header);
                curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
                curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

                $response = curl_exec($url);
                curl_close($url);

                $trxDetails = json_decode($response, true);
                if (!isset($trxDetails['errorCode'])) {
                    $existing = BkashRefunds::where('original_trxID', $trxDetails['originalTrxID'])->get();

                    if (count($existing) == 0) {
                        $newRefund = new BkashRefunds();
                        $newRefund->original_trxID = $trxDetails['originalTrxID'];
                        $newRefund->refund_trxID = $trxDetails['refundTrxID'];
                        $newRefund->amount = $trxDetails['amount'];
                        $newRefund->charge = $trxDetails['charge'];
                        $newRefund->status = $trxDetails['transactionStatus'];
                        $newRefund->timestamp = $trxDetails['completedTime'];
                        $newRefund->save();

                        if ($trxAmount == $trxDetails['amount']) {
                            BkashPayments::where('trxID', $trxDetails['originalTrxID'])->update([
                                'trxStatus' => "Full Refund (BDT " . $trxDetails['amount'] . ")"
                            ]);
                        } else {
                            BkashPayments::where('trxID', $trxDetails['originalTrxID'])->update([
                                'trxStatus' => "Partial Refund (BDT " . $trxDetails['amount'] . ")"
                            ]);
                        }
                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => "Refund complete! Amount: " . $trxDetails['amount'] . " BDT. Charge: " . $trxDetails['charge'] . " BDT. Find more details on bKash Refund page."
                    ]);
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => $trxDetails['errorMessage']
                    ]);
                }
            }
        }
    }

    public function organization_balance(Request $request)
    {
        $token = $this->grant_token();

        $url = curl_init($this->baseURL . '/checkout/payment/organizationBalance');
        $header = array(
            'Content-Type:application/json',
            'authorization:' . $token['id_token'],
            'x-app-key:' . $this->app_key
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

        $response = curl_exec($url);
        curl_close($url);

        return response()->json($response);
    }

    public function intra_account_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'transferType' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = $this->grant_token();

            $request_data = array(
                'amount' => $request->input('amount'),
                'currency' => 'BDT',
                'transferType' => $request->input('transferType')
            );

            $url = curl_init($this->baseURL . '/checkout/payment/intraAccountTransfer');
            $request_data_json = json_encode($request_data);
            $header = array(
                'Content-Type:application/json',
                'authorization:' . $token['id_token'],
                'x-app-key:' . $this->app_key
            );

            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

            $response = curl_exec($url);
            curl_close($url);

            return response()->json($response);
        }
    }

    public function b2c_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'merchantInvoiceNumber' => 'required',
            'receiverMSISDN' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $token = $this->grant_token();

            $request_data = array(
                'amount' => $request->input('amount'),
                'currency' => 'BDT',
                'merchantInvoiceNumber' => $request->input('merchantInvoiceNumber'),
                'receiverMSISDN' => $request->input('receiverMSISDN'),
            );

            $url = curl_init($this->baseURL . '/checkout/payment/b2cPayment');
            $request_data_json = json_encode($request_data);
            $header = array(
                'Content-Type:application/json',
                'authorization:' . $token['id_token'],
                'x-app-key:' . $this->app_key
            );

            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

            $response = curl_exec($url);
            curl_close($url);

            return response()->json($response);
        }
    }

    private function writeLog($logName, $logData)
    {
        file_put_contents('./bKashLog/log-' . $logName . date("j.n.Y") . '.log', $logData, FILE_APPEND);
    }

    private function get_content($URL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function getStringToSign($message)
    {
        $signableKeys = [
            'Message',
            'MessageId',
            'Subject',
            'SubscribeURL',
            'Timestamp',
            'Token',
            'TopicArn',
            'Type'
        ];

        $stringToSign = '';

        if ($message['SignatureVersion'] !== '1') {
            $errorLog =  "The SignatureVersion \"{$message['SignatureVersion']}\" is not supported.";
            $this->writeLog('SignatureVersion-Error', $errorLog);
        } else {
            foreach ($signableKeys as $key) {
                if (isset($message[$key])) {
                    $stringToSign .= "{$key}\n{$message[$key]}\n";
                }
            }
            $this->writeLog('StringToSign', $stringToSign . "\n");
        }
        return $stringToSign;
    }

    private function validateUrl($url)
    {
        $defaultHostPattern = '/^sns\.[a-zA-Z0-9\-]{3,}\.amazonaws\.com(\.cn)?$/';
        $parsed = parse_url($url);

        if (empty($parsed['scheme']) || empty($parsed['host']) || $parsed['scheme'] !== 'https' || substr($url, -4) !== '.pem' || !preg_match($defaultHostPattern, $parsed['host'])) {
            return false;
        } else {
            return true;
        }
    }

    public function webhook_endpoint()
    {
        //payload
        $payload  = (array) json_decode(file_get_contents('php://input'));
        $this->writeLog('Payload', $payload);

        // headers
        $messageType = $_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'];

        //verify signature
        $signingCertURL = $payload['SigningCertURL'];
        $certUrlValidation = $this->validateUrl($signingCertURL);
        if ($certUrlValidation == '1') {
            $pubCert = $this->get_content($signingCertURL);

            $signature = $payload['Signature'];
            $signatureDecoded = base64_decode($signature);

            $content = $this->getStringToSign($payload);
            if ($content != '') {
                $verified = openssl_verify($content, $signatureDecoded, $pubCert, OPENSSL_ALGO_SHA1);
                if ($verified == '1') {
                    if ($messageType == "SubscriptionConfirmation") {
                        $subscribeURL = $payload['SubscribeURL'];
                        $this->writeLog('Subscribe', $subscribeURL);
                        //subscribe
                        $url = curl_init($subscribeURL);
                        curl_exec($url);
                    } else if ($messageType == "Notification") {
                        $notificationData = $payload['Message'];
                        $this->writeLog('NotificationData-Message', $notificationData);

                        $notificationData = json_decode($notificationData, true);

                        $newPayment = new BkashPayments();
                        $newPayment->dateTime = $notificationData['dateTime'];
                        $newPayment->debitMSISDN = $notificationData['debitMSISDN'];
                        $newPayment->trxID = $notificationData['trxID'];
                        $newPayment->trxStatus = $notificationData['transactionStatus'];
                        $newPayment->amount = $notificationData['amount'];
                        $newPayment->save();
                    }
                }
            }
        }
    }
}
