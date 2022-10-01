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
                                <h4 class="card-title">Create Custom Order</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/products/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="text" class="form-control" id="customer_name" placeholder="Enter customer name" value="{{ $name }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">+88</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="customer_mobile" maxlength="11" value="{{ $mobile }}" aria-describedby="basic-addon1" placeholder="Enter customer mobile number" required autocomplete="off">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" id="delivery_address" placeholder="Enter delivery address" value="{{ $address }}" required autocomplete="off">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="division" class="form-control" id="division" required>
                                                        <option value="Select Division" selected>Select Division</option>
                                                        @foreach($cityList as $key => $item)
                                                        @if($cityName == $item->city_name)
                                                        <option value="{{ $item->city_name }}" selected>{{ $item->city_name }}</option>
                                                        @else
                                                        <option value="{{ $item->city_name }}">{{ $item->city_name }}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="text" class="form-control" id="delivery_note" placeholder="Enter delivery note" value="{{ $note }}" required autocomplete="off">
                                            </div>
                                            <div class="col-sm-12 col-12 col-lg-6 mb-1">
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" id="scheduled_date" class="form-control format-picker" placeholder="Select Delivery Date" value="{{ $schedule }}" required>
                                                    <div class="form-control-position">
                                                        <i class='bx bx-calendar'></i>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <button type="button" class="btn btn-primary btn-block" onclick="addProduct()">Add Product</button>
                                                <table class="table table-bordered mt-2 mb-5" id="cartTable" hidden>
                                                    <thead>
                                                        <tr>
                                                            <td>SN</td>
                                                            <td>Item Details</td>
                                                            <td class='text-center'>Quantity</td>
                                                            <td class='text-center'>Unit Price</td>
                                                            <td class='text-center'>Total Price</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cartItems">
                                                    </tbody>
                                                    <tbody>
                                                        <input type="text" id="order_data" name="order_data" value="" hidden required />
                                                        <tr>
                                                            <td class="text-right" colspan="4">Subtotal</td>
                                                            <td class='text-center' id="subTotalTd"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Delivery Charge</td>
                                                            <td class='text-center' id="deliveryChargeTd"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Discount</td>
                                                            <td>
                                                                <input type="number" class="text-center form-control" id="discount" min="0" value="0" required autocomplete="off" onkeyup="updateTotal()">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right" colspan="4">Total</td>
                                                            <td class='text-center' id="totalAmountTd"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="button" class="btn btn-block btn-success glow" onclick="updateOrder()">Update Order</button>
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
                                        <th class='text-center'>Quantity</th>
                                        <th class='text-center'>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productsList">
                                    @foreach($productList as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td class="text-center">
                                            <img src="{{ $item->product_thumbnail }}" width="80px" height="80px" />
                                        </td>
                                        <td>
                                            {{ $item->name }}
                                            <br>
                                            <small>{{ $item->details }}</small>
                                        </td>
                                        <td class="text-center">{{ $item->product_price }} Tk</td>
                                        <td class="text-center">
                                            <input type="text" class="text-center form-control" name="quantity" id="qt{{ $key }}" min="1" value="1" required autocomplete="off">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success" onclick="addToCart('{{ $key }}', '{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->product_description }}', '{{ $item->product_price }}', '{{ $item->product_thumbnail }}')">Add</button>
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
                    <button type="button" class="btn btn-primary ml-1" onclick="generateInvoice()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var cartItem = [];
        var index = 0;
        var subTotal = 0;
        var deliveryCharge = 0;
        var discount = 0;
        var totalAmount = 0;
        var preFinalPrice = 0;

        $(document).ready(function() {
            tempCart = '{{ $orderData }}';
            tempCart = tempCart.replace(/&quot;/g, '"');
            tempCart = JSON.parse(tempCart);
            index = 0;
            for (i = 0; i < tempCart.length; i++) {
                addToCartForEdit(tempCart[i].quantity, tempCart[i].id, tempCart[i].product_name, tempCart[i].e, tempCart[i].unit_price)
            }
            generateInvoice();
        });

        function addProduct() {
            $("#productList").modal('show');
        }

        function addToCartForEdit(quantity, productID, productName, productDescription, productPrice, productImage) {
            var productMap = {};
            productMap['productID'] = productID;
            productMap['productName'] = productName;
            productMap['productDescription'] = productDescription;
            productMap['productQuantity'] = quantity;
            productMap['productPrice'] = productPrice;
            productMap['productImage'] = productImage;
            cartItem[index] = productMap;
            index++;
        }

        function addToCart(id, productID, productName, productDescription, productPrice, productImage) {
            var matched = false;
            for (i = 0; i < cartItem.length; i++) {
                if (cartItem[i].productID == productID) {
                    cartItem[i].productQuantity = $("#qt" + id).val();
                    matched = true;
                    break;
                }
            }

            if (!matched) {
                var productMap = {};
                productMap['productID'] = productID;
                productMap['productName'] = productName;
                productMap['productDescription'] = productDescription;
                productMap['productQuantity'] = $("#qt" + id).val();
                productMap['productPrice'] = productPrice;
                productMap['productImage'] = productImage;
                cartItem[index] = productMap;
                index++;
            }

            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Added to cart',
                showConfirmButton: false,
                timer: 800
            });
        }

        function generateInvoice() {
            Swal.fire({
                title: 'Generating Invoice',
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            $("#cartItems").html("");
            var totalPrice = 0;
            for (i = 0; i < cartItem.length; i++) {
                var row = "<tr><td>" + (i + 1) + "</td><td>" + cartItem[i].productName + "<div class='badge badge-pill badge-danger round-cursor' style='margin-left: 10px' onclick='removeFromCart(" + cartItem[i].productID + ")'>X</div><br><small>";
                row += "</small></td><td class='text-center'>" + cartItem[i].productQuantity + "</td><td class='text-center'>" + cartItem[i].productPrice + " Tk</td>";
                row += "<td class='text-center'>" + (parseInt(cartItem[i].productQuantity) * parseInt(cartItem[i].productPrice)) + " Tk</td></tr>"
                $("#cartItems").append(row);
                totalPrice += parseInt(cartItem[i].productQuantity) * parseInt(cartItem[i].productPrice);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/api/deliveryCharge') }}",
                type: "POST",
                data: {
                    city_id: '{{ $cityID }}',
                    total_amount: totalPrice
                },
                success: function(result) {
                    deliveryCharge = result['deliveryCharge'];
                    $("#subTotal").val(totalPrice);
                    $("#subTotalTd").html(totalPrice + " Tk");

                    $("#deliveryCharge").val(deliveryCharge);
                    $("#deliveryChargeTd").html(deliveryCharge + " Tk");

                    $("#totalAmount").val(totalPrice + parseInt(deliveryCharge));
                    $("#totalAmountTd").html((totalPrice + parseInt(deliveryCharge)) + " Tk");
                    preFinalPrice = parseInt(totalPrice) + parseInt(deliveryCharge);

                    applyDiscount();
                }
            });
            subTotal = totalPrice;
            totalAmount = preFinalPrice;
            $("#order_data").val(cartItem);
            Swal.close();
            $("#productList").modal('hide');
            $("#cartTable").removeAttr('hidden');
        }

        function removeFromCart(productID) {
            var matched = false;
            tempCart = cartItem;
            cartItem = [];
            index = 0;
            for (i = 0; i < tempCart.length; i++) {
                if (tempCart[i].productID != productID) {
                    var productMap = {};
                    productMap['productID'] = tempCart[i].productID;
                    productMap['productName'] = tempCart[i].productName;
                    productMap['productDescription'] = tempCart[i].productDescription;
                    productMap['productQuantity'] = tempCart[i].productQuantity;
                    productMap['productPrice'] = tempCart[i].productPrice;
                    productMap['productImage'] = tempCart[i].productImage;
                    cartItem[index] = productMap;
                    index++;
                }
            }

            generateInvoice();
        }

        function applyDiscount() {
            discount = '{{ $discount }}';
            $("#discount").val(discount);
            $("#totalAmount").val((parseInt(preFinalPrice) - parseInt(discount)));
            $("#totalAmountTd").html((parseInt(preFinalPrice) - parseInt(discount)) + " Tk");
            totalAmount = parseInt(preFinalPrice) - parseInt(discount);
        }

        function updateTotal() {
            discount = $("#discount").val();
            $("#totalAmount").val((parseInt(preFinalPrice) - parseInt(discount)));
            $("#totalAmountTd").html((parseInt(preFinalPrice) - parseInt(discount)) + " Tk");
            totalAmount = parseInt(preFinalPrice) - parseInt(discount);
        }

        function updateOrder() {
            customerName = $("#customer_name").val();
            customerMobile = $("#customer_mobile").val();
            customerAddress = $("#delivery_address").val();
            customerDivision = $("#division").val();
            customerNote = $("#delivery_note").val();
            scheduledDate = $("#scheduled_date").val();

            if (customerName == "" || customerMobile == "" || customerAddress == "" || customerDivision == "" || customerDivision == "Select Division") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                })
            } else {
                Swal.fire({
                    title: 'Updating Order',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                })

                var itemData = '[';
                for (i = 0; i < cartItem.length; i++) {
                    if (i == 0) {
                        itemData += '{"id":"' + cartItem[i].productID + '","b":"' + cartItem[i].productName + '","c":"' + cartItem[i].productPrice;
                        itemData += '","d":"' + cartItem[i].productQuantity + '","e":"' + cartItem[i].productDescription + '","f":"' + cartItem[i].productImage + '"}';
                    } else {
                        itemData += ',{"id":"' + cartItem[i].productID + '","b":"' + cartItem[i].productName + '","c":"' + cartItem[i].productPrice;
                        itemData += '","d":"' + cartItem[i].productQuantity + '","e":"' + cartItem[i].productDescription + '","f":"' + cartItem[i].productImage + '"}';
                    }
                }
                itemData += ']';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('/restaurant/order/updateOrder') }}",
                    type: "POST",
                    data: {
                        order_id: '{{ Request::get("id") }}',
                        city_id: '{{ $cityID }}',
                        division: customerDivision,
                        customer_name: customerName,
                        customer_mobile: customerMobile,
                        delivery_address: customerAddress,
                        delivery_note: customerNote,
                        order_data: itemData,
                        discount: discount,
                        total_amount: totalAmount,
                        scheduled_date: scheduledDate
                    },
                    success: function(result) {
                        if (result['error']) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: result['message']
                            })
                        } else {
                            Swal.fire({
                                title: 'Success',
                                text: result['message'],
                                icon: 'success',
                                showCancelButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonColor: '#4CAF50',
                                confirmButtonText: 'Okay'
                            }).then((result) => {
                                window.location.href = '{{ url("/restaurant/order?city=" . $cityID) }}';
                            })
                        }
                    }
                });
            }
        }
    </script>
</body>
@endsection