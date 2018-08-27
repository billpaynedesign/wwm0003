<p>Name: {{ $request->input('name') }}</p>
<p>Email: {{ $request->input('real_email') }}</p>
@if($request->has('phone'))
<p>Phone: {{ $request->input('phone') }}</p>
@endif
<p>Message/Question: {{ $request->input('message') }}</p>
@if($request->has('checkboxes'))
<p>Interested In:<br> {{ implode(', ',$request->input('checkboxes')) }}</p>
@endif