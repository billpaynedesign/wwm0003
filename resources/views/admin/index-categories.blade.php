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
  $('#categories_table').DataTable({
    searchDelay: 500,
    serverSide: true,
    ajax: '{{ route('admin-categories') }}',
    stateSave: true,
    stateDuration: 1800,
    order: [[ 3, "asc" ]],
    "columns": [
      {
        "data": "id",
        "name": "id",
        "render": function ( data, type, full, meta ) {
          return '<input type="checkbox" class="action" value="'+data+'">';
        },
      },
      {
        "data": "name",
        "name": "name"
      },
      {
        "data": "parent.name",
        "name": "parent.name",
        "render": function ( data, type, full, meta ) {
          return data == null ? "" :data;
        },
        "orderable":false,
        "searchable":false,
      },
      {
        "data": "featured",
        "name": "featured"
      },
      {
        "data": "active",
        "name": "active"
      },
      {
        "data": "items",
        "name": "items",
        "orderable":false,
        "searchable":false,
      },
      {
        "data": "action",
        "name": "action",
        "orderable":false,
        "searchable":false,
      },
    ]
              
  });
  $('#edit_category').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var name = button.data('name');
    var img = button.data('img');
    var category_id = button.data('category-id');
    var description = button.data('description');

    var modal = $(this)
    modal.find('#edit_category_name').val(name);
    modal.find('#edit_category_img').attr('src',img);
    modal.find('#edit_category_id').val(category_id);
    modal.find('#edit_category_description').val(description);
  });
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
<div id="row-main" class="row">
  <div id="container-main" class="container-fluid">
    <div id="col-main" class="col-xs-12">
      <div class="page-header">
        <h1 id="dashboard">Admin Dashboard</h1>
      </div>
      <div id="admin_tab_panel" role="tabpanel">

        @include('admin.partials.nav-tabs', ["adminActive"=>'Categories'])

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
                    <th>Parent</th>
                    <th>Top Category</th>
                    <th>Active</th>
                    <th>Items</th>
                    <th>Edit</th>
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
  @include('admin.modals.category-add')
  @include('admin.modals.category-edit')
@stop
