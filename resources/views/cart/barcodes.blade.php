@extends('layout')

@section('title')
@parent :: Cart
@stop

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main">
            <div class="form-group col-xs-12">
                <div class="alert alert-info">
                    To upload your items you will need to print the following instruction sheet: <a href="{{ route('barcode-instructions') }}" title="Print Barcode Instructions" target="_blank"><u>Barcode Instructions</u></a></a>.
                </div>
                {{-- <a href="{{ route('cart') }}" class="btn btn-cancel" ><span class="fa fa-chevron-left"></span> Back</a> --}}
            </div>
            <div class="col-xs-12">
                <form action="{{ route('cart-barcodes-submit') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table id="cart_table" class="table table-hover table-striped table-bordered text-left">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                @if(Auth::check() && !Auth::user()->no_pricing)
                                    <th>Price</th>
                                @endif
                                <th>Quantity</th>
                                <th>Options</th>
                                @if(Auth::check() && !Auth::user()->no_pricing)
                                    <th>Item Total</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        @if(Auth::check() && !Auth::user()->no_pricing)
                            <tfoot>
                                <tr>
                                    <th colspan="4"><strong>Total</strong></th>
                                    <th id="cart_total">$0.00</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                    <div class="form-group form-inline pull-right">
                        <button type="button" class="btn" onclick="javascript:history.back();">Continue Shopping</button>
                        <button type="submit" class="btn btn-default disabled" id="add_to_cart_button" disabled>Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{ asset('/js/jquery.mmp.barcodereader/jquery.mmp.barcodereader.js') }}"></script>
<script>
    $cart_tbody = $('#cart_table tbody');
    $add_to_cart_button = $('#add_to_cart_button');
    var confirm_remove = "'Are you sure you want to remove this item from the cart?'";
    var uoms = new Array();
    var cart = new Array();
    var timeout_id = false;

    $(document).ready(function() {
        $(this).mmpBarcodeReader();
        $(this).bind('end.mmp.barcodereader', function(e, st) {
            var id = parseInt(st);
            var key = '' + id;
            if (cart[key] !== undefined) {
                cart[key] += 1;
                $('#uom_quantity_' + key).val(cart[key]);
                $('#tr_' + key + ' .qty').text(cart[key]);
            } else {
                cart[key] = 1;
                $.ajax({
                    url: '{{ route('api-cart-get-barcodes') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: id
                    },
                    error: function(r) {
                        // console.log(r);
                    },
                    success: function(uom) {
                        uoms.push(uom);
                        var price = false;
                        if (uom.price) {
                            price = new Number(uom.price);
                            price = price.format(2, 3, ',', '.');
                        }

                        var html = '<tr id="tr_' + uom.id + '">';
                        html += '<td>' + uom.product.name + '</td>';
                        if (price) html += '<td class="price">$' + price + '</td>';
                        html += '<td class="qty">1</td>';
                        html += '<td>' + uom.name + '</td>';
                        if (price) html += '<td class="sub_total">$' + price + '</td>';

                        html += '<td><button type="button" id="edit_button_' + uom.id + '" class="btn btn-info" onclick="edit(' + uom.id +
                            ');" title="Edit Quantity"> <span class="glyphicon glyphicon-edit"></span> </button> <div id="edit_form_group_' + uom.id +
                            '" class="form-inline form-group hide pull-left"> <div class="input-group"><input type="number" name="uom_quantities[' + uom.id + ']" id="uom_quantity_' + uom.id +
                            '" value="1" class="form-control" min="1" max-width="5" /> <span class="input-group-btn"> <button onclick="edit_submit(' + uom.id +
                            ');" type="button" class="btn btn-warning" title="Submit Quantity"><span class="glyphicon glyphicon-edit"></span></button> </span> </div> </div> <button onclick="remove(' + uom.id +
                            ');" class="btn btn-danger" onclick="return confirm(' + confirm_remove + ');" title="Delete Item"> <span class="glyphicon glyphicon-remove" type="button"></span> </button></td>';

                        if ($('#cart_table tbody tr').length >= 1) {
                            $cart_tbody.append(html);
                        } else {
                            $cart_tbody.html(html);
                        }
                    }
                });
            }
            if (timeout_id) {
                clearTimeout(timeout_id);
            }
            timeout_id = setTimeout(function() {
                update_totals()
            }, 1000);
        });
    });

    function remove(uom_id) {
        $('#tr_' + uom_id).remove();
        uoms = uoms.filter(x => x.id !== uom_id);
        update_totals();
    }

    function edit(id) {
        $('#edit_form_group_' + id).removeClass('hide');
        $('#edit_button_' + id).addClass('hide');
    }

    function edit_submit(id) {
        $('#edit_form_group_' + id).addClass('hide');
        $('#edit_button_' + id).removeClass('hide');
        var new_qty = $('#uom_quantity_' + id).val();
        $('#tr_' + id + ' .qty').text(new_qty);
        update_totals();
    }

    function update_totals() {
        var total = 0;
        if (uoms.length > 0 && cart.length > 0) {
            cart.forEach(function(qty, uom_id) {
                var uom = uoms.find(x => x.id === uom_id);
                if (uom) {
                    var price = new Number(uom.price);
                    var sub_total = price * qty;
                    total += sub_total;
                    sub_total = new Number(sub_total);
                    sub_total = sub_total.format(2, 3, ',', '.');
                    $('#tr_' + uom_id + ' .sub_total').text('$' + sub_total);
                }
            });
        }

        if (total > 0) {
            total = new Number(total);
            total = total.format(2, 3, ',', '.');
            $('#cart_total').text('$' + total);
            $add_to_cart_button.removeAttr('disabled');
            $add_to_cart_button.removeClass('disabled');
        } else {
            $('#cart_total').text('$0.00');
            $add_to_cart_button.attr('disabled', 'disabled');
            $add_to_cart_button.addClass('disabled');
        }
    }
</script>

{{-- build in some auto test data when working from local (its ugly... but its test data... oh well) --}}
@if(App::environment('local'))
  <script>
    $(function(){
      setTimeout(function(){
        @foreach(\App\UnitOfMeasure::has('product')->orderByRaw("RAND()")->limit(5)->get() as $uom)
            $('.main-container').trigger({
              type: "keydown", keyCode: 65, which: 65
            });
            @foreach(str_split($uom->id.'') as $char)
              $('.main-container').trigger({
                type: "keydown", keyCode: {{ ord($char) }}, which: {{ ord($char) }}
              });
            @endforeach
            $('.main-container').trigger({
              type: "keydown", keyCode: 13, which: 13
            });
        @endforeach
      }, 3000);
    });
  </script>
@endif
@endsection
