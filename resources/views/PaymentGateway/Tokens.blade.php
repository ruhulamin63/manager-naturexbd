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
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="six-item-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Order ID</th>
                                                    <th class="text-center">Init. Time</th>
                                                    <th class="text-center">Time Left</th>
                                                    <th class="text-center">Expired On</th>
                                                    <th class="text-center">Payment Channel</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tokens as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $item->order_id }}</td>
                                                    <td class="text-center">{{ date('d M Y h:i:s A', $item->init_time) }}</td>
                                                    <td class="text-center">{{ ((time() - $item->init_time)/60) <= 30 ? round((30-((time() - $item->init_time)/60))) : 0 }} Min</td>
                                                    <td class="text-center">{{ $item->expired_on == 'N/A' ? 'N/A': date('d M Y h:i:s A', $item->expired_on) }}</td>
                                                    <td class="text-center">{{ $item->payment_channel }}</td>
                                                    @if($item->current_status == "Pending")
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-warning">{{ $item->current_status }}</div>
                                                    </td>
                                                    @elseif($item->current_status == "Expired")
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-danger">{{ $item->current_status }}</div>
                                                    </td>
                                                    @elseif($item->current_status == "Completed")
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-success">{{ $item->current_status }}</div>
                                                    </td>
                                                    @endif
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