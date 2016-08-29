<div class="modal fade" id="add-inventory" tabindex="-1" role="dialog" aria-labelledby="add-inventory" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('product-new') }}" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="add-inventory-title">Add Product</h4>
        </div>
        <div class="modal-body">
         <div class="form-group">
          <label for="productname">Product Name:</label>
          <input type="text" class="form-control" id="productname" name="productname" placeholder="Product Name" value="{{ old('productname')?old('productname'):'' }}" required>
        </div>
        <div class="form-group">
          <label for="category">Category:</label>
          <select name="category" id="category" class="form-control" required>
            <option value="">-- Select One --</option>
            {!! $categoryHelper->htmlSelectOptions() !!}
          </select>
        </div>
        <div class="form-group">
          <label for="item_number">Item #:</label>
          <input type="text" class="form-control" id="item_number" name="item_number" placeholder="Item #" />
        </div>
        <div class="form-group">
          <label for="msrp">MSRP:</label>
          <input type="number" id="msrp" name="msrp" step="0.01" min="0" class="form-control" />
        </div>
        <div class="form-group">
          <label for="price">Price:</label>
          <input type="number" id="price" name="price" step="0.01" min="0" class="form-control" />
        </div>
        <div class="form-group">
          <label for="manufacturer">Manufacturer:</label>
          <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Manufacturer" />
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" id="lot_expiry_check" name="lot_expiry_check" value="1" /> Require Lot Number &amp; Expiry Date?
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" id="require_license" name="require_license" value="1" /> Require customer to have license?
          </label>
        </div>
        <div class="form-group">
          <label for="productshortdescription">Short Description:</label>
          <textarea class="form-control" id="productshortdescription" name="productshortdescription" placeholder="Short Description">{{ old('productshortdescription')?old('productshortdescription'):'' }}</textarea>
        </div>
        <div class="form-group">
          <label for="productdescription">Long Description:</label>
          <textarea class="form-control" id="productdescription" name="productdescription" placeholder="Long Description">{{ old('productdescription')?old('productdescription'):'' }}</textarea>
        </div>
        <div class="form-group">
          <label for="image">Product Picture:</label>
          <input type="file" id="image" name="image">
        </div>
        <div class="form-group">
          <label for="note">Admin Note:</label>
          <textarea type="checkbox" class="form-control" id="note" name="note">{{ old('note')?old('note'):'' }}</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-default">Save changes</button>
      </div>
    </form>
  </div>
</div>
</div>