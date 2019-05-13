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
                        <select name="category[]" id="category" class="form-control" required multiple>
                            {!! $categoryHelper->htmlSelectOptions() !!}
                        </select>
                        <p class="help-block">To select multiple categories, hold down the CTRL or SHIFT key while selecting</p>
                    </div>
                    <div class="form-group">
                        <label for="item_number">Item #:</label>
                        <input type="text" class="form-control" id="item_number" name="item_number" placeholder="Item #" />
                    </div>
                    <div id="uom_groups">
                        <div class="uom_single">
                            <hr />
                            <div class="form-group">
                                <label for="uom">Unit of Measure</label>
                                <input type="text" id="uom" name="uom[]" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="msrp">MSRP:</label>
                                <input type="number" id="msrp" name="msrp[]" step="0.01" min="0" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="number" id="price" name="price[]" step="0.01" min="0" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Weight:</label>
                                <div class="input-group input-group-select">
                                    <input type="number" name="weight[]" step="0.01" min="0" class="form-control" />
                                    <select class="form-control" name="weight_unit[]">
                                        <option value="lb">lb</option>
                                        <option value="oz">oz</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <hr />
                        <a class="btn btn-success" onclick="javascript:add_uom();"><span class="fa fa-plus"></span>&nbsp;Add Unit of Measure</a>
                        <hr />
                    </div>
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer:</label>
                        <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Manufacturer" />
                    </div>
                    <div class="form-group">
                        <label for="vendors">Vendors:</label>
                        <select id="vendors" name="vendors[]" class="form-control" multiple>
                            @foreach (App\Vendor::all() as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        <p class="help-block">To select multiple categories, hold down the CTRL or SHIFT key while selecting</p>
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
                        <label for="productshortdescription">Overview:</label>
                        <textarea class="form-control" id="productshortdescription" name="productshortdescription" placeholder="Overview">{{ old('productshortdescription')?old('productshortdescription'):'' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="productdescription">Details:</label>
                        <textarea class="form-control" id="productdescription" name="productdescription" placeholder="Details">{{ old('productdescription')?old('productdescription'):'' }}</textarea>
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
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-default">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
