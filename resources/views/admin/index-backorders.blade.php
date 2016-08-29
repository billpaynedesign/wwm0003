<div class="form-group">
  <a href="{{ route('order-print-backordered') }}" target="_blank" class="btn btn-info" >Print Backorder&nbsp;&nbsp;<span class="glyphicon glyphicon-print"></span></a>
</div>
<div class="table-responsive text-left">
  <table id="backorders_table" class="table table-striped table-hover">
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
      @foreach($backorders as $order)
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
            <td>{{ $detail->product->price_string }}</td>
            <td>{{ $detail->backordered }}</td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>
</div>
