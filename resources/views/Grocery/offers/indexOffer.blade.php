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
{{--                        @php--}}
{{--                            $permission = \App\Models\Grocery\Admin::select('*')->where('email', session()->get('GR_MANAGER_EMAIL'))->get();--}}
{{--                            $permission = $permission[0];--}}
{{--                        @endphp--}}
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="six-item-datatable" class="table">
                                            <thead>
                                            <tr>
                                                <th>##</th>
                                                <th>Image</th>
                                                <th>Offer Name</th>
                                                <th>Custom Url</th>
                                                <th>Meta Keywords</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($offerList as $key => $item)
                                                <tr>
                                                    <td>
                                                        {{ $key+1 }}
                                                    </td>
                                                    @if($item->image_path)
                                                        <td>
                                                            <img src="{{ asset('/storage'.$item->image_path) }}" width="80px" alt="blog image" style="border: 1px solid #000000;" />
                                                        </td>
                                                    @else
                                                        <td style="color: #96271A">
                                                            No Image
                                                        </td>
                                                    @endif

                                                    <td>
                                                        {{ $item->offer_name }}
                                                    </td>
                                                    <td>
                                                        {{ $item->url }}
                                                    </td>
                                                    <td>
                                                        {{ $item->meta_keyword }}
                                                    </td>

                                                    <td>
                                                        {{ $item->description }}
                                                    </td>

                                                    <td>
                                                        {{ date('d M Y h:i:s A', strtotime($item->created_at)) }}
                                                    </td>
                                                    <td>
                                                        @if($item->status == "Active")
                                                            <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                                <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="statusUpdate('{{ $item->id }}', '{{ $key }}')">
                                                                <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                            </div>
                                                        @else
                                                            <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                                <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="statusUpdate('{{ $item->id }}', '{{ $key }}')">
                                                                <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ url('/offer/edit/' . $item->id) }}">
                                                            <div class="badge badge-pill badge-secondary mb-1 round-cursor">Edit</div>
                                                        </a>
                                                    </td>
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

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>
        function statusUpdate(offer_id, item) {
            var status = "";
            if ($("#statusSwitch" + item).val() == "Active") {
                status = "Active";
                $("#statusSwitch" + item).val("InActive");
            } else {
                status = "InActive";
                $("#statusSwitch" + item).val("Active");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/offer/status/update') }}",
                type: "POST",
                data: {
                    // city_id: city_id,
                    offer_id: offer_id,
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
