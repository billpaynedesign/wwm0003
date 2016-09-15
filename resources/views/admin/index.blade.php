
@extends('app')


@section('title') Admin Dashboard :: @parent @stop

@section('scripts')
<script type="text/javascript">
Dropzone.autoDiscover = false;
$(document).ready(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });
  $('.html-popover').popover({html:true});
  $('#backorders_table').DataTable({"order": [[ 0, "desc" ]]});
  $('#orders_table').DataTable({"order": [[ 0, "desc" ]]});
  $('#categories_table').DataTable({"order": [[ 3, "asc" ]]});
  @if(Session::has('tab'))
  $('#admin_tab_panel a[href="#{{ Session::get('tab') }}"]').tab('show');
  @endif
  $("#add-picture-dropzone").dropzone({
    url: '/false',
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 100,
    previewTemplate: '#dropzone-prevew',
    init: function() {
      var myDropzone = this;
      this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        myDropzone.processQueue();
      });
    }

  });
  $('#csv-file-button').fileinput({
    browseClass: "btn btn-info",
    showCaption: false,
    showRemove: false,
    browseLabel: 'Csv Upload',
    showPreview: false
  });
  $('#products_table tr').on('click',function(){
    if($('#products_table').hasClass('removal_ready')){
      if( $(this).hasClass('checked')){
        $(this).removeClass('checked');
      }
      else{
        $(this).addClass('checked');
      }
    }
  });
  @if(session()->has('order-status-failed'))
    order_status({{ session()->get('order-status-failed') }});
    $('#order-status').modal('show')
  @endif
});
function order_status(id){
  $.post('{{ route("order-status") }}',{id:id},function(data){
    //console.log(data);
    $('#order-status .modal-body').html(data);
    $('.orderstatus-datepicker').datepicker();
  });
}
function category_toggle_active(id){
  $.get('{{ route("category-toggle-active") }}',{id:id},function(data){
    var html = (data.response == 1)?'<span class="text-success glyphicon glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#category-active-'+data.id).html(html);
  });
}
function category_toggle_featured(id){
  $.get('{{ route("category-toggle-featured") }}',{id:id},function(data){
    var html = (data.response == 1)?'<span class="text-yellow glyphicon glyphicon glyphicon-star"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#category-featured-'+data.id).html(html);
  });
}
function product_toggle_featured(id){
  $.get('{{ route("product-toggle-featured") }}',{id:id},function(data){
    var html = (data.response == 1)?'<span class="text-yellow glyphicon glyphicon glyphicon-star"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#featured-product-'+data.id).html(html);
  });
}
function product_toggle_active(id){
  $.get('{{ route("product-toggle-active") }}',{id:id},function(data){
    var html = (data.response == 1)?'<span class="text-success glyphicon glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#product-active-'+data.id).html(html);
  });
}
function order_toggle_backordered(id){
  $.get('{{ route("order-toggle-backordered",'') }}/'+id,{},function(data){
    var html = (data.response == 1)?'<span class="text-success glyphicon glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#order-backordered-'+data.id).html(html);
  });
}

function category_removal(){
  if(confirm('Are you sure you would like to remove the selected categories?')){
    $('#categories_table tr .action:checked').each(function(){
      var id = $(this).val();
      $.post('{{ route("category-delete") }}',{id:id},function(data){
        if(data.response = 'success'){
          location.reload();
        }
      });
    });
  }
}
function order_information(id){
  $('#order-info-title').html('Order Information');
  $('#order-info-body').html('Loading Order Information <i class="fa fa-spinner fa-pulse"></i>');
  $.post('{{ url("order/modal") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
    $('#order-info-body').html(data);
  });
}
function product_information(id){
  $('#order-info-title').html('Product');
  $('#order-info-body').html('Loading Product Information <i class="fa fa-spinner fa-pulse"></i>');
  $.post('{{ url("product/info/modal") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
    $('#order-info-body').html(data);
  });
}
function attribute_delete(id,name,option,element){
  if(confirm('Are you sure you want to remove '+name+': '+option)){  
    $.post('{{ url("product/attribute/delete") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
      if(data['response'] == 'success'){
        $('#attribute-delete-'+data['id']).parent().parent().remove();
      }
    });
  }
}
function parent_category_change(){
  if($('#parent_category').val() !== ''){
    $('#header').parent().addClass('hide');
    $('#header_sec').parent().addClass('hide');
  }
  else{
    $('#header').parent().removeClass('hide');
    $('#header_sec').parent().removeClass('hide');
  }
}
function add_uom(){
  var html = '<div class="uom_single"> <hr/> <div class="form-group"> <button class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-trash"></span></button> </div> <div class="form-group"> <label for="uom">Unit of Measure</label> <input type="text" id="uom" name="uom[]" class="form-control" required /> </div> <div class="form-group"> <label for="msrp">MSRP:</label> <input type="number" id="msrp" name="msrp[]" step="0.01" min="0" class="form-control" required /> </div> <div class="form-group"> <label for="price">Price:</label> <input type="number" id="price" name="price[]" step="0.01" min="0" class="form-control" required /> </div> </div>'
  $("#uom_groups").append(html);
  return false;
}
function add_option(){
  var html = '<div class="option_single"> <hr/> <div class="form-group"> <button class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-trash"></span></button> </div> <div class="form-group"> <label for="option_names">Option Name</label> <input type="text" id="option_names" name="option_names[]" class="form-control" required /> </div> </div>'
  $("#option_groups").append(html);
  return false;
}
</script>
@stop

