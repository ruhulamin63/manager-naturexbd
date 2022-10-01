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
                                <h4 class="card-title">Add New Admin</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/addNewAdmin') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="text" class="form-control" name="admin_name" placeholder="Enter admin name" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="email" class="form-control" name="admin_email" placeholder="Enter admin email address" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="number" class="form-control" name="admin_contact" placeholder="Enter admin contat number" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-3">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">New Access Rule</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/admin/addNewAccessRule') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="text" class="form-control" name="access_category" placeholder="Enter access category" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="text" class="form-control" name="access_name" placeholder="Enter access name" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="text" class="form-control" name="access_value" placeholder="Enter access value" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-3">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                                                    <th>Name</th>
                                                    <th class="text-center">Email</th>
                                                    <th class="text-center">Mobile</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($adminList as $key => $admin)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $admin->name }}</td>
                                                    <td class="text-center">{{ $admin->email }}</td>
                                                    <td class="text-center">{{ $admin->mobile }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary" onclick="showAccessForm('{{ $admin->name }}','{{ $admin->email }}','{{ $admin->permissions }}')">
                                                            Access Control
                                                        </button>
                                                        <button type="button" class="btn btn-danger" onclick="deleteAccess('{{ $admin->id }}')">
                                                            Delete
                                                        </button>
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

    <div class="modal fade text-left" id="updateAccessPermission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <form action="{{ url('/grocery/admins/updateAccess') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Access Control</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <fieldset class="form-group">
                                    <label>Admin Name</label>
                                    <input type="text" class="form-control" name="name" id="adminName" placeholder="Enter admin name" required>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <fieldset class="form-group">
                                    <label>Admin Email</label>
                                    <input type="email" class="form-control" name="email" id="adminEmail" placeholder="Enter admin email" required>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label>Access Control</label>
                                <ul class="list-unstyled mb-0">
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Page')->get();
                                    $index = 0;
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <br>
                                    <li class=" navigation-header"><span>Admin</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Admin')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <br>
                                    <li class=" navigation-header"><span>Category</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Category')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>City</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'City')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Order</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Order')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Transactions</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Transactions')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Mega Days</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Mega Days')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Marketing</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Marketing')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Product</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Product')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Printing</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Print')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                    <li class=" navigation-header"><span>Logging</span></li>
                                    @php
                                    $accessList = \App\Models\Grocery\AccessList::select('*')->where('access_category', 'Logging')->get();
                                    @endphp
                                    @foreach($accessList as $key => $item)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" name="access_permission[]" id="checkbox{{ $index }}" value="{{ $item->access_value }}">
                                                <label for="checkbox{{ $index }}">{{ $item->access_name }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                </ul>
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
                </div>
            </form>
        </div>
    </div>

    <script>
        var checkboxCount = '{{ count(\App\Models\Grocery\AccessList::all()) }}';

        function showAccessForm(name, email, permissions) {
            for (var j = 0; j < checkboxCount; j++) {
                var chkboxID = '#checkbox' + j;
                $(chkboxID).prop("checked", false);
            }

            var permissionList = permissions.split(',');
            for (var i = 0; i < permissionList.length; i++) {
                for (var j = 0; j < checkboxCount; j++) {
                    var chkboxID = '#checkbox' + j;
                    if ($(chkboxID).val() == permissionList[i]) {
                        $(chkboxID).prop("checked", true);
                    }
                }
            }
            $("#adminName").val(name);
            $("#adminEmail").val(email);
            $("#updateAccessPermission").modal('show');
        }

        function deleteAccess(admin_id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success ml-1',
                    cancelButton: 'btn btn-danger',
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    window.location.href = '{{ url("/grocery/admin/delete") }}' + "?id=" + admin_id;
                }
            })
        }
    </script>

</body>
@endsection