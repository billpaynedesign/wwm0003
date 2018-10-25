@extends('layout')

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <h1>Vendor pricing</h1>
            <p>{{ $product->name }}</p>

            <form action="{{ route('product-vendor-pricing-update',$product->id) }}" method="post" class="form-inline">
                @if(count($vendors))
                    @foreach($vendors as $vendor)
                        <h4>Vendor: {{ $vendor->name }}</h4>
                        @if(count($units_of_measure))
                            @foreach($units_of_measure as $uom)
                                <?php $uom_vendor = $uom->vendors->find($vendor->id); ?>
                                <div class="form-group">
                                    <label for="price-{{ $vendor->id }}-{{ $uom->id }}">{{ $uom->name }} Costs:</label>
                                    <input type="number" id="price-{{ $vendor->id }}-{{ $uom->id }}" name="costs[{{ $vendor->id }}][{{ $uom->id }}]" step="0.01" min="0" class="form-control" value="{{ $uom_vendor?$uom_vendor->pivot->cost:$uom->price }}" required />
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <a href="{{ route('product-edit',$product->id) }}#vendors-label">Add units of measures</a> to this product first.
                            </div>
                        @endif
                        <hr>
                    @endforeach
                @else
                    <div class="alert alert-warning">
                        <a href="{{ route('product-edit',$product->id) }}#vendors-label">Edit this product</a> to add vendors first.
                    </div>
                    <hr>
                @endif
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{{ $product->id }}"/>
                    <a href="{{ route('admin-products') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

</script>
@endsection
