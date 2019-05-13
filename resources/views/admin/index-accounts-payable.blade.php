@extends('layout')


@section('title') Admin Dashboard :: @parent @stop

@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container-fluid">
    <div id="col-main" class="col-xs-12">
      <div class="page-header">
        <h1 id="dashboard">Admin Dashboard</h1>
      </div>
      <div id="admin_tab_panel" role="tabpanel">

        @include('admin.partials.nav-tabs')

        <div class="tab-content">
          <div role="tabpanel" class="tab-pane tab-pane-admin active">
            <div class="form-group form-inline">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-vendor-bill">
                    <span class="fa fa-plus" aria-hidden="true"></span>&nbsp;Add Bill
                </button>
            </div>
              <table id="bill_table" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Bill #</th>
                    <th>Name/Memo</th>
                    <th>Due Date</th>
                    <th>Vendor</th>
                    <th>Terms</th>
                    <th>Amount</th>
                    <th>Account</th>
                    @if(request()->has('include_paid'))
                        <th>Paid?</th>
                    @endif
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($vendor_bills as $vbill)
                  <tr>
                    <td>{{ $vbill->id }}</td>
                    <td>{{ $vbill->name }}</td>
                    <td>{{ $vbill->date->format('m/d/Y') }}</td>
                    <td>{{ $vbill->vendor->name }}</td>
                    <td>{{ $vbill->payment_term->name }}</td>
                    <td>{{ $vbill->amount_string }}</td>
                    <td>{{ $vbill->bill_account->name }}</td>
                    @if(request()->has('include_paid'))
                        <td>{!! $vbill->paid_icon !!}</td>
                    @endif
                    <td>
                        <a href="{{ route('vendor-bill-update-paid',$vbill->id) }}" title="Mark bill as paid" class="btn btn-success">
                            Mark bill as paid
                        </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
<script>
$(function(){
    $('#bill_table').DataTable({"order": [[ 0, "desc" ]]});
    // $newaccountgroup = $('#new-account-group');
    // $('#account').on('change',function(e){
    //     $select = $(this);
    //     if($select.val()==='other'){
    //         $newaccountgroup.show();
    //     }
    //     else{
    //         $newaccountgroup.hide();
    //         $newaccountgroup.find('input').val('');
    //     }
    // });
    $('#bill_date').change(function(){
        $('#payment_terms :selected').each(function(index,element){
            $(element).prop('selected',false);
        });
    });
    $('#payment_terms').change(function(){
        if($(this).val() !== ''){
            var termDays = $(this).find(':selected').data('termdays');
            var currentDate = new Date();
            currentDate.setDate(currentDate.getDate() + termDays);
            var newDate = currentDate.toISOString().split('T')[0];
            $('#bill_date').val(newDate);
        }
    });
});
</script>
@endsection

@section('modals')
    @include('admin.modals.vendor-bill-add')
@stop
