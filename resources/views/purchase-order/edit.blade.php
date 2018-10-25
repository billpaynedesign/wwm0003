@extends('layout')

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <div class="col-xs-12">
                <h1>Edit Purchase Order {{ $purchase_order->invoice_num }}</h1>
                <h3>{{ $purchase_order->vendor->name }}</h3>
            </div>

            <form id="edit-form" action="{{ route('vendor-purchase-order-update',$purchase_order->id) }}" method="post">
                <div class="form-group col-lg-3 col-md-6 col-sm-12">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ $purchase_order->date->format('Y-m-d') }}" required>
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="current-items">
                                @foreach($purchase_order->details as $detail)
                                    <tr id="oldrow-{{ $detail->id }}">
                                        <td class="reordernum-cell">
                                            <span>{{ $detail->reorder_number }}</span>
                                            <input type="hidden" value="{{ $detail->reorder_number }}" name="old_reordernums[{{ $detail->id }}]">
                                        </td>
                                        <td>{{ $detail->product->name }}</td>
                                        {{-- <td>{{ $detail->product->item_number }}</td> --}}
                                        <td>{{ $detail->uom->name }}</td>
                                        <td class="note-cell">
                                            <span>{{ $detail->note }}</span>
                                            <input type="hidden" value="{{ $detail->note }}" name="old_notes[{{ $detail->id }}]">
                                        </td>
                                        <td class="text-center quantity-cell">
                                            <span>{{ $detail->quantity }}</span>
                                            <input type="hidden" value="{{ $detail->quantity }}" name="old_quantities[{{ $detail->id }}]">
                                        </td>
                                        <td class="text-center cost-cell">
                                            <span>${{ number_format($detail->cost,2) }}</span>
                                            <input type="hidden" value="{{ $detail->cost }}" name="old_cost[{{ $detail->id }}]">
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
                                    <th colspan="6"><strong>Total</strong></th>
                                    <th id="cart-total" class="text-right">${{ number_format($purchase_order->total,2) }}</th>
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
                        <a href="{{ route('admin-vendors') }}" class="btn btn-cancel">Cancel</a>
                        <button id="purchase-order-submit" type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
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
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cart-edit-reordernum">Reorder #</label>
                        <input id="cart-edit-reordernum" name="reordernum" class="form-control">
                    </div>
                    <div id="cart-edit-vendor-cost-group" class="form-group">
                        <label for="cart-edit-vendor-cost">Vendor Cost</label>
                        <input id="cart-edit-vendor-cost" name="vendor_cost" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cart-edit-quantity">Quantity</label>
                        <input id="cart-edit-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cart-edit-note">Note</label>
                        <input id="cart-edit-note" name="note" class="form-control">
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
        <td>
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
    var vendor_id = {{ $purchase_order->vendor_id }};
    var product;
    var uom;
    $(function(){
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
            carttable.querySelector('tbody#new-items').appendChild(clone);

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
        $('#edit-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var detail_id = button.data('detailid');

            var reordernum = $(`#oldrow-${detail_id} .reordernum-cell input`).val();
            var note = $(`#oldrow-${detail_id} .note-cell input`).val();
            var quantity = $(`#oldrow-${detail_id} .quantity-cell input`).val();
            var cost = parseFloat($(`#oldrow-${detail_id} .cost-cell input`).val());

            $('#cart-edit-detailid').val(detail_id);
            $('#cart-edit-reordernum').val(reordernum);
            $('#cart-edit-quantity').val(quantity);
            $('#cart-edit-vendor-cost').val(cost);
            $('#cart-edit-note').val(note);
        });
        $('#edit-modal-submit').click(function(event){
            var button = $(event.relatedTarget);
            var detail_id = $('#cart-edit-detailid').val();

            var reordernum = $('#cart-edit-reordernum').val();
            var note = $('#cart-edit-note').val();
            var quantity = $('#cart-edit-quantity').val();
            var cost = parseFloat($('#cart-edit-vendor-cost').val());

            $(`#oldrow-${detail_id} .reordernum-cell input`).val(reordernum);
            $(`#oldrow-${detail_id} .note-cell input`).val(note);
            $(`#oldrow-${detail_id} .quantity-cell input`).val(quantity);
            $(`#oldrow-${detail_id} .cost-cell input`).val(cost);

            $(`#oldrow-${detail_id} .reordernum-cell span`).text(reordernum);
            $(`#oldrow-${detail_id} .note-cell span`).text(note);
            $(`#oldrow-${detail_id} .quantity-cell span`).text(quantity);
            $(`#oldrow-${detail_id} .cost-cell span`).html('$'+cost.format(2, 3, ',', '.'));

            $('#cart-edit-detailid').val('');
            $('#cart-edit-reordernum').val('');
            $('#cart-edit-quantity').val('');
            $('#cart-edit-note').val('');
            $("#cart-edit-vendor-cost").val('');

            calculate_total();
        });

        calculate_total();
    });
    function calculate_total(){
        var total = 0;
        for (var key in cartrows) {
            let thisrow = cartrows[key];
            total += parseFloat(thisrow.item_total);
            console.log([thisrow.item_total,total]);
        }
        $('#current-items tr').each(function(index,element){
            var quantity = parseInt($(element).find('.quantity-cell input').val());
            var cost = parseFloat($(element).find('.cost-cell input').val());
            var itemtotal = quantity*cost;
            $(element).find('.itemtotal-cell').html('$'+itemtotal.format(2, 3, ',', '.'));
            total += itemtotal;
            console.log([quantity,cost,itemtotal,total]);
        });

        let total_string = '$'+total.format(2, 3, ',', '.');
        carttotal.html(total_string);

        if(total > 0){
            $('#purchase-order-submit').prop('disabled',false);
        }
        else{
            $('#purchase-order-submit').prop('disabled',true);
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
