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
  $('#categories_table').DataTable({"order": [[ 3, "asc" ]]});
});
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

function category_removal(){
  if(confirm('Are you sure you would like to remove the selected categories?')){
    $('#categories_table tr .action:checked').each(function(){
      var id = $(this).val();
      $.post('{{ route("category-delete") }}',{id:id},function(data){
        if(data.response = 'success'){
          $('tr#'+id).remove();
        }
      });
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
</script>
@stop

@section('content')
<div class="container-fluid main-container no-padding">
  <div class="col-xs-12 main-col">
    <div class="page-header">
      <h1 id="dashboard">Admin Dashboard</h1>
    </div>
    <div id="admin_tab_panel" role="tabpanel">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="{{ route('admin-dashboard') }}#dashboard">Home</a></li>
        <li role="presentation" class="active"><a href="{{ route('admin-categories') }}#dashboard">Categories</a></li>
        <li role="presentation"><a href="{{ route('admin-products') }}#dashboard">Products</a></li>
        <li role="presentation"><a href="{{ route('admin-options') }}#dashboard">Product Options</a></li>
        <li role="presentation"><a href="{{ route('admin-orders') }}#dashboard">Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-backorders') }}#dashboard">Back Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-users') }}#dashboard">Users</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane tab-pane-admin active">
          <div class="form-group form-inline">
            <button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#add_category">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp; Add Category
            </button>

            <button type="button" class="btn btn-danger" onclick="category_removal();" id="category_removal_btn">
              <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp; Remove Category
            </button>
          </div>

          <div class="table-responsive text-left">
            <table id="categories_table" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Action</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Parent</th>
                  <th>Featured</th>
                  <th>Active</th>
                  <th>Items</th>
                </tr>
              </thead>
              <tbody>
                @if($categories)
                @foreach($categories as $category)
                <tr id="{{ $category->id }}">
                  <td><input type="checkbox" class="action" value="{{ $category->id }}"></td>
                  <td>{{ $category->name }}</td>
                  <td>{{ $category->slug }}</td>
                  <td>{{ $category->parent?$category->parent->name:'' }}</td>
                  <td>
                    <button id="category-featured-{{ $category->id }}" onclick="category_toggle_featured({{ $category->id }});" class="btn btn-link">
                      {!! $category->featured == 1?'<span class="text-yellow glyphicon glyphicon-star"></span>':'<span class="text-danger glyphicon glyphicon-remove"></span>' !!}
                    </button>
                  </td>
                  <td>
                    <button id="category-active-{{ $category->id }}" onclick="category_toggle_active({{ $category->id }});" class="btn btn-link">
                      {!! $category->active == 1?'<span class="text-success glyphicon glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon-remove"></span>' !!}
                    </button>
                  </td>
                  <td>{{ $category->getProductsCount() }}</td>
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
  @include('admin.modals.category-add')
@stop
