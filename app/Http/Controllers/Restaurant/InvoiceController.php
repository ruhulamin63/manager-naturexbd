<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Order;
use App\Restaurant\resOrder;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
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

    public function orderInvoice(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'print_order_invoice')) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'order' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $details = resOrder::select('*')->where('order_id', $request->input('order'))
                        ->where('customer_name', $request->input('name'))->get();

                    $invoicePicture= DB::select("SELECT * FROM grocery_invoice_picture WHERE status=1 ORDER BY id DESC LIMIT 3;");

                    $subtotal = $details[0]->product_total;
                    $deliveryCharge = $details[0]->delivery_charge;
                    $total = $details[0]->total_amount;
                    $discount = $details[0]->discount;

                    return view('Invoice.OrderInvoice')
                        ->with('title', 'Order Invoice | Print')
                        ->with('details', $details)
                        ->with('subtotal', $subtotal)
                        ->with('deliveryCharge', $deliveryCharge)
                        ->with('discount', $discount)
                        ->with('orderId', $request->input('order'))
                        ->with('invoicePicture', $invoicePicture)
                        ->with('total', $total);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function dealerInvoice(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'print_order_invoice')) {
                $validator = Validator::make($request->all(), [
                    'from_date' => 'required',
                    'to_date' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $fromDate = $request->input('from_date');
                    $toDate = $request->input('to_date');

                    $fromDate = date('Y-m-d', strtotime($fromDate));
                    $orgToDate = date('Y-m-d', strtotime($toDate));
                    $toDate = date('Y-m-d', strtotime('+1 day', strtotime($orgToDate)));

                    $dealerInvoice = array();

                    $period = new DatePeriod(
                        new DateTime($fromDate),
                        new DateInterval('P1D'),
                        new DateTime($toDate)
                    );

                    $index = 0;

                    foreach ($period as $key => $value) {
                        $date = $value->format('d-M-Y');

                        $orders = Order::select('*')
                            ->where('city_name', 'Dhaka')
                            ->where('scheduled_date', 'LIKE', '%' . $date . '%')
                            ->where('order_status', 'Ongoing')
                            ->get();

                        if (count($orders) != 0) {
                            foreach ($orders as $orderData) {
                                $dealerInvoice[$index]['orderID'] = $orderData->order_id;
                                $dealerInvoice[$index]['customerName'] = $orderData->customer_name;
                                $dealerInvoice[$index]['orderTime'] = $orderData->created_at;

                                $orderItems = array();
                                $tIndex = 0;

                                foreach (json_decode($orderData->order_data, true) as $item) {
                                    $productID = $item["a"];
                                    $productName = $item["b"];
                                    $productPrice = $item["c"];
                                    $productQuantity = $item["d"];
                                    $productDescription = $item["e"];
                                    $productImage = $item["f"];

                                    $orderItems[$tIndex]['productName'] = $productName;
                                    $orderItems[$tIndex]['productDescription'] = $productDescription;
                                    $orderItems[$tIndex]['productQuantity'] = $productQuantity;
                                    $tIndex++;
                                }
                                $dealerInvoice[$index]['product_info'] = $orderItems;
                                $dealerInvoice[$index]['product_count'] = $tIndex;
                                $index++;
                            }
                        }
                    }

                    if ($index == 0) {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'No ongoing order found for Dhaka in the selected date'
                        ]);
                    } else {
                        return view('Invoice.DealerInvoice')
                            ->with('title', 'Dealer Invoice | Print')
                            ->with('details', $dealerInvoice)
                            ->with('city', "Dhaka")
                            ->with('from_date', $fromDate)
                            ->with('to_date', $orgToDate);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function invoiceAddImage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'add_product')) {
                $validator = Validator::make($request->all(), [
                    'invoiceImag_status' => 'required',
                    'invoiceImag_img' => 'required',
                    'invoiceImag_des' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                
                    $input = $request->all();
                    $details= $input['invoiceImag_des'];
                    $status= $input['invoiceImag_status'];

                    $imageID = strtoupper(Str::random(6));
                    $extension = request()->invoiceImag_img->getClientOriginalExtension();
                    $request->invoiceImag_img->storeAs('public/temp', $imageID . '.' . $extension);
                    $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                    Storage::disk('grocery_products')->put($imageID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/grocery/products/' . $imageID . '.' . $extension; 

                    $data=DB::insert("INSERT INTO grocery_invoice_picture (details, image, status) VALUES ('$details', '$imageURL','$status');");
                    
                    if($data==null){
                        return redirect('/grocery/invoiceManage')->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                    else{
                        return redirect('/grocery/invoiceManage')->with([
                            'error' => false,
                            'message' => 'New Image Added Successfully.'
                        ]);
                    }

                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function invoiceImageUpdateStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            // if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $id = $request->input('id');
                    $Status = $request->input('status');
                    $update = DB::update("UPDATE grocery_invoice_picture SET status= '$Status' WHERE id = '$id';");
                    
                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Status updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            // } else {
            //     return redirect(url('/dashboard/page/unauthorized'));
            // }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
