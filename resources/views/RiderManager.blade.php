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
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add New Rider</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/v1/addNewRider') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="text" class="form-control" name="name" id="rider_name" placeholder="Enter rider name" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="number" class="form-control" name="mobile" id="rider_mobile" placeholder="Enter rider mobile number" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input type="password" class="form-control" name="password" id="rider_password" placeholder="Enter password" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-3">
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
                                        <table id="rider-manager-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Name</th>
                                                    <th class="text-center">Mobile</th>
                                                    <th class="text-center">Updated On</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($riderList as $key => $rider)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $rider->name }}</td>
                                                    <td class="text-center">{{ $rider->mobile }}</td>
                                                    <td class="text-center">{{ date('d-M-Y h:i A', strtotime($rider->updated_at)) }}</td>
                                                    <td class="text-center">
                                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Update Password" onclick="updatePassword('{{ $rider->rider_id }}')">
                                                            <i class="bx bxs-key"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
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

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        function updatePassword(riderID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Update the password and send message to rider. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#66BB6A',
                cancelButtonColor: '#EF5350',
                confirmButtonText: 'Update Password'
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        imageUrl: 'https://miro.medium.com/max/1600/1*CsJ05WEGfunYMLGfsT2sXA.gif',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    })
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('/v1/updateRiderPassword') }}",
                        type: "POST",
                        data: {
                            riderID: riderID
                        },
                        success: function(result) {
                            if (!result.error) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: result.message,
                                    showConfirmButton: false,
                                    timer: 2000
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
            })
        }
    </script>
</body>
@endsection