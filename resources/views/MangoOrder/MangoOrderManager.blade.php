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
                        <div class="row">
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto mb-50">
                                                <i class="bx bx-calculator font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Pending Confirmation</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('order_status', "Pending Confirmation")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('order_status', "Pending Confirmation")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                                <i class="bx bx-calculator font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Confirmed</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('order_status', "Confirmed")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('order_status', "Confirmed")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                                <i class="bx bx-calculator font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Submitted for Delivery</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('order_status', "Submitted for Delivery")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('order_status', "Submitted for Delivery")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                                <i class="bx bx-calculator font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Delivery Dispatched</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('order_status', "Delivery Dispatched")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('order_status', "Delivery Dispatched")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bx-calculator font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Delivered</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('order_status', "Delivered")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('order_status', "Delivered")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                                <i class="bx bx-calculator font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Cancelled</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('order_status', "Cancelled")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('order_status', "Cancelled")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto mb-50">
                                                <i class="bx bx-money font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Pending Payment</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('payment_status', "Pending")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('payment_status', "Pending")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                                <i class="bx bx-money font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Pending Payment Verification</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('payment_status', "Pending Verification")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('payment_status', "Pending Verification")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bx-money font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Gross Profit</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('payment_status', "Verified")->get()) }}</h3>
                                            <small>৳ {{ (\App\Models\MangoOrder::where('payment_status', "Verified")->sum('sell_price')) - (\App\Models\MangoOrder::where('payment_status', "Verified")->sum('trade_price')) }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                                <i class="bx bx-money font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Declined Payments</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoOrder::where('payment_status', "Declined")->get()) }}</h3>
                                            <small>৳ {{ \App\Models\MangoOrder::where('payment_status', "Declined")->sum('sell_price') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 pr-0 pl-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('mango.promote.sms') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-lg-8">
                                            <fieldset>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">+88</span>
                                                    </div>
                                                    <input type="number" class="form-control" name="mobile" id="customer_mobile" maxlength="11" aria-describedby="basic-addon1" placeholder="Enter customer mobile number" required autocomplete="off">
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <button type="submit" class="btn btn-success btn-block">Send Message to Customer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="five-item-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Information</th>
                                                    <th class="text-center">Payment Status</th>
                                                    <th class="text-center">Order Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orderList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        <b>Order ID: </b>{{ $item->order_id }}
                                                        <br>
                                                        <b>Type: </b>{{ $item->type }}
                                                        <br>
                                                        <b>Name: </b>{{ ucwords($item->name) }}
                                                        <br>
                                                        <b>Contact: </b>{{ $item->mobile }}
                                                        <br>
                                                        <b>Delivery Note: </b>{{ $item->delivery_note }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->trx_id != "-")
                                                        <div class="badge badge-pill badge-success mb-1" style="background-color: #e2136e !important;">{{ $item->trx_id }}</div>
                                                        <br>
                                                        @endif
                                                        @if($item->payment_status == "Pending")
                                                        <div class="badge badge-pill badge-info mb-1">{{ $item->payment_status }}</div>
                                                        @elseif($item->payment_status == "Pending Verification")
                                                        <div class="badge badge-pill badge-warning mb-1">{{ $item->payment_status }}</div>
                                                        @elseif($item->payment_status == "Verified")
                                                        <div class="badge badge-pill badge-success mb-1">{{ $item->payment_status }}</div>
                                                        @elseif($item->payment_status == "Declined")
                                                        <div class="badge badge-pill badge-danger mb-1">{{ $item->payment_status }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->order_status == "Pending Confirmation")
                                                        <div class="badge badge-pill badge-info mb-1">{{ $item->order_status }}</div>
                                                        @elseif($item->order_status == "Confirmed")
                                                        <div class="badge badge-pill badge-primary mb-1">{{ $item->order_status }}</div>
                                                        @elseif($item->order_status == "Submitted for Delivery")
                                                        <div class="badge badge-pill badge-secondary mb-1">{{ $item->order_status }}</div>
                                                        @elseif($item->order_status == "Delivery Dispatched")
                                                        <div class="badge badge-pill badge-warning mb-1">{{ $item->order_status }}</div>
                                                        @elseif($item->order_status == "Delivered")
                                                        <div class="badge badge-pill badge-success mb-1">{{ $item->order_status }}</div>
                                                        @elseif($item->order_status == "Cancelled")
                                                        <div class="badge badge-pill badge-danger mb-1">{{ $item->order_status }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('mango.manage') . '?orderID=' . $item->order_id }}">
                                                            <div class="badge badge-pill badge-success mb-1 round-cursor">Manage Order</div>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- datatable ends -->
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
</body>
@endsection