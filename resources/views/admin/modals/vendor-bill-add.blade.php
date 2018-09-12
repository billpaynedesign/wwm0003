<div class="modal fade" id="add-vendor-bill" tabindex="-1" role="dialog" aria-labelledby="add-vendor-bill" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('vendor-bill-create') }}" method="post" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-vendor-bill-title">Add Vendor Bill</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name/Memo:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name')?old('name'):'' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor">Vendor:</label>
                            <select id="vendor" name="vendor" class="form-control">
                                <option value="">-- Select Vendor --</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Due Date:</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date')?old('date'):'' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="account">Account</label>
                            <select id="account" name="account" class="form-control" required>
                                <option value="">-- Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                                <option value="other">+ Add New</option>
                            </select>
                        </div>
                        <div id="new-account-group" class="form-group" style="display:none;">
                            <label for="account_name">New Account:</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name')?old('account_name'):'' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference_num">Reference Num:</label>
                            <input type="text" class="form-control" id="reference_num" name="reference_num" value="{{ old('reference_num')?old('reference_num'):'' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" class="form-control" step="0.01" min="0" id="amount" name="amount" value="{{ old('amount')?old('amount'):'' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_terms">Terms:</label>
                            <select id="payment_terms" name="payment_terms" class="form-control" required>
                                <option value="">-- Select Terms --</option>
                                @foreach ($payment_terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="paid">
                                    <input type="checkbox" id="paid" name="paid" value="on"> Mark bill as paid?
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-default">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
