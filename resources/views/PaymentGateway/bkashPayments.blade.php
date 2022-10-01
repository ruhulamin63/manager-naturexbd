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
                                        <table id="seven-item-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Trx ID</th>
                                                    <th class="text-center">Debit MSISDN</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Trx Status</th>
                                                    <th class="text-center">Timestamp</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payments as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $item->trxID }}</td>
                                                    <td class="text-center">{{ $item->debitMSISDN }}</td>
                                                    <td class="text-center">BDT {{ $item->amount }}</td>
                                                    <td class="text-center">{{ $item->trxStatus }}</td>
                                                    <td class="text-center">{{ date('d M Y h:i:s A', strtotime($item->updated_at)) }}</td>
                                                    <td class="text-center">
                                                        @if(strpos($item->trxStatus, 'Refund') !== false)
                                                        <div class="badge badge-pill badge-success mb-1">Refund Processed</div>
                                                        @else
                                                        <a href="#!" onclick="issue_refund('{{ $item->trxID }}')">
                                                            <div class="badge badge-pill badge-danger mb-1 round-cursor">Issue Refund</div>
                                                        </a>
                                                        @endif
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

    <div class="modal fade text-left" id="bkashRefund" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Issue Refund</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/payment/bkash/trx/refund') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" name="trxID" id="trxID" value="" required hidden />
                                <div class="form-group">
                                    <label>Refund Amount</label>
                                    <input type="text" name="amount" class="form-control" placeholder="Enter refund amount" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Refund Reason</label>
                                    <input type="text" name="reason" class="form-control" placeholder="Enter refund reason" required autocomplete="off">
                                </div>
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
                            <span class="d-none d-sm-block">Issue Refund</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function issue_refund(trxID) {
            $("#trxID").val(trxID);
            $("#bkashRefund").modal('show');
        }
    </script>
</body>
@endsection