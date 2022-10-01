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
                                <h4 class="card-title">Server Maintenance</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/serverMaintenance/update') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <input type="text" name="sm_id" value="1" hidden>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                    @if($MaintenanceInfo->image)
                                                            <?php if (file_exists("../public".$MaintenanceInfo->image)){ ?>
                                                            <div class="osahan-slider-item" style="background-color:#fff;">
                                                                <img src="{{asset($MaintenanceInfo->image)}}" style="width:100%;height:300px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                            </div>
                                                            <?php } else{ ?>
                                                            <div class="osahan-slider-item" style="background-color:#fff;">
                                                                <img src="https://i.gifer.com/B0eS.gif" style="width:100%;height:300px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                            </div>
                                                            <?php } ?>
                                                    @else
                                                        <div class="osahan-slider-item" style="background-color:#fff;">
                                                            <img src="https://i.gifer.com/kkR.gif" style="width:100%;height:300px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                        </div>
                                                    @endif  
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top:10px">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="sm_image">
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose image</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12" style="margin-bottom:10px">
                                                <p>Text</p>
                                                <input type="text" class="form-control" value="{{$MaintenanceInfo->text}}" name="sm_text" placeholder="Text">
                                            </div>
                                            
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <p>IP</p>
                                                <input type="text" class="form-control" value="{{$MaintenanceInfo->ip}}" name="sm_ip" placeholder="IP">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <p>Status</p>
                                                <fieldset class="form-group">
                                                    <select name="sm_status" class="form-control" id="basicSelect" required>
                                                        @if($MaintenanceInfo->status==1)
                                                            <option disabled selected>Select Status</option>
                                                            <option value="1" selected>Active</option>
                                                            <option value="2">Deactivate</option>
                                                        @elseif($MaintenanceInfo->status==2)
                                                            <option disabled selected>Select Status</option>
                                                            <option value="1">Active</option>
                                                            <option value="2" selected>Deactivate</option>
                                                        @else
                                                            <option disabled selected>Select Status</option>
                                                            <option value="1">Active</option>
                                                            <option value="2">Deactivate</option>
                                                        @endif
                                                        
                                                    </select>
                                                </fieldset>
                                                @if($MaintenanceInfo->status==1)
                                                <p style="color:red">Current: ACTIVE</p>
                                                @else
                                                <p>Current: OFF</p>
                                                @endif
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Update</button>
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