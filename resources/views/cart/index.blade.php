@extends('app')

@section('title') @parent :: Cart @stop

@section('scripts')
  <script type="text/javascript">
    function edit(id){
      $('#'+id).removeClass('hide');
      $('#edit_button_'+id).addClass('hide');
    }
  </script>
@endsection

@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <div class="table-responsive">
      <table class="table table-hover table-striped table-bordered text-left">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Options</th>
          <th>Item Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @if(count($cart)>0)
          @foreach($cart as $row)
            @if($row->product && $row->uom)
              <tr>
                <td><a href="{{ route('product-show', $row->product->slug) }}">{{ $row->product->name }}</a></td>
                <td>${{ number_format($row->cost,2) }}</td>
                <td>{{ $row->quantity }}</td>
                <td>{{ $row->uom->name }}</td>
                <td>${{ number_format($row->sub_total,2) }}</td>
                <td>
                  <button id="edit_button_{{ $row->id }}" class="btn btn-info" onclick="edit('{{ $row->id }}');" title="Edit Quantity">
                    <span class="glyphicon glyphicon-edit"></span>
                  </button>
                  <form id="{{ $row->id }}" class="form-inline form hide pull-left" action="{{ route('cart-update') }}" method="post" role="form">
                    <input type="hidden" name="rowid" value="{{ $row->id }}" />
                    <div class="input-group">
                      <input type="number" name="quantity" id="quantity" value="{{ $row->quantity }}" class="form-control" min="1" max-width="5" />
                      <span class="input-group-btn">
                        <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-warning" title="Submit Quantity"><span class="glyphicon glyphicon-edit"></span></button>
                      </span>
                    </div>
                  </form>
                  <a href="{{ route('cart-remove',$row->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this item from the cart?');" title="Delete Item">
                    <span class="glyphicon glyphicon-remove"></span>
                  </a>
                </td>
              </tr>
            @endif
          @endforeach
        @else
          <tr><td colspan="6">Nothing in your cart yet.</td></tr>
        @endif
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4"><strong>Total</strong></th>
          <th>${{ \number_format(Cart::total(),2) }}</th>
        </tr> 
      </tfoot>
      </table>
      <div class="form-group form-inline pull-right">
        <button class="btn" onclick="javascript:history.back();">Continue Shopping</button>
        <a href="{{ route('cart-shipping') }}" class="btn btn-default{{ (Cart::count()>0)?'':' disabled' }}" {{ (Cart::count()>0)?'':'disabled' }}>Checkout</a>
      </div>
    </div>
  </div>
</div>
  @include('partial.sidebar-contact-full')
@endsection
