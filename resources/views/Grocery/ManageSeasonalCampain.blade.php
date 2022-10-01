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
                                                    <th>Banner</th>
                                                    <th>Title</th>
                                                    <th>Campain Info</th>
                                                    <th>Meta Info</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($campainList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td style="text-align: center;">
                                                        <img src="{{ url($item->banner) }}" width="100px" alt="product_thumbnail" style="border: 1px solid #000000;" />
                                                        <br>
                                                        @if($item->slider_1 !="N/A")
                                                            <small><img src="{{ url($item->slider_1) }}" width="25px" alt="product_thumbnail" style="border: 1px solid #000000;" /></small>
                                                        @else
                                                            <small>[Empty]</small>
                                                        @endif
                                                        @if($item->slider_2 !="N/A")
                                                            <small><img src="{{ url($item->slider_2) }}" width="25px" alt="product_thumbnail" style="border: 1px solid #000000;" /></small>
                                                        @else
                                                            <small>[Empty]</small>
                                                        @endif
                                                        @if($item->slider_3 !="N/A")
                                                            <small><img src="{{ url($item->slider_3) }}" width="25px" alt="product_thumbnail" style="border: 1px solid #000000;" /></small>
                                                        @else
                                                            <small>[Empty]</small>
                                                        @endif
                                                        @if($item->slider_4 !="N/A")
                                                            <small><img src="{{ url($item->slider_4) }}" width="25px" alt="product_thumbnail" style="border: 1px solid #000000;" /></small>
                                                        @else
                                                            <small>[Empty]</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $item->title }}
                                                    </td>
                                                    <td>
                                                        <small><b>Sub-Title:</b> {{ $item->subtitle }}</small>
                                                        <br>
                                                        <small><b>Des:</b> {{ $item->details }}</small>
                                                        <br>
                                                        <small><b>Created at:</b> {{ date('d M Y h:i:s A', strtotime($item->timestamp)) }}</small>
                                                    </td>
                                                    <td>
                                                        <small><b>Meta Tag:</b> {{ $item->meta_tag }}</small>
                                                        <br>
                                                        <small><b>Meta Des:</b> {{ $item->meta_decs }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->status == "1")
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
                                                        <br>
                                                        @if(strpos($permission, 'edit_product') !== false)
                                                        <a href="{{ url('/grocery/SeasonalCampain/edit?id=' . $item->id) }}">
                                                            <div class="badge badge-pill badge-secondary mb-1 round-cursor">Edit</div>
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

    <!-- <div class="modal fade text-left" id="categoryChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
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
    </div> -->

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>
        function statusUpdate(product_id, item) {
            var status = "";
            if ($("#statusSwitch" + item).val() == "1") {
                status = "1";
                $("#statusSwitch" + item).val("0");
            } else {
                status = "0";
                $("#statusSwitch" + item).val("1");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/grocery/SeasonalCampain/status/update') }}",
                type: "POST",
                data: {
                    campaign_id: product_id,
                    campaign_status: status
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