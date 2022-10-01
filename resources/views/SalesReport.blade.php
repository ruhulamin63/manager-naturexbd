@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <section id="pick-a-date">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Select Date & Generate Report</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ url('/v1/generateReport') }}" method="POST">
                                        @csrf
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="text" name="date" class="form-control format-picker" placeholder="Select Date" required>
                                            <div class="form-control-position">
                                                <i class='bx bx-calendar'></i>
                                            </div>
                                        </fieldset>
                                        <button type="submit" class="btn btn-success btn-block">Generate Report</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @if($date != "NONE")
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Order Summary ({{ date('d-M-Y', strtotime($date)) }})</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                            <i class="bx bx-receipt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Total Orders</div>
                                        <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                            <i class="bx bx-receipt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Unique Orders</div>
                                        <h3 class="mb-0">{{ number_format($uniqueOrders) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                            <i class="bx bx-receipt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Multi-Restaurant Orders</div>
                                        <h3 class="mb-0">{{ number_format($multiRestaurantOrder) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                            <i class="bx bxs-smiley-happy font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Delivered Orders</div>
                                        <h3 class="mb-0">{{ number_format($deliveredOrders) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                            <i class="bx bxs-smiley-sad font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Cancelled Orders</div>
                                        <h3 class="mb-0">{{ number_format($cancelledOrders) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Transaction Summary ({{ date('d-M-Y', strtotime($date)) }})</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                            <i class="bx bx-receipt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Total Order Amount</div>
                                        <h3 class="mb-0">৳{{ number_format($totalBill) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                            <i class="bx bxs-smiley-happy font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Delivered Order Amount</div>
                                        <h3 class="mb-0">৳{{ number_format($deliveredBill) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                            <i class="bx bxs-smiley-sad font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Cancelled Order Amount</div>
                                        <h3 class="mb-0">৳{{ number_format($cancelledBill) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="users-list-wrapper">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Order List by Users ({{ date('d-M-Y', strtotime($date)) }})</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="orderListUser-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th class="text-center">Order ID</th>
                                                    <th>Customer Name</th>
                                                    <th class="text-center">Item Details</th>
                                                    <th class="text-center">Order Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Order Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allOrders as $key => $order)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>#{{ $order['orderID'] }}</td>
                                                    <td>
                                                        {{ $order['name'] }}
                                                        <small>({{ $order['contact'] }})</small><br>
                                                        <small>{{ $order['address'] }}</small>
                                                    </td>
                                                    <td>{!! $order['itemDetails'] !!}</td>
                                                    <td class="text-center">৳{{ $order['amount'] }}</td>
                                                    <td class="text-center">{{ $order['status'] }}</td>
                                                    <td class="text-center">{{ $order['created_at'] }}</td>
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
                <section class="users-list-wrapper">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Order List by Restaurants ({{ date('d-M-Y', strtotime($date)) }})</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="orderListRestaurant-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Restaurant Name</th>
                                                    <th class="text-center">Total Orders</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($restaurantOrders as $key => $order)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $order['name'] }}</td>
                                                    <td class="text-center">{{ $order['orders'] }}</td>
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
                @endif
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout.footer')

    @include('Layout.scripts')
</body>
@endsection