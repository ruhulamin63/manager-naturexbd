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
                                                Featured Restaurant Management
                                                <br><small>Total Restaurants: {{ count($restaurantList) }}</small>
                                            </span>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>

                    <form id="categorySort">
                        <section id="sortable-lists">
                            <div class="row">
                                <!-- Basic List Group -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <h5 class="pr-1 mb-0"><b>Feature Category</b></h5>
                                                    <small>Chage the order by dragging</small>
                                                </div>
                                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary" onclick="updateFeatureSerial()">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <ul class="list-group" id="basic-list-group-3">
                                                    @foreach($featureList as $key => $lists)
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <input type="text" name="sequence_{{ $key }}" value="{{ $lists->category }}" required hidden />
                                                            <div class="media-body">
                                                                <h5 class="mt-0"><b>{{ $lists->category }}</b></h5>
                                                                Last update : {{ date('d-M-Y h:i A', strtotime($lists->updated_at)) }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>

                    <form id="fastfood">
                        <section id="sortable-lists">
                            <div class="row">
                                <!-- Basic List Group -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <h5 class="pr-1 mb-0"><b>Fast Food</b></h5>
                                                    <small>Featured Restaurants</small>
                                                </div>
                                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary mr-1" onclick="updateFastFoodOrder()">Save</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#fastFoodList">Add/Edit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <ul class="list-group" id="basic-list-group">
                                                    @foreach($featuredFastFoodRestaurant as $key => $list)
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <input type="text" name="fast_food_{{ $key }}" value="{{ $list->restaurant_id }}" required hidden />
                                                            <div class="media-body">
                                                                <h5 class="mt-0"><b>{{ $list->restaurant_name }}</b></h5>
                                                                {{ $list->restaurant_address }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>

                    <form id="banglafood">
                        <section id="sortable-lists">
                            <div class="row">
                                <!-- Basic List Group -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <h5 class="pr-1 mb-0"><b>Bangla Food</b></h5>
                                                    <small>Featured Restaurants</small>
                                                </div>
                                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary mr-1" onclick="updateBanglaFoodOrder()">Save</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#banglaFoodList">Add/Edit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <ul class="list-group" id="basic-list-group-2">
                                                    @foreach($featuredBanglaFoodRestaurant as $key => $list)
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <input type="text" name="fast_food_{{ $key }}" value="{{ $list->restaurant_id }}" required hidden />
                                                            <div class="media-body">
                                                                <h5 class="mt-0"><b>{{ $list->restaurant_name }}</b></h5>
                                                                {{ $list->restaurant_address }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </section>
                <!-- users list ends -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <form id="featuredForm" action="{{ url('/v1/updateFeaturedRestaurants') }}" method="POST">
        @csrf
        <input type="text" name="city_id" value="{{ $cityID }}" hidden required />
        <input type="text" name="category" id="feature_category" value="Fast Food" hidden required />
        <input type="text" name="featured_restaurants" id="featured_restaurants" value="" hidden required />
    </form>

    <form id="featureOrderForm" action="{{ url('/v1/updateFeaturedRestaurantsOrder') }}" method="POST">
        @csrf
        <input type="text" name="city_id" value="{{ $cityID }}" hidden required />
        <input type="text" name="category" id="feature_category_sort" value="Fast Food" hidden required />
    </form>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <div class="modal fade text-left" id="fastFoodList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title white" id="myModalLabel110">Restaurant List<br><small>Fast Food</small></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning alert-dismissible mb-2" role="alert" id="limitAlert" hidden>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error"></i>
                            <span>
                                Maximum 5 restaurants can be selected.
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="feature-list-datatable" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Restaurant Name</th>
                                    <th class="text-center">Featured</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurantList as $key => $restaurant)
                                <tr class="modal-table">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $restaurant->restaurant_name }}</td>
                                    <td class="text-center">
                                        <div class="form-check checkbox" style="padding-left: 0px !important">
                                            <input type="checkbox" value="{{ $restaurant->restaurant_id }}" name="featureCheckBox" class="form-check-input checkbox-input featureCheck" id="featureCheck{{ $key }}" @if($restaurant->featured) checked @endif>
                                            <label class="form-check-label" for="featureCheck{{ $key }}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-success ml-1" onclick="saveFeatured()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="banglaFoodList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title white" id="myModalLabel110">Restaurant List<br><small>Bangla Food</small></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning alert-dismissible mb-2" role="alert" id="limitAlert" hidden>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error"></i>
                            <span>
                                Maximum 5 restaurants can be selected.
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="feature-list-datatable" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Restaurant Name</th>
                                    <th class="text-center">Featured</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurantList as $key => $restaurant)
                                <tr class="modal-table">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $restaurant->restaurant_name }}</td>
                                    <td class="text-center">
                                        <div class="form-check checkbox" style="padding-left: 0px !important">
                                            <input type="checkbox" value="{{ $restaurant->restaurant_id }}" name="featureCheckBoxBangla" class="form-check-input checkbox-input featureCheck" id="banglaFeatureCheck{{ $key }}" @if($restaurant->featured) checked @endif>
                                            <label class="form-check-label" for="banglaFeatureCheck{{ $key }}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-success ml-1" onclick="saveFeaturedBangla()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        var limit = 10;
        var checkedRestaurants = [];
        var featuredRestaurants = "";

        function saveFeatured() {
            checkedRestaurants = [];
            $.each($("input[name='featureCheckBox']:checked"), function() {
                checkedRestaurants.push($(this).val());
            });
            featuredRestaurants = checkedRestaurants.join(",");
            $("#feature_category").val("Fast Food");
            $("#featured_restaurants").val(featuredRestaurants);
            $("#featuredForm").submit();
        }

        function saveFeaturedBangla() {
            checkedRestaurants = [];
            $.each($("input[name='featureCheckBoxBangla']:checked"), function() {
                checkedRestaurants.push($(this).val());
            });
            featuredRestaurants = checkedRestaurants.join(",");
            $("#feature_category").val("Bangla");
            $("#featured_restaurants").val(featuredRestaurants);
            $("#featuredForm").submit();
        }

        $("input[name='featureCheckBox']").on('change', function(evt) {
            var checked = $("input[name='featureCheckBox']:checked").length;
            console.log(checked);
            if (checked > limit) {
                this.checked = false;
                $("#limitAlert").attr("hidden", false);
            } else {
                $("#limitAlert").attr("hidden", true);
            }
        });

        $("input[name='featureCheckBoxBangla']").on('change', function(evt) {
            var checked = $("input[name='featureCheckBoxBangla']:checked").length;
            console.log(checked);
            if (checked > limit) {
                this.checked = false;
                $("#limitAlert").attr("hidden", false);
            } else {
                $("#limitAlert").attr("hidden", true);
            }
        });

        function updateFastFoodOrder() {
            var fastFoodSerialization = $("#fastfood").serializeArray();
            var newFastFoodOrder = [];
            $.each(fastFoodSerialization, function(i) {
                newFastFoodOrder.push(fastFoodSerialization[i]['value']);
            });
            var featuredFastFood = newFastFoodOrder.join(",");
            $("#feature_category").val("Fast Food");
            $("#featured_restaurants").val(featuredFastFood);
            $("#featuredForm").submit();
        }

        function updateBanglaFoodOrder() {
            var banglaFoodSerialization = $("#banglafood").serializeArray();
            var newBanglaFoodOrder = [];
            $.each(banglaFoodSerialization, function(i) {
                newBanglaFoodOrder.push(banglaFoodSerialization[i]['value']);
            });
            var featuredBanglaFood = newBanglaFoodOrder.join(",");
            $("#feature_category").val("Bangla");
            $("#featured_restaurants").val(featuredBanglaFood);
            $("#featuredForm").submit();
        }

        function updateFeatureSerial(){
            var featureSerialization = $("#categorySort").serializeArray();
            var newCategoryOrder = [];
            $.each(featureSerialization, function(i) {
                newCategoryOrder.push(featureSerialization[i]['value']);
            });
            var featureOrder = newCategoryOrder.join(",");
            $("#feature_category_sort").val(featureOrder);
            $("#featureOrderForm").submit();
        }
    </script>
</body>
@endsection