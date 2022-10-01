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
                                                    <th>Logo</th>
                                                    <th>Name</th>
                                                    <th>Information</th>
                                                    <th>Discount Info</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($restaurantInfo as $key => $resItem)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        @if($resItem->logo)
                                                            <?php if (file_exists("../public".$resItem->logo)){ ?>
                                                                <div class="osahan-slider-item" style="background-color:#fff;">
                                                                    <img src="{{asset($resItem->logo)}}" style="height:100px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                                </div>
                                                            <?php } else{ ?>
                                                                <div class="osahan-slider-item" style="background-color:#fff;">
                                                                    <img src="https://i.gifer.com/B0eS.gif" style="height:100px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                                </div>
                                                            <?php } ?>
                                                        @else
                                                            <div class="osahan-slider-item" style="background-color:#fff;">
                                                                <img src="https://i.gifer.com/B0eS.gif" style="height:100px;box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                                                            </div>
                                                        @endif
                                                        <br>
                                                        <a href="#" data-toggle="modal" data-target="#coverImageModal">
                                                            <div class="badge badge-pill badge-secondary mb-1 round-cursor" onclick="coverImageShow('{{ $resItem->coverImage }}')">Show Cover Image</div>
                                                        </a>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <b>{{ $resItem->name }}</b>
                                                        <br>
                                                        <small><b>City:</b>
                                                            @foreach($cityList as $cityName)
                                                                @if($cityName->id == $resItem->cityID)
                                                                    {{ $cityName->city_name }}
                                                                @endif
                                                            @endforeach
                                                        </small>
                                                        <br>
                                                        <small><b>Type:</b>
                                                        @if($resItem->type == "1")
                                                            Reqular
                                                        @elseif($resItem->type == "2")
                                                            Feature + Reqular
                                                        @else
                                                            Not found
                                                        @endif
                                                        </small>

                                                        <br>

                                                        <small><b>Category:</b>
                                                            @foreach($resCategory as $resCat)
                                                                @if($resCat->res_id == $resItem->id)
                                                                    @foreach($categoryList as $category)
                                                                        @if($resCat->cat_id == $category->id)
                                                                            {{$category->name}},
                                                                            @break
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small><b>Open:</b>
                                                            {{ $resItem->opening_time}}
                                                        </small>
                                                        <br>
                                                        <small><b>Close:</b>
                                                            {{ $resItem->closing_time }}
                                                         </small>
                                                        <br>
                                                        <small><b>Rating:</b>
                                                        {{$resItem->rating}}
                                                        </small>
                                                        <br>
                                                        <small><b>Phone:</b>
                                                        {{$resItem->phone}}
                                                        </small>
                                                        <br>
                                                        <small><b>Address:</b>{{$resItem->address}}
                                                        </small>
                                                        <br>
                                                        <small><b>Latitude & Longitude:</b>{{$resItem->lat}},{{$resItem->lon}}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small><b>Discount: </b>
                                                         {{ $resItem->discount }}
                                                        </small>
                                                        <br>
                                                        <small><b>Type:</b>
                                                        @if($resItem->discount_type == 1)
                                                            Flat
                                                        @elseif($resItem->discount_type == 1)
                                                            percentage
                                                        @else
                                                            No Type Set Yet
                                                        @endif
                                                        </small>
                                                        <br>
                                                        <br>
                                                        @if($resItem->discount > 0)
                                                        <a href="{{ url('/restaurant/RestaurantList/addProperty/removeDiscount?id='.$resItem->id) }}">
                                                            <div class="badge badge-pill badge-secondary mb-1 round-cursor">Remove Discount</div>
                                                        </a>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($resItem->status == "1")
                                                        <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                            <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="statusUpdate('{{ $resItem->id }}', '{{ $key }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                        @else
                                                        <div class="custom-control custom-switch custom-control-inline mb-1" style="margin-top: 15px;">
                                                            <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="statusUpdate('{{ $resItem->id }}', '{{ $key }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        
                                                        <div type="submit" data-toggle="modal" data-target="#updateModal" class="badge badge-pill badge-info" onclick="updateRes('{{ $resItem->id }}','{{ $resItem->name }}','{{ $resItem->cityID }}','{{ $resItem->category }}','{{ $resItem->type }}')">Edit Restaurant</div>
                                                        <br><br>
                                                        <div type="submit" data-toggle="modal" data-target="#updateCatModal" class="badge badge-pill badge-info mb-1 round-cursor" onclick="updateResCat('{{ $resItem->id }}')">Update Category</div>
                                                        <br>
                                                        @if($resItem->resId == "")
                                                            <a href="{{ url('/restaurant/RestaurantList/addProperty?id=' . base64_encode($resItem->id)) }}" style="margin-top: 5px;">
                                                                <button type="submit" class="btn btn-info glow">Add Property</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ url('/restaurant/RestaurantList/editProperty?id=' . base64_encode($resItem->id)) }}" style="margin-top: 5px;">
                                                                <button type="submit" class="btn btn-info glow">Edit Property</button>
                                                            </a>
                                                            <br>
                                                            <button type="submit" data-toggle="modal" data-target="#updateDiscountModal" class="btn btn-info glow" style="margin-top: 5px;" onclick="updateDiscount('{{ $resItem->id }}','{{ $resItem->discount }}','{{ $resItem->discount_type }}')">Add Discount</button>
                                                        @endif                                                      
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 col-12 overflow-auto">
                                            {!! $restaurantInfo->links() !!}
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

    <div class="modal fade" id="updateCatModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="editCatForm" action="{{ url('/restaurant/RestaurantList/restaurantCategoryUpdate') }}" method="POST">
                @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div class="form-group row">
                            <!-- <label class="col-sm-3 control-label">ID: </label> -->
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="editResCat_id" placeholder="ID" name="editResCat_id" required>
                            </div>
                        </div> 
                        <div class="row" style="margin-top:10px;">
                        <label for="edit_categoryList" class="col-sm-12 control-label">Select Restaurant Category</label>
                            <div class="col-sm-12" style="margin-top:10px;">
                                <ul class="list-unstyled mb-0">
                                    @foreach($categoryList as $key => $item)
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" name="editCategory_coverage[]" id="checkbox{{ $key }}" value="{{ $item->id }}">
                                                    <label for="checkbox{{ $key }}">{{ $item->name }}</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    @endforeach
                                </ul>
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
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="editResForm" action="{{ url('/restaurant/RestaurantList/restaurantUpdate') }}" method="POST">
                @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div class="form-group row">
                            <!-- <label class="col-sm-3 control-label">ID: </label> -->
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="editRes_id" placeholder="ID" name="editRes_id" required>
                            </div>
                        </div> 
                        <div class="row">
                            <label for="edit_cityList" class="col-sm-3 control-label">City</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="edit_cityList" class="form-control" id="edit_cityList" required>
                                        <option disabled selected>Select City</option>
                                        @foreach($cityList as $key => $item)
                                            <option value="{{ $item->id }}">{{ $item->city_name }}</option>
                                        @endforeach     
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <!-- /form-group-->
                        <div class="row">
                            <label for="editResName" class="col-sm-3 control-label">Name: </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editResName" placeholder="Name" name="editResName" required>
                            </div>
                        </div> <!-- /form-group-->
                        <div class="row" style="margin-top:10px;">
                            <label for="edit_typeList" class="col-sm-3 control-label">Type</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <fieldset class="form-group">
                                    <select name="edit_typeList" class="form-control" id="edit_typeList" required>
                                        <option disabled selected>Select Type</option>
                                        <option value="1">Regular</option>
                                        <option value="2">Regular + Features</option> 
                                    </select>
                                </fieldset>
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

    <div class="modal fade" id="updateDiscountModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="editResForm" action="{{ url('/restaurant/RestaurantList/addProperty/updateDiscount') }}" method="POST">
                @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Update Discount</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div class="form-group row">
                            <!-- <label class="col-sm-3 control-label">ID: </label> -->
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="edit_Dis_Res_id" placeholder="ID" name="edit_Dis_Res_id" required>
                            </div>
                        </div>
                        <div class="row">
                            <label for="editDis" class="col-sm-3 control-label">Discount: </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editDis" placeholder="Discount" name="editDis" required>
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

    <div class="modal fade" id="coverImageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Cover Image</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div>
                            <div class="osahan-slider-item" style="background-color:#fff;">
                                <img src="#" name="cover_image" id="cover_image" style="box-shadow:none !important;object-fit:contain;" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image">
                            </div>
                        </div> <!-- /form-group-->
                    </div>
                    <div class="modal-footer editResFooter">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                    </div>
            </div>
            <!-- /modal-content -->
        </div>
        <!-- /modal-dailog -->
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>

        function updateResCat(id) {
            $('#editResCat_id').val(id)            
        }

        function updateRes(id,name,cityId,category,type) {
            $('#editRes_id').val(id)
            $('#editResName').val(name)
            $('#edit_cityList').val(cityId)
            $('#edit_categoryList').val(category)
            $('#edit_typeList').val(type)
            
        }

        function updateDiscount(id,discount,type){
            $('#edit_Dis_Res_id').val(id)
            $('#editDis').val(discount)
            $('#edit_dis_type').val(type)
            
        }

        function coverImageShow(image){
            if(image == ""){
                $("#cover_image").attr("src", "https://i.gifer.com/B0eS.gif");
            }else{
                $("#cover_image").attr("src", image);
            }
            
        }

        function statusUpdate(id, item) {
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
                url: "{{ url('/restaurant/RestaurantList/status/update') }}",
                type: "POST",
                data: {
                    res_id: id,
                    res_status: status
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