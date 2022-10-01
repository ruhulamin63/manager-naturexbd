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
                                <h4 class="card-title">Select Product</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary btn-block" onclick="addProduct()">Add Product</button>
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
                                        <table id="four-item-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Thumbnail</th>
                                                    <th>Product Details</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($megaProducts as $key => $item)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        <img src="{{ asset($item->product_image) }}" width="80px" height="80px" />
                                                    </td>
                                                    <td>
                                                        {{ $item->product_name }}<br>
                                                        <small>{{ $item->product_description }}</small><br><br>
                                                        Regular Price: {{ $item->regular_price }} Tk.<br>
                                                        <font color="red">Discounted Price: {{ $item->discounted_price }} Tk.</font>
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- <div class="badge badge-pill badge-light-warning round-cursor">Edit</div> -->
                                                        <a href="{{ route('megadays.products.delete', ['cid' => $cid, 'pid' => $item->pid]) }}">
                                                            <div class="badge badge-pill badge-light-danger round-cursor">Delete</div>
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

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <div class="modal fade text-left" id="productList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Product Catalogue</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="five-item-datatable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th class='text-center'>Thumbnail</th>
                                        <th>Description</th>
                                        <th class='text-center'>Price</th>
                                        <th class='text-center'>Discount Price</th>
                                        <th class='text-center'>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productsList">
                                    @foreach($products as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset($item->product_thumbnail) }}" width="80px" height="80px" />
                                        </td>
                                        <td>
                                            {{ $item->product_name }}
                                            <br>
                                            <small>{{ $item->product_description }}</small>
                                        </td>
                                        <td class="text-center">{{ $item->product_price }} Tk</td>
                                        <td class="text-center">
                                            <input type="text" class="text-center form-control" name="discount" id="qt{{ $key }}" min="1" required autocomplete="off">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success" onclick="addToList('{{ $key }}', '{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->product_description }}', '{{ $item->product_price }}', '{{ $item->product_thumbnail }}')">Add</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" onclick="addToCategory()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var categoryItem = [];
        var index = 0;
        var counter = '{{ count($megaProducts) }}';

        function addProduct() {
            $("#productList").modal('show');
        };

        function addToList(id, productID) {
            counter = parseInt(counter);
            var price = $("#qt" + id).val();
            if (price == "") {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Price can not be blank.',
                    showConfirmButton: false,
                    timer: 800
                });
            } else {
                if (counter <= 2) {
                    var matched = false;
                    for (i = 0; i < categoryItem.length; i++) {
                        if (categoryItem[i].productID == productID) {
                            matched = true;
                            break;
                        }
                    }

                    if (!matched) {
                        var productMap = {};
                        productMap['productID'] = productID;
                        productMap['discountedPrice'] = $("#qt" + id).val();
                        categoryItem[index] = productMap;
                        index++;
                        counter++;
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Added to list.',
                            showConfirmButton: false,
                            timer: 800
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'Already in the list.',
                            showConfirmButton: false,
                            timer: 800
                        });
                    }
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Product limit reached!',
                        showConfirmButton: false,
                        timer: 800
                    });
                }
            }
        }

        function addToCategory() {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Adding to category.',
                text: 'Please Wait...',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            for (i = 0; i < categoryItem.length; i++) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('megadays.products.store', ['mid' => $mid, 'cid' => $cid]) }}",
                    type: "POST",
                    data: {
                        productID: categoryItem[i].productID,
                        discountedPrice: categoryItem[i].discountedPrice
                    }
                });

            }
            setTimeout(function() {
                location.reload();
            }, 1000);
        }
    </script>
</body>
@endsection