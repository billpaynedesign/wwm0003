@foreach($cart as $row)
    @if($row->product && $row->uom)
        <tr>
            <td><a href="{{ route('product-show', $row->product->slug) }}">{{ $row->product->name }}</a></td>
            <td>${{ number_format($row->cost,2) }}</td>
            <td>{{ $row->quantity }}</td>
            <td>{{ $row->uom->name }}</td>
            <td>${{ number_format($row->sub_total,2) }}</td>
			<td>
				<button id="edit_button_{{ $row->id }}" class="btn btn-info" onclick="edit('{{ $row->id }}');" title="Edit Quantity">
					<span class="glyphicon glyphicon-edit"></span>
				</button>
				<div id="{{ $row->id }}" class="form-inline form hide pull-left">
					<div class="input-group">
						<input type="number" name="quantity" id="quantity-{{ $row->id }}" value="{{ $row->quantity }}" class="form-control" min="1" max-width="5" />
						<span class="input-group-btn">
							<button type="button" onclick="update_item({{ $row->id }})" class="btn btn-warning" title="Submit Quantity"><span class="glyphicon glyphicon-edit"></span></button>
						</span>
					</div>
				</div>
				<button class="btn btn-danger" onclick="delete_item({{ $row->id }});" title="Delete Item">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</td>
		</tr>
	@endif
@endforeach