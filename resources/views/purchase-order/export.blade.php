<html>

<head>
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
        <a href="javascript:window.history.back()" class="back-button">
            Back
        </a>
    </div>
@endif
    <div class="row">
        <img src="{{ asset('/images/logo.png') }}" class="logo pull-left" alt="logo">
        <h1 class="pull-right">Purchase Order</h1>
    </div>
    <div class="row">
        <div class="pull-left">
            <h3>World Wide Medical Distributors</h3>
        </div>
        <div class="pull-right">
            <p class="text-right"><strong>Purchase Order #: </strong>{{ $purchase_order->invoice_num }}</p>
            <p class="text-right"><strong>Date: </strong>{{ $purchase_order->date->format('m-d-Y') }}</p>
        </div>
    </div>
    <div class="row">
        <table style="margin-bottom: 5px;">
            <tr>
                <td>180 S Broadway Ste 205A, White Plains, NY 10605</td>
            </tr>
            <tr>
                <td>Telephone: 914.358.9879</td>
            </tr>
            <tr>
                <td>Fax: 914.358.9880</td>
            </tr>
            <tr>
                <td>brent&#64;wwmdusa.com</td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col-half">
            <table>
                <tr>
                    <th colspan="2" class="text-left">Vendor</th>
                </tr>
                <tr>
                    <td>{{ $purchase_order->vendor->name }}</td>
                </tr>
                <tr>
                    <td>{{ $purchase_order->vendor->full_address }}</td>
                </tr>
                <tr>
                    <td>{{ $purchase_order->vendor->phone }}</td>
                </tr>
                <tr>
                    <td>{{ $purchase_order->vendor->email }}</td>
                </tr>
            </table>
        </div>
        <div class="col-half">
            <table>
                <tr>
                    <th colspan="2" class="text-left">Ship To</th>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>World Wide Medical Distributors</td>
                </tr>
                <tr>
                    <td>180 S Broadway Ste 205A</td>
                </tr>
                <tr>
                    <td>White Plains, NY 10605</td>
                </tr>
                <tr>
                    <td>914.358.9879</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <table id="product_table" width="100%" cellpadding="0" border="1" cellspacing="0" style="margin-top:15px;padding:0px 0px 0px 0px;border:1px solid #CCC;font-size:10px;">
            <thead>
                <tr>
                    <th>Reorder #</th>
                    <th>Product Name</th>
                    <th>Item Number</th>
                    <th>Option</th>
                    <th>Note</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Item Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase_order->details as $detail)
                    <tr>
                        <td>{{ $detail->reorder_number }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->product->item_number }}</td>
                        <td>{{ $detail->uom->name }}</td>
                        <td>{{ $detail->note }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">${{ number_format($detail->cost,2) }}</td>
                        <td class="text-right">${{ number_format($detail->item_total,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6"></td>
                    <th class="text-right" style="padding-right:8px;">Total</th>
                    <td class="text-right">${{ number_format($purchase_order->total,2) }}</td>
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
