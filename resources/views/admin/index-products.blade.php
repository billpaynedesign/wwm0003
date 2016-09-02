<div class="form-group form-inline">
  <!--
  <form action='{{ route("product-import-preview") }}' method="post" enctype="multipart/form-data" style="display:inline-block;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="file" class="file-button" name="csv" id="csv-file-button">
  </form>
  -->

  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-inventory">
    <span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp; Add Product
  </button>
</div>
<div class="table-responsive">
  <table id="products_table" class="table table-striped table-hover tablesorter text-left">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Slug</th>
        <th>Category</th>
        <th>Manufacturer</th>
        <th>Item #</th>
        <th>Available</th>
        <!--<th>Taxable</th>-->
        <!--<th>Featured</th>-->
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if($products)
      @foreach($products as $product)
      <tr id="{{ $product->id }}">
        <td>{{ $product->name }}</td>
        <td>{{ $product->slug }}</td>
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
