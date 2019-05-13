@extends('layout')

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <h1>Create Quote</h1>
            <form action="{{ route('quote-store') }}" method="post">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="rfq_num">RFQ #:</label>
                        <input type="text" class="form-control" id="rfq_num" name="rfq_num" value="{{ old('rfq_num')?old('rfq_num'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email')?old('email'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_address1">Billing Address:</label>
                        <input type="text" class="form-control" id="billing_address1" name="billing_address1" value="{{ old('billing_address1')?old('billing_address1'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_address2">Billing Address 2:</label>
                        <input type="text" class="form-control" id="billing_address2" name="billing_address2" value="{{ old('billing_address2')?old('billing_address2'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_city">Billing City:</label>
                        <input type="text" class="form-control" id="billing_city" name="billing_city" value="{{ old('billing_city')?old('billing_city'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_state">Billing State:</label>
                        <input type="text" class="form-control" id="billing_state" name="billing_state" value="{{ old('billing_state')?old('billing_state'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_zip">Billing Zip:</label>
                        <input type="text" class="form-control" id="billing_zip" name="billing_zip" value="{{ old('billing_zip')?old('billing_zip'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_address1">Shipping Address:</label>
                        <input type="text" class="form-control" id="shipping_address1" name="shipping_address1" value="{{ old('shipping_address1')?old('shipping_address1'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_address2">Shipping Address 2:</label>
                        <input type="text" class="form-control" id="shipping_address2" name="shipping_address2" value="{{ old('shipping_address2')?old('shipping_address2'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_city">Shipping City:</label>
                        <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="{{ old('shipping_city')?old('shipping_city'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_state">Shipping State:</label>
                        <input type="text" class="form-control" id="shipping_state" name="shipping_state" value="{{ old('shipping_state')?old('shipping_state'):'' }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_zip">Shipping Zip:</label>
                        <input type="text" class="form-control" id="shipping_zip" name="shipping_zip" value="{{ old('shipping_zip')?old('shipping_zip'):'' }}">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Items</label>
                        <table id="cart-table" class="table table-hover table-striped table-bordered text-left">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Option</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Item Total</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4"><strong>Total</strong></th>
                                    <th id="cart-total" class="text-right">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <button type="button" id="add-cart-button" class="btn btn-success" data-toggle="modal" href="#AddCartItem">
                            <span class="fa fa-plus"></span>&nbsp;Add Item
                        </button>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <a href="{{ route('admin-quotes') }}" class="btn btn-cancel">Cancel</a>
                        <button id="quote-submit" type="submit" name="submit" value="true" class="btn btn-default" disabled>Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
                    <div id="cart-add-price-group" class="form-group" style="display:none;">
                        <label for="cart-add-price">Price</label>
                        <input id="cart-add-price" name="price" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <input type="hidden" id="cart-add-product" name="product_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="addToCart">Save changes</button>
                    <a class="btn btn-cancel" data-dismiss="modal">Close</a>
                </div>
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
            {uom}
            <input type="hidden" value="{uom_id}" name="uoms['{id}']">
        </td>
        <td class="text-center">
            {quantity}
            <input type="hidden" value="{quantity}" name="quantities['{id}']">
        </td>
        <td class="text-right">
            {price_string}
            <input type="hidden" value="{price}" name="price['{id}']">
        </td>
        <td class="text-right">
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
    var product_id;
    var uom_id;
    var product;
    var uom;
    $(function(){
        $('#cart-add-uom').on('change',function(){
            uom_id = $(this).find(':selected').val();
            if(product_id !=='' && uom_id !==''){
                let parameters = {
                    product_id: product_id,
                    uom_id: uom_id
                };
                $.get('{{ route('api-product-uom-get-data') }}', parameters, response => response.data)
                    .then(data => {
                        product = data.product;
                        uom = data.uom;
                        $("#cart-add-price-group").show();
                        $("#cart-add-price").val(uom.price);
                    });
            }
        });
        var $selectizeCartEditSearch = $('#cart-edit-search').selectize({
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
                // get the item id and add it to the modal window
                product_id = this.items[0];
                $("#cart-add-product").val(product_id);

                // get the uom select dropdown for this product
				$.get('{{ route('api-get-uom-product-options-html') }}', {product_id: product_id}, function(data){
               		$("#cart-add-uom-group").show();
               		$("#cart-add-quantity-group").show();
                    $("#cart-add-uom").html(data);
				});
            }
        });
        $('#addToCart').click(function(){
            var clone = document.importNode(cartrow.content, true);
            var el = clone.querySelector("tr");

			var quantity = $('#cart-add-quantity').val();

            var thisid = 'row-'+product_id+'-'+uom_id;
            el.id = thisid;

            // $.get('{{ route('api-product-uom-get-data') }}', {product_id: product_id,uom_id: uom_id}, function(data){
            //
            // });
            // make sure our returned price is a float
            let price = parseFloat($("#cart-add-price").val());
            // calculate line total
            let item_total = price*quantity;

            // set all the appropriate data to the template tag
            el.innerHTML = el.innerHTML.replace(/{item_name}/g,product.name);
            el.innerHTML = el.innerHTML.replace(/{product_id}/g,product_id);
            el.innerHTML = el.innerHTML.replace(/{uom_id}/g,uom_id);
            el.innerHTML = el.innerHTML.replace(/{id}/g,thisid);
            el.innerHTML = el.innerHTML.replace(/{price_string}/g,'$'+price.format(2, 3, ',', '.'));
            el.innerHTML = el.innerHTML.replace(/{price}/g,price);
            el.innerHTML = el.innerHTML.replace(/{quantity}/g,quantity);
            el.innerHTML = el.innerHTML.replace(/{uom}/g,uom.name);
            el.innerHTML = el.innerHTML.replace(/{item_total}/g,'$'+item_total.format(2, 3, ',', '.'));

            // keep record of data
            cartrows[thisid] = {
                'product': product,
                'uom': uom,
                'price': price,
                'item_total': item_total
            };

            // add to the table
            carttable.querySelector('tbody').appendChild(clone);

            // re-initialize inputs
            $("#cart-add-uom-group").hide();
            $("#cart-add-quantity-group").hide();
            $("#cart-add-price-group").hide();
            $("#cart-add-uom").html('');
            $('#cart-add-product').val('');
            $('#cart-add-quantity').val('1');
            $("#cart-add-price").val('');

            // reset selectize input
            let control = $selectizeCartEditSearch[0].selectize;
            control.clear();

            // calculate purchase order total
            calculate_total();
		});
    });
    function calculate_total(){
        var total = 0;
        for (var key in cartrows) {
            let thisrow = cartrows[key];
            total += thisrow.item_total;
        }
        let total_string = '$'+total.format(2, 3, ',', '.');
        carttotal.html(total_string);

        if($(carttable).find('tbody tr').length > 0){
            $('#quote-submit').prop('disabled',false);
        }
        else{
            $('#quote-submit').prop('disabled',true);
        }
    }
</script>
@endsection
