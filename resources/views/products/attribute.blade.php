@extends('app')


@section('head')

@endsection
@section('scripts')
<script type="text/javascript">    

$(document).ready(function(){        
  var _attributes = new Array();
  var attributeLoaded = false;
  var optionCount = 0;
   
 

  $("#attribute_selector").on('change',function(){

      var _this = $(this);
      var _thisVal = $(this).val();
      if($(this).val() == '')
      {
        if(attributeLoaded != false)
          {
              $("div[name='new_attribute']").remove();
              attributeLoaded = false;
          }
      } else if(_this.val() == 'attributeNew')
      {
        var optionCount = 0;
        if({!! $attributeNames !!})
        {
          if(attributeLoaded != false)
          {
              $("div[name='new_attribute']").remove();
              attributeLoaded = false;
          }
        }
          var html = '<div > <div> <div> <div class="option-group clearfix " style="margin:0px 0px 80px 0px; border-top:none;" name="new_attribute"> <label for="name"> <strong> Create a new Attribute for {!! $product->name !!}:</strong> </label> <input type="text" class="form-control" id="name" name="name" placeholder="New Attribute Name" /> <div class="option-group clearfix" style="margin:0px 0px 80px 0px; border-top:none;"> <label for="options">Attribute Options &nbsp;&nbsp;&nbsp; </label> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; "><strong>Attribute Option</strong></label> <input type="text" class="form-control" id="options" name="options[]" placeholder="Option Name" value=""> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; " for="options_prices">Option Price ($)</label> <input type="number" class="form-control" id="options_prices" name="options_prices[]" value="0" step="0.01" min="0" /> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; " for="options_msrp">Option MSRP ($)</label> <input type="number" class="form-control" id="options_msrp" name="options_msrp[]" value="0" step="0.01" min="0" /> </div> <div class="clearfix" style="margin:15px 0px 15px 0px; "> <input type="hidden" name="options_active['+ optionCount +']" id="'+ optionCount +'" value="off"> <input class="checkbox " style="margin:10px 0px 5px 0px; display: inline;" checked type="checkbox" id="'+ optionCount +'" name="options_active['+ optionCount +']" > <label class="" style="margin:10px 0px 5px 0px; display: inline;" for="options_active"> Is This Option Active?</label> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <a href="javascript:void(0);" class="pull-left btn btn-sm btn-danger" style="margin:0px 0px 0px 0px;" onclick="$(this).parent().parent().remove();"> <span class="glyphicon glyphicon-trash" aria-hidden="true"><label style="margin:0px 0px 0px 0px; "  class="" for="options_active">  Delete Option</label>  </span></a> </div> </div> <div id="new_option_spot"> </div> <div> <p> <a href="javascript:void(0);" class="btn btn-sm btn-success" onclick="add_attribute();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"> </span> <label style="margin:0px 0px 0px 0px;">New Option</label> </a> </p> </div></div> </div> </div>  </div>  '; optionCount += 1;
          $(".attribute-option-group").append(html);
      } else {
        var grouped = new Array();
        grouped['usable_optionid'] = new Array();
        grouped['usable_optionname'] = new Array();
        grouped['usable_productid'] = new Array();
        if({!! $attributeNames !!})
        {
          if(attributeLoaded != false)
          {
              $("div[name='new_attribute']").remove();
              attributeLoaded = false;
          }
          @foreach($attributeNames as $attribute)
            var count = 0;
            var duplicatesFound = false;
            {{$count = 0}}
            @foreach($attributesAllGrouped as $grouped)
              @foreach($grouped as $grp)
                if('{!! $grp->name !!}' == _thisVal)
                {
                  var found = false;
                  var index = 0;
                  if(grouped['usable_optionname'].length > 0)
                  {
                    for(var i = 0; i < grouped['usable_optionname'].length;i++)
                    {
                      if(grouped['usable_optionname'][i] == '{!! $grp->option !!}' && grouped['usable_optionid'] != '{!! $grp->id !!}')
                      {
                        found = true;
                        index = i;
                        break;
                      }
                    }
                  }
                  if(found == true)
                  {
                    if(grouped['usable_productid'][index] != '{!! $product->id !!}')
                    {
                      grouped['usable_optionid'][index] = '{!! $grp->id !!}';
                    }
                  } 
                  else 
                  {
                    grouped['usable_optionid'][count] = '{!! $grp->id !!}';
                    grouped['usable_optionname'][count] = '{!! $grp->option !!}';
                    grouped['usable_productid'][count] = '{!! $grp->product_id !!}';
                  }
                  
                }
                count++;
              @endforeach  
            @endforeach
          if('{!! $attribute->name !!}' == _thisVal)
          {
            if(attributeLoaded == false)
            {//to know when there are previou instantiated html to delete before instantiating more html
              attributeLoaded = 'new_attribute';
                var optionCount = 0;
                var html_end = "";
                var html_begin = "";
                html_begin = '<div class="option-group clearfix" name="{!! $attribute->name !!}"> <label for="name">Edit Attribute Name</label> <input type="text" class="form-control" id="name" name="name" placeholder="Attribute Name" value="{!! $attribute->name !!}"> </div> ';
                var attributeIndex = 0;
                @foreach($attributesAll as $attributeOp)
                  var active = 0;
                  var htmlActive = "checked";
                  var match = false;
                  if('{!! $attributeOp->active !!}' == '0' && '{!! $attributeOp->product_id !!}' == '{!! $product->id !!}')
                  {
                    active = 1;
                    htmlActive = "unchecked";
                  }
                   if('{!! $attributeOp->name !!}' == _thisVal)
                   {
                      if(grouped['usable_optionid'].length > 0)
                      {
                        for(var i = 0; i < grouped['usable_optionid'].length; i++)
                        {
                          if('{!! $attributeOp->id !!}' == grouped['usable_optionid'][i])
                          {
                            match = true;
                          }
                        }
                      }
                    if(match == true)
                    {
                      html_end += '<div class="option-group clearfix" style="margin:0px 0px 80px 0px; border-top:none;"> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; "><strong>Attribute Option</strong></label> <input type="text" class="form-control" id="options" name="options[]" placeholder="Option Name" value="{!! $attributeOp->option !!}"> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; " for="options_prices">Option Price ($)</label> <input type="number" class="form-control" id="options_prices" name="options_prices[]" value="{!! $attributeOp->price !!}" step="0.01" min="0" /> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; " for="options_msrp">Option MSRP ($)</label> <input type="number" class="form-control" id="options_msrp" name="options_msrp[]" value="{!! $attributeOp->msrp !!}" step="0.01" min="0" /> </div> <div class="clearfix" style="margin:15px 0px 15px 0px; "> <input type="hidden" name="options_active['+ optionCount +']" id="'+ optionCount +'" value="off"> <input class="checkbox " style="margin:10px 0px 5px 0px; display: inline;" '+ htmlActive +' type="checkbox" id="'+ optionCount +'" name="options_active['+ optionCount +']" > <label class="" style="margin:10px 0px 5px 0px; display: inline;" for="options_active"> Is This Option Active?</label> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <a href="javascript:void(0);" class="pull-left btn btn-sm btn-danger" onclick="$(this).parent().parent().remove();"> <span class="glyphicon glyphicon-trash" aria-hidden="true"><label style="margin:0px 0px 0px 0px; "  class="" for="options_active">  Delete Option</label>  </span></a> </div> </div>'; optionCount += 1;
                   
                    
                      attributeIndex += 1;
                      }
                    }                  
                @endforeach
                if(optionCount == 0)
                {
                  html_end = ' <div id="new_option_spot"> </div> <div> <a href="javascript:void(0);" class="btn btn-sm btn-success"   "onclick="add_attribute();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"> </span> </a> </div>';
                }
                var html = '<div class="option-group clearfix listed_attribute" name="new_attribute"> <label for="name"> <!-- <strong> ' + optionCount + ' options asigned to {!! $product->name !!} </strong> </label> <div class="option-group clearfix"> <p> <a href="javascript:void(0);" class="pull-left btn btn-sm btn-danger" onclick="$(this).parent().remove();"> <span class="glyphicon glyphicon-trash" aria-hidden="true"> </span> <label>  Delete Attribute from all Products</label> </a> </p> <p>&nbsp;</p> ' + html_begin + ' -->' + html_end + '<div id="new_option_spot"> </div> <div> <p> <a href="javascript:void(0);" class="btn btn-sm btn-success" onclick="add_attribute();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"> </span> <label style="margin:0px 0px 0px 0px;">New Option</label> </a> </p> </div> </div>';
                $(".attribute-option-group").append(html);
              }
            }
          @endforeach
        }
      }
  });
});
var load_attribute = function(){
  if({!! $attributeNames !!})
  {
    @foreach($attributeNames as $attribute)
    if('{!! $attribute->name !!}' == _thisVal){
           var html = '<div class="option-group clearfix"> <a href="javascript:void(0);" class="pull-left btn btn-sm btn-danger" onclick="$(this).parent().remove();"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </a> <p>&nbsp;</p> <label for="options">Option Name</label> <input type="text" class="form-control" id="options" name="options[]" placeholder="Option Name" value="{!! $attribute->name !!}"> <label for="options_prices">Attribute Option Price ($)</label> <input type="number" class="form-control" id="options_prices" name="options_prices[]" value="{!! $attribute->price !!}" step="0.01" min="0" /> <label for="options_msrp">Attribute Option MSRP ($)</label> <input type="number" class="form-control" id="options_msrp" name="options_msrp[]" value="{!! $attribute->msrp !!}" step="0.01" min="0" /> </div>';
          $(".attribute-option-group").append(html);
          //console.log(html);
    }
    @endforeach
  }
};
var optionCount = 0;
function add_attribute(){
  var html = '<div  class="option-group clearfix" style="margin:0px 0px 80px 0px; border-top:none; "> <div class=" clearfix" style="margin:0px 0px 0px 0px; border-top:none; "> <label style="margin:10px 0px 5px 0px; "><strong>Attribute Option</strong></label> <input type="text" class="form-control" id="options" name="options[]" placeholder="Option Name" value=""> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; " for="options_prices">Option Price ($)</label> <input type="number" class="form-control" id="options_prices" name="options_prices[]" value="0" step="0.01" min="0" /> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <label style="margin:10px 0px 5px 0px; " for="options_msrp">Option MSRP ($)</label> <input type="number" class="form-control" id="options_msrp" name="options_msrp[]" value="0" step="0.01" min="0" /> </div> <div class="clearfix" style="margin:15px 0px 15px 0px; "> <input type="hidden" name="options_active['+ optionCount +']" id="'+ optionCount +'" value="off"> <input class="checkbox " style="margin:10px 0px 5px 0px; display: inline;" checked type="checkbox" id="'+ optionCount +'" name="options_active['+ optionCount +']" > <label class="" style="margin:10px 0px 5px 0px; display: inline;" for="options_active"> Is This Option Active?</label> </div> <div class="clearfix" style="margin:0px 0px 0px 0px; "> <a href="javascript:void(0);" class="pull-left btn btn-sm btn-danger" onclick="$(this).parent().parent().remove();"> <span class="glyphicon glyphicon-trash" aria-hidden="true"><label style="margin:0px 0px 0px 0px; "  class="" for="options_active">  Delete Option</label>  </span></a> </div> <div id="new_option_spot"> </div> </div>   ';
  optionCount += 1;
  $("#new_option_spot").append(html);
}
</script>
@endsection
@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Add Options to Attribute: {{ $product->name }}</h1>
    <div class="col-xs-3">
      <img src="{{ asset('pictures/'.$product->picture) }}" class="img-responsive center-block" />
    </div>
    <div class="col-md-8 col-md-offset-1 col-xs-9">
      <div class="alert alert-info">If you would like to use the product Price and MSRP leave the respective attribute Price/MSRP $0</div>
      <form action="{{ route('product-attribute-new') }}" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="product_id" class="product_id" value="{{$product->id}}">
          <label for="name">Attribute Name</label>
          <select class="form-control" name="name" id="attribute_selector">
                <option value="">-- Select One --</option>
                @if($attributeNames)
                  @foreach($attributeNames as $attributeName)
                    <option value="{!! $attributeName->name !!}">{!! $attributeName->name !!}</option>
                  @endforeach
                @endif
                <option value="attributeNew">-- Create New --</option>
        </select>
        <div id="attributeGroupNew" class="form-group attribute-option-group">
          
          </div>
        </div>
        <button type="submit" name="cancel" id="cancel" value="true" class="btn btn">Cancel</button>
        <button type="submit" name="submit" id="submit" value="true" class="btn btn-default">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection