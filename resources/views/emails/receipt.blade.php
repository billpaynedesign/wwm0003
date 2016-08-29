@extends('emails.layout')
@section('h1')
Thank you for your purchase.
@stop
@section('content')
<strong>Order/Billing Information:</strong>
<br/>
Order ID:  {{ $order->id }}<br/>
Transaction ID:  {{ $transaction->transaction_id.'-'.$transaction->id }}<br/>
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
<tr style="text-align:left;">
  <th align="left">Item Name</th>
  <th align="left">Quantity</th>
  <th align="left">Options</th>
  <th align="left">SubTotal</th>
</tr>
</thead>
<tbody>
@foreach($details as $detail)
<tr>
  <td>{{ $detail->product->name }}</td>
  <td>{{ $detail->quantity }}</td>
  <td>{{ $detail->size.' '.$detail->color }}</td>
  <td>${{ \number_format($detail->subtotal,2) }}</td>
</tr>
@endforeach
@if($transaction->state == 'FL')
  <tr>
    <th align="left" colspan="3"><strong>SubTotal</strong></th>
    <td align="left">${{ \number_format($order->total,2) }}</td>
  </tr>
  <tr>
    <th align="left" colspan="3"><strong>Tax</strong></th>
    <th align="left">+${{ \number_format(round($order->total * .065,2),2) }}</th>
  </tr>
  <tr>
    <th align="left" colspan="3"><strong>Total</strong></th>
    <th align="left">${{ \number_format($order->total+round($order->total * .065,2),2) }}</th>
  </tr>
@else
  <tr>
    <th align="left" colspan="3"><strong>Total</strong></th>
    <th align="left">${{ \number_format($order->total,2) }}</th>
  </tr>            
@endif
</tbody>
</table>
@stop