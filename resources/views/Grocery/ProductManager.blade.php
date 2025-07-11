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
//                        @endphp

{{--                        @if(strpos($permission, 'regenerate_product') !== false)--}}
{{--                            <div class="card">--}}
{{--                                <div class="card-content">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="col-12 col-sm-12" style="margin-top: 10px">--}}
{{--                                                <a href="{{ url('/grocery/products/regenerate?id=' . $cityID) }}">--}}
{{--                                                    <button type="button" class="btn btn-block btn-success glow">Regenrate Products</button>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}

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
                                                    <th>Base Image</th>
                                                    <th>Multi-Images</th>
                                                    <th>Product Name</th>
                                                    <th>Product Description</th>
                                                    <th>Custom URL</th>
                                                    <th>Short Description</th>
                                                    <th>SEO Keywords</th>
                                                    <th>Category</th>
                                                    <th>Measuring Unit</th>
                                                    <th>Stock In/Out</th>
                                                    <th>Trade Price</th>
                                                    <th>Retail Price</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        <img src="{{ asset('/storage'.$item->product_thumbnail) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%;">
                                                    </td>
                                                    <td>
                                                        @foreach($productListImages as $image)
                                                            @if($image->product_id == $item->id)
                                                                <img src="{{ asset('/storage'.$image->image_path) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%;">
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        {{ $item->product_name }}
{{--                                                        <small><b>Created at:</b> {{ date('d M Y h:i:s A', strtotime($item->created_at)) }}</small>--}}
{{--                                                        <br>--}}
{{--                                                        <small><b>Modified at:</b> {{ date('d M Y h:i:s A', strtotime($item->updated_at)) }}</small>--}}
                                                    </td>
                                                    <td>{!! Str::limit($item->product_description, 20), '...' !!}</td>
                                                    <td>{{ $item->url }}</td>
                                                    <td>{{ Str::limit($item->short_description, 20), '...' }}</td>
                                                    <td>{{ $item->meta_keywords }}</td>
                                                    <td>{{ $item->category }}</td>
                                                    <td>{{ $item->measuring_unit_new }}</td>

                                                    <td>
                                                        @if($item->stock == "Stock In")
                                                            <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                                <input type="checkbox" class="custom-control-input" checked="" id="stockStatusSwitch{{ $key }}" value="Stock Out" onclick="stockStatusUpdate('{{ $item->id }}', '{{ $key }}')">
                                                                <label class="custom-control-label" for="stockStatusSwitch{{ $key }}"></label>
                                                            </div>
                                                        @else
                                                            <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                                <input type="checkbox" class="custom-control-input" id="stockStatusSwitch{{ $key }}" value="Stock In" onclick="stockStatusUpdate('{{ $item->id }}', '{{ $key }}')">
                                                                <label class="custom-control-label" for="stockStatusSwitch{{ $key }}"></label>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td>৳{{ number_format($item->trade_price, 2) }}</td>
                                                    <td>৳{{ number_format($item->product_price, 2) }}</td>
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
                                                        @if(strpos($permission, 'edit_product') !== false)
                                                            <a href="{{ url('/grocery/products/edit?id=1') }}">
                                                                <div class="badge badge-pill badge-secondary mb-1 round-cursor">Edit</div>
                                                            </a>
                                                        @endif
                                                        @if(strpos($permission, 'update_product_category') !== false)
                                                            <br />
                                                            <button class="badge badge-pill badge-primary mb-1 round-cursor" onclick="updateCategory('{{ $item->product_name }}','{{ $item->category }}')">Change</button>
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

    <div class="modal fade text-left" id="categoryChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Change Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/grocery/products/edit/category') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" id="product_name" name="product_name" value="" hidden />
                            <input type="text" id="current_category" name="current_category" value="" hidden />
                            <div class="col-md-12">
                                <h6>Current Category</h6>
                                <fieldset class="form-group">
                                    <select class="form-control" id="currentCategory" disabled>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <h6>New Category</h6>
                                <fieldset class="form-group">
                                    <select class="form-control" name="new_category" id="newCategory" required>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>
        // import Swal from "laravel-mix/src/Dispatcher";

        function statusUpdate(product_id, item) {
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
                url: "{{ url('/grocery/products/status/update') }}",
                type: "POST",
                data: {
                    // city_id: city_id,
                    product_id: product_id,
                    product_status: status
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

        function stockStatusUpdate(product_id, item) {
            var status = "";
            if ($("#stockStatusSwitch" + item).val() == "Stock In") {
                status = "Stock In";
                $("#stockStatusSwitch" + item).val("Stock Out");
            } else {
                status = "Stock Out";
                $("#stockStatusSwitch" + item).val("Stock In");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/grocery/products/stock/update') }}",
                type: "POST",
                data: {
                    // city_id: city_id,
                    product_id: product_id,
                    stock: status
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

        function updateCategory(product_name, current_category) {
            $("#currentCategory").html("");
            $("#newCategory").html("");
            $("#product_name").val(product_name);
            categoryList = '@json($categoryList)';
            categoryList = JSON.parse(categoryList);
            for (i = 0; i < categoryList.length; i++) {
                if (current_category == categoryList[i].category) {
                    var option = "<option value='" + categoryList[i].category + "' selected>" + categoryList[i].category + "</option>"
                    $("#currentCategory").append(option);
                    $("#current_category").val(categoryList[i].category);
                } else {
                    var option = "<option value='" + categoryList[i].category + "'>" + categoryList[i].category + "</option>"
                    $("#newCategory").append(option);
                }
            }
            $("#categoryChange").modal('show');
        }
    </script>
</body>
@endsection
