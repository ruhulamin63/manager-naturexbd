<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Order;
use Illuminate\Http\Request;

class Aamarpay extends Controller
{
    public function index($order_id){
        $orderDetails = Order::where('order_id', $order_id)->get();
        if (count($orderDetails) == 0) {
            return view('PaymentGateway.bKash')->with([
                'invalid' => true,
                'order_id' => $order_id,
                'total' => 0.00
            ]);
        } else {
            $name = $orderDetails[0]->customer_name;
            $totalAmount = $orderDetails[0]->total_amount;
            $total = $totalAmount;
            return view('PaymentGateway.Aamarpay')->with([
                'invalid' => false,
                'name' => $name,
                'order_id' => $order_id,
                'totalAmount' => number_format($totalAmount, 2, '.', ''),
                'total' => number_format($total, 2, '.', '')
            ]);
        }
    }
}
