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
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add New Product</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/restaurant/addProduct/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <!-- <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Enter product name" required>
                                            </div> -->
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="city_Id" class="form-control all-city-list" id="city_Id" required>
                                                        <option disabled selected>Select city</option>
                                                        @foreach($cityList as $key => $item)
                                                        <option value="{{ $item->id }}">{{ $item->city_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="restaurant_id" class="form-control res-name-list" id="restaurant_id" required>
                                                    <option disabled selected>Select Restaurant</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <!-- <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="branch_id" class="form-control res-banch-list" id="branch_id">
                                                    <option value="0">Select Branch</option>
                                                    </select>
                                                </fieldset>
                                            </div> -->

                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="text" class="form-control" name="product_name" placeholder="Enter Product Name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="category_id" class="form-control" id="category_id" required>
                                                    <option disabled selected>Select Category</option>
                                                    @foreach($productCategory as $key => $item)
                                                        <option value="{{ $item->id }}">{{ $item->category }}</option>
                                                    @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="number" class="form-control" name="product_price" placeholder="Enter product price" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="product_type" class="form-control" id="product_type" required>
                                                        <option disabled selected>Select product Type</option>
                                                        <option value="1">Reqular Product</option>
                                                        <option value="2">Feature + Reqular Product</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="product_thumbnail" required>
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose product image</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="product_status" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="2">Deactivate</option>
                                                        
                                                    </select>
                                                </fieldset>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
                                            </div>
                                        </div>
                                    </form>
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
        $(document).ready(function(){

            $(document).on('change','.all-city-list',function(){
                //console.log("hmm its change");

                var cityId = $("#city_Id").val();
                //console.log(cityId);
                var op = " ";

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('/restaurant/addProduct/showRestaurantList') }}",
                    type: "get",
                    data: {
                        cityId: cityId
                    },
                    success: function(result) {
                        // console.log('success');
                        // console.log(result);
                        // console.log(result.length);

                        op+='<option value="0" disabled selected>Select Restaurant</option>';
                        for(var i=0; i<result.length; i++){
                            //console.log(result[i].name);
                            op+='<option value="'+result[i].id+'">'+result[i].name+'</option>';
                        }
                        $('.res-name-list').html(op);

                    },
                    error:function(){
                        console.log('error');
                    }
                });
            });

            // $(document).on('change','.res-name-list',function(){
            //     //console.log("hmm its change");

            //     var resId = $("#restaurant_id").val();
            //     //console.log(cityId);
            //     var op = " ";

            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         url: "{{ url('/restaurant/addProduct/showBranchList') }}",
            //         type: "get",
            //         data: {
            //             resId: resId
            //         },
            //         success: function(result) {
            //             op+='<option value="0">Select Branch</option>';
            //             for(var i=0; i<result.length; i++){
            //                 op+='<option value="'+result[i].id+'">'+result[i].branchName+'</option>';
            //             }
            //             $('.res-banch-list').html(op);

            //         },
            //         error:function(){
            //             console.log('error');
            //         }
            //     });
            // });
        });
    </script>

</body>
@endsection