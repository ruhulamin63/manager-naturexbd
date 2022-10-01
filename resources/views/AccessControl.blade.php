@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users list start -->
                <section class="users-list-wrapper">
                    <div class="users-list-filter px-1">
                        <form>
                            <div class="row border rounded py-2 mb-2">
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label for="users-list-verified">Verified</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="users-list-verified">
                                            <option value="">Any</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label for="users-list-role">Role</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="users-list-role">
                                            <option value="">Any</option>
                                            <option value="User">User</option>
                                            <option value="Staff">Staff</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label for="users-list-status">Status</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="users-list-status">
                                            <option value="">Any</option>
                                            <option value="Active">Active</option>
                                            <option value="Close">Close</option>
                                            <option value="Banned">Banned</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-4 col-lg-3 offset-sm-8 offset-lg-0 d-flex align-items-center">
                                    <button class="btn btn-block btn-primary glow">Show</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="users-list-table">
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
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="access-control-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Title</th>
                                                    <th>ID</th>
                                                    <th>Permissions</th>
                                                    <th>Updated By</th>
                                                    <th>Last Activity</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1 @endphp
                                                @foreach($accessList as $access)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $access->role_title }}</td>
                                                    <td>{{ $access->role_id }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-success glow" onclick="populatePermission('{{ $access->role_permissions }}')">View Permissions</button>
                                                    </td>
                                                    <td>{{ explode(',', $access->updated_by)[0] }}</td>
                                                    <td>{{ date('d-M-Y h:i A', strtotime($access->updated_at)) }}</td>
                                                    <td>
                                                        <a href="">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $i += 1 @endphp
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

    <!--Success theme Modal -->
    <div class="modal fade text-left" id="permissionList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title white" id="myModalLabel110">Permission List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Page Title</th>
                                    <th class="text-center">Add</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="permission_list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        function populatePermission(permissions) {
            Swal.fire({
                imageUrl: "{{ asset('/images/pages/loading.gif') }}",
                background: "#DEE1E2",
                imageWidth: '100%',
                imageHeight: '100%',
                imageAlt: 'Custom image',
                showCancelButton: false,
                showCloseButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            })

            var titleMarkup = '';
            var permissionMarkup = '';
            var allowMarkup = '<td class="text-center"><i class="bx bx-check-circle icon-success mr-50"></i></td>';
            var denyMarkup = '<td class="text-center"><i class="bx bx-x-circle icon-danger mr-50"></i></td>';
            $('#permission_list').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/v1/decodePermissions') }}",
                type: "POST",
                data: {
                    permissionList: permissions
                },
                success: function(result) {
                    Swal.close();
                    for (var i = 0; i < result.length; ++i) {
                        titleMarkup = '<tr><td class="text-bold-500">' + result[i]['page_title'] + '</td>';
                        var pagePermission = result[i]['page_permission'];
                        if (pagePermission == 000) {
                            permissionMarkup = denyMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 001) {
                            permissionMarkup = denyMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 010) {
                            permissionMarkup = denyMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 011) {
                            permissionMarkup = denyMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 100) {
                            permissionMarkup = allowMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 101) {
                            permissionMarkup = allowMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 110) {
                            permissionMarkup = allowMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += '</tr>';
                        } else if (pagePermission == 111) {
                            permissionMarkup = allowMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += allowMarkup;
                            permissionMarkup += '</tr>';
                        } else {
                            permissionMarkup = denyMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += denyMarkup;
                            permissionMarkup += '</tr>';
                        }
                        var rowMarkup = titleMarkup + permissionMarkup;
                        $('#permission_list').append(rowMarkup);
                        $('#permissionList').modal('show');
                    }
                }
            });
        }
    </script>
</body>
@endsection