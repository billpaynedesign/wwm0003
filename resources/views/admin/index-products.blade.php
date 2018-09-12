@extends('layout')


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
  $('#products_table').DataTable({
    searchDelay: 500,
    serverSide: true,
    ajax: '{{ route('admin-products') }}',
    stateSave: true,
    stateDuration: 1800,
    "columns": [
      {
        "data": "name",
        "name": "name"
      },
      {
        "data": "manufacturer",
        "name": "manufacturer"
      },
      {
        "data": "item_number",
        "name": "item_number"
      },
      {
        "data": "active",
        "name": "active"
      },
      {
        "data": "action",
        "name": "action",
        "orderable":false,
        "searchable":false,
      },
    ]

  });
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
function product_toggle_featured(id){
  $.get('{{ route("product-toggle-featured") }}',{id:id},function(data){
    var html = (data.response == 1)?'<span class="text-yellow glyphicon glyphicon glyphicon-star"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#product-featured-'+data.id).html(html);
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
  var html = '<div class="uom_single"> <hr/> <div class="form-group"> <button class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-trash"></span></button> </div> <div class="form-group"> <label for="uom">Unit of Measure</label> <input type="text" id="uom" name="uom[]" class="form-control" required /> </div> <div class="form-group"> <label for="msrp">MSRP:</label> <input type="number" id="msrp" name="msrp[]" step="0.01" min="0" class="form-control" required /> </div> <div class="form-group"> <label for="price">Price:</label> <input type="number" id="price" name="price[]" step="0.01" min="0" class="form-control" required /> </div> <div class="form-group"> <label>Weight:</label> <div class="input-group input-group-select"> <input type="number" name="weight[]" step="0.01" min="0" class="form-control" /> <select class="form-control" name="weight_unit[]"> <option value="lb">lb</option> <option value="oz">oz</option> </select> </div> </div> </div>'
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
<div id="row-main" class="row">
  <div id="container-main" class="container-fluid">
    <div id="col-main" class="col-xs-12">
      <div class="page-header">
        <h1 id="dashboard">Admin Dashboard</h1>
      </div>
      <div id="admin_tab_panel" role="tabpanel">

        @include('admin.partials.nav-tabs', ["adminActive"=>'Products'])

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
              <table id="products_table" class="table table-striped table-hover text-left">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Manufacturer</th>
                    <th>Item #</th>
                    <th>Available</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop






@section('modals')
  @include('admin.modals.product-add')
  @include('admin.modals.order-info')
  @include('admin.modals.option-add')
@stop
