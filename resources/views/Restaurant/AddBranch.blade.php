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
                                <h4 class="card-title">Add New Restaurant</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/restaurant/addBranch/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                        <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="city_Id" class="form-control all-city-list" id="city_Id" required>
                                                        <option disabled selected>Select city</option>
                                                        @foreach($cityList as $key => $item)
                                                        <option value="{{ $item->id }}">{{ $item->city_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <fieldset class="form-group">
                                                    <select name="restaurant_id" class="form-control res-name-list" id="restaurant_id" required>
                                                    <option disabled selected>Select Restaurant</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <input type="text" class="form-control" name="branch_name" placeholder="Enter Branch Name" required>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12 col-lg-6" style="margin-top:10px;">
                                                <fieldset class="form-group">
                                                    <select name="branch_type" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select Restaurant Type</option>
                                                        <option value="1">Regular</option>
                                                        <option value="2">Regular + Features</option>
                                                        
                                                    </select>
                                                </fieldset>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12 col-lg-6" style="margin-top:10px;">
                                                <fieldset class="form-group">
                                                    <select name="branch_status" class="form-control" id="basicSelect" required>
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
                                                    <th>Name</th>
                                                    <th>Info</th>
                                                    <th>Others</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($branchList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        {{ $item->branchName }}
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
                                                        @if($item->type == "1")
                                                            <small><b>Type:</b> Reqular Product</small>
                                                        @elseif($item->type == "2")
                                                            <small><b>Type:</b> Feature + Reqular Product</small>
                                                        @else
                                                            <small><b>Type:</b> Not found</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small><b>Created:</b> {{ date('M d,Y h:m a', strtotime($item->created_at)) }}</small>
                                                        <br>
                                                        <small><b>Updated:</b> {{ date('M d,Y h:m a', strtotime($item->updated_at)) }}</small>
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
                                                        <button type="submit" data-toggle="modal" data-target="#updateModal" class="btn btn-info glow" onclick="updateBranchName('{{ $item->id }}','{{ $item->branchName }}','{{ $item->resId }}','{{ $item->cityID }}')">Edit</button>
                                                        <button type="submit" id="deleteBtn" class="btn btn-danger glow" style="margin-top: 3px" onclick="deleteBranch('{{ $item->id }}')">Delete</button>
                                                        <br>
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
                    </div>
                </section>
                <!-- users list ends -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="editBranchForm" action="{{ url('/restaurant/addBranch/updateBranchName') }}" method="POST">
                @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
                    </div>
                    <div style="padding: 10px;">
                        <div class="form-group row">
                            <!-- <label class="col-sm-3 control-label">ID: </label> -->
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="editBranch_id" placeholder="ID" name="editBranch_id" required>
                            </div>
                        </div> 
                        <!-- /form-group-->
                        <div class="row">
                            <label for="editBranchName" class="col-sm-3 control-label">Name: </label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editBranchName" placeholder="Name" name="editBranchName" required>
                            </div>
                        </div> <!-- /form-group-->
                    </div>
                    <div class="modal-footer editBrandFooter">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                        <button type="submit" class="btn btn-success" id="editBrandBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                    </div>
                <!-- /modal-footer -->
                </form>
                <!-- /.form -->
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

        function deleteBranch(branch_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    
                    document.getElementById('deleteBtn').innerText = 'Loading..';
                    var status = "0";
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('/restaurant/addBranch/deleteBranch') }}",
                        type: "POST",
                        data: {
                            branch_id: branch_id
                        },
                        success: function(result) {
                            if (!result.error) {
                                document.getElementById('deleteBtn').innerText = 'Done';
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: result.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                location.reload();
                            } else {
                                document.getElementById('deleteBtn').innerText = 'Error';
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'danger',
                                    title: result.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                location.reload();
                            }
                        }
                    });
                }
            })
                // if(confirm("Are You Sure to delete this")){
                //     event.preventDefault(); 
                // }
        }

        function updateBranchName(id,branchName,resId,cityId) {
            $('#editBranch_id').val(id)
            $('#editBranchName').val(branchName)
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
                url: "{{ url('/restaurant/addBranch/updateStatus') }}",
                type: "POST",
                data: {
                    id: id,
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

                    }
                });
            });
            
        });
    </script>

</body>
@endsection