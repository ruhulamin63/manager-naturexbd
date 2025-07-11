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
                                <h4 class="card-title">Add New Seasonal Product</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/seasonalProducts/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="seasonal_offer_id" class="form-control" id="seasonal_offer_id" required>
                                                        <option disabled selected>Select Seasonal Offer</option>
                                                        @foreach($seasonalProductList as $skey => $seasonalList)
                                                        <option value="{{ $seasonalList->id }}">{{ $seasonalList->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="product_name" placeholder="Enter product name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="product_category" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select product category</option>
                                                        @foreach($categoryList as $key => $item)
                                                        <option value="{{ $item->category }}">{{ $item->category }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="number" class="form-control" name="trade_price" placeholder="Enter trade price" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="number" class="form-control" name="product_price" placeholder="Enter product price" required>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" name="product_description" id="basicTextarea" rows="3" placeholder="Product Description" required></textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="product_thumbnail" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose product image</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="product_type" class="form-control" id="product_type" required>
                                                        <option disabled >Select product Type</option>
                                                        <option selected value="4">Seasional Product</option>
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