@extends('Layout.app')

@section('body')
<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- not authorized start -->
                <section class="row flexbox-container">
                    <div class="col-xl-7 col-md-8 col-12">
                        <div class="card bg-transparent shadow-none">
                            <div class="card-content">
                                <div class="card-body text-center">
                                    <!-- <img src="{{ asset('/images/logo/logo-yellow.png') }}" class="img-fluid" alt="not authorized" width="300">
                                    <br> -->
                                    <img src="{{ asset('/images/pages/not-authorized.png') }}" class="img-fluid" alt="not authorized" width="300" style="margin: 70px !important">
                                    <br>
                                    <h3 class="error-title">
                                        <font class="error-txt">You are not authorized!</font>
                                    </h3>
                                    <p class="error-desc">
                                        You do not have permission to view this directory or page using
                                        the token that you supplied.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- not authorized end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    @include('Layout.scripts')
</body>
@endsection