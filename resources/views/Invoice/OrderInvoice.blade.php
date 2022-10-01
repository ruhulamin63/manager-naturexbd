<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order#{{ $details[0]->order_id }} - Khaidai Today</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .invoice-box {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
            margin-top: 30px;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            /* padding-top: 40px; */
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border: 1px solid #000;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border: 1px solid #000;
        }

        .invoice-box table tr.item.last td {
            border: none;
        }

        .invoice-box table tr.total td {
            border: 1px solid #000;
            /* font-weight: bold; */
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        .same-padding {
            padding: 10px !important;
        }

        .footnote {
            padding-top: 60px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('/images/logo/invoice_logo.png') }}" style="width:100%; max-width:300px;">
                            </td>

                            <td>
                                <div class="row">
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-9">
                                        <table class="table table-bordered" style="width: 100%;">
                                            <tr>
                                                <th class="same-padding" style="border: 1px solid #000"><b>Order ID</b></th>
                                                <td class="same-padding text-right" style="border: 1px solid #000">#{{ $details[0]->order_id }}</td>
                                            </tr>
                                            <tr>
                                                <th class="same-padding" style="border: 1px solid #000"><b>Ordered On</b></th>
                                                <td class="same-padding text-right" style="border: 1px solid #000">{{ date('d-M-Y h:i:s A', strtotime($details[0]->created_at)) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="5">
                    <table>
                        <tr>
                            <td style="width: 70%">
                                <b>Customer Name:  </b> {{ $details[0]->customer_name }}<br>
                                <b>Contact Number:  </b> {{ $details[0]->contact_number }}<br>
                                <b>Delivery Address:  </b> {{ $details[0]->delivery_address }}<br>
                                <b>Delivery Note:  </b> {{ $details[0]->delivery_note }}<br>
                            </td>

                            <td style="text-align: right; width: 30%">
                                <b>Payment Method</b><br>
                                Cash On Delivery
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td style="width: 5%">
                    SN
                </td>

                <td style="width: 50%">
                    Item Details
                </td>

                <td class="text-center" style="width: 15%">
                    Quantity
                </td>

                <td class="text-center" style="width: 15%">
                    Unit Price
                </td>

                <td style="width: 15%; text-align: right">
                    Total Price
                </td>
            </tr>
            @if(substr($orderId,3,1)=='W')
                @foreach(json_decode($details[0]->order_data, true) as $key => $item)
                <tr class="item">
                    <td style="width: 5%">
                        {{ $key+1 }}
                    </td>

                    <td style="width: 50%">
                        {{ $item["product_name"] }}<br>
                        <small></small>
                    </td>

                    <td class="text-center" style="width: 15%">
                        {{ $item["quantity"] }}
                    </td>

                    <td class="text-center" style="width: 15%">
                        {{ $item["unit_price"] }} Tk
                    </td>

                    <td style="width: 15%; text-align: right">
                        {{ $item["total_price"] }} Tk
                    </td>
                </tr>
                @endforeach
            @else
                @foreach(json_decode($details[0]->order_data, true) as $key => $item)
                <tr class="item">
                    <td style="width: 5%">
                        {{ $key+1 }}
                    </td>

                    <td style="width: 50%">
                        {{ $item["b"] }}<br>
                        <small>{{ $item["e"] }}</small>
                    </td>

                    <td class="text-center" style="width: 15%">
                        {{ $item["d"] }}
                    </td>

                    <td class="text-center" style="width: 15%">
                        {{ $item["c"] }} Tk
                    </td>

                    <td style="width: 15%; text-align: right">
                        {{ $item["c"] * $item["d"] }} Tk
                    </td>
                </tr>
                @endforeach
            @endif
            <tr class="total">
                <td colspan="4" style="text-align: right">
                    <b>Subtotal</b>
                </td>

                <td style="text-align: right">
                    {{ $subtotal }} Tk
                </td>
            </tr>
            <tr class="total">
                <td colspan="4" style="text-align: right">
                    <b>Delivery Charge</b>
                </td>

                <td style="text-align: right">
                    {{ $deliveryCharge }} Tk
                </td>
            </tr>
            <tr class="total">
                <td colspan="4" style="text-align: right">
                    <b>Discount</b>
                </td>

                <td style="text-align: right">
                    (-) {{ $discount }} Tk
                </td>
            </tr>
            <tr class="total">
                <td colspan="4" style="text-align: right">
                    <b>Total Payable</b>
                </td>

                <td style="text-align: right">
                    {{ $total }} Tk
                </td>
            </tr>
        </table>
        <div class="footnote">
            <b>Note:</b> Invoice was created on a computer and is valid without the signature and seal.<br><br>
            <b>Customer Support:</b> +8801791865233<br>
            <b>Website:</b> www.khaidaitoday.com<br>
            <b>Our App:</b> khaidaitoday.com/play
            <br><br>
            <div class="text-center">
                <small>Printed on: {{ date('d-M-Y h:i:s A') }} | Printed by: {{ session()->get('GR_MANAGER_NAME') }}</small>
                <hr>
                <b>Thanks for being with Khaidai Today</b>
                <br>
            </div>
        </div>
        
            <div class="container" style="margin-top: 10px;">
                <div class="row mx-auto">
                @foreach($invoicePicture as $picture)
                    <div class="col-6 col-sm-6 col-md-4">
                        @if($picture->image)
                        <?php if (file_exists("../public".$picture->image)){ ?>
                            <img style="height: 200px;object-fit: contain;" src="{{asset(''.$picture->image)}}" alt="">
                        <?php } else{ ?>
                            <img style="height: 200px;object-fit: contain;" src="{{asset(''.$picture->image)}}" alt="">
                        <?php } ?>
                        @else
                        <img style="height: 200px;object-fit: contain;" src="{{asset(''.$picture->image)}}" alt="">
                        @endif
                        <br>
                        <small>{{$picture->details}}</small>
                    </div>
                @endforeach
                </div>
            </div>
    </div>
</body>

</html>