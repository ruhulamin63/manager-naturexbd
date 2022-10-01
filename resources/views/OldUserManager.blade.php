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
                                <h4 class="card-title">Upload Old User Data</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/v1/uploadOldUserData') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-8">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="csv_file" class="custom-file-input" id="inputGroupFile04" accept=".csv" required>
                                                        <label class="custom-file-label" for="inputGroupFile04">Choose CSV file</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-2">
                                                <button type="submit" class="btn btn-block btn-success glow">Upload</button>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-2">
                                                <a href="{{ url('/v1/processOldUserData') }}">
                                                    <button type="button" class="btn btn-block btn-primary glow">Process Data</button>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="users-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="old-user-manager-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Mobile</th>
                                                    <th>Gender</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($oldUserList as $key => $oldUser)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $oldUser->name }}</td>
                                                    <td>{{ $oldUser->email }}</td>
                                                    <td>{{ $oldUser->mobile }}</td>
                                                    <td>{{ $oldUser->gender }}</td>
                                                    <td class="text-center">
                                                        <form id="{{ $key+1 }}" action="{{ url('/v1/deleteSingleOldUserData') }}" method="POST">
                                                            @csrf
                                                            <input type="text" name="user_mobile" value="{{ $oldUser->mobile }}" hidden required />
                                                            <a href="#!" data-toggle="tooltip" data-placement="left" title="Delete" onclick="deleteRequest('{{ $key+1 }}')">
                                                                <i class="bx bx-trash"></i>
                                                            </a>
                                                        </form>
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

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        function deleteRequest(userID) {
            $("#" + userID).submit();
        }
    </script>
</body>
@endsection