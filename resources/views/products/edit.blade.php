@extends('layout')

@section('scripts')
<script type="text/javascript">
    function remove_uom(id, name) {
        if (confirm('Are you sure you want to remove the Unit of Measure: ' + name)) {
            $.post("{{ route('unit_of_measure.destroy','') }}/" + id, {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#uom-single-' + data['uom']['id']).remove();
            });
        }
        return false;
    }

    function add_uom() {
        var html = '<div class="uom_single"> <hr/> <div class="form-group"> <button class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-trash"></span></button> </div> <div class="form-group"> <label for="uom">Unit of Measure</label> <input type="text" id="uom" name="uom_new[]" class="form-control" required /> </div> <div class="form-group"> <label for="msrp">MSRP:</label> <input type="number" id="msrp" name="msrp_new[]" step="0.01" min="0" class="form-control" required /> </div> <div class="form-group"> <label for="price">Price:</label> <input type="number" id="price" name="price_new[]" step="0.01" min="0" class="form-control" required /> </div> <div class="form-group"> <label>Weight:</label> <div class="input-group input-group-select"> <input type="number" name="weight_new[]" step="0.01" min="0" class="form-control" required /> <select class="form-control" name="weight_unit_new[]"> <option value="lb">lb</option> <option value="oz">oz</option> </select> </div> </div> </div>';
        $("#uom_groups").append(html);
        return false;
    }
</script>
@endsection
@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <h1>Edit {{ $product->name }}</h1>
            <div class="col-xs-3">
                <img src="{{ asset($product->picture?'pictures/'.$product->picture:'images/noimg.gif') }}" class="img-responsive center-block" />
            </div>
            <div class="col-md-8 col-md-offset-1 col-xs-9">
                <form action="{{ route('product-update') }}" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name: </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" />
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category[]" id="category" class="form-control" multiple required style="resize: vertical;">
                            @foreach($categories as $category)
                                @if($product->categories->contains($category->id))
                                    <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="help-block">To select multiple categories, hold down the CTRL or SHIFT key while selecting</p>
                    </div>
                    <div class="form-group">
                        <label for="item_number">Item Number</label>
                        <input type="text" class="form-control" id="item_number" name="item_number" placeholder="Item Number" value="{{ $product->item_number }}" />
                    </div>
                    <div id="old_uom_groups">
                        @if($product->units_of_measure)
                            @foreach($product->units_of_measure as $uom)
                                <div id="uom-single-{{ $uom->id }}" class="old_uom_single">
                                    <hr />
                                    <div class="form-group">
                                        <a class="btn btn-danger" onclick="remove_uom({{ $uom->id }},'{{ $uom->name }}');"><span class="fa fa-trash"></span>&nbsp;Remove {{ $uom->name }}</a>
                                    </div>
                                    <div class="form-group">
                                        <label for="uom-{{ $uom->id }}">Unit of Measure</label>
                                        <input type="text" id="uom-{{ $uom->id }}" name="uom[{{ $uom->id }}]" class="form-control" value="{{ $uom->name }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="msrp-{{ $uom->id }}">MSRP:</label>
                                        <input type="number" id="msrp-{{ $uom->id }}" name="msrp[{{ $uom->id }}]" step="0.01" min="0" class="form-control" value="{{ $uom->msrp }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="price-{{ $uom->id }}">Price:</label>
                                        <input type="number" id="price-{{ $uom->id }}" name="price[{{ $uom->id }}]" step="0.01" min="0" class="form-control" value="{{ $uom->price }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="weight-{{ $uom->id }}">Weight:</label>
                                        <div class="input-group input-group-select">
                                            <input type="number" id="weight-{{ $uom->id }}" name="weight[{{ $uom->id }}]" step="0.01" min="0" class="form-control" value="{{ $uom->weight }}" />
                                            <select class="form-control" name="weight_unit[{{ $uom->id }}]">
                                                <option value="lb">lb</option>
                                                <option value="oz">oz</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div id="uom_groups"></div>
                    <div class="form-group">
                        <hr />
                        <a class="btn btn-success" onclick="add_uom();"><span class="fa fa-plus"></span>&nbsp;Add Unit of Measure</a>
                        <hr />
                    </div>
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer</label>
                        <input type="text" name="manufacturer" id="manufacturer" class="form-control" value="{{ $product->manufacturer }}" />
                    </div>
                    <div class="form-group">
                        <label for="vendor">Vendor:</label>
                        <select id="vendor" name="vendor" class="form-control">
                            <option value="">-- Select Vendor --</option>
                            @foreach (App\Vendor::all() as $vendor)
                                <option value="{{ $vendor->id }}" {{ $vendor->id==$product->vendor_id?'selected':'' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shortdescription">Overview</label>
                        <textarea class="form-control" id="shortdescription" name="shortdescription" placeholder="Overview">{{ $product->short_description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="productdescription">Details</label>
                        <textarea class="form-control" id="productdescription" name="productdescription" placeholder="Details">{{ $product->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="active" id="active" {{ $product->active?'checked':'' }}> Available
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="has_lot_expiry" id="has_lot_expiry" value="1" {{ $product->has_lot_expiry?'checked':'' }}> Require Lot Number &amp; Expiry Date?
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="require_license" name="require_license" value="1" {{ $product->require_license?'checked':'' }}/> Require customer to have license?
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">Admin Note:</label>
                        <textarea id="note" name="note" class="form-control">{!! nl2br($product->note) !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Item Picture</label>
                        <input type="file" id="image" name="image">
                        <p class="help-block">If no picture is chosen the existing product picture will be used.</p>
                    </div>
                    @if(count($product->groups)>0)
                        <div class="form-group">
                            <hr />
                            <h4>Product Groups</h4>
                            <table class="table table-striped table-hover table-bordered">
                                @foreach($product->groups as $product_group)
                                    <tr>
                                        <td>{{ $product_group->option_group->name }}:</td>
                                        <td>
                                            <?php $first = '';
                                            foreach($product_group->products as $option_product):
                                                if($option = $product_group->option_group->options()->whereHas('products', function($query) use($option_product){ $query->where('product_id', $option_product->id); })->first()):
                                                    if($option_product->id===$product->id):
                                                        echo $first.'<u>'.$option->option.'</u>';
                                                    else:
                                                        echo $first.$option->option;
                                                    endif;
                                                endif;
                                                $first = ', ';
                                            endforeach;
                                            ?>
                                        </td>
                                        <td>
                                            <a href="{{ route('option.edit',$product_group->id) }}" class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            <hr />
                        </div>
                        @endif
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{ $product->id }}"/>
                            <button type="submit" name="cancel" value="true" class="btn btn-cancel">Cancel</button>
                            <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
