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
