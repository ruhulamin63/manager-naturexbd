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
                                <h4 class="card-title">Create Mega Days</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('megadays.store') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label>Mega Days Title</label>
                                                <input type="text" class="form-control" id="mega_days_title" name="title" value="{{ old('title') }}" placeholder="Enter Mega Days Title" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <label>Mega Days Slug</label>
                                                <input type="text" class="form-control" id="mega_days_slug" name="slug" value="{{ old('slug') }}" placeholder="Enter Mega Days Slug" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 mb-1">
                                                <label>Mega Days Description</label>
                                                <textarea class="form-control" rows="5" id="mega_days_description" name="description" placeholder="Enter Mega Days Description" required autocomplete="off">{{ old('description') }}</textarea>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                            <label>Mega Days Banner (Size: 1000 X 300)</label>
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="banner" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose Mega Days Banner</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Create Mega Days</button>
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