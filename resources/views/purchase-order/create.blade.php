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
                        <select id="vendor" name="vendor" class="form-control" required>
                            <option value="">-- Select Vendor --</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
						@if($vendors->count()===0)
							<div class="alert alert-warning">
								<a href="{{ route('admin-vendors') }}" target="_blank" rel="nofollow noreferrer">Add a vendor</a> first.
							</div>
						@endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Items</label>
                        <table id="cart-table" class="table table-hover table-striped table-bordered text-left">
                            <thead>
                                <tr>
                                    <th>Reorder #</th>
                                    <th>Product Name</th>
                                    {{-- <th>Item Number</th> --}}
                                    <th>Option</th>
                                    <th>Note</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Item Total</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6"><strong>Total</strong></th>
                                    <th id="cart-total" class="text-right">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <button type="button" id="add-cart-button" class="btn btn-success" data-toggle="modal" href="#AddCartItem" disabled>
                            <span class="fa fa-plus"></span>&nbsp;Add Item
                        </button>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <a href="{{ route('admin-vendors') }}" class="btn btn-cancel">Cancel</a>
                        <button id="purchase-order-submit" type="submit" name="submit" value="true" class="btn btn-default" disabled>Submit</button>
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
                        <label for="cart-add-reordernum">Reorder #</label>
                        <input id="cart-add-reordernum" name="reordernum" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cart-edit-search">Product</label>
                        <select id="cart-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
                    </div>
                    <div id="cart-add-uom-group" class="form-group" style="display:none;">
                        <label for="cart-add-uom">UOM</label>
                        <select id="cart-add-uom" name="uom_id" class="form-control"></select>
                    </div>
                    <div id="cart-add-vendor-cost-group" class="form-group" style="display:none;">
                        <label for="cart-add-vendor-cost">Vendor Cost</label>
                        <input id="cart-add-vendor-cost" name="vendor_cost" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <div id="cart-add-quantity-group" class="form-group" style="display:none;">
                        <label for="cart-add-quantity">Quantity</label>
                        <input id="cart-add-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cart-add-note">Note</label>
                        <input id="cart-add-note" name="note" class="form-control">
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


@section('scripts')
<!--[if IE]>
<script src="<?= asset( 'js/modernizr-custom-template.js' ); ?>"></script>
<![endif]-->
<template id="cart-row">
    <tr>
        <td>
            {reordernum}
            <input type="hidden" value="{reordernum}" name="reordernums['{id}']">
        </td>
        <td>
            {item_name}
            <input type="hidden" value="{product_id}" name="products['{id}']">
        </td>
        <td>
            {uom}
            <input type="hidden" value="{uom_id}" name="uoms['{id}']">
        </td>
        <td>
            {note}
            <input type="hidden" value="{note}" name="notes['{id}']">
        </td>
        <td class="text-center">
            {quantity}
            <input type="hidden" value="{quantity}" name="quantities['{id}']">
        </td>
        <td class="text-right">
            {cost_string}
            <input type="hidden" value="{cost}" name="cost['{id}']">
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
    var vendor_id;
    var product;
    var uom;
    $(function(){
        // on vendor change check if vendor is selected and if so allow item adding
        $('#vendor').on('change',function(){
            vendor_id = $(this).find(':selected').val();
            if(vendor_id ===''){
                $('#add-cart-button').prop('disabled',true);
            }
            else{
                $('#add-cart-button').prop('disabled',false);
            }
        });
        $('#cart-add-uom').on('change',function(){
            uom_id = $(this).find(':selected').val();
            if(product_id !=='' && uom_id !=='' && vendor_id !==''){
                let parameters = {
                    product_id: product_id,
                    uom_id: uom_id,
                    vendor_id: vendor_id
                };
                $.get('{{ route('api-product-uom-vendor-get-data') }}', parameters, response => response.data)
                    .then(data => {
                        product = data.product;
                        uom = data.uom;
                        $("#cart-add-vendor-cost-group").show();
                        $("#cart-add-vendor-cost").val(data.price);
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
            var note = $('#cart-add-note').val();
            var reordernum = $('#cart-add-reordernum').val();

            var thisid = 'row-'+product_id+'-'+uom_id;
            el.id = thisid;

            // $.get('{{ route('api-product-uom-get-data') }}', {product_id: product_id,uom_id: uom_id}, function(data){
            //
            // });
            // make sure our returned price is a float
            let cost = parseFloat($("#cart-add-vendor-cost").val());
            // calculate line total
            let item_total = cost*quantity;

            // set all the appropriate data to the template tag
            el.innerHTML = el.innerHTML.replace(/{item_name}/g,product.name);
            el.innerHTML = el.innerHTML.replace(/{product_id}/g,product_id);
            el.innerHTML = el.innerHTML.replace(/{uom_id}/g,uom_id);
            el.innerHTML = el.innerHTML.replace(/{id}/g,thisid);
            el.innerHTML = el.innerHTML.replace(/{cost_string}/g,'$'+cost.format(2, 3, ',', '.'));
            el.innerHTML = el.innerHTML.replace(/{cost}/g,cost);
            el.innerHTML = el.innerHTML.replace(/{quantity}/g,quantity);
            el.innerHTML = el.innerHTML.replace(/{uom}/g,uom.name);
            el.innerHTML = el.innerHTML.replace(/{item_total}/g,'$'+item_total.format(2, 3, ',', '.'));
            el.innerHTML = el.innerHTML.replace(/{note}/g,note);
            el.innerHTML = el.innerHTML.replace(/{reordernum}/g,reordernum);

            // keep record of data
            cartrows[thisid] = {
                'product': product,
                'uom': uom,
                'cost': cost,
                'item_total': item_total,
                'note': note,
                'reordernum': reordernum
            };

            // add to the table
            carttable.querySelector('tbody').appendChild(clone);

            // re-initialize inputs
            $("#cart-add-uom-group").hide();
            $("#cart-add-quantity-group").hide();
            $("#cart-add-vendor-cost-group").hide();
            $("#cart-add-uom").html('');
            $('#cart-add-product').val('');
            $('#cart-add-quantity').val('1');
            $('#cart-add-note').val('');
            $('#cart-add-reordernum').val('');
            $("#cart-add-vendor-cost").val('');

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

        if(total > 0){
            $('#purchase-order-submit').prop('disabled',false);
        }
        else{
            $('#purchase-order-submit').prop('disabled',true);
        }
    }
</script>
@endsection
