<div class="row">
  <div class="col-xs-12">
    <h1>{{ $product->name }}</h1>
    <p><strong>Information:</strong></p>
    <div class="col-md-8 col-xs-12">
      <table class="table">
        <tr>
          <td>Category: </td>
          <td>{{ $product->category?$product->category->name:'' }}</td>
        </tr>
        <tr>
          <td>Manufacturer: </td>
          <td>{{ $product->manufacturer }}</td>
        </tr>
        <tr>
          <td>Item Number: </td>
          <td>{{ $product->item_number }}</td>
        </tr>
        <tr>
          <td>Require Lot Number &amp; Expiry Date: </td>
          <td>{!! $product->has_lot_expiry?'<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon-remove"></span>' !!}</td>
        </tr>
        <tr>
          <td>Require customer to have license: </td>
          <td>{!! $product->require_license?'<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon-remove"></span>' !!}</td>
        </tr>
        <tr>
          <td>Available: </td>
          <td>{!! $product->active?'<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon-remove"></span>' !!}</td>
        </tr>
      </table>
    </div>
    <div class="clearfix"></div>
    <p><strong>Pricing:</strong></p>
    <div class="col-md-8 col-xs-12">
      <table class="table">
        <tr>
          <th>UOM</th>
          <th>MSRP</th>
          <th>Price</th>
        </tr>
        @if($product->units_of_measure)
          @foreach($product->units_of_measure as $uom)
            <tr>
              <td>{{ $uom->name }}:</td>
              <td>{{ $uom->msrp_string }}</td>
              <td>{{ $uom->price_string }}</td>
            </tr>
          @endforeach
        @endif
      </table>
    </div>
  </div>
    <div class="clearfix"></div>
  
  <div class="col-xs-12">
    <p><strong>Overview:</strong></p>
    <p>{!! nl2br($product->short_description) !!}</p>
    <p><strong>Details:</strong></p>
    <p>{!! nl2br($product->description) !!}</p>
    <p><strong>Admin Note:</strong></p>
    <p>{!! nl2br($product->note) !!}</p>
    <p><strong>Picture:</strong></p>
    <img src="{{ asset($product->picture?'pictures/'.$product->picture:'images/noimg.gif') }}" class="pull-left" style="max-width: 250px;"/></h1>
  </div>
</div>