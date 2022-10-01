@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- login page start -->
                <section id="auth-login" class="row flexbox-container">
                    <div class="col-xl-8 col-11">
                        <div class="card bg-authentication mb-0" style="background: white;">
                            <div class="row m-0">
                                <!-- left section-login -->
                                <div class="col-md-6 col-12 px-0">
                                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                        <div class="card-header pb-1">
                                            <div class="text-center card-title">
                                                <h4 class="text-center mb-2">Welcome Back</h4>
                                                <small>Login with your email and password</small>
                                                <br><br>
                                            </div>
                                        </div>
                                        <div class="card-content">
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
                                                <form action="{{ url('/v1/login') }}" method="POST">
                                                    @csrf
                                                    <div class="form-group mb-50">
                                                        <label class="text-bold-600" for="exampleInputEmail1">Email address</label>
                                                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email address" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-bold-600" for="exampleInputPassword1">Password</label>
                                                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
                                                    </div>
                                                    <fieldset class="form-group">
                                                        <label class="text-bold-600" for="dashboardSelect">Select Dashboard</label>
                                                        <select name="dashboard" class="form-control" id="dashboardSelect">
                                                            <option value="grocery">Super Shop</option>
                                                            <option value="food">Restaurant</option>
                                                        </select>
                                                    </fieldset>
                                                    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                        <div class="text-left">
                                                            <div class="checkbox checkbox-sm">
                                                                <input type="checkbox" name="remember_me" value="remember_me" class="form-check-input" id="exampleCheck1">
                                                                <label class="checkboxsmall" for="exampleCheck1"><small>Keep me logged in</small></label>
                                                            </div>
                                                        </div>
                                                        <div class="text-right"><a href="#!" class="card-link"><small>Forgot Password?</small></a>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary glow w-100 position-relative">Login<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
                                                <hr>
                                                <div class="text-center">
                                                    <small class="mr-25">Don't have an account?</small>
                                                    <a href="#!">
                                                        <small>Sign up</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- right section image -->
                                <div class="col-md-6 d-md-block d-none text-center align-self-center no-padding" style="background: white;">
                                    <div class="card-content" style="background: white;">
                                        <img class="img-fluid" src="{{ asset('/images/pages/login.png') }}" alt="branding logo" width="100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- login page ends -->

            </div>
        </div>
    </div>
    @include('Layout.scripts')
</body>
@endsection