@section('content')
<div class="container-fluid main-container no-padding">
  <div class="col-xs-12 main-col">
    <div class="page-header">
      <h1>Admin Dashboard</h1>
    </div>
    <div id="admin_tab_panel" role="tabpanel">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
        <li role="presentation"><a href="#categories" aria-controls="categories" role="tab" data-toggle="tab">Categories</a></li>
        <li role="presentation"><a href="#products" aria-controls="products" role="tab" data-toggle="tab">Products</a></li>
        <li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Orders</a></li>
        <li role="presentation"><a href="#backorders" aria-controls="backorders" role="tab" data-toggle="tab">Back Orders</a></li>
        <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">Users</a></li>
        <!--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>-->
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <!-- HOME -->
        <div role="tabpanel" class="tab-pane tab-pane-admin active" id="home">
          <div class="row">
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Latest Orders</h3>
              </div>
              <table class="panel-body table table-bordered">
              @foreach($latest_orders as $lorder)
              <tr>
                <td>
                  {{ $lorder->shippingname }}
                </td>
                <td>
                  {{ $lorder->created_at->format('m-d-Y') }}
                </td>
                <td>
                  @foreach($lorder->details as $ldetail)
                    {{ $ldetail->product->category?$ldetail->product->category->name:'Uncategorized ' }} - {{ $ldetail->product->name }}<br/>
                  @endforeach
                </td>
                <td>
                  @foreach($lorder->details as $ldetail)
                    {{ $ldetail->quantity }}<br/>
                  @endforeach
                </td>
                <td>
                  ${{ \number_format($lorder->total,2) }}
                </td>
              </tr>
              @endforeach
              </table>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Latest Products</h3>
              </div>
              <table class="panel-body table table-bordered">
                @foreach($latest_products as $latest)
                <tr>
                  <td>
                    <img src="{{ asset($latest->picture?'pictures/'.$latest->picture:'images/noimg.gif') }}" class="img-responsive center-block" style="max-height:40px; max-width: 100px;"/>
                  </td>
                  <td>
                   {{ $latest->name }}
                 </td>
                 <td>
                  {{ $latest->category?$latest->category->name:'' }}
                </td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- CATEGORIES -->
    <div role="tabpanel" class="tab-pane tab-pane-admin" id="categories">
      @include('admin.index-categories')
    </div>
    <!-- PRODUCTS -->
    <div role="tabpanel" class="tab-pane tab-pane-admin" id="products">
      @include('admin.index-products')
    </div>
    <!-- ORDERS -->
    <div role="tabpanel" class="tab-pane tab-pane-admin" id="orders">
      @include('admin.index-orders')
    </div>
    <!-- BACKORDERS -->
    <div role="tabpanel" class="tab-pane tab-pane-admin" id="backorders">
      @include('admin.index-backorders')
    </div>
    <!-- USERS -->
    <div role="tabpanel" class="tab-pane tab-pane-admin" id="users">
      @include('admin.index-users')
    </div>
  </div>
</div>
</div>
<hr/>
@stop






@section('modals')
  @include('admin.modals.product-add')
  @include('admin.modals.category-add')
  @include('admin.modals.order-info')
  @include('admin.modals.option-add')

  <div class="modal fade" id="order-status" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="order_status_title">Order Status</h4>
          @if(session()->has('order-status-failed'))
            <div class="alert alert-danger">{{ session()->get('fail') }}</div>
          @endif
        </div>
        <form action="{{ route('order-status-update') }}" method="post">
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal" onsubmit="return false;">Close</button>
            <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@stop
