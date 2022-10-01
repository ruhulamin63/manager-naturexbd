@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <section id="pick-a-date">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Send SMS</h4>
                            <hr>
                        </div>
                    </div>
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
                                    <label>Send Message Hot Key</label>
                                    <br>
                                    <button class="btn btn-success" onclick="sendTo('{{ $riderContact }}')">Riders ({{ count(explode(',', $riderContact)) }})</button>
                                    <button class="btn btn-warning" onclick="sendTo('{{ $adminContact }}')">Admins ({{ count(explode(',', $adminContact)) }})</button>
                                    <!-- <button class="btn btn-primary">Customers</button> -->
                                    <br><br>
                                    <form action="{{ url('/v1/sendSMS') }}" method="POST">
                                        @csrf
                                        <fieldset class="form-label-group">
                                            <input type="text" class="form-control" id="campaign" name="campaignName" placeholder="Campaign Name (Optional)">
                                            <label for="campaign">Campaign Name (Optional)</label>
                                        </fieldset>
                                        <fieldset class="form-label-group">
                                            <input type="text" class="form-control" id="sendToNumbers" name="sendTo" placeholder="Send To (017XXXXXXXX,018XXXXXXXX)" required onkeyup="countNumber(this)">
                                            <label for="sendToNumbers">Send To | Count: <span id="number_count">1</span></label>
                                        </fieldset>
                                        <fieldset class="form-label-group">
                                            <textarea class="form-control" id="label-textarea" rows="4" name="message" placeholder="Message" required onkeyup="countCharacter(this)"></textarea>
                                            <label for="label-textarea">Message | Length: <span id="textarea_count">0</span></label>
                                        </fieldset>
                                        <button type="submit" class="btn btn-success btn-block">Send Message</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12 dashboard-users-danger">
                                    <div class="card text-center">
                                        <div class="card-content">
                                            <div class="card-body py-1">
                                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                                    <i class="bx bx-check-double font-medium-5"></i>
                                                </div>
                                                <div class="text-muted line-ellipsis">Total SMS Sent</div>
                                                <h3 class="mb-0">{{ number_format($smsCount) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 dashboard-users-danger">
                                    <div class="card text-center">
                                        <div class="card-content">
                                            <div class="card-body py-1">
                                                <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50">
                                                    <i class="bx bx-dollar-circle font-medium-5"></i>
                                                </div>
                                                <div class="text-muted line-ellipsis">SMS Balance</div>
                                                <h3 class="mb-0">৳{{ number_format($smsBalance, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 dashboard-users-warning">
                                    <div class="card text-center">
                                        <div class="card-content">
                                            <div class="card-body py-1">
                                                <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                    <i class="bx bx-calendar-event font-medium-5"></i>
                                                </div>
                                                <div class="text-muted line-ellipsis">Expiry Date</div>
                                                <h3 class="mb-0">{{ $smsBalanceExpiry }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>SMS History</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="sms-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="sms-list-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Campaign Name</th>
                                                    <th>Message</th>
                                                    <th class="text-center">Total SMS</th>
                                                    <th class="text-center">Total Cost</th>
                                                    <th class="text-center">Timestamp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($smsHistory as $key => $sms)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $sms->campaign }}</td>
                                                    <td>{!! nl2br($sms->message) !!}</td>
                                                    <td class="text-center">{{ $sms->totalSMS }}</td>
                                                    <td class="text-center">৳{{ $sms->totalCost }}</td>
                                                    <td class="text-center">{{ date('d-M-Y h:i A', strtotime($sms->created_at)) }}</td>
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
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        function countCharacter(val) {
            var len = val.value.length;
            $("#textarea_count").html(len);
        };

        function sendTo(contacts) {
            var res = contacts.split(",");
            $("#number_count").html(res.length);

            $("#sendToNumbers").val(contacts);
        };

        function countNumber(numbers) {
            var res = numbers.value.split(",");
            $("#number_count").html(res.length);
        }
    </script>
</body>
@endsection