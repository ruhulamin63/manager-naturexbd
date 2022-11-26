@extends('Layout_Grocery.app')

@section('body')

    <body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout_Grocery.menu')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

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
                                <h4 class="card-title">Add New Offer</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('offer.store') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <label >Offer Name</label>
                                                <input type="text" class="form-control" name="offer_name" id="offer_name" placeholder="Enter Offer Name">
                                                <br>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label >Meta Keyword</label>
                                                <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" placeholder="meta keyword">
                                                <br>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label >Custom Link</label>
                                                <input type="text" class="form-control" name="url" id="url" placeholder="custom url">
                                                <br>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label >Description</label>
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" name="offer_description" id="basicTextarea" rows="3" placeholder="Blog Description"></textarea>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-12 mb-1">
                                                <label >Image</label>
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="offer_image">
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Image</label>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12" style="margin-top: 10px;">
                                                <button type="submit" class="btn btn-block btn-success glow">Submit</button>
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

    <script>
        CKEDITOR.replace( 'offer_description' );
    </script>

    </body>
@endsection
