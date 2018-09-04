<div class="modal fade" id="add-vendor-bill" tabindex="-1" role="dialog" aria-labelledby="add-vendor-bill" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('vendor-bill-create') }}" method="post" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-vendor-bill-title">Add Vendor Bill</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name')?old('name'):'' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="vendor">Vendor:</label>
                        <select id="vendor" name="vendor" class="form-control">
                            <option value="">-- Select Vendor --</option>
                            @foreach (App\Vendor::all() as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
{{--                     vendor_id
date
reference_num
amount
term_id
paid --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-default">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
