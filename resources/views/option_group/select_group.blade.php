@extends('app')

@section('scripts')
<script type="text/javascript">

</script>
@endsection
@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Select Group</h1>
    <div class="col-xs-6">
      <form action="{{ route('group-product-select-products') }}" method="POST" >
        <div class="form-group">
          <label for="group">Select Options to Group Products</label>
          <select id="group" name="group" class="form-control" required>
            <option value="">-- Select Options --</option>
            @if($options)
              @foreach($options as $option)
                <option value="{{ $option->id }}">{{ $option->name }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="form-group">
          {!! csrf_field() !!}
          <a href="{{ route('admin-products') }}" class="btn btn-cancel">Cancel</a>
          <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<hr/>
@endsection
