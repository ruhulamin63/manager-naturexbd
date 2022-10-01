<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ date('d-M-Y', strtotime($from_date)) }} - {{ date('d-M-Y', strtotime($to_date)) }}</title>
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
            border-bottom: none;
        }

        .invoice-box table tr.total {
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

        .tr-border {
            border: 1px solid #000;
        }
    </style>
</head>
<!-- onload="window.print()" -->

<body onload="window.print()">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('/images/logo/invoice_logo.png') }}" style="width:100%; max-width:300px;">
                                <br>
                                <div class="text-center" style="max-width: 300px; margin-top: 15px">
                                    <h4>Vendor Invoice</h4>
                                </div>
                            </td>

                            <td>
                                <div class="row">
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-9">
                                        <table class="table table-bordered" style="width: 100%">
                                            <tr>
                                                <th class="same-padding" style="border: 1px solid #000"><b>City</b></th>
                                                <td class="same-padding text-right" style="border: 1px solid #000">{{ $city }}</td>
                                            </tr>
                                            <tr>
                                                <th class="same-padding" style="border: 1px solid #000"><b>From Date</b></th>
                                                <td class="same-padding text-right" style="border: 1px solid #000">{{ date('d-M-Y', strtotime($from_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="same-padding" style="border: 1px solid #000"><b>To Date</b></th>
                                                <td class="same-padding text-right" style="border: 1px solid #000">{{ date('d-M-Y', strtotime($to_date)) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td style="width: 5%">
                    SN
                </td>

                <td style="width: 40%">
                    Customer Info
                </td>

                <td>
                    Item Details
                </td>

                <td class="text-center">
                    Quantity
                </td>

                <td class="text-center" style="width: 10%">
                    Remarks
                </td>
            </tr>

            @foreach($details as $key => $item)
            @php
            $showSN = true
            @endphp
            @foreach($item['product_info'] as $sn => $product)
            <tr class="item">
                @if($showSN)
                <td style="width: 5%" rowspan="{{ $item['product_count'] }}">
                    {{ $key+1 }}
                </td>

                <td style="width: 40%" rowspan="{{ $item['product_count'] }}">
                    <b>Order ID: </b>#{{ $item['orderID'] }}<br>
                    <b>Customer Name: </b>{{ $item['customerName'] }}<br>
                    <b>Order Time: </b> {{ date('d-M-Y h:i:s A', strtotime($item['orderTime'])) }}
                </td>
                @endif

                <td>
                    {{ $product['productName'] }}<br>
                    <small>{{ $product['productDescription'] }}</small>
                </td>

                <td class="text-center">
                    {{ $product['productQuantity'] }}
                </td>

                <td></td>
            </tr>
            @if((count($item['product_info'])-1) == $sn)
            @php
            $showSN = true
            @endphp
            @else
            @php
            $showSN = false
            @endphp
            @endif
            @endforeach
            @endforeach

        </table>
        <div class="footnote">
            <b>Note:</b> Invoice was created on a computer and is valid without the signature and seal.<br><br>
            <b>Customer Support:</b> +8801791865233<br>
            <b>Website:</b> www.khaidaitoday.com<br>
            <b>Our App:</b> khaidaitoday.com/play
            <br><br><br><br><br>
            <div class="text-center">
                <small>Printed on: {{ date('d-M-Y h:i:s A') }} | Printed by: {{ session()->get('GR_MANAGER_NAME') }}</small>
                <hr>
                <b>Thanks for being with Khaidai Today</b>
            </div>
        </div>
    </div>
</body>

</html>