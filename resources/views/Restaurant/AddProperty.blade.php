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
                                <h4 class="card-title">Add Property</h4>
                            </div>
                            <div class="card-header">
                                <h4 class="card-title">Restaurant Name: {{ $restaurantInfo->name }} </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/restaurant/RestaurantList/addProperty/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="text" name="res_id" value="{{ $restaurantID }}" required hidden />
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Opening Time<mark>*</mark></label>
                                                <input type="time" class="form-control" name="open_time" placeholder="Enter Opening Time" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>closing Time<mark>*</mark></label>
                                                <input type="time" class="form-control" name="close_time" placeholder="Enter Closing Time" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Rating</label>
                                                <input type="number" min="0" max="5" class="form-control" name="rating" placeholder="Enter Rating">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Phone Number</label>
                                                <input type="number" class="form-control" name="phone_num" placeholder="Enter Phone Number">
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Address<mark>*</mark></label>
                                                    <textarea class="form-control" name="address" id="basicTextarea" rows="3" placeholder="Enter Restaurant Address" required></textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Latitude</label>
                                                <input type="text" class="form-control" name="lat" placeholder="Enter Latitude">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Longitude</label>
                                                <input type="text" class="form-control" name="lon" placeholder="Enter Longitude">
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Restaurant Logo<mark>*</mark></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="restaurant_logo" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Logo</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Restaurant Cover Picture<mark>*</mark></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="restaurant_cover" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Cover Picture</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Save</button>
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