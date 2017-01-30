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
});
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
      <h1 id="dashboard">Admin Dashboard</h1>
    </div>
    <div id="admin_tab_panel" role="tabpanel">

      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="{{ route('admin-dashboard') }}#dashboard">Home</a></li>
        <li role="presentation"><a href="{{ route('admin-categories') }}#dashboard">Categories</a></li>
        <li role="presentation" class="active"><a href="{{ route('admin-products') }}#dashboard">Products</a></li>
        <li role="presentation"><a href="{{ route('admin-options') }}#dashboard">Product Options</a></li>
        <li role="presentation"><a href="{{ route('admin-orders') }}#dashboard">Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-backorders') }}#dashboard">Back Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-users') }}#dashboard">Users</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane tab-pane-admin active">
          <div class="form-group form-inline">
            <!--
            <form action='{{ route("product-import-preview") }}' method="post" enctype="multipart/form-data" style="display:inline-block;">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="file" class="file-button" name="csv" id="csv-file-button">
            </form>
            -->

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-inventory">
              <span class="fa fa-barcode" aria-hidden="true"></span>&nbsp; Add Product
            </button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-option">
              <span class="fa fa-plus" aria-hidden="true"></span>&nbsp; Add Product Option
            </button>
            <a href="{{ route('group-product-select-group') }}" class="btn btn-info">
              <span class="fa fa-sitemap" aria-hidden="true"></span>&nbsp; Group Products By Options
            </a>
          </div>
          <div class="table-responsive">
            <table id="products_table" class="table table-striped table-hover tablesorter text-left">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Manufacturer</th>
                  <th>Item #</th>
                  <th>Available</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($products)
                @foreach($products as $product)
                <tr id="{{ $product->id }}">
                  <td><a href="{{ route('product-show', $product->slug) }}">{{ $product->name }}</a></td>
                  <td>{{ $product->category?$product->category->name:'' }}</td>
                  <td>{{ $product->manufacturer }}</td>
                  <td>{{ $product->item_number }}</td>
                  <td>
                    <button id="product-active-{{ $product->id }}" onclick="product_toggle_active({{ $product->id }});" class="btn btn-link">
                      {!! $product->active == 1?'<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon-remove"></span>' !!}
                    </button>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-info" data-toggle="modal" data-target="#order-info" title="{{ $product->name }} Product Information" onclick="product_information('{{ $product->id }}');">
                      <span class="fa fa-info"></span>
                    </button>
                    <a href="{{ route('product-edit',$product->id) }}" class="btn btn-warning" title="Edit {{ $product->name }}">
                      <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('product-delete',$product->id) }}" class="btn btn-danger" title="Remove {{ $product->name }}" onclick="return confirm('Are you sure you want to remove product: {{ str_replace('"', "", str_replace("'", "", $product->name)) }}');">
                      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </a>
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<hr/>
@stop






@section('modals')
  @include('admin.modals.product-add')
  @include('admin.modals.order-info')
  @include('admin.modals.option-add')

@stop
