<html>
<head>
  <style type="text/css">
    table{
      text-align: left;
      border: 1px solid #000;
      width: 100%;
    }
    table>tbody>tr>td,
    table>tbody>tr>th,
    table>tfoot>tr>td,
    table>tfoot>tr>th,
    table>thead>tr>td,
    table>thead>tr>th{
      padding: 4px 8px;
      border: 1px solid #000;
    }
  </style>
</head>
<body onload="window.print();">
  <h1>Back Ordered</h1>
  <table>
    <thead>
      <tr>
        <th>Invoice ID</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Item</th>
        <th>Manufacturer</th>
        <th>Item Number</th>
        <th>Lot Number</th>
        <th>Expiry Date</th>
        <th>Price</th>
        <th>Backordered</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
        @foreach($order->details()->where('backordered','>',0)->get() as $detail)
          <tr>
            <td>{{ $order->invoice_num }}</td>
            <td>{{ $order->shippingname }}</td>
            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->orderDate)->format('m-d-Y') }}</td>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->product->manufacturer }}</td>
            <td>{{ $detail->product->item_number }}</td>
            <td>{{ $detail->lot_number }}</td>
            <td>{{ $detail->expirations }}</td>
            <td>
              @if($order->user->product_price_check($detail->product->id))
                {{ $order->user->product_price_check($detail->product->id)->price_string }}
              @else
                {{ $detail->product->min_price_string }}
              @endif
            </td>
            <td>{{ $detail->backordered }}</td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>
</body>
</html>

