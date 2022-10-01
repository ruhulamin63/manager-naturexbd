@extends('Layout_Grocery.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout_Grocery.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <section id="pick-a-date">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
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
                                    <form action="{{ url('/grocery/dealer/print/invoice') }}" method="POST">
                                        @csrf
                                        <label>From Date</label>
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="text" name="from_date" class="form-control format-picker" placeholder="Select Date" required>
                                            <div class="form-control-position">
                                                <i class='bx bx-calendar'></i>
                                            </div>
                                        </fieldset>
                                        <label>To Date</label>
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="text" name="to_date" class="form-control format-picker" placeholder="Select Date" required>
                                            <div class="form-control-position">
                                                <i class='bx bx-calendar'></i>
                                            </div>
                                        </fieldset>
                                        <button type="submit" class="btn btn-success btn-block">Print Dealer Invoice</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')
</body>
@endsection