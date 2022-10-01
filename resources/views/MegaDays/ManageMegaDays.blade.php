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
                        <div class="restaurant-list-table">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <!-- datatable start -->
                                        <div class="table-responsive">
                                            <table id="five-item-datatable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>SN</th>
                                                        <th>Title</th>
                                                        <th class="text-center">Preview</th>
                                                        <th class="text-center">Action</th>
                                                        <th class="text-center">Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($megaDays as $key => $item)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ $item->title }}</td>
                                                        <td class="text-center">
                                                            <a href="https://web.khaidaitoday.com/promotional/{{ $item->slug }}" class="btn btn-primary" target="_blank">Preview</a>
                                                        </td>
                                                        @if($item->status == "Active")
                                                        <td class="text-center" style="width: 10%;">
                                                            <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                                <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="megaStatusUpdate('{{ $item->mid }}', '{{ $key }}')">
                                                                <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                            </div>
                                                            <a href="{{ route('megadays.delete', ['mid' => $item->mid]) }}">
                                                                <div class="badge badge-pill badge-light-danger round-cursor">Delete</div>
                                                            </a>
                                                        </td>
                                                        @else
                                                        <td class="text-center" style="width: 10%;">
                                                            <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                                <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="megaStatusUpdate('{{ $item->mid }}', '{{ $key }}')">
                                                                <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                            </div>
                                                            <a href="{{ route('megadays.delete', ['mid' => $item->mid]) }}">
                                                                <div class="badge badge-pill badge-light-danger round-cursor">Delete</div>
                                                            </a>
                                                        </td>
                                                        @endif
                                                        <td class="text-center">{{ date('d M Y h:i:s A', strtotime($item->created_at)) }}</td>
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
        function megaStatusUpdate(mid, item) {
            var status = "";
            if ($("#statusSwitch" + item).val() == "Active") {
                status = "Active";
                $("#statusSwitch" + item).val("Inactive");
            } else {
                status = "Inactive";
                $("#statusSwitch" + item).val("Active");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('megadays.status') }}",
                type: "POST",
                data: {
                    mid: mid,
                    status: status
                },
                success: function(result) {
                    if (!result.error) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: result.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'danger',
                            title: result.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            });
        }
    </script>
</body>
@endsection