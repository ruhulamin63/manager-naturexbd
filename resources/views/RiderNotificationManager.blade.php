@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout.menu')

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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Send Notification to Riders</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form action="{{ url('/v1/sendNotificationToRider') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <fieldset class="form-label-group">
                                                            <input type="text" class="form-control" id="floating-label1" name="nf_title" placeholder="Notification Title" maxlength="58" required>
                                                            <label for="floating-label1">Notification Title</label>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-12">
                                                        <fieldset class="form-label-group">
                                                            <textarea class="form-control" id="label-textarea" rows="4" id="nf_message" name="nf_message" placeholder="Notification message" required onkeydown="countCharacter(this)"></textarea>
                                                            <label for="label-textarea">Notification message | Length: <span id="textarea_count">0</span></label>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-block btn-success glow">Send Notification</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        function countCharacter(val){
            var len = val.value.length;
            $("#textarea_count").html(len);
        }
    </script>
</body>
@endsection