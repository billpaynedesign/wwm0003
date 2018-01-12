<div class="modal fade" id="edit_category" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="">Edit Category</h4>
      </div>
      <form class="" action="{{ route('category-edit') }}" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <img id="edit_category_img" src="" class="center-block img-responsive" style="max-width:150px;">
          </div>
          <div class="form-group">
            <label for="edit_category_name">Category Name</label>
            <input id="edit_category_name" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edit_image_input">Picture</label>
            <input type="file" id="edit_image_input" name="image">
          </div>
          <div class="form-group">
            <label for="edit_category_description">Description</label>
            <textarea name="description" id="edit_category_description" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="category_id" id="edit_category_id">
          <button type="button" class="btn btn-cancel" data-dismiss="modal" onsubmit="return false;">Close</button>
          <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>