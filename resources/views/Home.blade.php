@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
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
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Statistics</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                            <i class="bx bx-user font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">New Users</div>
                                        <h3 class="mb-0">{{ number_format(count(\App\Models\UserInfo::all())) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                            <i class="bx bxs-group font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Old Users</div>
                                        <h3 class="mb-0">{{ number_format(count(\App\Models\Backend\OldUser::all())) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Server Billing</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                            <i class="bx bx-dollar-circle font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Remaining Credit</div>
                                        <h3 class="mb-0">${{ number_format($remainingCredit, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                            <i class="bx bx-wallet-alt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Charged this Month ({{ date('M') }})</div>
                                        <h3 class="mb-0">${{ number_format($chargedThisMonth, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                            <i class="bx bxs-server font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Total Disk Space</div>
                                        <h3 class="mb-0">{{ number_format($diskSpace/(1024*1024*1024), 2) }} GB</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                            <i class="bx bxs-pie-chart-alt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Free Disk Space</div>
                                        <h3 class="mb-0">{{ number_format($freeDiskSpace/(1024*1024*1024), 2) }} GB</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                            <i class="bx bxs-pie-chart font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Used Disk Space</div>
                                        <h3 class="mb-0">{{ number_format($usedDiskSpace/(1024*1024*1024), 2) }} GB</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                            <i class="bx bx-calendar-event font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Last Payment Date</div>
                                        <h3 class="mb-0">{{ $lastPaymentDate }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                            <i class="bx bx-credit-card-alt font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Last Payment Amount</div>
                                        <h3 class="mb-0">${{ number_format($lastPaymentAmount, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Bulk SMS Service</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-danger">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                            <i class="bx bx-dollar-circle font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">SMS Balance</div>
                                        <h3 class="mb-0">৳{{ number_format($smsBalance, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 dashboard-users-warning">
                            <div class="card text-center">
                                <div class="card-content">
                                    <div class="card-body py-1">
                                        <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                            <i class="bx bx-calendar-event font-medium-5"></i>
                                        </div>
                                        <div class="text-muted line-ellipsis">Expiry Date</div>
                                        <h3 class="mb-0">{{ $smsBalanceExpiry }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout.footer')

    @include('Layout.scripts')
</body>
@endsection