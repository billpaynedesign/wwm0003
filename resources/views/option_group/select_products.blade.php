@extends('app')

@section('scripts')
<script type="text/javascript">
  function add_product(category, name, id){
    var html = '';
    var exists = $('#add_product_table input[value='+id+']');
    if(!(exists.length>0)){
      html = '<tr> <td> <button type="button" class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-angle-double-left"></span></button> '+name+' - '+category+' <input type="hidden" class="products-input" name="products[]" value="'+id+'" /> </td> </tr>';
      $('#add_product_table tbody').append(html);
    }
  }
  function validate_products(){
    if($('.products-input').length > 0){
      return true;
    }
    else{
      alert('Please add products by clicking the green right arrow button before you can continue');
      return false;
    }
  }
</script>
@endsection
@section('content')
<div class="container-fluid main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Add Products to Group by: {{ $option->name }}</h1>
    <div class="col-lg-8 col-md-9 col-xs-12">
      <div class="table-responsive">
        <table id="products_table" class="table table-striped table-hover tablesorter text-left">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Category</th>
              <th>Manufacturer</th>
              <th>Item #</th>
              <th>Available</th>
              <th>Add?</th>
            </tr>
          </thead>
          <tbody>
            @if($products)
            @foreach($products as $product)
            <tr id="{{ $product->id }}">
              <td>{{ $product->name }}</td>
              <td>{{ $product->category?$product->category->name:'' }}</td>
              <td>{{ $product->manufacturer }}</td>
              <td>{{ $product->item_number }}</td>
              <td>
                <span class="hide">{{ $product->active }}</span>
                {!! $product->active == 1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>' !!}
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-success" onclick="add_product('{{ $product->category?str_replace("'",'',$product->category->name):'' }}','{{ str_replace("'",'',$product->name) }}', {{ $product->id }});">
                  <span class="fa fa-angle-double-right"></span>
                </button>
              </td>
            </tr>
            @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
    <form action="{{ route('group-product-option-associate') }}" method="POST" onsubmit="return validate_products();">

      <div class="col-lg-4 col-md-3 col-xs-12">
        <div class="form-group">
          <table id="add_product_table" class="table table-striped table-hover" style="margin-top: 50px;">
            <thead class="text-left">
              <tr>
                <th>Adding to group</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group pull-right">
          {!! csrf_field() !!}
          <input type="hidden" name="option_group_id" value="{{ $option->id }}" />
          <a href="{{ route('admin-products') }}" class="btn btn-cancel">Cancel</a>
          <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
<hr/>
@endsection
