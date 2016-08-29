@extends('app')
<?php $translate_common_headers = array();
$select_list = array(
	'do_not_include'=>'Do Not Include',
	'name' => 'Product Name',
	'sku' => 'SKU',
	'price' => 'Price',
	'msrp' => 'MSRP',
	'category' => 'Category',
	'picture' => 'Picture',
	'attributes' => 'Attributes',
	'description' => 'Description',
	'inStock' => 'In Stock',
	'note' => 'Note'
	);
?>
@section('content')
<div class="page-header">
  <h3>
    Product Import - Preview and Select Columns
  </h3>
</div>
<form action="{{ route('product-import-upload') }}" method="post" role="form">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="table-responsive" style="max-height: 600px;">
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					@if(array_key_exists(0,$csv))
					@foreach($csv[0] as $key => $value)
					<th>
						<select name="columns[]" class="column_select">
							@foreach($select_list as $value => $option)
								<option value="{{ $value }}">{{ $option }}</option>
							@endforeach
						</select>
						<br/>{{ $key }}
					</th>
					@endforeach
					@endif
				</tr>
			</thead>
			<tbody>
				@foreach($csv as $row)
				<tr>
					@foreach($row as $column)
						<td>{{ $column }}</td>
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<button type="submit" class="btn btn-default">Submit</button>
</form>
@endsection
