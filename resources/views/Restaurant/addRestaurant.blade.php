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
                                <h4 class="card-title">Add New Restaurant</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/restaurant/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="cityId" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Restaurant City</option>
                                                        @foreach($cityList as $key => $city)
                                                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <input type="text" class="form-control" name="restaurant_name" placeholder="Enter Restaurant Name" required>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12 col-lg-6" style="margin-top:10px;">
                                                <fieldset class="form-group">
                                                    <select name="res_type" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Restaurant Type</option>
                                                        <option value="1">Regular</option>
                                                        <option value="2">Regular + Features</option>
                                                        
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6" style="margin-top:10px;">
                                                <fieldset class="form-group">
                                                    <select name="res_status" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="2">Deactivate</option>
                                                        
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <p>Select Restaurant Category</p>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($categoryList as $key => $item)
                                                    <li class="d-inline-block mr-2 mb-1">
                                                        <fieldset>
                                                            <div class="checkbox">
                                                                <input type="checkbox" class="checkbox-input" name="category_coverage[]" id="checkbox{{ $key }}" value="{{ $item->id }}">
                                                                <label for="checkbox{{ $key }}">{{ $item->name }}</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
                                            </div>
                                        </div>
                                    </form>
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