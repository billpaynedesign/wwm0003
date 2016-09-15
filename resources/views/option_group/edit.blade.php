@extends('app')

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('#order-edit-search').selectize({
        valueField: 'url',
        labelField: 'name',
        searchField: ['name'],
        maxOptions: 10,
        options: [],
        create: false,
        render: {
            option: function(item, escape) {
              var picturespath  = '{{ asset("/pictures") }}/';
              var noimage = '{{ asset("/images") }}/noimg.gif';
              if(item.picture){
                var picture = picturespath+item.picture;
              }
              else{
                var picture = noimage;
              }
                return '<div><img src="'+picture+'" style="max-width:50px; max-height: 50px; margin-right:5px;">' +item.name+'</div>';
            }
        },
        optgroups: [
            {value: 'product', label: 'Products'},
            {value: 'item_number', label: 'Item-Num'}
        ],
        optgroupField: 'class',
        optgroupOrder: ['product','item_number'],
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: root+'/api/product/add/search',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.data);
                }
            });
        },
        onChange: function(){
            $("#add_product_id").val(this.items);
        }
    });
  });
</script>
@endsection
@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Edit Product Group: {{ $product_group->option_group->name }}</h1>
    <div class="col-xs-12">
      <form action="{{ route('group-product-select-products') }}" method="POST" >
        <div class="form-group">
          <table class="table table-striped table-hover table-border">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Option</th>
                <th>Delete?</th>
              </tr>
            </thead>
            <tbody>
              @foreach($product_group->products as $option_product)
                @if($option = $product_group->option_group->options()->whereHas('products', function($query) use($option_product){ $query->where('product_id', $option_product->id); })->first())
                  <tr>
                    <td>{{ $option_product->name }}</td>
                    <td>{{ $option_product->category?$option_product->category->name:'' }}</td>
                    <td>{{ $option->option }}</td>
                    <td>
                      <form action="{{ route('group-product-option-delete', $product_group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $option_product->name }} from this group?');">
                        <input type="hidden" name="product_id" value="{{ $option_product->id }}" />
                        <input type="hidden" name="option_id" value="{{ $option->id }}" />
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                      </form>
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="form-group">
          @if(!$can_add)
            <p class="help-block text-danger">This group is full with every option available, if you need to add a product please delete one from this group or <a data-toggle="modal" href="#add-option">add another option</a> to this group first.</p>
          @endif
          {!! csrf_field() !!}
          <button type="submit" name="cancel" value="true" class="btn btn">Cancel</button>
          <a class="btn btn-success" data-toggle="modal" href='#add-product' {{ $can_add?'':'disabled' }}>Add Product</a>
          <a class="btn btn-info" data-toggle="modal" href="#add-option">Add Option</a>
          <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="add-product">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('group-product-product-add', $product_group->id) }}" method="POST" role="form">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Add Product</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" id="add_product_id" name="add_product_id" />
            <label for="order-edit-search">Product</label>
            <select id="order-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
            <label for="option_id">Option</label>
            <select id="option_id" name="option_id" class="form-control" required>
              <option value="">-- Select an option --</option>
              @if($select_options)
                @foreach($select_options as $option)
                    <option value="{{ $option->id }}">{{ $option->option }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="modal-footer">
          {!! csrf_field() !!}
          <button type="button" class="btn" data-dismiss="modal">Close</button>
          <button type="type" class="btn btn-default">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="add-option">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('group-product-option-add', $product_group->option_group_id) }}" method="POST" role="form">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Add Option</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="option_add">Option Name</label>
            <input type="text" id="option_add" name="option_add" class="form-control" />
          </div>
        </div>
        <div class="modal-footer">
          {!! csrf_field() !!}
          <button type="button" class="btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-default">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
