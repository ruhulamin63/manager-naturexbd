@extends('Layout_Grocery.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout_Grocery.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users list start -->
                <section class="users-list-wrapper">
                    <div class="users-list-filter">
                        @if(session()->has('error') && !session()->get('error'))
                        <div class="alert alert-success alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-like"></i>
                                <span>
                                    {{ session()->get('message') }}
                                </span>
                            </div>
                        </div>
                        @endif
                        @if(session()->has('error') && session()->get('error'))
                        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error"></i>
                                <span>
                                    {{ session()->get('message') }}
                                </span>
                            </div>
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Manage Order ({{ $orderDetails[0]->order_id }})</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('mango.update') }}" method="POST">
                                        @csrf
                                        <input type="text" name="orderID" value="{{ $orderDetails[0]-> order_id }}" hidden required />
                                        <div class="row">
                                            <div class="col-12 col-sm-12 mb-2" style="margin-top: 10px">
                                                <a href="{{ route('mango.payment.sms') . '?orderID=' . $orderDetails[0]->order_id }}">
                                                    <button type="button" class="btn btn-block btn-danger glow">Send Payment Info to Customer</button>
                                                </a>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="customer_name">Name</label>
                                                <input type="text" class="form-control" name="name" id="customer_name" placeholder="Enter customer name" value="{{ $orderDetails[0]->name }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <fieldset>
                                                    <label for="customer_mobile">Mobile</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">+88</span>
                                                        </div>
                                                        <input type="number" class="form-control" name="mobile" id="customer_mobile" maxlength="11" value="{{ $orderDetails[0]->mobile }}" aria-describedby="basic-addon1" placeholder="Enter customer mobile number" required autocomplete="off">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="thana">Thana</label>
                                                <input type="text" class="form-control" id="thana" name="thana" placeholder="Enter thana" value="{{ $orderDetails[0]->thana }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="zilla">Zilla</label>
                                                <input type="text" class="form-control" id="zilla" name="zilla" placeholder="Enter zilla" value="{{ $orderDetails[0]->zilla }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="quantity">Quantity</label>
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">In Kilogram (Kg)</span>
                                                        </div>
                                                        <input type="number" class="form-control" name="quantity" id="quantity" min="10" value="{{ $orderDetails[0]->quantity }}" aria-describedby="basic-addon1" placeholder="Enter quantity" required autocomplete="off">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="delivery_note">Delivery Note</label>
                                                <input type="text" class="form-control" id="delivery_note" name="delivery_note" placeholder="Enter delivery note" value="{{ $orderDetails[0]->delivery_note }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12mb-1">
                                                <hr>
                                                <h4 class="card-title">Payment Information</h4>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <label for="payment_method">Payment Method</label>
                                                <fieldset class="form-group">
                                                    <select name="payment_method" class="form-control" id="payment_method" required>
                                                        <option value="Select Payment Method" @if($orderDetails[0]->payment_method == "-") selected @endif>Select Payment Method</option>
                                                        <option value="bKash" @if($orderDetails[0]->payment_method == "bKash") selected @endif>bKash</option>
                                                        <option value="Bank" @if($orderDetails[0]->payment_method == "Bank") selected @endif>Bank</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <label for="trx_id">Trasaction ID</label>
                                                <input type="text" class="form-control" id="trx_id" name="trx_id" placeholder="Enter transaction id" value="{{ $orderDetails[0]->trx_id }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <label for="payment_status">Payment Status</label>
                                                <fieldset class="form-group">
                                                    <select class="form-control" id="payment_status" onchange="updatePaymentStatus('{{ $orderDetails[0]->order_id }}', this.value)" required>
                                                        <option value="Pending" @if($orderDetails[0]->payment_status == "Pending") selected @endif>Pending</option>
                                                        <option value="Pending Verification" @if($orderDetails[0]->payment_status == "Pending Verification") selected @endif>Pending Verification</option>
                                                        <option value="Verified" @if($orderDetails[0]->payment_status == "Verified") selected @endif>Verified</option>
                                                        <option value="Declined" @if($orderDetails[0]->payment_status == "Declined") selected @endif>Declined</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12mb-1">
                                                <hr>
                                                <h4 class="card-title">Courier Information</h4>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="courier">Courier Info</label>
                                                <input type="text" class="form-control" id="courier" name="courier" placeholder="Enter courier info" value="{{ $orderDetails[0]->courier }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label for="tracking_id">Tracking ID</label>
                                                <input type="text" class="form-control" id="tracking_id" name="tracking" placeholder="Enter tracking id" value="{{ $orderDetails[0]->tracking }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12mb-1">
                                                <hr>
                                                <h4 class="card-title">Order Status</h4>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <label for="order_status">Order Status</label>
                                                <fieldset class="form-group">
                                                    <select class="form-control" id="order_status" onchange="updateOrderStatus('{{ $orderDetails[0]->order_id }}', this.value)" required>
                                                        <option value="Pending Confirmation" @if($orderDetails[0]->order_status == "Pending Confirmation") selected @endif>Pending Confirmation</option>
                                                        <option value="Confirmed" @if($orderDetails[0]->order_status == "Confirmed") selected @endif>Confirmed</option>
                                                        <option value="Submitted for Delivery" @if($orderDetails[0]->order_status == "Submitted for Delivery") selected @endif>Submitted for Delivery</option>
                                                        <option value="Delivery Dispatched" @if($orderDetails[0]->order_status == "Delivery Dispatched") selected @endif>Delivery Dispatched</option>
                                                        <option value="Delivered" @if($orderDetails[0]->order_status == "Delivered") selected @endif>Delivered</option>
                                                        <option value="Cancelled" @if($orderDetails[0]->order_status == "Cancelled") selected @endif>Cancelled</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <table class="table table-bordered mt-2 mb-5" id="cartTable">
                                                    <thead>
                                                        <tr>
                                                            <td>SN</td>
                                                            <td>Item Details</td>
                                                            <td class='text-center'>Quantity</td>
                                                            <td class='text-center'>Unit Price</td>
                                                            <td class='text-center'>Total Price</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cartItems">
                                                        <tr>
                                                            <td>1</td>
                                                            <td>{{ $orderDetails[0]->type }}</td>
                                                            <td class="text-center"><span>{{ $orderDetails[0]->quantity }}</span> Kg</td>
                                                            <td class="text-center"><span>{{ number_format($orderDetails[0]->sell_price/$orderDetails[0]->quantity, 2) }}</span> Tk</td>
                                                            <td class="text-center"><span>{{ $orderDetails[0]->sell_price }}</span> Tk</td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Sell Rate (10 Kg)</td>
                                                            <td>
                                                                <input type="number" class="text-center form-control" name="sell_price" min="0" value="{{ $orderDetails[0]->sell_price == '' ? '0' : $orderDetails[0]->sell_price }}" required autocomplete="off">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Delivery Charge</td>
                                                            <td>
                                                                <input type="number" class="text-center form-control" id="delivery_charge" name="delivery_charge" min="0" value="{{ $orderDetails[0]->delivery == '' ? '0' : $orderDetails[0]->delivery }}" required autocomplete="off">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Trade Price for 20 Kg</td>
                                                            <td>
                                                                <input type="number" class="text-center form-control" id="trade_price" name="trade_price" min="0" value="{{ ($orderDetails[0]->trade_price/$orderDetails[0]->quantity)*20 }}" required autocomplete="off">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Total Trade Price</td>
                                                            <td>
                                                                <input type="number" class="text-center form-control" id="total_trade_price" min="0" value="{{ $orderDetails[0]->trade_price }}" required autocomplete="off">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Profit</td>
                                                            <td>
                                                                <input type="number" class="text-center form-control" id="profit_price" min="0" value="{{ $orderDetails[0]->sell_price - $orderDetails[0]->trade_price }}" autocomplete="off" disabled>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Update Order</button>
                                            </div>
                                            <div class="col-12 text-center mt-5">
                                                <h4><b>Order Timeline</b></h4>
                                            </div>
                                            @php
                                            $timeline = $orderDetails[0]->timeline;
                                            $timeline = explode(',', $timeline);
                                            @endphp
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <table class="table table-bordered mt-2 mb-5">
                                                    <thead>
                                                        <tr>
                                                            <td class="text-center">Timestamp</td>
                                                            <td>Description</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($timeline as $key => $item)
                                                        <tr>
                                                            <td class="text-center">{{ explode('_', $item)[0] }}</td>
                                                            <td>{{ explode('_', $item)[1] }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users list ends -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>
        function updatePaymentStatus(orderID, status) {
            Swal.fire({
                title: 'Updating...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('mango.payment.update') }}",
                type: "POST",
                data: {
                    orderID: orderID,
                    status: status
                },
                success: function(result) {
                    if (!result['error']) {
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'danger',
                            title: 'Something went wrong',
                            text: 'Please try again'
                        });
                    }
                }
            });
        }

        function updateOrderStatus(orderID, status) {
            Swal.fire({
                title: 'Updating...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('mango.status.update') }}",
                type: "POST",
                data: {
                    orderID: orderID,
                    status: status
                },
                success: function(result) {
                    if (!result['error']) {
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'danger',
                            title: 'Something went wrong',
                            text: 'Please try again'
                        });
                    }
                }
            });
        }
    </script>
</body>
@endsection