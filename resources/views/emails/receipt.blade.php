@extends('emails.layout')
@section('h1')
Thank you for your purchase.
@stop
@section('content')
<strong>Order/Billing Information:</strong>
<br/>
Order ID:  {{ $order->id }}<br/>
@if(!empty($order->transaction->purchase_order_num))
  Purchase Order #:{{ $order->transaction->purchase_order_num }}<br/>
@endif
@if(!empty($order->transaction->transaction_id))
  Transaction ID: {{ $order->transaction->transaction_id }}<br/>
@endif
Order URL: <a href="{{ route('order-show',$order->token) }}">{{ route('order-show',$order->token) }}</a><br/>
Name:  {{ $transaction->name }}<br/>
Address:  {{ $transaction->address1.' '.$transaction->address2 }}<br/>
City:  {{ $transaction->city }}<br/>
State:  {{ $transaction->state }}<br/>
Zip:  {{ $transaction->zip }}<br/>
<br/>
<br/>

<strong>Shipping Information:</strong>
<br/>
Name:  {{ $order->shippingname }}<br/>
Address:  {{ $order->address1.' '.$order->address2 }}<br/>
City:  {{ $order->city }}<br/>
State:  {{ $order->state }}<br/>
Zip:  {{ $order->zip }}<br/>
@if($order->phone)
Phone:  {{ $order->phone }}<br/>
@endif
@if($order->secondary_phone)
Secondary Phone:  {{ $order->secondary_phone }}<br/>
@endif
<br/>
<br/>
<table border="1" cellpadding="5" cellspacing="0" width="100%" style="text-align:left;font-size:10pt;">
  <thead>
  <tr style="text-align:left;" bgcolor="#CCC">
    <th align="left">Item Name</th>
    <th align="left">Product Detail</th>
    <th align="left">Quantity</th>
    <th align="left">Options</th>
    <th align="left">SubTotal</th>
  </tr>
  </thead>
  <tbody>
  @foreach($order->details as $detail)
  <tr>
    <td>{{ $detail->product->name }}</td>
    <td>{{ $detail->product->item_number }} {{ $detail->product->options()->select('option')->get()->implode('option',',') }}</td>
    <td style="text-align:center;">{{ $detail->quantity }}</td>
    <td style="text-align:center;">{{ $detail->options }}</td>
    <td style="text-align:center;">${{ \number_format($detail->subtotal,2) }}</td>
  </tr>
  @endforeach
  @if(!$order->user->tax_exempt)
  <tr>
    <td colspan="3"></td>
    <th bgcolor="#CCC" align="right" style="padding-right:8px;"> State Tax + </th>
    <td style="text-align:center;">${{ \number_format($order->tax,2) }}</td>
  </tr>
  @endif
  <tr>
    <td colspan="3"></td>
    <th bgcolor="#CCC" align="right" style="padding-right:8px;">Total</th>
    <td style="text-align:center;">${{ \number_format($order->total_with_tax,2) }}</td>
  </tr>
</tbody>
</table>
@stop
