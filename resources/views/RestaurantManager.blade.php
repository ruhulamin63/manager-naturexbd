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
                            <div id="headingCollapse5" class="card-header collapsed">
                                <span class="collapse-title">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-1 text-center">
                                            <img class="round" src="{{ asset('/images/logo/restaurant.png') }}" height="80px" />
                                        </div>
                                        <div class="col-sm-12 col-md-5">
                                            <span class="align-middle">
                                                <h3 style="margin-bottom: 0px !important">
                                                    <b>{{ $cityName }}</b>
                                                </h3>
                                                Restaurant Management
                                                <br><small>Total Restaurants: {{ count($restaurantList) }}</small>
                                            </span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                            <span class="align-middle">
                                                <button type="button" class="btn btn-success shadow" data-toggle="modal" data-target="#inlineForm">Add Restaurant</button>
                                            </span>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="restaurant-list-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th class="text-center">Delivery Charge</th>
                                                    <th class="text-center">Address</th>
                                                    <th class="text-center">Geo Location</th>
                                                    <th class="text-center">Logo</th>
                                                    <th class="text-center">App Preview</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1 @endphp
                                                @foreach($restaurantList as $key => $restaurant)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    @if($restaurant->restaurant_mobile == "")
                                                    <td>
                                                        {{ $restaurant->restaurant_name }}
                                                        @if($restaurant->discount > 0)
                                                        <div class="badge badge-pill badge-danger">{{ $restaurant->discount }}% Discount</div>
                                                        @endif
                                                        <br>
                                                        <small>{{ $restaurant->restaurant_category }}</small>
                                                        <br>
                                                        <i class="bx bx-x-circle icon-danger mr-50"></i>
                                                    </td>
                                                    @else
                                                    <td>
                                                        {{ $restaurant->restaurant_name }}
                                                        @if($restaurant->discount > 0)
                                                        <div class="badge badge-pill badge-danger">{{ $restaurant->discount }}% Discount</div>
                                                        @endif
                                                        <br>
                                                        <small>{{ $restaurant->restaurant_category }}</small>
                                                        <br>
                                                        <a href="tel:{{ $restaurant->restaurant_mobile }}">
                                                            {{ $restaurant->restaurant_mobile }}
                                                        </a>
                                                    </td>
                                                    @endif

                                                    @if($restaurant->restaurant_category == "")
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-x-circle icon-danger mr-50"></i></td>
                                                    @else
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-check-circle icon-success mr-50"></i></td>
                                                    @endif

                                                    <td class="text-center" style="width: 5%">{{ $restaurant->delivery_charge }} ৳</td>

                                                    @if($restaurant->restaurant_address == "")
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-x-circle icon-danger mr-50"></i></td>
                                                    @else
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-check-circle icon-success mr-50"></i></td>
                                                    @endif

                                                    @if($restaurant->restaurant_coordinate == "")
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-x-circle icon-danger mr-50"></i></td>
                                                    @else
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-check-circle icon-success mr-50"></i></td>
                                                    @endif

                                                    @if($restaurant->restaurant_logo == "")
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-x-circle icon-danger mr-50"></i></td>
                                                    @else
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-check-circle icon-success mr-50"></i></td>
                                                    @endif

                                                    @if($restaurant->restaurant_preview == "")
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-x-circle icon-danger mr-50"></i></td>
                                                    @else
                                                    <td class="text-center" style="width: 5%"><i class="bx bx-check-circle icon-success mr-50"></i></td>
                                                    @endif

                                                    @if($restaurant->status == "Active")
                                                    <td class="text-center" style="width: 5%">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                                            <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="statusUpdate('{{\'statusSwitch\' . $key }}','{{ $restaurant->restaurant_id }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="text-center" style="width: 5%">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                                            <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="statusUpdate('{{\'statusSwitch\' . $key }}','{{ $restaurant->restaurant_id }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @endif
                                                    <td class="text-center restaurant-checklist" style="width: 10%">
                                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Discount" onclick="showDiscountModal('{{ $restaurant->restaurant_id }}','{{ $restaurant->discount }}')">
                                                            <i class="bx bxs-discount"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Edit Info" onclick="showEditModal('{{ $restaurant->restaurant_id }}')">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                        <br>
                                                        <a href="{{ url('/dashboard/page?id=' . 8119) }}&city={{ $cityID }}&restaurant={{ $restaurant->restaurant_id }}" data-toggle="tooltip" data-placement="left" title="Menu">
                                                            <i class="bx bx-restaurant"></i>
                                                        </a>
                                                        <a href="" data-toggle="tooltip" data-placement="left" title="Delete">
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

    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Restaurant </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <fieldset class="form-group">
                                    <label>Restaurant Name</label>
                                    <input type="text" class="form-control" placeholder="Enter restaurant name">
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <fieldset class="form-group">
                                    <label>Mobile Number</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+88</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Mobile number" aria-describedby="basic-addon1" maxlength="11">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <fieldset class="form-group">
                                    <label>Restaurant Address</label>
                                    <input type="text" class="form-control" placeholder="Enter restaurant address">
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>Geolocation</label>
                                    <input type="text" class="form-control" placeholder="Enter co-ordinates">
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>Delivery Charge</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">৳</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Delivery charge" aria-describedby="basic-addon1">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>Restaurant Logo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputGroupFile01">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>App Preview</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputGroupFile02">
                                        <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label>Restaurant Category</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach($restaurantCategory as $key => $category)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" class="checkbox-input" id="checkbox0{{ $key }}" value="{{ $category->category }}">
                                                <label for="checkbox0{{ $key }}">{{ $category->category }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="discountForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3334" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel3334">Set Restaurant Discount </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/v1/setRestaurantDiscount') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="text" name="rest_id" id="disc_restaurant_id" value="" required hidden>
                            <div class="col-sm-4">
                                <label>Discount</label>
                                <ul class="list-unstyled">
                                    <li class="d-inline-block">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="text" name="discount_status" id="discount_status_val" value="NO" hidden required>
                                                <input type="checkbox" class="checkbox-input" id="discount-status" value="YES">
                                                <label for="discount-status">Yes</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-8">
                                <label>Type</label>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio">
                                                <input type="radio" name="discount_type" id="percentageCheck" value="Percentage">
                                                <label for="percentageCheck">Percentage (%)</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Discount Amount</label>
                                    <input type="text" class="form-control" placeholder="Enter discount amount" name="discount_amount" id="discount_amount">
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

    <div class="modal fade text-left" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <form action="{{ url('/v1/updateRestaurant') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Update Restaurant Info</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label>Restaurant Logo</label><br>
                                <img id="logo_1" src="{{ asset('/images/dummy/200x200.png') }}" height="180px" style="padding: 20px" />
                                <img id="logo_2" class="round" src="{{ asset('/images/dummy/200x200.png') }}" height="100px" style="margin: 20px" />
                                <img id="logo_3" class="round" src="{{ asset('/images/dummy/200x200.png') }}" height="50px" style="margin: 20px" />
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label>Restaurant Preview</label><br>
                                <img id="rest_preview" src="{{ asset('/images/dummy/800x300.png') }}" width="100%" />
                            </div>
                            <div class="col-sm-12">
                                <hr>
                            </div>
                            <input type="text" name="rest_id" id="rest_id" value="" required hidden>
                            <div class="col-sm-12 col-md-6">
                                <fieldset class="form-group">
                                    <label>Restaurant Name</label>
                                    <input type="text" class="form-control" name="rest_name" placeholder="Enter restaurant name" id="rest_name" required>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <fieldset class="form-group">
                                    <label>Mobile Number</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+88</span>
                                        </div>
                                        <input type="text" class="form-control" name="rest_mobile" placeholder="Mobile number" id="rest_mobile" aria-describedby="basic-addon1" minlength="11" maxlength="11">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <fieldset class="form-group">
                                    <label>Restaurant Address</label>
                                    <input type="text" class="form-control" name="rest_address" placeholder="Enter restaurant address" id="rest_address">
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>Geolocation</label>
                                    <input type="text" class="form-control" name="rest_geolocation" placeholder="Enter co-ordinates" id="rest_geo">
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>Delivery Charge</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">৳</span>
                                        </div>
                                        <input type="text" class="form-control" name="rest_delivery" placeholder="Delivery charge" id="rest_delivery" aria-describedby="basic-addon1">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>Restaurant Logo</label>
                                    <div class="custom-file">
                                        <input type="file" name="rest_logo" class="custom-file-input" id="inputGroupFile04" accept="image/*">
                                        <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <fieldset class="form-group">
                                    <label>App Preview</label>
                                    <div class="custom-file">
                                        <input type="file" name="rest_preview" class="custom-file-input" id="inputGroupFile03" accept="image/*">
                                        <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label>Restaurant Category</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach($restaurantCategory as $key => $category)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox">
                                                <input type="checkbox" name="rest_category[]" class="checkbox-input" id="checkbox{{ $key }}" value="{{ $category->category }}">
                                                <label for="checkbox{{ $key }}">{{ $category->category }}</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    @endforeach
                                </ul>
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

                </div>
            </div>
        </form>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        $(document).ready(function() {
            $("#discount-status").change(function() {
                if ($("#discount-status").val() == "YES") {
                    $("#discount_status_val").val("YES");
                    $("#discount-status").val("NO");
                    $("#percentageCheck").prop("checked", true);
                    $("#discount_amount").attr("required", true);
                } else {
                    $("#discount_status_val").val("NO");
                    $("#discount-status").val("YES");
                    $("#percentageCheck").prop("checked", false);
                    $("#discount_amount").attr("required", false);
                }
            });
        });

        function showEditModal(restaurantID) {
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

            for (var j = 0; j < '{{ count($restaurantCategory) }}'; j++) {
                var chkboxID = '#checkbox' + j;
                $(chkboxID).prop("checked", false);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/v1/decodeRestaurant') }}",
                type: "POST",
                data: {
                    restaurantID: restaurantID
                },
                success: function(result) {
                    $("#rest_id").val(result[0]['restaurant_id']);
                    $("#rest_name").val(result[0]['restaurant_name']);
                    $("#rest_mobile").val(result[0]['restaurant_mobile']);
                    $("#rest_address").val(result[0]['restaurant_address']);
                    $("#rest_geo").val(result[0]['restaurant_coordinate']);
                    $("#rest_delivery").val(result[0]['delivery_charge']);
                    var categoryArr = result[0]['restaurant_category'].split(',');
                    for (var i = 0; i < categoryArr.length; i++) {
                        for (var j = 0; j < '{{ count($restaurantCategory) }}'; j++) {
                            var chkboxID = '#checkbox' + j;
                            if ($(chkboxID).val() == categoryArr[i]) {
                                $(chkboxID).prop("checked", true);
                            }
                        }
                    }

                    if (result[0]['restaurant_logo'] != "") {
                        var imgPath = '{{ URL::to("/") }}' + "/" + result[0]['restaurant_logo'];
                        $("#logo_1").attr('src', imgPath);
                        $("#logo_2").attr('src', imgPath);
                        $("#logo_3").attr('src', imgPath);
                    } else {
                        var imgPath = '{{ URL::to("/") }}' + "/images/dummy/200x200.png";
                        $("#logo_1").attr('src', imgPath);
                        $("#logo_2").attr('src', imgPath);
                        $("#logo_3").attr('src', imgPath);
                    }

                    if (result[0]['restaurant_preview'] != "") {
                        var imgPath = '{{ URL::to("/") }}' + "/" + result[0]['restaurant_preview'];
                        $("#rest_preview").attr('src', imgPath);
                    } else {
                        var imgPath = '{{ URL::to("/") }}' + "/images/dummy/800x300.png";
                        $("#rest_preview").attr('src', imgPath);
                    }

                    Swal.close();

                    $("#editInfoModal").modal('show');
                }
            });
        }

        function statusUpdate(id, restaurantID) {
            var status = "";
            if ($("#" + id).val() == "Active") {
                status = "Active";
                $("#" + id).val("Inactive");
            } else {
                status = "Inactive";
                $("#" + id).val("Active");
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/v1/updateRestaurantStatus') }}",
                type: "POST",
                data: {
                    rest_id: restaurantID,
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

        function showDiscountModal(restaurantID, discount) {
            if (discount > 0) {
                $("#discount-status").prop("checked", true);
                $("#percentageCheck").prop("checked", true);
                $("#discount_amount").val(discount);
            } else {
                $("#discount-status").prop("checked", false);
                $("#percentageCheck").prop("checked", false);
                $("#discount_amount").val("");
            }
            $("#disc_restaurant_id").val(restaurantID);
            $("#discountForm").modal('show');
        }
    </script>
</body>
@endsection