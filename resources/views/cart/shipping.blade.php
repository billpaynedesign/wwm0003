@extends('app')

@section('title') @parent :: Checkout Shipping @stop

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
  @if (count($errors) > 0)
    @foreach ($errors->keys() as $error)
    $('#{{ $error }}').addClass('has-error');
    @endforeach
  @endif
  $('#shipping_id').on('change',function(){
    if($(this).val() === 'new'){
      $('#new_shipping_group').slideDown();
    }
    else{
      $('#new_shipping_group').slideUp();
    }
  });
});
function validate_form(){
  if($('#shipping_id').val() === 'new'){
    if($('#name').val()!=='' && $('#address1').val()!=='' && $('#city').val()!=='' && $('#state').val()!=='' && $('#zip').val()!==''){
      return true;
    }
    else{
      if($('#shipping_error').hasClass('hide')){
        $('#shipping_error').removeClass('hide');
      }
      var html = '';
      if(!$('#name').val()){
        html += '<li>Please fill out the Shipping Name Field</li>';
        if(!$('#name').closest('.form-group').hasClass('has-error')){
          $('#name').closest('.form-group').addClass('has-error');
        }
      }
      else{
        if($('#name').closest('.form-group').hasClass('has-error')){
          $('#name').closest('.form-group').removeClass('has-error');
        }
      }
      if(!$('#address1').val()){
        html += '<li>Please fill out the Address 1 Field</li>';
        if(!$('#address1').closest('.form-group').hasClass('has-error')){
          $('#address1').closest('.form-group').addClass('has-error');
        }
      }
      else{
        if($('#address1').closest('.form-group').hasClass('has-error')){
          $('#address1').closest('.form-group').removeClass('has-error');
        }
      }
      if(!$('#city').val()){
        html += '<li>Please fill out the City Field</li>';
        if(!$('#city').closest('.form-group').hasClass('has-error')){
          $('#city').closest('.form-group').addClass('has-error');
        }
      }
      else{
        if($('#city').closest('.form-group').hasClass('has-error')){
          $('#city').closest('.form-group').removeClass('has-error');
        }
      }
      if(!$('#state').val()){
        html += '<li>Please select a State from the list</li>';
        if(!$('#state').closest('.form-group').hasClass('has-error')){
          $('#state').closest('.form-group').addClass('has-error');
        }
      }
      else{
        if($('#state').closest('.form-group').hasClass('has-error')){
          $('#state').closest('.form-group').removeClass('has-error');
        }
      }
      if(!$('#zip').val()){
        html += '<li>Please fill out the Zip Field</li>';
        if(!$('#zip').closest('.form-group').hasClass('has-error')){
          $('#zip').closest('.form-group').addClass('has-error');
        }
      }
      else{
        if($('#zip').closest('.form-group').hasClass('has-error')){
          $('#zip').closest('.form-group').removeClass('has-error');
        }
      }
      $('#shipping_error ul').html(html);
      return false;
    }
  }
  else if(!$('#shipping_id').val()){
    if($('#shipping_error').hasClass('hide')){
      $('#shipping_error').removeClass('hide');
      $('#shipping_error ul').html('<li>Please select or enter shipping information.</li>');
    }
    return false;
  }
  else{
    return true;
  }
}
</script>
@endsection

@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Shipping Information</h1>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form action="{{ route('cart-payment') }}" method="post" role="form" onsubmit="return validate_form();">
      <div class="form-group">
        <div id="shipping_error" class="alert alert-danger hide">
          <ul>
          </ul>
        </div>
      </div>
      <div class="form-group">
        <select id="shipping_id" name="shipping_id" class="form-control" required>
          <option value="">-- Select Shipping --</option>
          @if(count(Auth::user()->shipping)>0)
            @foreach(Auth::user()->shipping as $s)
              <option value="{{ $s->id }}" {{ session()->has('shipping')?$s->id===session()->get('shipping')->id?'selected':'':'' }}>{{ $s->name }} - {{ $s->address1.' '.$s->address2.' '.$s->city.', '. $s->state.' '.$s->zip }}</option>
            @endforeach
          @endif
          <option value="new">New Shipping</option>
        </select>
      </div>
      <div id="new_shipping_group" class="form-group" style="display: none;">
        <div class="form-group">
          <label for="name">Shipping Name</label>
          <input type="text" name="name" id="name" class="form-control" />
        </div>
        <div class="form-group">
          <label for="address1">Address 1</label>
          <input type="text" name="address1" id="address1" class="form-control" />
        </div>
        <div class="form-group">
          <label for="address2">Address 2</label>
          <input type="text" name="address2" id="address2" class="form-control" />
        </div>
        <div class="form-group">
          <label for="city">City</label>
          <input type="text" name="city" id="city" class="form-control" />
        </div>
        <div class="form-group">
          <label for="state">State</label>
          <select name="state" id="state" class="form-control" >
            <option value="">-- Select State --</option>
            @foreach(App\State::all() as $state)
            <option value="{{ $state->abbr }}">{{ $state->state }}</option>
            @endforeach
        </select>
        </div>
        <div class="form-group">
          <label for="zip">Zip</label>
          <input type="text" name="zip" id="zip" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Submit</button>
      </div>
    </form>
  </div>
</div>
@include('partial.sidebar-contact-full')
@endsection
