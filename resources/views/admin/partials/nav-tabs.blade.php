<?php $tabs = [
  [
    'text' => "Home",
    'route' => route('admin-dashboard')
  ],
  [
    'text' => "Categories",
    'route' => route('admin-categories')
  ],
  [
    'text' => "Products",
    'route' => route('admin-products')
  ],
  [
    'text' => "Product Options",
    'route' => route('admin-options')
  ],
  [
    'text' => "Orders",
    'route' => route('admin-orders')
  ],
  [
    'text' => "Back Orders",
    'route' => route('admin-backorders')
  ],
  [
    'text' => "Users",
    'route' => route('admin-users')
  ],
  [
    'text' => "Specials",
    'route' => route('admin-specials')
  ],
  [
    'text' => "Vendors",
    'route' => route('admin-vendors')
  ],
  [
    'text' => "Accounts Receivable",
    'route' => route('admin-accounts-receivable')
  ],
  [
    'text' => "Accounts Payable",
    'route' => route('admin-accounts-payable')
  ],
];
?>
<ul class="nav nav-tabs" role="tablist">
  @foreach($tabs as $t)
    <li role="presentation" {!! $adminActive==$t['text']?'class="active"':'' !!}><a href="{{ $t['route'] }}#dashboard">{{ $t['text'] }}</a></li>
  @endforeach
</ul>
