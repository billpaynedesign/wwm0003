<html>
  <head>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/invoice.css') }}">
  </head>
  <body>
    <table id="header">
      <tr>
        <td><img src="{{ asset('/images/logo.png') }}" class="logo" alt="logo"></td>
        <td><strong>Order<br>Acknowledgement</strong></td>
        <td><strong>Invoice #</strong><br>{{ $order->invoice_num }}</td>
        <td><strong>Date</strong><br>{{ $order->created_at->format('m-d-Y') }}</td>
        @if($order->transaction->purchase_order_num)
          <td><strong>Purchase Order #</strong><br>{{ $order->transaction->purchase_order_num }}</td>
        @endif
        @if($order->transaction->transaction_id)
          <td><strong>Transaction ID</strong><br>{{ $order->transaction->transaction_id }}</td>
        @endif
      </tr>
      <tr>
        <td><strong>Name:</strong> {{ $order->user->company }}</td>
        <td><strong>Email:</strong> {{ $order->user->email }}</td>
      </tr>
    </table>
    <table id="address">
      <tr>
        <td colspan="2">Billing Address:</td>
        <td colspan="2">Shipping Address:</td>
      </tr>
      <tr>
          <td>Name:</td>
          <td>{{ $order->transaction->name }}</td>
          <td>Name:</td>
          <td>{{ $order->shippingname }}</td>
      </tr>
      <tr>
          <td>Location:</td>
          <td>{{ $order->transaction->address1.($order->transaction->address2?' '.$order->transaction->address2:'') }}</td>
          <td>Location:</td>
          <td>{{ $order->address1.($order->address2?' '.$order->address2:'') }}</td>
      </tr>
      <tr>
          <td>City,State:</td>
          <td>{{ $order->transaction->city }},{{ $order->transaction->state }}</td>
          <td>City,State:</td>
          <td>{{ $order->city }},{{ $order->state }}</td>
      </tr>
      <tr>
          <td>Country:</td>
          <td>United States</td>
          <td>Country:</td>
          <td>United States</td>
      </tr>
      <tr>
          <td>Zipcode:</td>
          <td>{{ $order->transaction->zip }}</td>
          <td>Zipcode:</td>
          <td>{{ $order->zip }}</td>
      </tr>
    </table>
      <table id="product_table" width="100%" cellpadding="0" border="1" cellspacing="0">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Product Detail</th>
            <th>LotNum</th>
            <th>Expiry Date</th>
            <!-- <th>Option</th> -->
            <th>Qty</th>
            <th>Backordered</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
        <?php $total = 0; ?>
        @foreach($details as $detail)
        <tr>
          <td>{{ $detail->product->name }}</td>
          <td>{{ $detail->product->item_number }} {{ $detail->product->options()->select('option')->get()->implode('option',',') }}</td>
          <td style="text-align:center;">{{ $detail->lot_number }}</td>
          <td style="text-align:center;">{{ $detail->expiration }}</td>
          <!-- <td></td> -->
          <td style="text-align:center;">{{ $detail->quantity }}</td>
          <td style="text-align:center;">{{ $detail->backordered }}</td>
          <td style="text-align:center;">{{ $detail->product->price_string }}</td>
          <td style="text-align:center;">${{ \number_format($detail->subtotal,2) }}</td>
        </tr>
        <?php $total += floatval($detail->product->price)*intval($detail->quantity); ?>
        @endforeach
        <tr>
          <td colspan="7"></td>
          <th align="right"> State Tax + </th>
          <td style="text-align:center;">${{ \number_format($total*0.0888,2) }}</td>
        </tr>
        <tr>
          <td colspan="7"></td>
          <th bgcolor="#CCC" align="right">Total</th>
          <td style="text-align:center;">${{ \number_format(($total*0.0888)+$total,2) }}</td>
        </tr>
      </tbody>
    </table>
    <p class="footer">bw&#64;wwmdusa.com   |   914-358-9878</p>
  </body>
</html>