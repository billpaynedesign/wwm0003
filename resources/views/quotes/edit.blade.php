@extends('layout')

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <div class="col-xs-12">
                <h1>Edit Quote # {{ $quote->quote_num }}</h1>
            </div>

            <form id="edit-form" action="{{ route('quote-update',$quote->id) }}" method="post">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="rfq_num">RFQ #:</label>
                        <input type="text" class="form-control" id="rfq_num" name="rfq_num" value="{{ old('rfq_num')?old('rfq_num'):$quote->rfq_num }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email')?old('email'):$quote->email }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_address1">Billing Address:</label>
                        <input type="text" class="form-control" id="billing_address1" name="billing_address1" value="{{ old('billing_address1')?old('billing_address1'):$quote->billing_address1 }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_address2">Billing Address 2:</label>
                        <input type="text" class="form-control" id="billing_address2" name="billing_address2" value="{{ old('billing_address2')?old('billing_address2'):$quote->billing_address2 }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_city">Billing City:</label>
                        <input type="text" class="form-control" id="billing_city" name="billing_city" value="{{ old('billing_city')?old('billing_city'):$quote->billing_city }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_state">Billing State:</label>
                        <input type="text" class="form-control" id="billing_state" name="billing_state" value="{{ old('billing_state')?old('billing_state'):$quote->billing_state }}">
                    </div>
                    <div class="form-group">
                        <label for="billing_zip">Billing Zip:</label>
                        <input type="text" class="form-control" id="billing_zip" name="billing_zip" value="{{ old('billing_zip')?old('billing_zip'):$quote->billing_zip }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_address1">Shipping Address:</label>
                        <input type="text" class="form-control" id="shipping_address1" name="shipping_address1" value="{{ old('shipping_address1')?old('shipping_address1'):$quote->shipping_address1 }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_address2">Shipping Address 2:</label>
                        <input type="text" class="form-control" id="shipping_address2" name="shipping_address2" value="{{ old('shipping_address2')?old('shipping_address2'):$quote->shipping_address2 }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_city">Shipping City:</label>
                        <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="{{ old('shipping_city')?old('shipping_city'):$quote->shipping_city }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_state">Shipping State:</label>
                        <input type="text" class="form-control" id="shipping_state" name="shipping_state" value="{{ old('shipping_state')?old('shipping_state'):$quote->shipping_state }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_zip">Shipping Zip:</label>
                        <input type="text" class="form-control" id="shipping_zip" name="shipping_zip" value="{{ old('shipping_zip')?old('shipping_zip'):$quote->shipping_zip }}">
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="current-items">
                                @foreach($quote->products as $detail)
                                    <tr id="oldrow-{{ $detail->id }}">
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->uom->name }}</td>
                                        <td class="text-center quantity-cell">
                                            <span>{{ $detail->quantity }}</span>
                                            <input type="hidden" value="{{ $detail->quantity }}" name="old_quantities[{{ $detail->id }}]">
                                        </td>
                                        <td class="text-right price-cell">
                                            <span>${{ number_format($detail->price,2) }}</span>
                                            <input type="hidden" value="{{ $detail->price }}" name="old_price[{{ $detail->id }}]">
                                        </td>
                                        <td class="text-right itemtotal-cell">${{ number_format($detail->item_total,2) }}</td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning"
                                                title="Edit row"
                                                data-toggle="modal"
                                                data-target="#edit-modal"
                                                data-detailid="{{ $detail->id }}">
                                                <span class="fa fa-edit"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" title="Delete row" onclick="delete_old_row({{ $detail->id }})">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tbody id="new-items">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4"><strong>Total</strong></th>
                                    <th id="cart-total" class="text-right">${{ number_format($quote->total,2) }}</th>
                                    <th></th>
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
                        <button id="quote-submit" type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
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
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cart-edit-quantity">Quantity</label>
                        <input id="cart-edit-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <div id="cart-edit-price-group" class="form-group">
                        <label for="cart-edit-price">Price</label>
                        <input id="cart-edit-price" name="price" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="cart-edit-detailid" value="">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="edit-modal-submit">Save changes</button>
                    <a class="btn btn-cancel" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-new-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cart-edit-new-quantity">Quantity</label>
                        <input id="cart-edit-new-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <div id="cart-edit-new-price-group" class="form-group">
                        <label for="cart-edit-new-price">Price</label>
                        <input id="cart-edit-new-price" name="price" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="cart-edit-new-detailid" value="">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="edit-new-modal-submit">Save changes</button>
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
        <td class="quantity-cell text-center">
            <span>{quantity}</span>
            <input type="hidden" value="{quantity}" name="quantities['{id}']">
        </td>
        <td class="price-cell text-right">
            <span>{price_string}</span>
            <input type="hidden" value="{price}" name="price['{id}']">
        </td>
        <td class="itemtotal-cell text-right">
            {item_total}
        </td>
        <td>
            <button
                type="button"
                class="btn btn-warning"
                title="Edit row"
                data-toggle="modal"
                data-target="#edit-new-modal"
                data-detailid="{id}">
                <span class="fa fa-edit"></span>
            </button>
            <button type="button" class="btn btn-danger" title="Delete row" onclick="delete_new_row('{id}')">
                <span class="fa fa-trash"></span>
            </button>
        </td>
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
            el.innerHTML = el.innerHTML.replace(/{detailid}/g,thisid);

            // keep record of data
            cartrows[thisid] = {
                'product': product,
                'uom': uom,
                'price': price,
                'item_total': item_total,
            };
            // add to the table
            carttable.querySelector('tbody#new-items').appendChild(clone);

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
        $('#edit-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var detail_id = button.data('detailid');

            var quantity = $(`#oldrow-${detail_id} .quantity-cell input`).val();
            var price = parseFloat($(`#oldrow-${detail_id} .price-cell input`).val());

            $('#cart-edit-detailid').val(detail_id);
            $('#cart-edit-quantity').val(quantity);
            $('#cart-edit-price').val(price);
        });
        $('#edit-modal-submit').click(function(event){
            var button = $(event.relatedTarget);
            var detail_id = $('#cart-edit-detailid').val();

            var quantity = $('#cart-edit-quantity').val();
            var price = parseFloat($('#cart-edit-price').val());

            $(`#oldrow-${detail_id} .quantity-cell input`).val(quantity);
            $(`#oldrow-${detail_id} .price-cell input`).val(price);

            $(`#oldrow-${detail_id} .quantity-cell span`).text(quantity);
            $(`#oldrow-${detail_id} .price-cell span`).html('$'+price.format(2, 3, ',', '.'));

            $('#cart-edit-detailid').val('');
            $('#cart-edit-quantity').val('');
            $("#cart-edit-price").val('');

            calculate_total();
        });
        $('#edit-new-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var detail_id = button.attr('data-detailid');

            var quantity = $(`#${detail_id} [name="quantities['${detail_id}']"]`).val();
            var price = parseFloat($(`#${detail_id} [name="price['${detail_id}']"]`).val());

            $('#cart-edit-new-detailid').val(detail_id);
            $('#cart-edit-new-quantity').val(quantity);
            $('#cart-edit-new-price').val(price);
        });
        $('#edit-new-modal-submit').click(function(event){
            var button = $(event.relatedTarget);
            var detail_id = $('#cart-edit-new-detailid').val();

            var quantity = $('#cart-edit-new-quantity').val();
            var price = parseFloat($('#cart-edit-new-price').val());

            $(`#${detail_id} .quantity-cell input`).val(quantity);
            $(`#${detail_id} .price-cell input`).val(price);

            $(`#${detail_id} .quantity-cell span`).text(quantity);
            $(`#${detail_id} .price-cell span`).html('$'+price.format(2, 3, ',', '.'));

            $('#cart-edit-new-detailid').val('');
            $('#cart-edit-new-quantity').val('');
            $("#cart-edit-new-price").val('');

            calculate_total();
        });

        calculate_total();
    });
    function calculate_total(){
        var total = 0;
        $('#current-items tr').each(function(index,element){
            var quantity = parseInt($(element).find('.quantity-cell input').val());
            var price = parseFloat($(element).find('.price-cell input').val());
            var itemtotal = quantity*price;
            $(element).find('.itemtotal-cell').html('$'+itemtotal.format(2, 3, ',', '.'));
            total += itemtotal;
        });
        $('#new-items tr').each(function(index,element){
            var quantity = parseInt($(element).find('.quantity-cell input').val());
            var price = parseFloat($(element).find('.price-cell input').val());
            var itemtotal = quantity*price;
            $(element).find('.itemtotal-cell').html('$'+itemtotal.format(2, 3, ',', '.'));
            total += itemtotal;
        });

        let total_string = '$'+total.format(2, 3, ',', '.');
        carttotal.html(total_string);

        if(total > 0){
            $('#quote-submit').prop('disabled',false);
        }
        else{
            $('#quote-submit').prop('disabled',true);
        }
    }
    function delete_new_row(row_id){
        document.querySelector(`#${row_id}`).remove();
        delete cartrows[row_id];
        calculate_total();
    }
    function delete_old_row(row_id){
        document.querySelector(`#oldrow-${row_id}`).remove();
        document.querySelector('#edit-form').innerHTML += `<input type='hidden' name='deletedetails[]' value='${row_id}'>`;

        calculate_total();
    }
</script>
@endsection
