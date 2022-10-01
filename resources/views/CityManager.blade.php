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
                                <h4 class="card-title">Add New City</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/v1/addNewCity') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <input type="text" class="form-control" name="city_name" placeholder="Enter city name" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <fieldset class="form-group">
                                                    <select class="form-control" name="city_status" required>
                                                        <option value="" disabled selected>Select Status</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-4">
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
                                        <table id="city-manager-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>City Name</th>
                                                    <th>City ID</th>
                                                    <th>Total Restaurants</th>
                                                    <th>City Status</th>
                                                    <th>Updated By</th>
                                                    <th>Last Activity</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1 @endphp
                                                @foreach($cityList as $city)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $city->city_name }}</td>
                                                    <td>{{ $city->city_id }}</td>
                                                    <td>0</td>
                                                    @if($city->status == "Active")
                                                    <td><span class="badge badge-light-success">Active</span></td>
                                                    @else
                                                    <td><span class="badge badge-light-danger">Inactive</span></td>
                                                    @endif
                                                    <td>{{ explode(',',$city->updated_by)[0] }}</td>
                                                    <td>{{ date('d-M-Y h:i A', strtotime($city->updated_at)) }}</td>
                                                    <td>
                                                        <a class="table-action" href="" data-toggle="tooltip" data-placement="left" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                        <a href="" data-toggle="tooltip" data-placement="left" title="Delete">
                                                            <i class="bx bx-trash"></i>
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

    @include('Layout.footer')

    @include('Layout.scripts')
</body>
@endsection