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
                        <div class="card collapse-header">
                            <div id="headingCollapse5" class="card-header collapsed" role="button" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                <span class="collapse-title">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-1 text-center">
                                            @if($restaurantDetails[0]->restaurant_logo != "")
                                            <img class="round" src="{{ asset($restaurantDetails[0]->restaurant_logo) }}" height="80px" />
                                            @else
                                            <img class="round" src="{{ asset('/images/dummy/200x200.png') }}" height="80px" />
                                            @endif
                                        </div>
                                        <div class="col-sm-12 col-md-5">
                                            <span class="align-middle">
                                                <h3 style="margin-bottom: 0px !important">
                                                    <b>{{ $restaurantDetails[0]->restaurant_name }}</b>
                                                </h3>
                                                {{ $restaurantDetails[0]->restaurant_address }}
                                                <br>Mobile: <a href="tel:{{ $restaurantDetails[0]->restaurant_mobile }}">
                                                    {{ $restaurantDetails[0]->restaurant_mobile }}
                                                </a>
                                            </span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                            <span class="align-middle">
                                                <button type="button" class="btn btn-success shadow" onclick="addNewItem('{{ $restaurantDetails[0]->restaurant_id }}')">Add Item</button>
                                            </span>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Upload Restaurant Menu</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/v1/uploadRestaurantMenu') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" name="restaurant" value="{{ $restaurantID }}" hidden required />
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-8">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="csv_file" class="custom-file-input" id="inputGroupFile04" accept=".csv" required>
                                                        <label class="custom-file-label" for="inputGroupFile04">Choose CSV file</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-2">
                                                <button type="submit" class="btn btn-block btn-success glow">Upload</button>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-2">
                                                <a href="{{ url('/v1/processMenuData?restaurant=' . $restaurantID) }}">
                                                    <button type="button" class="btn btn-block btn-primary glow">Process Data</button>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
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
                                        <table id="menu-list-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Item Name</th>
                                                    <th class="text-center">Price</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1 @endphp
                                                @foreach($restaurantMenu as $key => $item)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        {{ $item->item_name }}<br>
                                                        <small>{{ $item->item_description }}</small>
                                                    </td>
                                                    <td class="text-center">৳ {{ $item->price }}</td>
                                                    @if($item->status == "Active")
                                                    <td class="text-center" style="width: 5%">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                                            <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="statusUpdate('{{ $key }}','{{ $item->item_id }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="text-center" style="width: 5%">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                                            <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="statusUpdate('{{ $key }}','{{ $item->item_id }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @endif
                                                    <td class="text-center restaurant-checklist" style="width: 10%">
                                                        <a href="#!" data-toggle="tooltip" data-placement="left" title="Edit Item" onclick="editItem('{{ $restaurantID }}','{{ $item->item_id }}','{{ $item->item_name }}', '{{ $item->price }}')">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                        <a href="#!" data-toggle="tooltip" data-placement="left" title="Delete" onclick="deleteItem('{{ $restaurantID }}','{{ $item->item_id }}')">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $i += 1 @endphp
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

    <div class="modal fade text-left" id="editMenuItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/v1/editItem') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" name="rest_id" id="edit_rest_id" value="" required hidden>
                            <input type="text" name="item_id" id="edit_item_id" value="" required hidden>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Item Name</label>
                                    <input type="text" class="form-control" placeholder="Enter item name" name="item_name" id="edit_item_name" required>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Item Description</label>
                                    <input type="text" class="form-control" placeholder="Enter item description" name="item_description" id="item_description">
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Item Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">৳</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Enter item price" name="item_price" id="edit_item_price" aria-describedby="basic-addon1" required>
                                    </div>
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

    <div class="modal fade text-left" id="addNewItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add New Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/v1/addNewItem') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" name="rest_id" id="rest_id" value="" required hidden>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Item Name</label>
                                    <input type="text" class="form-control" placeholder="Enter item name" name="item_name" id="item_name" required>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Item Description</label>
                                    <input type="text" class="form-control" placeholder="Enter item description" name="item_description" id="item_description">
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Item Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">৳</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Enter item price" name="item_price" id="item_price" aria-describedby="basic-addon1" required>
                                    </div>
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

    @include('Layout.footer')

    @include('Layout.scripts')
    <script>
        $(document).ready(function() {
            $("#discount-status").change(function() {
                if ($("#discount-status").val() == "YES") {
                    $("#discount_status_val").val("YES");
                    $("#discount-status").val("NO");
                    $("#percentageCheck").prop("checked", true);
                    $("#tkCheck").prop("checked", false);
                    $("#discount_amount").attr("required", true);
                } else {
                    $("#discount_status_val").val("NO");
                    $("#discount-status").val("YES");
                    $("#percentageCheck").prop("checked", false);
                    $("#tkCheck").prop("checked", false);
                    $("#discount_amount").attr("required", false);
                }
            });
        });

        function addNewItem(restaurantID) {
            Swal.fire({
                imageUrl: "{{ asset('/images/pages/loading.gif') }}",
                background: "#DEE1E2",
                imageWidth: '100%',
                imageHeight: '100%',
                imageAlt: 'Custom image',
                showCancelButton: false,
                showCloseButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            })


            $("#discount-status").prop("checked", false);
            $("#percentageCheck").prop("checked", false);
            $("#tkCheck").prop("checked", false);
            $("#item_name").val("");
            $("#item_price").val("");
            $("#discount_amount").val("");
            $("#rest_id").val(restaurantID);
            Swal.close();
            $("#addNewItem").modal('show');
        }

        function editItem(restID, itemID, itemName, itemPrice) {
            $("#edit_rest_id").val(restID);
            $("#edit_item_id").val(itemID);
            $("#edit_item_name").val(itemName);
            $("#edit_item_price").val(itemPrice);
            $("#editMenuItem").modal('show');
        }

        function deleteItem(restID, itemID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('/v1/deleteItem') }}",
                        type: "POST",
                        data: {
                            rest_id: restID,
                            item_id: itemID
                        },
                        success: function(result) {
                            if (!result.error) {
                                location.reload();
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
            })
        }

        function statusUpdate(id, itemID) {
            var status = "";
            if ($("#statusSwitch" + id).val() == "Active") {
                status = "Active";
                $("#statusSwitch" + id).val("Inactive");
            } else {
                status = "Inactive";
                $("#statusSwitch" + id).val("Active");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/v1/updateItemStatus') }}",
                type: "POST",
                data: {
                    item_id: itemID,
                    status: status
                },
                success: function(result) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            });
        }
    </script>
</body>
@endsection