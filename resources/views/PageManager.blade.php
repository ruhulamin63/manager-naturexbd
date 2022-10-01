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
                                <h4 class="card-title">Add New Page</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/v1/addNewPage') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <input type="text" class="form-control" name="page_title" id="page_title" placeholder="Enter page title" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <fieldset class="form-group">
                                                    <select class="form-control" name="page_view" required>
                                                        <option value="" disabled selected>Select View</option>
                                                        @foreach($viewList as $views)
                                                        <option value="{{ $views }}">{{ $views }}</option>
                                                        @endforeach
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
                    <div class="users-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="page-manager-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Page Title</th>
                                                    <th>Page ID</th>
                                                    <th>Page View</th>
                                                    <th>Updated By</th>
                                                    <th>Last Activity</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1 @endphp
                                                @foreach($pageList as $pages)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $pages->page_title }}</td>
                                                    <td>{{ $pages->page_id }}</td>
                                                    <td>{{ $pages->page_view }}</td>
                                                    <td>{{ explode(',',$pages->updated_by)[0] }}</td>
                                                    <td>{{ date('d-M-Y h:i A', strtotime($pages->updated_at)) }}</td>
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