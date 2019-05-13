<div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="">New Category</h4>
      </div>
      <form class="" action="{{ route('category-create') }}" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="category_name">Name</label>
            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="" required>
          </div>
          <div class="form-group">
            <label for="parent_category">Parent</label>
            <select name="parent_category" id="parent_category" class="form-control" onchange="parent_category_change();">
              <option value="">-- None --</option>
              {!! $categoryHelper->htmlSelectOptions() !!}
            </select>
          </div>
          <div class="form-group">
            <label for="image">Picture</label>
            <input type="file" id="image" name="image">
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal" onsubmit="return false;">Close</button>
          <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>