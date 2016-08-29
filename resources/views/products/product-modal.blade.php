<div class="row">
  <div class="col-xs-12">
    <h1>{{ $product->name }}</h1>
    <p><strong>Information:</strong></p>
    <div class="col-md-6 col-xs-12">
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
          <td>Price: </td>
          <td>{{ $product->price_string }}</td>
        </tr>
        <tr>
          <td>MSRP: </td>
          <td>{{ $product->msrp_string }}</td>
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
  </div>
  
  <div class="col-xs-12">
    <p><strong>Description:</strong></p>
    <p>{!! nl2br($product->description) !!}</p>
    <p><strong>Details:</strong></p>
    <p>{!! nl2br($product->short_description) !!}</p>
    <p><strong>Admin Note:</strong></p>
    <p>{!! nl2br($product->note) !!}</p>
    <?php $attributeOptions = $product->productAttributes()->active()->where('name','=',$attribute->name)->orderBy('id')->get(); ?>
    @foreach($attributeOptions as $option)
      <p>Attribute Option {{ $option->id }}: </p>
      <p>{{ $option->option }} - ${{ $option->price }}.00</p>
    @endforeach
    <p><strong>Picture:</strong></p>
    <img src="{{ asset($product->picture?'pictures/'.$product->picture:'images/noimg.gif') }}" class="pull-left" style="max-width: 250px;"/></h1>
  </div>
</div>