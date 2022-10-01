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
                                        <table id="" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>Info</th>
                                                    <th>Price</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td style="text-align: center;">
                                                        @if($item->image)
                                                            <?php if (file_exists("../public".$item->image)){ ?>
                                                            <div class="osahan-slider-item" style="background-color:#fff;">
                                                                <img src="{{asset($item->image)}}" style="height:100px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                            </div>
                                                            <?php } else{ ?>
                                                            <div class="osahan-slider-item" style="background-color:#fff;">
                                                                <img src="{{asset('/images/loading.gif')}}" style="height:100px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                            </div>
                                                            <?php } ?>
                                                        @else
                                                            <div class="osahan-slider-item" style="background-color:#fff;">
                                                                <img src="{{asset('/images/loading.gif')}}" style="height:100px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                            </div>
                                                        @endif
                                                    
                                                        <form action="{{ url('/restaurant/allProduct/updateImage') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="text" name="productImageId" value="{{ $item->id }}" hidden>
                                                                <div class="row" style="margin-top: 10px">
                                                                    <div class="col-6">
                                                                        <fieldset class="form-group">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="inputGroupFile02" name="editImage" required>
                                                                                <label class="custom-file-label" for="inputGroupFile02"></label>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <button type="submit" class="btn btn-success glow">Save</button>
                                                                    </div>
                                                                </div>
                                                        </form>

                                                    </td>
                                                    <td>
                                                        {{ $item->name }}
                                                    </td>
                                                    <td>
                                                    <small><b>Restaurant:</b> 
                                                            @foreach($restaurantList as $restaurant)
                                                                @if($restaurant->id == $item->resId)
                                                                    {{ $restaurant->name }}
                                                                @endif
                                                            @endforeach
                                                        </small>
                                                        <br>
                                                        <small><b>City:</b> 
                                                            @foreach($cityList as $cityName)
                                                                @if($cityName->id == $item->cityID)
                                                                    {{ $cityName->city_name }}
                                                                @endif
                                                            @endforeach
                                                        </small>
                                                        <br>
                                                        <small><b>Branch:</b>
                                                            @foreach($branchList as $branch)
                                                                @if($branch->id == $item->branchId)
                                                                    {{ $branch->branchName }}
                                                                @endif
                                                            @endforeach
                                                        </small>
                                                        <br>
                                                        <small><b>Menu:</b>
                                                            @foreach($productCategory as $menu)
                                                                @if($menu->id == $item->category)
                                                                    {{ $menu->category }}
                                                                @endif
                                                            @endforeach
                                                        </small>
                                                        <br>
                                                        @if($item->type == "1")
                                                            <small><b>Type:</b> Reqular Product</small>
                                                        @elseif($item->type == "2")
                                                            <small><b>Type:</b> Feature + Reqular Product</small>
                                                        @else
                                                            <small><b>Type:</b> Not found</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small><b>Price:</b> {{ $item->price }}</small>
                                                        <br>
                                                        <small><b>Discount Type:</b>
                                                            @if($item->discount_type == "1")
                                                                Flat
                                                            @elseif($item->discount_type == "2")
                                                                Percentage
                                                            @endif
                                                        </small>
                                                        <br>
                                                        <small><b>Old Price:</b> {{ $item->oldPrice }}</small>
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
                                                        <a href="#">
                                                            <div data-toggle="modal" data-target="#updateProductModal" class="badge badge-pill badge-secondary mb-1 round-cursor" onclick="updateProduct('{{ $item->id }}','{{ $item->name }}','{{ $item->resId }}','{{ $item->cityID }}','{{ $item->category }}','{{ $item->type }}','{{ $item->price }}')">Edit</div>
                                                        </a>
                                                        <br>
                                                        <a href="#">
                                                            <div data-toggle="modal" data-target="#updateDiscountModal" class="badge badge-pill badge-secondary mb-1 round-cursor" onclick="updateDiscount('{{ $item->id }}','{{ $item->price }}','{{ $item->discount_type }}','{{ $item->oldPrice }}')">Discount</div>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 col-12 overflow-auto">
                                            {!! $productList->links() !!}
                                        </div>
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

    <div class="modal fade" id="updateDiscountModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="editResForm" action="{{ url('/restaurant/allProduct/updateDiscount') }}" method="POST">
                @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Update Discount</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div class="form-group row">
                            <!-- <label class="col-sm-3 control-label">ID: </label> -->
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="editProductId" placeholder="ID" name="editProductId" required>
                            </div>
                        </div>
                        <div class="row">
                            <label for="editNewPrice" class="col-sm-3 control-label">New Discount Price: </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editNewPrice" placeholder="New Discount Price" name="editNewPrice" required>
                            </div>
                        </div> <!-- /form-group-->
                        <div class="row" style="margin-top: 10px;">
                            <label for="edit_dis_type" class="col-sm-3 control-label">Type</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="edit_dis_type" class="form-control" id="edit_dis_type" required>
                                        <option disabled selected>Select Discount Type</option>
                                        <option value="1">Flat</option>
                                        <option value="2">Percentage</option> 
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <label for="editOldPrice" class="col-sm-3 control-label">Old Price: </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editOldPrice" placeholder="Old Price" name="editOldPrice" required>
                            </div>
                        </div> <!-- /form-group-->
                    </div>
                    <div class="modal-footer editResFooter">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                        <button type="submit" class="btn btn-success" id="editResBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                    </div>
                <!-- /modal-footer -->
                </form>
                <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
        <!-- /modal-dailog -->
    </div>

    <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="editResForm" action="{{ url('/restaurant/allProduct/updateProduct') }}" method="POST">
                @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Product Update</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div class="form-group row">
                            <!-- <label class="col-sm-3 control-label">ID: </label> -->
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="editProId" placeholder="ID" name="editProId" required>
                            </div>
                        </div>
                        <div class="row">
                            <label for="editProductName" class="col-sm-3 control-label">Product Name: </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editProductName" placeholder="Product Name" name="editProductName" required>
                            </div>
                        </div> <!-- /form-group-->
                        <div class="row" style="margin-top: 10px;">
                            <label for="edit_restaurant" class="col-sm-3 control-label">Restaurant</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="edit_restaurant" class="form-control" id="edit_restaurant" required>
                                        <option disabled selected>Select Restaurant</option>
                                        @foreach($restaurantList as $restaurant)
                                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <label for="edit_city" class="col-sm-3 control-label">City</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="edit_city" class="form-control" id="edit_city" required>
                                        <option disabled selected>Select City</option>
                                        @foreach($cityList as $cityName)
                                            <option value="{{ $cityName->id }}">{{ $cityName->city_name }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <label for="edit_menu" class="col-sm-3 control-label">Menu</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="edit_menu" class="form-control" id="edit_menu" required>
                                        <option disabled selected>Select Menu</option>
                                        @foreach($productCategory as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->category }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>                                 
                        <div class="row">
                            <label for="editProduct_type" class="col-sm-3 control-label">Product Type</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="editProduct_type" class="form-control" id="editProduct_type" required>
                                        <option disabled selected>Select Product Type</option>
                                        <option value="1">Reqular Product</option>
                                        <option value="2">Feature + Reqular Product</option>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <label for="editPrice" class="col-sm-3 control-label">Price </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editPrice" placeholder="Price" name="editPrice" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer editResFooter">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                        <button type="submit" class="btn btn-success" id="editResBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                    </div>
                <!-- /modal-footer -->
                </form>
                <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
        <!-- /modal-dailog -->
    </div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>

        function updateProduct(id,name,resturant,city,menu,type,price){
            $('#editProId').val(id)
            $('#editProductName').val(name)
            $('#edit_restaurant').val(resturant)
            $('#edit_city').val(city)
            $('#edit_menu').val(menu)
            $('#editProduct_type').val(type)
            $('#editPrice').val(price)
        }

        function updateDiscount(id,price,type,oldPrice){
            $('#editProductId').val(id)
            $('#editNewPrice').val(price)
            $('#editOldPrice').val(oldPrice)
            $('#edit_dis_type').val(type)
            
        }

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
                url: "{{ url('/restaurant/allProduct/updateStatus') }}",
                type: "POST",
                data: {
                    product_id: product_id,
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