@extends('layout')

@section('title')
@parent :: Barcodes
@stop

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <form id="barcode_form" action="{{ route('product-barcodes') }}" method="GET" onsubmit="return print_barcodes()">
                <input type="hidden" name="print" value="1">
                {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                <div class="form-group form-inline pull-right">
                    <a href="{{ route('admin-users') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-default" id="add_to_cart_button">Print</button>
                </div>
            </form>
            <div class="clearfix"></div>
            <table id="barcodes_table" class="table table-hover table-striped table-bordered text-left">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>UOM</th>
                        <th>Purchased</th>
                        <th>Print?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uoms as $uom)
                        <tr>
                            <td>{{ $uom->product->name }}</td>
                            <td>{{ $uom->name }}</td>
                            <td>{{ $quantities[$uom->product->id] }}</td>
                            <td class="text-center">{{ $uom->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var table;
    $barcode_form = $('#barcode_form');
    $(document).ready(function() {
        table = $('#barcodes_table').DataTable({
            "order": [
                [2, "desc"]
            ],
            "columnDefs": [{
                "targets": 3,
                "searchable": false,
                "orderable": false,
                "render": function(data) {
                    return '<input type="checkbox" name="uoms[]" value="' + data + '">';
                }
            }]
        });
    });

    function print_barcodes() {
        // Iterate over all checkboxes in the table
        table.$('input[type="checkbox"]').each(function() {
            // If checkbox doesn't exist in DOM
            if (!$.contains($barcode_form[0], this)) {
                // If checkbox is checked
                if (this.checked) {
                    // Create a hidden element
                    $barcode_form.append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', this.name)
                        .val(this.value)
                    );
                }
            }
        });
        return true;
    }
</script>
@endsection
