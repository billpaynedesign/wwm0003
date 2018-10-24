@extends('layout')

@section('title') @parent :: Order Confirmation @stop

@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container">
    <div id="col-main" class="col-xs-12">
      <h1>Order Confirmation</h1>
      <div class="row">
        <div class="col-md-10 col-md-offset-1 col-xs-12">
          <div class="alert alert-danger" role="alert">Please do not refresh this page or your card could be recharged. A brief summary of your order can be seen below or visit the link provided for more information.</div>
          @if(Auth::check())
          	<p>You can check your order information at anytime by logging in and using order history link in the navigation above.</p>
          	<p>For your convenience we have also generated a unique url for quick access to your order information <a href="{{ route('order-show',$order->token) }}" target="_blank">{{ route('order-show',$order->token) }}</a></p>
          @else
          	<p>For your convenience we have generated a unique url for quick access to your order information <a href="{{ route('order-show',$order->token) }}" target="_blank">{{ route('order-show',$order->token) }}</a>. Please use this to check your order information and progress at anytime. If you would like to keep a history of your orders please <a href="{{ url('/auth/login') }}">Login</a> next time before making your purchase.</p>
          @endif
        </div>
      </div>
      <div class="row">
      	<div class="col-md-10 col-md-offset-1 col-xs-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Item Name</th>
                    <th>Options</th>
                    <th>Quantity</th>
                    <th>Item Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->details as $detail)
                    <tr>
                      <td>{{ $detail->product->name }}</td>
                      <td>
                        {{ $detail->size.' '.$detail->color.' ' }}
                        @if($detail->other)
                          @foreach(explode(',',$detail->other) as $other)
                            {{ explode(":",$other)[1].' ' }}
                          @endforeach
                        @endif
                      </td>
                      <td>{{ $detail->quantity }}</td>
                      <td>${{ \number_format($detail->subtotal,2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                @if($transaction->state == 'FL')
                  <tr>
                    <th colspan="3"><strong>SubTotal</strong></th>
                    <th>${{ \number_format($order->total,2) }}</th>
                  </tr>
                  @if(!$order->user->tax_exempt)
                  <tr>
                    <th colspan="3"><strong>Tax</strong></th>
                    <th>+${{ \number_format(round($order->total *$order->user->tax,2),2) }}</th>
                  </tr>
                  @endif
                  <tr>
                    <th colspan="3"><strong>Total</strong></th>
                    <th>${{ \number_format($order->total+round($order->total * ,2),2) }}</th>
                  </tr>
                @else
                  <tr>
                    <th colspan="3"><strong>Total</strong></th>
                    <th>${{ \number_format($order->total,2) }}</th>
                  </tr>
                @endif
                </tfoot>
              </table>
            </div>
      	</div>
      </div>
    </div>
  </div>
</div>
@endsection
