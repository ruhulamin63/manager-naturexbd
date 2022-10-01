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
                                <h4 class="card-title">Add New Area</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('CoverageArea.Create') }}" method="POST">
                                        @csrf
                                        <input type="text" value="{{ Request::get('city') }}" name="city_id" required hidden />
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-8">
                                                <input type="text" class="form-control" name="area_name" placeholder="Enter area name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-4">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="edit_area" hidden>
                            <div class="card-header">
                                <h4 class="card-title">Edit Area Name</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('CoverageArea.Update') }}" method="POST">
                                        @csrf
                                        <input type="text" value="{{ Request::get('city') }}" name="city_id" required hidden />
                                        <input type="text" value="" id="area_id" name="area_id" required hidden />
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-8">
                                                <input type="text" class="form-control" id="area_name" name="area_name" value="" placeholder="Enter area name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-4">
                                                <button type="submit" class="btn btn-block btn-warning glow">Update</button>
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
                                        <table id="four-item-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Area Name</th>
                                                    <th class="text-center">Updated On</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($areaList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $item->area_name }}</td>
                                                    <td class="text-center">{{ date('d M Y h:i:s A', strtotime($item->updated_at)) }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-warning" onclick="editArea('{{ $item->id }}','{{ $item->area_name }}')">Edit</button>
                                                        <a href="{{ route('CoverageArea.Delete') . '?id=' . $item->id }}">
                                                            <button class="btn btn-danger">Delete</button>
                                                        </a>
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

    <script>
        function editArea(id, area_name){
            $("#area_id").val(id);
            $("#area_name").val(area_name);
            $("#edit_area").removeAttr("hidden");
        }
    </script>
</body>
@endsection