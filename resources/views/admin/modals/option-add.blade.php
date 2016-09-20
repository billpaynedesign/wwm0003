<div class="modal fade" id="add-option">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('option.store') }}" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add Product Options</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="option_group_name">Option Group Name</label>
						<input type="text" id="option_group_name" name="option_group_name" class="form-control" />
					</div>
			        <div id="option_groups">
			          <div class="option_single">
			            <hr/>
			            <div class="form-group">
			              <label for="option_names">Option Name</label>
			              <input type="text" id="option_names" name="option_names[]" class="form-control" required />
			            </div>
			          </div>
			        </div>
			        <div class="form-group">
			          <hr/>
			          <a class="btn btn-success" onclick="javascript:add_option();"><span class="fa fa-plus"></span>&nbsp;Add Option</a>
			        </div>
				</div>
				<div class="modal-footer">
					{!! csrf_field() !!}
					<button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-default">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>