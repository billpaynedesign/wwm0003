<html>

<head>
    <title>Quote {{ $quote->quote_num }}</title>
    <style>
        .clearfix {
            clear: both;
        }
        .row {
            position: relative;
            width: 100%;
            float: left;
        }
        .pull-right {
            float: right !important;
        }
        .pull-left {
            float: left !important;
        }
        .col-half {
            position: relative;
            width: 50%;
            float: left;
        }
        .logo {
            width: 150px;
            height: auto;
            float: left;
        }
        h1{
            margin: 0;
        }
        h3{
            margin: 5px 0 0 0;
        }
        p {
            font-size: 14px;
            font-weight: normal;
            margin-left: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        #product_table th,
        #product_table td {
            padding: 4px 2px;
        }

        #product_table p {
            font-size: 12px;
            margin: 0;
        }
        .back-button{
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            text-decoration: none;
            margin-bottom: 15px;
        }
        .img-responsive{
            width: 100%;
        }
        td .img-responsive{
            max-width: 150px;
            max-height: 150px;
        }
        @media print{
            .print-hide{
                display: none;
            }
        }
    </style>
</head>

@if(isset($_GET['noprint']))
<body>
@else
<body onload="window.print();">
    <div class="row print-hide">
        <a href="{{ route('admin-quotes') }}" class="back-button">
            Back
        </a>
    </div>
@endif
    <div class="row">
        <img src="{{ asset('/images/logo.png') }}" class="logo pull-left" alt="logo">
        <h1 class="pull-right">Quote</h1>
    </div>
    <div class="row">
        <div class="pull-left">
            <h3>World Wide Medical Distributors</h3>
        </div>
        <div class="pull-right">
            <p class="text-right"><strong>Quote #: </strong>{{ $quote->quote_num }}</p>
            <p class="text-right"><strong>RFQ #: </strong>{{ $quote->rfq_num }}</p>
        </div>
    </div>
    <div class="row">
        <table style="margin-bottom: 5px; text-align: left;">
            <tr>
                <th>
                    Bill To:
                </th>
                <td>{{ $quote->full_billing_address }}</td>
            </tr>
            <tr>
                <th>
                    Ship To:
                </th>
                <td>{{ $quote->full_shipping_address }}</td>
            </tr>
        </table>
    </div>
    <div class="row">
        <table id="product_table" width="100%" cellpadding="0" border="1" cellspacing="0" style="margin-top:15px;padding:0px 0px 0px 0px;border:1px solid #CCC;font-size:10px;">
            <thead>
                <tr>
                    <th></th>
                    <th>Product Name</th>
                    <th>Item Number</th>
                    <th>Option</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Item Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->products as $detail)
                    <tr>
                        <td>
                            @if($detail->product->picture)
                                  <img src="{{ asset('pictures/'.$detail->product->picture) }}" class="img-responsive">
                            @endif
                        </td>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->product->item_number }}</td>
                        <td>{{ $detail->uom->name }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">${{ number_format($detail->price,2) }}</td>
                        <td class="text-right">${{ number_format($detail->item_total,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <th class="text-right" style="padding-right:8px;">Total</th>
                    <td class="text-right">${{ number_format($quote->total,2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-half">
            <p>Thank You</p>
        </div>
    </div>
    <div class="row text-center bold" style="margin-top: 15px;">
        <p>brent&#64;wwmdusa.com | 914.358.9879</p>
    </div>
    </body>

</html>
