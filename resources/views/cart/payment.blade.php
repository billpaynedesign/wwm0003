@extends('layout')

@section('title') @parent :: Checkout Payment @stop

@section('scripts')
<script type="text/javascript">
  Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
    num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
  };
  var name = '{{ $order->shippingname }}';
  var address1 = '{{ $order->address1 }}';
  var address2 = '{{ $order->address2 }}';
  var city = '{{ $order->city }}';
  var state = '{{ $order->state }}';
  var zip = '{{ $order->zip }}';
  var total = {{ $order->total }};
  var states = {!! App\State::all() !!};
  $(document).ready(function(){
    $("#purchase_order_check").on('change',function(){
      if($(this).is(':checked')){
        $('#card_group').slideUp();
        $('#purchase_order_group').slideDown();
      }
      else{
        $('#card_group').slideDown();
        $('#purchase_order_group').slideUp();
      }
    });
    $('#same-as-shipping').on('change',function(){
      if($(this).is(':checked')){
        $('#name').val(name).prop('readonly',true);
        $('#address1').val(address1).prop('readonly',true);
        $('#address2').val(address2).prop('readonly',true);
        $('#city').val(city).prop('readonly',true);
        $('#state').val(state).prop('readonly',true);
        $('#zip').val(zip).prop('readonly',true);
      }
      else{
        $('#name').val('').prop('readonly',false);
        $('#address1').val('').prop('readonly',false);
        $('#address2').val('').prop('readonly',false);
        $('#city').val('').prop('readonly',false);
        $('#state').val('').prop('readonly',false);
        $('#zip').val('').prop('readonly',false);
      }
    });
  });
  function validate_form(){
    var error = '';
    if($("#purchase_order_check").is(':checked')){
      if(!($.trim($('#purchase_order_number').val()))){
        error += '<li>Purchase order number is required</li>';
      }
    }
    else{
      if(!($.trim($('#card_num').val()))){
        error += '<li>Card number is required</li>';
      }
      if(!($.trim($('#expiry_month').val()))){
        error += '<li>Expiration month is required</li>';
      }
      if(!($.trim($('#expiry_year').val()))){
        error += '<li>Expiration year is required</li>';
      }
      if(!($.trim($('#cvv').val()))){
        error += '<li>Cvv code is required</li>';
      }
    }
    if(error !== ''){
      $('#error_alert').html('<ul>'+error+'</ul>').removeClass('hide');
      return false;
    }
    else{
      $('#error_alert').html('').addClass('hide');
      return true;
    }
  }
</script>
@endsection

@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container">
    <div id="col-main" class="col-xs-12">
      <h1>Payment Information</h1>
      @if(isset($response_text))
      <div class="alert alert-danger" role="alert">{{ $response_text }}</div>
      @endif
      <div id="error_alert" class="alert alert-danger hide" role="alert"></div>
      <form action="{{ route('cart-checkout') }}" method="post" role="form" onsubmit="return validate_form();">
        <div class="form-group" style="display:none;">
          <div class="checkbox">
            <label for="purchase_order_check">
              <input type="checkbox" id="purchase_order_check" name="purchase_order_check" checked="checked"/> Pay by Purchase Order
            </label>
          </div>
        </div>
        <div id="purchase_order_group" class="form-group">
            <label for="purchase_order_number">Purchase Order Number</label>
            <input type="text" id="purchase_order_number" name="purchase_order_number" class="form-control" />
        </div>
        @if(Auth::check())
            @if(!Auth::user()->no_pricing)
            <div id="card_group" style="display:none;">
              <div class="form-group">
                <label for="card_num">Card Number</label>
                <input type="text" name="card_num" id="card_num" class="form-control" />
              </div>
              <div class="form-group">
                <label for="expiry_month">Expiration Date</label>
                <div class="row">
                  <div class="col-xs-6">
                    <select name="expiry_month" id="expiry_month" class="form-control">
                      <option value="">Month</option>
                      <option value="01">Jan (01)</option>
                      <option value="02">Feb (02)</option>
                      <option value="03">Mar (03)</option>
                      <option value="04">Apr (04)</option>
                      <option value="05">May (05)</option>
                      <option value="06">June (06)</option>
                      <option value="07">July (07)</option>
                      <option value="08">Aug (08)</option>
                      <option value="09">Sep (09)</option>
                      <option value="10">Oct (10)</option>
                      <option value="11">Nov (11)</option>
                      <option value="12">Dec (12)</option>
                    </select>
                  </div>
                  <div class="col-xs-6">
                    <select name="expiry_year" id="expiry_year" class="form-control">
                      <option value="">Year</option>
                      <option value="13">2013</option>
                      <option value="14">2014</option>
                      <option value="15">2015</option>
                      <option value="16">2016</option>
                      <option value="17">2017</option>
                      <option value="18">2018</option>
                      <option value="19">2019</option>
                      <option value="20">2020</option>
                      <option value="21">2021</option>
                      <option value="22">2022</option>
                      <option value="23">2023</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" id="cvv" class="form-control"/>
              </div>
            </div>
            @endif
            @if(Auth::user()->gsa)
                <div class="form-group">
                    <label for="solicitation_number">Solicitation #</label>
                    <input type="text" id="solicitation_number" name="solicitation_number" class="form-control" />
                </div>
            @endif
        @endif
        <h2 class="text-blue">Billing Information</h2>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input id="same-as-shipping" type="checkbox" name="same_as_shipping" value="true"> Same as shipping
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" name="name" id="name" class="form-control" required/>
        </div>
        <div class="form-group">
          <label for="address1">Address Line 1:</label>
          <input type="text" name="address1" id="address1" class="form-control" required/>
        </div>
        <div class="form-group">
          <label for="address2">Address Line 2:</label>
          <input type="text" name="address2" id="address2" class="form-control"/>
        </div>
        <div class="form-group">
          <label for="city">City:</label>
          <input type="text" name="city" id="city" class="form-control" required/>
        </div>
        <div class="form-group">
          <label for="state">State:</label>
          <select name="state" id="state" class="form-control">
            <option value="">State</option>
            @foreach(App\State::all() as $state)
            <option value="{{ $state->abbr }}">{{ $state->state }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="zip">Zip Code:</label>
          <input type="text" name="zip" id="zip" class="form-control" required/>
        </div>
        @if(Auth::check() && !Auth::user()->no_pricing)
          <div class="form-group">
            <br/>
            <p><strong>Total: ${{ \number_format($order->total,2) }}</strong></p>
            @if(!auth()->user()->tax_exempt && auth()->user()->tax>0)
            <p><strong>Estimated Tax: +${{ number_format($order->tax,2) }}</strong></p>
            <p><strong>Total After Tax: ${{ number_format($order->total_with_tax,2) }}</strong></p>
            @endif
          </div>
        @endif
        <input type="hidden" name="id" value="{{ $order->id }}" />
        <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Complete Purchase</button>
      </form>
    </div>
  </div>
</div>
@endsection
