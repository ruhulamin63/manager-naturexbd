<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrom=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon2.png') }}">
    <title>Payment Gateway | Khaidai Today</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/nice-select.css') }}">
    <style>
        html,
        body {
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            /* Location of the image */
            background-image: url('{{ asset("images/pages/PGWbgb.jpg") }}');

            /* Background image is centered vertically and horizontally at all times */
            background-position: center center;

            /* Background image doesn't tile */
            background-repeat: no-repeat;

            /* Background image is fixed in the viewport so that it doesn't move when the content's height is greater than the image's height */
            background-attachment: fixed;

            /* This is what makes the background image rescale based on the container's size */
            background-size: cover;

            /* Set a background color that will be displayed while the background image is loading */
            background-color: #ffffff;
        }

        .full-height {
            height: 100vh;
            /* position: fixed;
            background: url('{{ asset("images/pages/PGWbgb.jpg") }}') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover; */
        }

        .card {
            border: none !important;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            margin-bottom: 80px;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            padding-top: 60px;
            position: relative;
        }

        .content {
            text-align: center;
        }

        .table td {
            border-top: none;
            border-bottom: 1px solid #dee2e6;
        }

        .bt-hover {
            margin-top: 10px;
            border-radius: 20px;
        }

        .bt-hover:hover {
            background-color: #dee2e6;
        }

        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (max-width: 1200px) {
            .table {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <div class="position-ref full-height">
        <div class="container" style="background: none !important;">
            <div class="col-12 col-md-8 col-lg-6 offset-0 offset-lg-3 offset-md-2">
                <div class="card" id="info-sec" style="border-radius: 20px; padding: 15px">
                    @if(!$invalid && !$complete)
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <img src="{{ asset('images/logo/logo2.png') }}" width="240px" />
                            <br><br>
                        </h5>
                        <div class="form-group" id="payMethod" hidden>
                            <div class="text-center mb-3">Select your preferred payment method</div>
                            <select class="nice-select wide" onchange="payMethodSelected(this.value)">
                                <option data-display="Select Payment Method" selected disabled>Select Payment Method</option>
                                <option value="bKash">bKash</option>
                                <!-- <option value="Roket/Surecash">Roket/Surecash (Fee: 2.1%)</option>
                                <option value="DBBL Nexus/Visa/Master Card">DBBL Nexus/Visa/Master Card (Fee: 3.25%)</option> -->
                            </select>
                        </div>
                        <div class="text-center" id="window-warning" hidden>
                            <img src="{{ asset('images/loading.gif') }}" width="80%" />
                            <h4>Please do not close the browser window</h4>
                        </div>
                        <table class="table" id="dataTable" style="margin-top: 80px !important;" hidden>
                            <tbody>
                                <tr>
                                    <td>
                                        <span style="font-weight: 700;">Name</span>
                                    </td>
                                    <td class="text-right">{{ ucwords($name) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: 700;">Order ID</span>
                                    </td>
                                    <td class="text-right">{{ ucwords($order_id) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: 700;">Invoice Amount</span>
                                    </td>
                                    <td class="text-right">BDT {{ $total }}</td>
                                </tr>
                                <!-- <tr>
                                    <td>
                                        <span style="font-weight: 700;" id="gateway_name"></span>
                                    </td>
                                    <td class="text-right" id="gateway_fee"></td>
                                </tr> -->
                                <tr>
                                    <td>
                                        <span style="font-weight: 700;">Total Amount</span>
                                    </td>
                                    <td class="text-right" id="total_amount">BDT 0</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <img id="loading" src="{{ asset('images/loading.gif') }}" width="80%" />
                        </div>
                        <button type="button" class="btn bt-hover text-center" id="bKash_button" style="width: 100%;" hidden>
                            <img src="{{ asset('images/logo/bKash-logo.png') }}" width="40%" />
                            <br>
                            Tap here to continue
                        </button>
                    </div>
                    @elseif($invalid)
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <img src="{{ asset('images/logo/logo2.png') }}" width="240px" />
                            <br><br>
                            <font color="red">
                                <small><b>Sorry! Invalid information provided<br>Please try again</b></small>
                            </font>
                            <br><br>
                        </h5>
                    </div>
                    @elseif($complete)
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <img src="{{ asset('images/logo/logo2.png') }}" width="240px" />
                            <br><br>
                            <font color="#333333">
                                <small>Your payment has been completed successfully.<br><br><b>Thanks for being with Khaidai Today</b></small>
                            </font>
                            <br><br>
                            <a class="btn btn-success" href="https://www.khaidaitoday.com" target="_blank">
                                Explore Khaidai Today
                            </a>
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('/js/jquery.nice-select.js') }}"></script>
    <script>
        var paymentID = '';
        var invoiceTotal = parseFloat('{{ $total }}');
        var paymentTimeoutMonitor;
        var totalBill = 0.0;

        $(document).ready(function() {
            $('select').niceSelect();
        });

        function payMethodSelected(method) {
            $("#loading").removeAttr('hidden');
            if (method == "bKash") {
                // var gateway_fee = invoiceTotal * 0.015;
                // gateway_fee = parseFloat(Math.round((gateway_fee + Number.EPSILON) * 100) / 100);
                // $("#gateway_name").html("bKash Charge (1.5%)");
                // $("#gateway_fee").html("BDT " + gateway_fee);
                totalBill = invoiceTotal.toFixed(2);
                $("#total_amount").html("BDT " + totalBill);
                $("#dataTable").removeAttr('hidden');
                init_bkash(parseFloat(totalBill));
            } else if (method == "Roket/Surecash") {
                var gateway_fee = invoiceTotal * 0.021;
                gateway_fee = parseFloat(gateway_fee.toFixed(2));
                $("#gateway_name").html("Gateway Charge (2.1%)");
                $("#gateway_fee").html("BDT " + gateway_fee);
                totalBill = invoiceTotal + gateway_fee;
                $("#total_amount").html("BDT " + totalBill);
                $("#dataTable").removeAttr('hidden');
                $("#loading").attr('hidden', true);
            } else if (method == "DBBL Nexus/Visa/Master Card") {
                var gateway_fee = invoiceTotal * 0.0325;
                gateway_fee = parseFloat(gateway_fee.toFixed(2));
                $("#gateway_name").html("Gateway Charge (3.25%)");
                $("#gateway_fee").html("BDT " + gateway_fee);
                totalBill = invoiceTotal + gateway_fee;
                $("#total_amount").html("BDT " + totalBill);
                $("#dataTable").removeAttr('hidden');
                $("#loading").attr('hidden', true);
            } else {
                Swal.fire({
                    title: 'Payment Failed',
                    text: 'Invalid payment method.',
                    icon: 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    }
                })
            }
        }

        function init_bkash(total_bill) {
            var invoice_id = '{{ $invoice_id }}';

            bKash.init({
                paymentMode: 'checkout',
                paymentRequest: {
                    amount: total_bill,
                    intent: 'sale'
                },
                createRequest: function(request) {
                    $.ajax({
                        beforeSend: toggleVisibility,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ url("/payment/bkash/checkout/createPayment") }}',
                        type: 'POST',
                        data: {
                            amount: total_bill,
                            invoice: invoice_id
                        },
                        success: function(data) {
                            // clearTimeout(paymentTimeoutMonitor);
                            // paymentTimeoutMonitor = setTimeout(createPaymentTimeout, 60000);
                            data = JSON.parse(data['data']);
                            if (data && data.paymentID != null) {
                                paymentID = data.paymentID;
                                bKash.create().onSuccess(data);
                            } else {
                                errorCode = data.errorCode;
                                errorMessgae = data.errorMessage;
                                bKash.create().onError();
                                Swal.fire({
                                    title: 'Payment Failed',
                                    text: errorMessgae,
                                    icon: 'error',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                }).then((result) => {
                                    if (result.value) {
                                        location.reload();
                                    }
                                })
                            }
                        },
                        error: function() {
                            bKash.create().onError();
                            Swal.fire({
                                title: 'Payment Failed',
                                text: 'Something went wrong!',
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                    });
                },
                executeRequestOnAuthorization: function() {
                    $.ajax({
                        beforeSend: executePaymentTimeoutCall,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ url("/payment/bkash/checkout/executePayment") }}',
                        type: 'POST',
                        data: {
                            paymentID: paymentID
                        },
                        success: function(data) {
                            clearTimeout(paymentTimeoutMonitor);
                            data = JSON.parse(data);
                            if (data && data.paymentID != null) {
                                location.reload();
                            } else {
                                errorCode = data.errorCode;
                                errorMessgae = data.errorMessage;
                                bKash.create().onError();
                                Swal.fire({
                                    title: 'Payment Failed',
                                    text: errorMessgae,
                                    icon: 'error',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                }).then((result) => {
                                    if (result.value) {
                                        location.reload();
                                    }
                                })
                            }
                        },
                        error: function() {
                            bKash.create().onError();
                            Swal.fire({
                                title: 'Payment Failed',
                                text: 'Something went wrong!',
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                    });
                },
                onClose: function() {
                    Swal.fire({
                        title: 'Payment Failed',
                        text: 'Payment cancelled by user!',
                        icon: 'warning',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    })
                }
            });

            $("#loading").attr('hidden', true);
            $('#bKash_button').removeAttr('hidden');
        }

        function createPaymentTimeout() {
            $("#bKashFrameWrapper").attr('hidden', true);
            Swal.fire({
                title: 'Payment Failed',
                text: 'Your payment has been timed out! Please try again.',
                icon: 'error',
                confirmButtonText: 'Try again',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                if (result.value) {
                    location.reload();
                }
            })
        }

        function paymentFailed() {
            $("#bKashFrameWrapper").attr('hidden', true);
            Swal.fire({
                title: 'Payment Failed',
                text: 'Your payment might have been unsuccessful! Please try again.',
                icon: 'error',
                confirmButtonText: 'Try again',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                if (result.value) {
                    location.reload();
                }
            })
        }

        function executePaymentTimeout() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url("/payment/bkash/trx/query") }}',
                type: 'POST',
                data: {
                    paymentID: paymentID
                },
                success: function(data) {
                    clearTimeout(paymentTimeoutMonitor);
                    data = JSON.parse(data);
                    if (data.transactionStatus == "Completed") {
                        location.reload();
                    } else {
                        paymentFailed();
                    }
                }
            });
        }

        function callReconfigure(val) {
            bKash.reconfigure(val);
        }

        function clickPayButton() {
            $("#bKash_button").trigger('click');
        }

        var toggleVisibility = function() {
            $("#payMethod").attr('hidden', true);
            $("#dataTable").attr("hidden", true);
            $("#bKash_button").attr("disabled", true);
            $("#window-warning").removeAttr("hidden");
            // paymentTimeoutMonitor = setTimeout(createPaymentTimeout, 30000);
            // $("#info-sec").attr("hidden", true);
        }

        var executePaymentTimeoutCall = function() {
            clearTimeout(paymentTimeoutMonitor);
            paymentTimeoutMonitor = setTimeout(executePaymentTimeout, 30000);
        }

        $(window).on('load', function() {
            setTimeout(function() {
                $("#loading").attr('hidden', true);
                $("#payMethod").removeAttr("hidden");
            }, 500);
        });
    </script>
</body>

</html>