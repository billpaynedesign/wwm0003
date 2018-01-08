@extends('layout')

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#add_order_line').on('click',function(){
			var original = {{ $line->quantity }};
			var total = 0;
			$('.qty').each(function(){
				total += parseInt($(this).val());
			});
			console.log(total, original);
			if(total < original){
				var html = $('#order_lines tbody tr:last-child').clone();
				$('#order_lines tbody').append(html);
				$('#order_lines tbody tr:last-child .qty').val(1);
				$('#order_lines tbody tr:last-child .qty').on('change',function(){
					update_total();
				});
				update_total();
			}
			else{
				alert('You are trying to split into too many lines. First take away quantity before trying to add another line.');
			}
			return false;
		});
		$('.qty').on('change',function(){
			update_total();
		});

		update_total();
	});
	function update_total(){
		var total = 0;
		$('.qty').each(function(){
			total += parseInt($(this).val());
		});
		$('#total').html(total);
	}
</script>
@stop

@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Split Line</h1>
			<p><strong>Item:</strong> {{ $line->product->name }}</p>
			<p><strong>Original Quantity:</strong> {{ $line->quantity }}</p>
			<form action="{{ route('order-edit-line-update',$line->id) }}" method="post" role="form">
				<table id="order_lines" class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Item</th>
							<th>Qty</th>
							<th>Price</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@if(old('line'))
							@foreach(old('line') as $value)
								<tr>
									<td>{{ $line->product->name }}</td>
									<td><input type="number" step="1" name="line[]" min="1" max="{{ $line->quantity }}" class="form-control qty" value="{{ $value }}"/></td>
									<td>{{ $line->product->price_string }}</td> 
									<td><button class="btn btn-danger" onclick="$(this).parent().parent().remove(); update_total(); return false;"><span class="glyphicon glyphicon-minus"></span></button></td>
								</tr>
							@endforeach
						@else
						<tr>
							<td>{{ $line->product->name }}</td>
							<td><input type="number" step="1" name="line[]" min="1" max="{{ $line->quantity }}" class="form-control qty" value="1"/></td>
							<td>{{ $line->product->min_price_string }}</td>
							<td><button class="btn btn-danger" onclick="$(this).parent().parent().remove(); update_total(); return false;"><span class="glyphicon glyphicon-minus"></span></button></td>
						</tr>
						@endif
					</tbody>
					<tfoot>
						<tr>
							<td><button id="add_order_line" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button></td>
							<td id="total" colspan="10"></td>
						</tr>
					</tfoot>	
				</table>
				<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
			</form>
		</div>
	</div>
</div>
@stop