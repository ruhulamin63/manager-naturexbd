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
                                    <form action="{{ url('/grocery/seasonalProducts/createCampain') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="campain_title" placeholder="Enter Campain Title" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="text" class="form-control" name="campain_SubTitle" placeholder="Enter Campain Sub-Title" required>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" name="description" id="basicTextarea" rows="3" placeholder="Description" required></textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="campain_banner" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Campain Banner</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="slider1" >
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Slider 1</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="slider2" >
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Slider 2</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="slider3" >
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Slider 3</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="slider4" >
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Slider 4</label>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="campain_metaTag" placeholder="Meta Tag">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" name="campain_metaDes" id="basicTextarea" rows="3" placeholder="Meta Description" ></textarea>
                                                </fieldset>
                                            </div>

                                            <!-- <div class="col-12 col-sm-12 col-lg-8">
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
                                            </div> -->
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