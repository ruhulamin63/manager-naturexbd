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
                            <div class="col-lg-4 col-sm-6 col-12 dashboard-users-warning">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                                <i class="bx bx-rocket font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Total Leads</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoLeads::all()) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12 dashboard-users-warning">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                                <i class="bx bxs-smiley-sad font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Pending</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoLeads::select('*')->where('status', 'Pending')->get()) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12 dashboard-users-warning">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bxs-smiley-happy font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Resolved</div>
                                            <h3 class="mb-0">{{ count(\App\Models\MangoLeads::select('*')->where('status', 'Resolved')->get()) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                        $permission = \App\Models\Grocery\Admin::select('*')->where('email', session()->get('GR_MANAGER_EMAIL'))->get();
                        $permission = $permission[0];
                        @endphp
                        @if(strpos($permission, 'upload_leads') !== false)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Upload Leads</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/leads/mango/upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-8">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="leads_csv" accept=".csv" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Leads CSV</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-4">
                                                <button type="submit" class="btn btn-block btn-success glow">Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                                                    <th>Source</th>
                                                    <th>Contact Information</th>
                                                    <th>Details</th>
                                                    <th>Remarks</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($leadsData as $key => $lead)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $lead->source }}</td>
                                                    <td>{{ $lead->name }}<br>{{ $lead->mobile }}</td>
                                                    <td>{!! nl2br($lead->details) !!}</td>
                                                    <td>{{ $lead->remarks }}</td>
                                                    @if($lead->status == "Pending")
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-danger">Pending</div>
                                                    </td>
                                                    @elseif($lead->status == "Resolved")
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-success">Resolved</div>
                                                    </td>
                                                    @endif
                                                    <td class="text-center">
                                                        <div class="badge badge-pill badge-light-warning round-cursor" onclick="showRemarksForm('{{ $lead->id }}')">Mark Resolved</div>
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

    <div class="modal fade text-left" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/grocery/leads/mango/resolve') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" name="leadID" id="leadID" value="" required hidden />
                                <fieldset class="form-group">
                                    <textarea class="form-control" name="remarks" id="basicTextarea" rows="5" placeholder="Add customer note" required></textarea>
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

    <script>
        function showRemarksForm(id) {
            $("#leadID").val(id);
            $("#remarksModal").modal('show');
        }
    </script>
</body>
@endsection