
@extends('app')

@section('title') Order Information :: @parent @stop

@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Order Information</h1>
    <div id="order_information_table" class="col-md-6 col-xs-12">
      <table>
        <tr><th colspan="2">Shipping Information</th></tr>
        <tr>
          <td>Name: </td>
          <td>{{ $order->shippingname }}</td>
        </tr>
        <tr>
          <td>Address: </td>
          <td>{{ $order->address1.' '.$order->address2 }}</td>
        </tr>
        <tr>
          <td>City: </td>
          <td>{{ $order->city }}</td>
        </tr>
        <tr>
          <td>State: </td>
          <td>{{ $order->state }}</td>
        </tr>
        <tr>
          <td>Zip: </td>
          <td>{{ $order->zip }}</td>
        </tr>
        @if($order->phone)
        <tr>
          <td>Phone: </td>
          <td>{{ $order->phone }}</td>
        </tr>
        @endif
        @if($order->secondary_phone)
        <tr>
          <td>Secondary Phone: </td>
          <td>{{ $order->secondary_phone }}</td>
        </tr>
        @endif
        <tr><th colspan="2">Billing Information</th></tr>
        <tr>
          <td>Name:  </td>
          <td>{{ $order->transaction->name }}</td>
        </tr>
        <tr>
          <td>Address:  </td>
          <td>{{ $order->transaction->address1.' '.$order->transaction->address2 }}</td>
        </tr>
        <tr>
          <td>City:  </td>
          <td>{{ $order->transaction->city }}</td>
        </tr>
        <tr>
          <td>State:  </td>
          <td>{{ $order->transaction->state }}</td>
        </tr>
        <tr>
          <td>Zip:  </td>
          <td>{{ $order->transaction->zip }}</td>
        </tr>
      </table>
    </div>
    <div class="col-md-6 col-xs-12">
      <table>
        <tr><th colspan="2">Order Status/Information</th></tr>
        <tr>
          <td>Invoice #:  </td>
          <td>{{ $order->invoice_num }}</td>
        </tr>
        @if(!empty($order->transaction->transaction_id))
        <tr>
          <td>Transaction ID:  </td>
          <td>{{ $order->transaction->transaction_id }}</td>
        </tr>
        @endif
        @if(!empty($order->transaction->purchase_order_num))
        <tr>
          <td>Purchase Order #:  </td>
          <td>{{ $order->transaction->purchase_order_num }}</td>
        </tr>
        @endif
        <tr>
          <td>Payment: </td>
          <td>{{ $order->transactionStatus?$order->transactionStatus:'Payment Pending' }}</td>
        </tr>
        <tr>
          <td>Ship Status: </td>
          <td>{{ $order->shipStatus }}</td>
        </tr>
      </table>
    </div>
    <div class="form-group clearfix"></div>
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Ship Status</th>
              <th>Quantity</th>
              <th>Options</th>
              @if(Auth::check() && !Auth::user()->no_pricing)
                <th>Item Total</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach($order->details as $detail)
            <tr>
              <td>{{ $detail->product->name }}</td>
              <td>{{ $detail->shipped?'Shipped':'Not Shipped' }}</td>
              <td>{{ $detail->quantity }}</td>
              <td>{{ $detail->options }}</td>
              @if(Auth::check() && !Auth::user()->no_pricing)
                <td>${{ \number_format($detail->subtotal,2) }}</td>
              @endif
            </tr>
            @endforeach
          </tbody>
          @if(Auth::check() && !Auth::user()->no_pricing)
            <tfoot>
              <tr>
                <th colspan="4" class="text-right"><strong>Sub-Total</strong></th>
                <th>${{ \number_format($order->total,2) }}</th>
              </tr>
              <tr>
                <th colspan="4" class="text-right"><strong>+ Tax</strong></th>
                <th>${{ \number_format($order->tax,2) }}</th>
              </tr>
              <tr>
                <th colspan="4" class="text-right"><strong>Total</strong></th>
                <th>${{ \number_format($order->total_with_tax,2) }}</th>
              </tr>
            </tfoot>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>
  @include('partial.sidebar-contact-full')
@stop






