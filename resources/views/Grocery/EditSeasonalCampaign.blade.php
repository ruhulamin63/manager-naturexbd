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
                                <h4 class="card-title">Edit Product</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/SeasonalCampain/edit/confirm') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="text" name="campaign_id" value="{{ $campaignID }}" required hidden />
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label>Title</label>
                                                <input type="text" class="form-control" name="cam_title" value="{{ $campainDetails->title }}" placeholder="Enter Title" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label>Sub Title</label>
                                                <input type="text" class="form-control" name="cam_Sub_title" value="{{ $campainDetails->subtitle }}" placeholder="Enter Sub-Title" required>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Description</label>
                                                    <textarea class="form-control" name="cam_description" id="basicTextarea" rows="3" placeholder="Description" required>{{ $campainDetails->details }}</textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 mb-1">
                                                <label>Meta Tag</label>
                                                <input type="text" class="form-control" name="cam_meta_tag" value="{{ $campainDetails->meta_tag }}" placeholder="Enter Meta Tag">
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Meta Description</label>
                                                    <textarea class="form-control" name="cam_meta_des" id="basicTextarea" rows="3" placeholder="Meta Description" >{{ $campainDetails->meta_decs }}</textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="cam_status" class="form-control" id="product_type" required>
                                                        <option disabled>Select Status</option>
                                                        @if($campainDetails->status == "1")
                                                            <option selected value="1">Active</option>
                                                            <option value="0">Deactivate</option>
                                                        @else
                                                            <option value="1">Active</option>
                                                            <option selected value="0">Deactivate</option>
                                                        @endif
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Update Campaign Information</button>
                                            </div>
                                        </div>
                                    </form>
                                    <br><br><br>
                                    <form action="{{ url('/grocery/SeasonalCampain/edit/images') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="text" name="edit_img_campaign_id" value="{{ $campaignID }}" required hidden/>
                                        <div class="row">
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="edit_campain_banner" name="edit_campain_banner">
                                                        <label class="custom-file-label" for="edit_campain_banner">Choose Campain Banner</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="edit_slider1" name="edit_slider1" >
                                                        <label class="custom-file-label" for="edit_slider1">Choose Slider 1</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="edit_slider2" name="edit_slider2" >
                                                        <label class="custom-file-label" for="edit_slider2">Choose Slider 2</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="edit_slider3" name="edit_slider3" >
                                                        <label class="custom-file-label" for="edit_slider3">Choose Slider 3</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="edit_slider4" name="edit_slider4" >
                                                        <label class="custom-file-label" for="edit_slider4">Choose Slider 4</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Update Campaign Image</button>
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