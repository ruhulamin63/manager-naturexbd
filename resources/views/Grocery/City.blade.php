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
                                <h4 class="card-title">Add New City</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/city/create') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <input type="text" class="form-control" name="city_name" placeholder="Enter city name" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <fieldset class="form-group">
                                                    <select class="form-control" name="city_status" required>
                                                        <option value="" disabled>Select Status</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive" selected>Inactive</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-4">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
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
                                        <table id="three-item-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>City Preview</th>
                                                    <th>City Name</th>
                                                    <th>City Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cityList as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    @php
                                                        $city_preview = \App\Models\Grocery\CityPreview::where('id', $item->id)->get();
                                                    @endphp
                                                        <td>
                                                            @if (count($city_preview) == 1)
                                                            <img src="{{ asset($city_preview[0]->url) }}" width="200px"/>
                                                            @endif
                                                            <form action="{{ url('/grocery/city/preview/update') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="text" name="city_id" value="{{ $item->id }}" hidden>
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <fieldset class="form-group">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="inputGroupFile02" name="city_preview" accept=".png" required>
                                                                                <label class="custom-file-label" for="inputGroupFile02">Choose Preview</label>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <button type="submit" class="btn btn-success glow">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    <td>{{ $item->city_name }}</td>
                                                    @if($item->status == "Active")
                                                    <td class="text-center" style="width: 5%">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                                            <input type="checkbox" class="custom-control-input" checked="" id="statusSwitch{{ $key }}" value="Inactive" onclick="statusUpdate('{{ $item->id }}', '{{ $key }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="text-center" style="width: 5%">
                                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                                            <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $key }}" value="Active" onclick="statusUpdate('{{ $item->id }}', '{{ $key }}')">
                                                            <label class="custom-control-label" for="statusSwitch{{ $key }}"></label>
                                                        </div>
                                                    </td>
                                                    @endif
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
        function statusUpdate(city_id, item) {
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
                url: "{{ url('/grocery/city/update') }}",
                type: "POST",
                data: {
                    city_id: city_id,
                    city_status: status
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