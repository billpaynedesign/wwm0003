<!--
<div class="form-group">
  <a href="{{ route('order-print-backordered') }}" target="_blank" class="btn btn-info" >Print Backorder&nbsp;&nbsp;<span class="glyphicon glyphicon-print"></span></a>
</div>
-->
<div class="table-responsive text-left">
  <table id="orders_table" class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Order Date</th>
        <th>Invoice ID</th>
        <th>Ship Status</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone #</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if($orders)
      @foreach($orders as $order)
      <tr id="{{ $order->id }}">
        <td>{{ $order->created_at->format('m-d-Y H:i:s') }}</td>
        <td>{{ $order->invoice_num }}</td>
        <td>{{ $order->shipstatus }}</td>
        <td>{{ $order->shippingname }}</td>
        <td>{{ $order->address1.' '.$order->address2.' '.$order->city.', '.$order->state.' '.$order->zip }}</td>
        <td>{{ $order->phone.' '.($order->secondary_phone?'or '.$order->secondary_phone:'') }}</td>
        <td>
          <button class="btn btn-info" data-toggle="modal" data-target="#order-info" title="Order Information" onclick="order_information('{{ $order->id }}')">
            <span class="fa fa-info"></span>
          </button>
          <button class="btn btn-success" title="Edit Status for #{{ $order->id }}" data-toggle="modal" data-target="#order-status" onclick="order_status('{{ $order->id }}')"> 
            <span class="fa fa-truck" aria-hidden="true"></span>
          </button>
          <a href="{{ route('order-edit',$order->id) }}" class="btn btn-warning" title="Edit Shipping/Items for #{{ $order->id }}">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
          </a>
          <a href="{{ route('order-delete',$order->id) }}" class="btn btn-danger" title="Remove #{{ $order->id }}" onclick="return confirm('Are you sure you want to remove order: #{{ $order->id }}');">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
          </a>
          <button class="btn btn-default html-popover" data-toggle="popover" data-container="body" data-placement="left" data-trigger="click" data-content="{!! $order->invoice_html_list !!}"><span class="fa fa-files-o"></span></button>
        </td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>
