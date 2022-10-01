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
                        @php
                        $permission = \App\Models\Grocery\Admin::select('*')->where('email', session()->get('GR_MANAGER_EMAIL'))->get();
                        $permission = $permission[0];
                        @endphp
                        @if(strpos($permission, 'regenerate_category') !== false)
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12" style="margin-top: 10px">
                                            <a href="{{ url('/grocery/category/regenerate?id=' . $cityID) }}">
                                                <button type="button" class="btn btn-block btn-success glow">Regenrate Category</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
                                                    <th>SN</th>
                                                    <th>Thumbnail</th>
                                                    <th>Category Name</th>
                                                    <th class="text-center">Pre-payment</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($categoryList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        <img src="{{ url($item->thumbnail) }}" width="100px" alt="category_thumbnail" style="border: 1px solid #000000;" />
                                                    </td>
                                                    <td>{{ $item->category }}</td>
                                                    @if($item->prepayment == "Yes")
                                                    <td class="text-center" style="width: 15%;">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                            <input type="checkbox" class="custom-control-input" checked="" id="prePayment{{ $key }}" value="No" onclick="prepaymentUpdate('{{ $item->id }}', '{{ $key }}', '{{ $cityID }}')">
                                                            <label class="custom-control-label" for="prePayment{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="text-center" style="width: 15%;">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                            <input type="checkbox" class="custom-control-input" id="prePayment{{ $key }}" value="Yes" onclick="prepaymentUpdate('{{ $item->id }}', '{{ $key }}', '{{ $cityID }}')">
                                                            <label class="custom-control-label" for="prePayment{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @endif
                                                    @if($item->status == "Active")
                                                    <td class="text-center" style="width: 10%;">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                            <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="statusUpdate('{{ $item->id }}', '{{ $key }}', '{{ $cityID }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="text-center" style="width: 10%;">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                            <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="statusUpdate('{{ $item->id }}', '{{ $key }}', '{{ $cityID }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @endif
                                                    <td class="text-center">
                                                        @if(strpos($permission, 'edit_category') !== false)
                                                        <a href="{{ url('/grocery/category/edit?id=' . $item->id) }}">
                                                            <button class="btn btn-primary">Edit</button>
                                                        </a>
                                                        @endif
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>
        function statusUpdate(category_id, item, city_id) {
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
                url: "{{ url('/grocery/category/status/update') }}",
                type: "POST",
                data: {
                    city_id: city_id,
                    category_id: category_id,
                    category_status: status
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

        function prepaymentUpdate(category_id, item, city_id) {
            var status = "";
            if ($("#prePayment" + item).val() == "Yes") {
                status = "Yes";
                $("#prePayment" + item).val("No");
            } else {
                status = "No";
                $("#prePayment" + item).val("Yes");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/grocery/category/prePayment/update') }}",
                type: "POST",
                data: {
                    city_id: city_id,
                    category_id: category_id,
                    payment_status: status
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