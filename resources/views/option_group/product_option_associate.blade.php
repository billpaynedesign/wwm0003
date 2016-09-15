@extends('app')

@section('scripts')
<script type="text/javascript">
  $('.product_assoc_radio').on('change',function(){
    var id = $(this).attr('id');
    var option_id = $(this).val();
    $('[value="'+option_id+'"]:not(#'+id+')').prop('checked',false);
  });
</script>
@endsection
@section('content')
<div class="container-fluid main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Associate Products to Options: {{ $option_group->name }}</h1>
    <form action="{{ route('group-product-option-save') }}" method="POST">
      <div class="form-group col-xs-12">
        <div class="table-responsive">
          <table id="products_table" class="table table-striped table-hover text-left">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Manufacturer</th>
                <th>Item #</th>
                <th>Available</th>
                <th>Option</th>
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
                <td>
                  @if(count($option_group->options)>0)
                    @foreach($option_group->options as $option)
                      <label class="radio-inline">
                        <input type="radio" class="product_assoc_radio" name="product_associations[{{ $product->id }}]" id="product-associate-{{ $product->id }}" value="{{ $option->id }}"> {{ $option->option }}
                      </label>
                    @endforeach
                  @endif
                </td>
              </tr>
              @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
      <div class="form-group col-xs-12">
        {!! csrf_field() !!}
        <input type="hidden" name="option_group_id" value="{{ $option_group->id }}" />
        <button type="submit" name="cancel" value="true" class="btn btn">Cancel</button>
        <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
      </div>
    </form>
  </div>
</div>
<hr/>
@endsection
