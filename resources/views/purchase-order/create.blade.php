@extends('layout')

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <h1>Create Purchase Order</h1>
            <form action="{{ route('vendor-purchase-order-store') }}" method="post">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendor">Vendor:</label>
                        <select id="vendor" name="vendor" class="form-control">
                            <option value="">-- Select Vendor --</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Items</label>
                        <table id="cart-table" class="table table-hover table-striped table-bordered text-left">
                            <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Options</th>
                                <th>Item Total</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4"><strong>Total</strong></th>
                                <th id="cart-total">$0.00</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <a id="add_cart_button" class="btn btn-success" data-toggle="modal" href="#AddCartItem"><span class="fa fa-plus"></span>&nbsp;Add Item</a>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <a href="{{ route('admin-vendors') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!--[if IE]>
<script src="<?= asset( 'js/modernizr-custom-template.js' ); ?>"></script>
<![endif]-->
<template id="cart-row">
    <tr>
        <td>
            {item_name}
            <input type="hidden" value="{product_id}" name="products['{id}']">
        </td>
        <td>
            {price}
        </td>
        <td>
            {quantity}
            <input type="hidden" value="{quantity}" name="quantities['{id}']">
        </td>
        <td>
            {uom}
            <input type="hidden" value="{uom_id}" name="uoms['{id}']">
        </td>
        <td>
            {item_total}
        </td>
        {{-- <td>{{action}}</td> --}}
    </tr>
</template>
<script type="text/javascript">
    Number.prototype.format = function(n, x, s, c) {
      var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
      num = this.toFixed(Math.max(0, ~~n));

      return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };
    var cartrow = document.querySelector('#cart-row');
    var carttable = document.querySelector('#cart-table');
    var cartrows = {};
    var carttotal = $('#cart-total');
    $(function(){
        $('#cart-edit-search').selectize({
	        valueField: 'url',
	        labelField: 'name',
            searchField: ['name','item_number'],
	        maxOptions: 1000,
	        options: [],
	        create: false,
	        render: {
	            option: function(item, escape) {
	            	var picturespath  = '{{ asset("/pictures") }}/';
	            	var noimage = '{{ asset("/images") }}/noimg.gif';
	            	if(item.picture){
	            		var picture = picturespath+item.picture;
	            	}
	            	else{
	            		var picture = noimage;
	            	}
	            	item.name = item.name+' - #'+item.item_number;
	                return '<div><img src="'+picture+'" style="max-width:50px; max-height: 50px; margin-right:5px;">' +item.name+'</div>';
	            }
	        },
	        optgroups: [
	            {value: 'product', label: 'Products'}
	        ],
	        optgroupField: 'class',
	        optgroupOrder: ['product'],
	        load: function(query, callback) {
	            if (!query.length) return callback();
	            $.ajax({
	                url: root+'/api/product/add/search',
	                type: 'GET',
	                dataType: 'json',
	                data: {
	                    q: query
	                },
	                error: function() {
	                    callback();
	                },
	                success: function(res) {
	                    callback(res.data);
	                }
	            });
	        },
            onChange: function(value){
                $("#cart-add-product").val(this.items[0]);

				$.get('{{ route('api-get-uom-product-options-html') }}', {product_id: this.items[0]}, function(data){
               		$("#cart-add-uom-group").show();
               		$("#cart-add-quantity-group").show();
                    $("#cart-add-uom").html(data);
				});
            }
        });
        $('#addToCart').click(function(){
            var clone = document.importNode(cartrow.content, true);
            var el = clone.querySelector("tr");

            var product_id = $('#cart-add-product').val();
			var uom_id = $('#cart-add-uom').find(':selected').val();
			var quantity = $('#cart-add-quantity').val();

            var thisid = 'row-'+product_id+'-'+uom_id;
            el.id = thisid;

            $.get('{{ route('api-product-uom-get-data') }}', {product_id: product_id,uom_id: uom_id}, function(data){
                el.innerHTML = el.innerHTML.replace(/{item_name}/g,data.product.name);
                let price = new Number(data.uom.price);
                let item_total = price*quantity;

                el.innerHTML = el.innerHTML.replace(/{product_id}/g,product_id);
                el.innerHTML = el.innerHTML.replace(/{uom_id}/g,uom_id);
                el.innerHTML = el.innerHTML.replace(/{id}/g,thisid);
                el.innerHTML = el.innerHTML.replace(/{price}/g,'$'+price.format(2, 3, ',', '.'));
                el.innerHTML = el.innerHTML.replace(/{quantity}/g,quantity);
                el.innerHTML = el.innerHTML.replace(/{uom}/g,data.uom.name);
                el.innerHTML = el.innerHTML.replace(/{item_total}/g,'$'+item_total.format(2, 3, ',', '.'));

                cartrows[thisid] = {
                    'product': data.product,
                    'uom': data.uom,
                    'price': price,
                    'item_total': item_total
                };


                carttable.querySelector('tbody').appendChild(clone);

                $("#cart-add-uom-group").hide();
                $("#cart-add-quantity-group").hide();
                $("#cart-add-uom").html('');
                $('#cart-add-product').val('');
                $('#cart-add-quantity').val('1');

                calculate_total();
            });
		});
    });
    function calculate_total(){
        var total = 0;
        for (var key in cartrows) {
            let thisrow = cartrows[key];
            total += thisrow.item_total;
        }
        carttotal.html('$'+total.format(2, 3, ',', '.'));
    }
</script>
@endsection

@section('modals')
    <div class="modal fade" id="AddCartItem">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cart-edit-search">Product</label>
                        <select id="cart-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
                    </div>
                    <div id="cart-add-uom-group" class="form-group" style="display:none;">
                        <label for="cart-add-uom">UOM</label>
                        <select id="cart-add-uom" name="uom_id" class="form-control"></select>
                    </div>
                    <div id="cart-add-quantity-group" class="form-group" style="display:none;">
                        <label for="cart-add-quantity">Quantity</label>
                        <input id="cart-add-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <input type="hidden" id="cart-add-product" name="product_id" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="addToCart">Save changes</button>
                    <a class="btn btn-cancel" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
@endsection
