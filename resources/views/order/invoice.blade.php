<html>
  <head>
    <style>
    .clearfix{ clear: both; }
    .text-center{ text-align: center; }
    .row{ position: relative; width: 100%; float: left; }
    .pull-left{ float: left !important; }
    .col-half{ position: relative; width: 50%; float: left; }
    .bold *{ font-weight: bold !important; }
    .logo{
    width: 150px;
    height: auto;
    float: left;
    }
    p{
    font-size: 14px;
    font-weight: normal;
    margin-left: 10px;
    margin-top: 15px;
    }
    .col-half p{
    margin: 0 0 0 10px;
    }
    .col-half ul{
    list-style: none;
    margin: 0 0 0 10px;
    padding: 0;
    }
    .col-half ul li{
    font-size: 14px;
    font-weight: normal;
    }
    .col-half ul li:nth-child(odd){
    width: 25%;
    float: left;
    }
    .col-half ul li:nth-child(even){
    width: 75%;
    float: left;
    }
    #product_table td{
    padding: 4px 0;
    }
    #product_table p{
    font-size: 10px;
    margin: 0;
    }
    .text-left{
      text-align: left;
    }
    </style>
  </head>
  <body @if(isset($_GET['print']))onload="window.print();" @endif>
    <div class="row">
      <img src="{{ asset('/images/logo.png') }}" class="logo pull-left" alt="logo">
      <p class="pull-left"><strong>Order<br>Acknowledgement</strong></p>
      <p style="margin-left: 30px;" class="text-center pull-left"><strong>Invoice #</strong><br>{{ $order->invoice_num }}</p>
      <p style="margin-left: 30px;" class="text-center pull-left"><strong>Date</strong><br>{{ $order->created_at->format('m-d-Y') }}</p>
      @if(!empty($order->transaction->purchase_order_num))
        <p style="margin-left: 30px;" class="text-center pull-left"><strong>Purchase Order #</strong><br>{{ $order->transaction->purchase_order_num }}</p>
      @endif
      @if(!empty($order->transaction->transaction_id))
        <p style="margin-left: 30px;" class="text-center pull-left"><strong>Transaction ID</strong><br>{{ $order->transaction->transaction_id }}</p>
      @endif
    </div>
    <div class="row">
      <p class="pull-left"><strong>Name:</strong> {{ $order->user->company }}</p>
      <p class="pull-left"><strong>Email:</strong> {{ $order->user->email }}</p>
    </div>
    <div class="row">
      <div class="col-half">
        <table>
          <tr>
            <th colspan="2" class="text-left">Billing Address:</th>
          </tr>
          <tr>
            <td>Name:</td>
            <td>{{ $order->transaction->name }}</td>
          </tr>
          <tr>
            <td>Location:</td>
            <td>{{ $order->transaction->address1.($order->transaction->address2?' '.$order->transaction->address2:'') }}</td>
          </tr>
          <tr>
            <td>City:</td>
            <td>{{ $order->transaction->city }}</td>
          </tr>
          <tr>
            <td>State:</td>
            <td>{{ $order->transaction->state }}</td>
          </tr>
          <tr>
            <td>Country:</td>
            <td>United States</td>
          </tr>
          <tr>
            <td>Zipcode:</td>
            <td>{{ $order->transaction->zip }}</td>
          </tr>
        </table>
      </div>
      <div class="col-half">
        <table>
          <tr>
            <th colspan="2" class="text-left">Shipping Address:</th>
          </tr>
          <tr>
            <td>Name:</td>
            <td>{{ $order->shippingname }}</td>
          </tr>
          <tr>
            <td>Location:</td>
            <td>{{ $order->address1.($order->address2?' '.$order->address2:'') }}</td>
          </tr>
          <tr>
            <td>City:</td>
            <td>{{ $order->city }}</td>
          </tr>
          <tr>
            <td>State:</td>
            <td>{{ $order->state }}</td>
          </tr>
          <tr>
            <td>Country:</td>
            <td>United States</td>
          </tr>
          <tr>
            <td>Zipcode:</td>
            <td>{{ $order->zip }}</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="row">
      <table id="product_table" width="100%" cellpadding="0" border="1" cellspacing="0" style="margin-top:15px;padding:0px 0px 0px 0px;border:1px solid #CCC;font-size:10px;">
        <tbody><tr>
          <th bgcolor="#CCC">Product Name</th>
          <th bgcolor="#CCC">Product Detail</th>
          <th bgcolor="#CCC">LotNum</th>
          <th bgcolor="#CCC">Expiry Date</th>
          <!-- <th bgcolor="#CCC">Option</th> -->
          <th bgcolor="#CCC">Qty</th> 
          <th bgcolor="#CCC">Size</th>
          <th bgcolor="#CCC">Backordered</th>
          <th bgcolor="#CCC">Price</th>
          <th bgcolor="#CCC">Total</th>
        </tr>
        @foreach($order->details as $detail)
        <tr>
          <td>{{ $detail->product->name }}</td>
          <td>{{ $detail->product->item_number }} {{ $detail->product->options()->select('option')->get()->implode('option',',') }}</td>
          <td style="text-align:center;">{{ $detail->lot_number }}</td>
          <td style="text-align:center;">{{ $detail->expiration }}</td>
          <td style="text-align:center;">{{ $detail->quantity }}</td>
          <td style="text-align:center;">{{ $detail->options }}</td>
          <td style="text-align:center;">{{ $detail->backordered }}</td>
          <td style="text-align:center;">
            @if($order->user->product_price_check($detail->product->id))
              {{ $order->user->product_price_check($detail->product->id)->price_string }}
            @else
              {{ $detail->product->min_price_string }}
            @endif
          </td>
          <td style="text-align:center;">${{ \number_format($detail->subtotal,2) }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="7"></td>
          <th bgcolor="#CCC" align="right" style="padding-right:8px;"> State Tax + </th>
          <td style="text-align:center;">${{ \number_format($order->tax,2) }}</td>
        </tr>
        <tr>
          <td colspan="7"></td>
          <th bgcolor="#CCC" align="right" style="padding-right:8px;">Total</th>
          <td style="text-align:center;">${{ \number_format($order->total_with_tax,2) }}</td>
        </tr>
      </tbody>
    </table>
    </div>
    <div class="row" style="margin-top: 15px;">
      <div class="col-half">
        <p>Thank You</p>
      </div>
    </div>
    <div class="row text-center bold" style="margin-top: 15px;">
      <p>brent&#64;wwmdusa.com   |   914-358-9878</p>
    </div>
  </body>
</html>