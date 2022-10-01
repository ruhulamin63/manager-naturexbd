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
                                            <div class="text-muted line-ellipsis">Total Orders</div>
                                            <h3 class="mb-0">{{ count(\App\Restaurant\resOrder::select('*')->where('city_id', $cityID)->get()) }}</h3>
                                            <small>৳ {{ \App\Restaurant\resOrder::where('city_id', $cityID)->sum('total_amount') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                                <i class="bx bx-calendar-event font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Today's Orders</div>
                                            <h3 class="mb-0">{{ count(\App\Restaurant\resOrder::select('*')->where('city_id', $cityID)->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->get()) }}</h3>
                                            <small>৳ {{ \App\Restaurant\resOrder::where('city_id', $cityID)->where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('total_amount') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                                <i class="bx bx-receipt font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Pending</div>
                                            <h3 class="mb-0">{{ count(\App\Restaurant\resOrder::select('*')->where('order_status', 'Pending')->where('city_id', $cityID)->get()) }}</h3>
                                            <small>৳ {{ \App\Restaurant\resOrder::where('order_status', 'Pending')->where('city_id', $cityID)->sum('total_amount') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-warning">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                                <i class="bx bxs-error font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Ongoing</div>
                                            <h3 class="mb-0">{{ count(\App\Restaurant\resOrder::select('*')->where('order_status', 'Ongoing')->where('city_id', $cityID)->get()) }}</h3>
                                            <small>৳ {{ \App\Restaurant\resOrder::where('order_status', 'Ongoing')->where('city_id', $cityID)->sum('total_amount') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-warning">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bxs-smiley-happy font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Delivered</div>
                                            <h3 class="mb-0">{{ count(\App\Restaurant\resOrder::select('*')->where('order_status', 'Delivered')->where('city_id', $cityID)->get()) }}</h3>
                                            <small>৳ {{ \App\Restaurant\resOrder::where('order_status', 'Delivered')->where('city_id', $cityID)->sum('total_amount') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12 dashboard-users-warning">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                                <i class="bx bxs-smiley-sad font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Cancelled</div>
                                            <h3 class="mb-0">{{ count(\App\Restaurant\resOrder::select('*')->where('order_status', 'Cancelled')->where('city_id', $cityID)->get()) }}</h3>
                                            <small>৳ {{ \App\Restaurant\resOrder::where('order_status', 'Cancelled')->where('city_id', $cityID)->sum('total_amount') }} Tk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" id="navTop">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ url('/restaurant/order') }}" method="GET">
                                            <input type="text" name="city" value="{{ $cityID }}" hidden required />
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="text" name="schedule" class="form-control format-picker" placeholder="Select Date" required>
                                                <div class="form-control-position">
                                                    <i class='bx bx-calendar'></i>
                                                </div>
                                            </fieldset>
                                            <button type="submit" class="btn btn-success btn-block">Filter Delivery by Selected Date</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12" id="changeSchedule" hidden>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ url('/restaurant/order/schedule') }}" method="POST">
                                            @csrf
                                            <input type="text" class="form-control mb-1" id="customer_name" disabled>
                                            <input type="text" class="form-control mb-1" id="customer_mobile" disabled>
                                            <input type="text" class="form-control mb-1" id="sc_order_id" name="order_id" required hidden>
                                            <input type="text" class="form-control mb-1" id="sc_order" disabled>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="text" id="d1" name="schedule" class="form-control format-picker" placeholder="Select Date" required>
                                                <div class="form-control-position">
                                                    <i class='bx bx-calendar'></i>
                                                </div>
                                            </fieldset>
                                            <button type="submit" class="btn btn-primary btn-block">Change Order Schedule</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if(Request()->get('schedule') != "")
                            <div class="col-sm-12">
                                <div class="alert alert-primary alert-dismissible mb-2" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-error"></i>
                                        <span>
                                            Showing orders scheduled on {{ Request()->get('schedule') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Order Details</th>
                                                    <th>Total Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $permission = \App\Models\Grocery\Admin::select('*')->where('email', session()->get('GR_MANAGER_EMAIL'))->get();
                                                $permission = $permission[0];
                                                @endphp
                                                @foreach($orderList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td style="width: 40%">
                                                        <small>
                                                            <b>Order ID:</b> {{ $item->order_id }}
                                                            <br>
                                                            <b>Name:</b> {{ $item->customer_name }}
                                                            <br>
                                                            <b>Mobile:</b> {{ $item->contact_number }}
                                                            <br>
                                                            <b>Address:</b> {{ $item->delivery_address }}
                                                            <br>
                                                            <b>Delivery Note:</b> {{ $item->delivery_note }}
                                                            <br>
                                                            <b>Order Time:</b> {{ date('d-M-Y h:i A', strtotime($item->created_at)) }}
                                                            <br>
                                                            <b>Scheduled On:</b> {{ date('d-M-Y', strtotime($item->scheduled_date)) }}
                                                            @if($item->order_status == "Delivered")
                                                            <br>
                                                            <b>Delivered On:</b> {{ date('d-M-Y', strtotime($item->updated_at)) }}
                                                            @endif
                                                        </small>
                                                    </td>
                                                    <td style="width: 30%">
                                                        <b>Product Price:</b> {{ $item->product_total }} Tk
                                                        <br>
                                                        <b>Delivery Charge:</b> {{ $item->delivery_charge }} Tk
                                                        <br>
                                                        <b>Discount:</b> {{ $item->discount }} Tk
                                                        <br>
                                                        <b>Total:</b> {{ $item->total_amount }} Tk
                                                    </td>
                                                    @if($item->order_status == "Pending")

                                                    @if(substr($item->order_id,3,1)=='W')
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-primary mb-1">Pending</div>
                                                        <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetailsWeb('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                    </td>
                                                    @else
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-primary mb-1">Pending</div>
                                                        <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetails('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                    </td>
                                                    @endif
        
                                                    @elseif($item->order_status == "Ongoing")
                                                        @if(substr($item->order_id,3,1)=='W' || substr($item->order_id,3,1)=='M')
                                                        <td class="text-center">
                                                            <div class="badge badge-pill badge-light-warning mb-1">Ongoing</div>
                                                            <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetailsWeb('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                            <a href="{{ url('/restaurant/print/invoice?name=' . $item->customer_name . '&order=' . $item->order_id) }}" target="_blank">
                                                                <div class="badge badge-pill badge-light-secondary mb-1 round-cursor">Print Invoice</div>
                                                            </a>
                                                        </td>
                                                        @else
                                                        <td class="text-center">
                                                            <div class="badge badge-pill badge-light-warning mb-1">Ongoing</div>
                                                            <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetails('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                            <a href="{{ url('/restaurant/print/invoice?name=' . $item->customer_name . '&order=' . $item->order_id) }}" target="_blank">
                                                                <div class="badge badge-pill badge-light-secondary mb-1 round-cursor">Print Invoice</div>
                                                            </a>
                                                        </td>
                                                        @endif

                                                    @elseif($item->order_status == "Delivered")
                                                        @if(substr($item->order_id,3,1)=='W' || substr($item->order_id,3,1)=='M')
                                                        <td class="text-center">
                                                            <div class="badge badge-pill badge-light-success mb-1">Delivered</div>
                                                            <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetailsWeb('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                        </td>
                                                        @else
                                                        <td class="text-center">
                                                            <div class="badge badge-pill badge-light-success mb-1">Delivered</div>
                                                            <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetails('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                        </td>
                                                        @endif
                                                    @elseif($item->order_status == "Cancelled")
                                                        @if(substr($item->order_id,3,1)=='W' || substr($item->order_id,3,1)=='M')
                                                        <td class="text-center">
                                                            <div class="badge badge-pill badge-light-danger mb-1">Cancelled</div>
                                                            <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetailsWeb('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                        </td>
                                                        @else
                                                        <td class="text-center">
                                                            <div class="badge badge-pill badge-light-danger mb-1">Cancelled</div>
                                                            <div class="badge badge-pill badge-light-success mb-1 round-cursor" onclick="showOrderDetails('{{ $item->order_data }}', '{{ $item->product_total }}', '{{ $item->delivery_charge }}', '{{ $item->discount }}', '{{ $item->total_amount }}')">Order Details</div>
                                                        </td>
                                                        @endif
                                                    @endif
                                                    <td class="text-center">
                                                        @if(strpos($permission, 'edit_order') !== false && $item->order_status != "Cancelled" && $item->order_status != "Delivered")
                                                        <!-- <a href="{{ url('/restaurant/order/edit?id=' . $item->order_id . '&city=' . Request::get('city')) }}">
                                                            <div class="badge badge-pill badge-secondary mb-1 round-cursor">Edit Order</div>
                                                        </a> -->
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'complete_order') !== false && $item->order_status == "Ongoing")
                                                        <div class="badge badge-pill badge-warning mb-1 round-cursor" onclick="completeOrder('{{ $item->order_id }}')">Complete Order</div>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'confirm_order') !== false && $item->order_status == "Pending")
                                                        <div class="badge badge-pill badge-success mb-1 round-cursor" onclick="confirmOrder('{{ $item->order_id }}')">Confirm Order</div>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'cancel_order') !== false && $item->order_status == "Pending")
                                                        <div class="badge badge-pill badge-danger mb-1 round-cursor" onclick="cancelOrder('{{ $item->order_id }}')">Cancel Order</div>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'force_cancel_order') !== false)
                                                        <div class="badge badge-pill badge-danger mb-1 round-cursor" onclick="cancelOrder('{{ $item->order_id }}')">Force Cancel Order</div>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'force_pending_order') !== false)
                                                        <div class="badge badge-pill badge-primary mb-1 round-cursor" onclick="pendingOrder('{{ $item->order_id }}')">Force Pending Order</div>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'apply_discount') !== false)
                                                        <div class="badge badge-pill badge-warning mb-1 round-cursor" onclick="applyDiscount('{{ $item->order_id }}')">Apply Discount</div>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'change_schedule') !== false && $item->order_status != "Delivered" && $item->order_status != "Cancelled")
                                                        <a href="#navTop">
                                                            <div class="badge badge-pill badge-primary mb-1 round-cursor" onclick="changeSchedule('{{ $item->order_id }}','{{ $item->customer_name }}','{{ $item->contact_number }}')">Change Schedule</div>
                                                        </a>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'generate_trx_token') !== false && $item->order_status != "Delivered" && $item->order_status != "Cancelled")
                                                        <a href="{{ url('/payment/Res/tokens/generate/' . $item->order_id) }}">
                                                            <div class="badge badge-pill badge-success mb-1 round-cursor">Generate Payment Token</div>
                                                        </a>
                                                        <br>
                                                        @endif
                                                        @if(strpos($permission, 'delete_order') !== false)
                                                        <a href="{{ url('/restaurant/order/delete?id=' . $item->order_id) }}">
                                                            <div class="badge badge-pill badge-secondary mb-1 round-cursor">Delete Order</div>
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 col-12 overflow-auto">
                                            {!! $orderList->links() !!}
                                        </div>
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

    <div class="modal fade text-left" id="orderDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Order Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Description</th>
                                    <th class='text-center'>Quantity</th>
                                    <th class='text-center'>Unit Price</th>
                                    <th class='text-center' style="width: 20%">Total</th>
                                </tr>
                            </thead>
                            <tbody id="orderDetailsTable">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="applyDiscount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Apply Discount</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/restaurant/order/edit/discount') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" id="order_id" name="order_id" value="" hidden />
                            <div class="col-12 col-sm-12 mb-1">
                                <input type="number" class="form-control" name="discount" placeholder="Enter discount amount" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="changeSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Change Schedule</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/grocery/order/edit/discount') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" id="sc_order_id" name="order_id" value="" hidden />
                            <div class="col-sm-12" style="height: 500px">
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" id="d2" name="schedule" class="form-control format-picker" placeholder="Select Date" required>
                                    <div class="form-control-position">
                                        <i class='bx bx-calendar'></i>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="cancelRemarks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/restaurant/order/cancel') }}" method="GET">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" name="id" id="orderID" value="" required hidden />
                                <fieldset class="form-group">
                                    <textarea class="form-control" name="remarks" id="cancelRemarks" rows="5" placeholder="Add cancel reason" required></textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-danger ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Cancel Order</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showOrderDetails(orderData, productTotal, deliveryCharge, discount, totalAmount) {
            $("#orderDetailsTable").html("");
            var products = JSON.parse(orderData);
            for (var i = 0; i < products.length; i++) {
                var row = "<tr><td class='text-center'>" + (i + 1) + "</td><td>" + products[i].b + "<br><small>" + products[i].e + "</small></td><td class='text-center'>" + products[i].d + "</td><td class='text-center'>" + products[i].c + " Tk</td><td class='text-center'>" + (products[i].d * products[i].c) + " Tk</td></tr>";
                $("#orderDetailsTable").append(row);
            }
            var row = "<tr><td colspan='4' class='text-right'><b>Subtotal</b></td><td class='text-center'>" + parseInt(productTotal) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            var row = "<tr><td colspan='4' class='text-right'><b>Delivery Charge</b></td><td class='text-center'>" + parseInt(deliveryCharge) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            var row = "<tr><td colspan='4' class='text-right'><b>Discount</b></td><td class='text-center'>" + parseInt(discount) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            var row = "<tr><td colspan='4' class='text-right'><b>Total</b></td><td class='text-center'>" + parseInt(totalAmount) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            $("#orderDetails").modal('show');
        }

    
        function cancelOrder(orderID) {
            $("#orderID").val(orderID);
            $("#cancelRemarks").modal('show');
        }

        function confirmOrder(orderID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: {
                    margin: 10
                }
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure to confirm order?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    url = '{{ url("/restaurant/order/confirm") }}';
                    url = url + "?id=" + orderID;
                    window.location.href = url;
                }
            });
        }

        function completeOrder(orderID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: {
                    margin: 10
                }
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure to complete order?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    url = '{{ url("/restaurant/order/complete") }}';
                    url = url + "?id=" + orderID;
                    window.location.href = url;
                }
            });
        }

        function pendingOrder(orderID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: {
                    margin: 10
                }
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure to complete order?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    url = '{{ url("/restaurant/order/pending") }}';
                    url = url + "?id=" + orderID;
                    window.location.href = url;
                }
            });
        }

        function applyDiscount(orderID) {
            $("#order_id").val(orderID);
            $("#applyDiscount").modal('show');
        }

        function changeSchedule(orderID, name, mobile) {
            $("#sc_order_id").val(orderID);
            $("#sc_order").val(orderID);
            $("#customer_name").val(name);
            $("#customer_mobile").val(mobile);
            $("#changeSchedule").removeAttr('hidden');
        }

        function showOrderDetailsWeb(orderData, productTotal, deliveryCharge, discount, totalAmount) {
            $("#orderDetailsTable").html("");
            var products = JSON.parse(orderData);
            for (var i = 0; i < products.length; i++) {
                var row = "<tr><td class='text-center'>" + (i + 1) + "</td><td>" + products[i].product_name + "<br><small> </small></td><td class='text-center'>" + products[i].quantity + "</td><td class='text-center'>" + products[i].unit_price + " Tk</td><td class='text-center'>" + products[i].total_price  + " Tk</td></tr>";
                $("#orderDetailsTable").append(row);
            }
            var row = "<tr><td colspan='4' class='text-right'><b>Subtotal</b></td><td class='text-center'>" + parseInt(productTotal) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            var row = "<tr><td colspan='4' class='text-right'><b>Delivery Charge</b></td><td class='text-center'>" + parseInt(deliveryCharge) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            var row = "<tr><td colspan='4' class='text-right'><b>Discount</b></td><td class='text-center'>" + parseInt(discount) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            var row = "<tr><td colspan='4' class='text-right'><b>Total</b></td><td class='text-center'>" + parseInt(totalAmount) + " Tk</td></tr>";
            $("#orderDetailsTable").append(row);
            $("#orderDetails").modal('show');
        }


    </script>
</body>
@endsection