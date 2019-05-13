@extends('layout')

@section('scripts')
<script type="text/javascript">
function add_option(){
  var html = '<div class="option_single"> <hr/> <div class="form-group"> <button class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-trash"></span></button> </div> <div class="form-group"> <label for="option_names">Option Name</label> <input type="text" id="option_names" name="option_names[]" class="form-control" required /> </div> </div>'
  $("#option_groups").append(html);
}
</script>
@endsection
@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container">
    <div id="col-main" class="col-xs-12">
      <h1>Edit {{ $option_group->name }}</h1>
      <div class="col-xs-9">
        <form action="{{ route('option-group-update',$option_group->id) }}" method="post">
          <div class="form-group">
            <label for="name">Option Group Name: </label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $option_group->name }}" required/>
          </div>
          @if($option_group->options)
            @foreach($option_group->options as $opt)
              <div class="form-group">
                <label for="opt-{{ $opt->id }}">Option Name: </label>
                <input type="text" name="options[{{ $opt->id }}]" id="opt-{{ $opt->id }}" class="form-control" value="{{ $opt->option }}" required/>
              </div>
            @endforeach
          @endif
          <div id="option_groups">
          </div>
          <div class="form-group">
            <hr>
            <a class="btn btn-success" onclick="javascript:add_option();"><span class="fa fa-plus"></span>&nbsp;Add Option</a>
          </div>
          <div class="form-group">
            {!! csrf_field() !!}
            <a href="{{ route('admin-options') }}" class="btn btn-cancel">Cancel</a>
            <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
