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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Send Notification</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <font style="color: red">
                                                * Recommended image resolution: 1600px X 500px | Max filesize: 2MB<br>
                                                * Maximum Title Length: 58 Character<br>
                                                * Image + Text | Maximum Text Length: 58 Character<br><br><br>
                                            </font>
                                            <form action="{{ url('/v1/sendNotification') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <fieldset class="form-group">
                                                            <select name="city" class="form-control" id="city" required>
                                                                <option value="All" selected>All City</option>
                                                                @foreach($cityList as $key => $item)
                                                                <option value="{{ $item->city_name }}">{{ $item->city_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </fieldset>
                                                    </div>
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
                                                        <fieldset class="form-label-group">
                                                            <input type="text" class="form-control" id="floating-label2" name="nf_redirect" placeholder="Redirect URL (Optional)">
                                                            <label for="floating-label2">Redirect URL (Optional)</label>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <fieldset class="form-group">
                                                            <label>Notification Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="nf_preview" class="custom-file-input" id="inputGroupFile03" accept=".jpg">
                                                                <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                                                            </div>
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
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="city-manager-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Title</th>
                                                    <th>Message</th>
                                                    <th class="text-center">Image</th>
                                                    <th class="text-center">Url</th>
                                                    <th class="text-center">Success</th>
                                                    <th class="text-center">Failure</th>
                                                    <th class="text-center">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($notificationList as $key => $notification)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td style="width: 10%">{{ $notification->title }}</td>
                                                    <td>{{ $notification->message }}</td>
                                                    <td class="text-center" style="width: 10%">
                                                        @if($notification->image != "-")
                                                        <a href="{{ asset($notification->image) }}" target="_blank">Click to View</a>
                                                        @else - @endif
                                                    </td>
                                                    <td class="text-center" style="width: 10%">
                                                        @if($notification->redirect != "-")
                                                        <a href="{{ $notification->redirect }}" target="_blank">Click to View</a>
                                                        @else - @endif
                                                    </td>
                                                    <td class="text-center">{{ $notification->success }}</td>
                                                    <td class="text-center">{{ $notification->failed }}</td>
                                                    <td class="text-center" style="width: 10%">{{ date('d-M-Y h:i A', strtotime($notification->created_at)) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- datatable ends -->
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
        function countCharacter(val) {
            var len = val.value.length;
            $("#textarea_count").html(len);
        }
    </script>
</body>
@endsection