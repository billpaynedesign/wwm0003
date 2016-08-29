<div>
  <a href="{{ route('order-invoice',$order->token) }}?print=true" target="_new" class="btn btn-default pull-right">Print</a>
  <div class="embed-responsive embed-responsive-16by9">
    <iframe src="{{ route('order-invoice',$order->token) }}" width="100%" class="embed-responsive-item"></iframe>
  </div>
</div>