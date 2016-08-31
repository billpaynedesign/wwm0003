@extends('app')

@section('keywords') @parent:: {{ $product->category?$product->category->name:'Uncategorized' }} :: {{ $product->name }} @stop
@section('title') @parent :: {{ $product->category?$product->category->name:'Uncategorized' }} :: {{ $product->name }} @stop

@section('scripts')
  <script type="text/javascript">
    $(document).on('ready',function(){

    });
    $(document).ready(function(){   
      $('.attribute_select').each(function(){
        $(this).on('change',update_price);
      });
      $('.option-swatch').on('click',function(){
        $('.option-swatch').removeClass('selected');
        $(this).addClass('selected');
        $('option[value="'+$(this).data('option')+'"]').prop('selected','selected');
        update_price();
      });
      function update_price(){
      var msrp = {{ $product->msrp }}
      var price = {{ $product->discountAvailable?$product->msrp - $product->discount:$product->msrp }};
      var option = 0;
      var html;
      var final_price;
      $('.attribute_select').each(function(){
        //var addative = parseFloat($(this).find(':selected').data('price'));
        if(option == 0)
        {


        option = option + parseFloat($(this).find(':selected').data('price'));
        if($(this).attr('id') == 'Color'){
          $('.option-swatch').removeClass('selected');
          $('[data-option="'+$(this).val()+'"]').addClass('selected');
        }
      }
        if(option > 0)
        {
          final_price = option;
          if(price < msrp || final_price < msrp){
            html = '<span class="strike-through"><s>$'+msrp + '</s><strong class="text-orange">  $'+final_price+'</strong></span>';
          }
          else{
            html = '$'+final_price;
          }

          $('#price').html(html);
          
        }
      });
      return;
      //Moved the rest of this function into the logic of if(option > 0 statement)
      final_price = option;
      if(price < msrp || final_price < msrp){
        html = '<span style=".strike-through:{text-decoration:line-through;}" class="strike-through">$'+msrp + '</span><br/><strong class="text-orange">$'+final_price+'</strong>';
      }
      else{
        html = '$'+final_price;
      }

      $('#price').html(html);
    }
    });
  </script>
@endsection

@section('content')
<div class="container main-container no-padding">
  <div class="col-md-8 col-xs-12 main-col">
    <div class="col-md-4 col-xs-12 text-center">
      @if($product->picture)
        <img src="{{ asset('pictures/'.$product->picture) }}" class="img-responsive center-block" alt="{{ $product->name }}" />
      @else
        <img src="{{ asset('images/noimg.gif') }}" class="img-responsive center-block" alt="No Image Available" />
      @endif  
    </div>
    <div class="col-md-8 col-xs-12 product-details">
      
      <h1>{{ $product->name }}</h1>

      <div class="form-group row">
      <p><strong>Item Number:</strong> {{ $product->item_number }}</p>
      <p><strong>Overview:</strong> {!! nl2br($product->short_description) !!}</p>
      <p><strong>Retail Price:</strong> {{ $product->msrp_string }}</p>
      @if(Auth::check())
        @if(Auth::user()->product_price_check($product->id))
          <p class="price">Your Price: <span id="price">{{ Auth::user()->product_price_check($product->id)->price_string }}</span></p>
        @else
          <p class="price">Your Price: <span id="price">{{ $product->price_string }}</span></p>
        @endif
      @else
        <p class="price">Your Price: <span id="price">{{ $product->price_string }}</span></p>
      @endif
      </div>
      <form action="{{ route('add-to-cart') }}" method="post" role="form">
          <div class="form-group row"> 
          <div class="col-md-4">
          <label class="pull-right" style="font-size:12px;" for="quantity">QTY</label>
          </div>
          <div class="col-md-8">
          <input class="pull-left" style="min-width:50%; max-width:50%;" type="number" name="quantity" id="quantity" value="1" maxlength="5" class="form-control">
          </div>
          </div>
          @if($product->productAttributes)
            @foreach($product->productAttributes()->active()->groupBy('name')->get() as $attribute)
            <div class="form-group row"> 
            <div class="col-md-4">
            <label class="pull-right" style="font-size:12px;" for="{{ $attribute->name }}">{{ $attribute->name }}</label>
            </div>
            <div class="col-md-8">
            <select class="pull-left attribute_select" style="min-width:50%; max-width:50%;" id="{{ $attribute->name }}" name="options[{{ $attribute->name }}]" class="form-control">

              <?php $attributeOptions = $product->productAttributes()->active()->where('name','=',$attribute->name)->orderBy('id')->get(); ?>
              <option value="" data-price="0">
                  -Please Select-
                </option>
              @foreach($attributeOptions as $option)
                <option value="{{ $option->option }}" data-price="{{ \number_format($option->price,2) }}">
                  {{ $option->option }} - ${{ number_format($option->price,2) }}
                </option>
              @endforeach
            </select>
            </div>
            </div>
            @endforeach
          @endif
          <input type="hidden" name="id" value="{{ $product->id }}"/>
          <button type="submit" name="_token" class="btn btn-addcart" value="{{ csrf_token() }}" title="Add to cart">Add to cart</button>
        
      </form>
      <hr>
    </div>
    <div class="col-xs-12">
      <p><strong class="text-blue">Details:</strong></p>
      <p>{!! nl2br($product->description) !!}</p>
    </div>
  </div>
  @include('partial.sidebar-contact-col4')
</div>

@endsection
