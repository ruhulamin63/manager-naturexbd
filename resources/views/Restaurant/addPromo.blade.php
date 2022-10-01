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
                                <h4 class="card-title">Add New Promo</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/restaurant/promo/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="promo_code" placeholder="Enter Promo Code" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="promo_count" placeholder="Count" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1" style="margin-top:10px">
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="datetime-local" name="start_date"  class="form-control" id="#" placeholder="Start Date" autocomplete="off" required>
                                                    <!-- <div class="form-control-position">
                                                        <i class='bx bx-calendar'></i>
                                                    </div> -->
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1" style="margin-top:10px">
                                                <input type="datetime-local" id="#" name="end_date" class="form-control" placeholder="End Date" autocomplete="off" required>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" name="promo_con" id="basicTextarea" rows="3" placeholder="Conditions" required></textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="promo_image">
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Promo image</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="number" class="form-control" name="discount_amount" placeholder="Enter Discount Amount" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="number" class="form-control" name="min_amount" placeholder="Enter Minimum Purchase amount" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6" style="margin-top:10px;">
                                                <fieldset class="form-group">
                                                    <select name="promo_type" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Promo discount type</option>
                                                        <option value="1">Flat</option>
                                                        <option value="2">Percentage</option>
                                                        
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6" style="margin-top:10px;">
                                                <fieldset class="form-group">
                                                    <select name="promo_status" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="2">Deactivate</option>
                                                        
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-8">
                                                 <p>Select City Coverage</p> 
                                                <ul class="list-unstyled mb-0">
                                                     @foreach($cityList as $key => $item) 
                                                    <li class="d-inline-block mr-2 mb-1">
                                                        <fieldset>
                                                            <div class="checkbox">
                                                                 <input type="checkbox" class="checkbox-input" name="" id="" value="">
                                                                 <!--<label for="">Name</label>-->
                                                                 <input type="checkbox" class="checkbox-input" name="city_coverage[]" id="checkbox{{ $key }}" value="{{ $item->id }}"> 
                                                                 <label for="checkbox{{ $key }}">{{ $item->city_name }}</label> 
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