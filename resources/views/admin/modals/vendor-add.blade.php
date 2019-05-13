<div class="modal fade" id="add-vendor" tabindex="-1" role="dialog" aria-labelledby="add-vendor" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('vendor-create') }}" method="post" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-vendor-title">Add Vendor</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name')?old('name'):'' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email')?old('email'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone')?old('phone'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="attn">ATTN:</label>
                        <input type="text" class="form-control" id="attn" name="attn" value="{{ old('attn')?old('attn'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address')?old('address'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2:</label>
                        <input type="text" class="form-control" id="address2" name="address2" value="{{ old('address2')?old('address2'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city')?old('city'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="state">State:</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state')?old('state'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="zip">Zip:</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip')?old('zip'):'' }}">
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
