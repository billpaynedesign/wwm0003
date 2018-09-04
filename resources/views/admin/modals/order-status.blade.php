<input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}" />
<div class="form-group">
  <div class="checkbox">
      <label>
        <input type="checkbox" name="boxed" value="true" onchange="unhide_tracking_label($(this));"/> Shipped in the same Box?
      </label>
  </div>
</div>
<div id="tracking_group" class="form-group">
    <label for="tracking">Tracking #</label>
    <input type="text" id="tracking" name="tracking" class="form-control" />
</div>
<div id="label_group" class="form-group hide">
    <label for="label"></label>
    <input type="text" id="label" name="label" class="form-control" />
</div>
<div class="form-group">
  <div class="checkbox">
      <label>
        <input type="checkbox" name="invoice" value="true" /> Generate an invoice for this change?
      </label>
  </div>
</div>
<table class="table table-bordered table-striped">
<thead>
  <tr>
    <th>Item Name</th>
    <th>Quantity</th>
    <th>Backordered</th>
    <th>Item Total</th>
    <th>Paid?</th>
    <th>Shipped?</th>
    <th>Lot #</th>
    <th>Expiry Date</th>
  </tr>
</thead>
<tbody>
  @foreach($order->details()->get() as $detail)
  <tr>
    <td>{{ $detail->product->name }}</td>
    <td>{{ $detail->quantity }}</td>
    <td>{{ $detail->backordered }}</th>
    <!--
    <td>
      {{ $detail->size.' '.$detail->color.' ' }}
      @if($detail->other)
        @foreach(explode(',',$detail->other) as $other)
          {{ explode(":",$other)[1].' ' }}
        @endforeach
      @endif
    </td>
    -->
    <td>${{ \number_format($detail->subtotal,2) }}</td>
    <td><input type="checkbox" name="paid[{{ $detail->id }}]" value="true" {!! $detail->paid?'checked':'' !!} /></td>
    <td><input type="checkbox" name="shipped[{{ $detail->id }}]" value="true" {!! $detail->shipped?'checked':'' !!} /></td>
    <td><input type="text" name="lot_number[{{ $detail->id }}]" class="form-control" /></td>
    <td><input type="text" name="expiration[{{ $detail->id }}]" class="form-control orderstatus-datepicker" /></td>
  </tr>
  @endforeach
</tbody>
</table>
