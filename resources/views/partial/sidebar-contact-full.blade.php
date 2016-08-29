<div class="container index-contact">
	<h1>Need to find a product?
		<strong>Ask the experts at WWMD</strong>
	</h1>

	<form action="{{ route('contact-us-submit') }}" method="post" role="form">
		<div class="form-group col-md-6 no-padding">
			<input type="text" class="form-control" id="name" name="name" placeholder="Name">
			<input type="text" class="hidden-xs hidden-sm hidden-md hidden-lg" name="email" placeholder="email" />
			<input type="text" class="form-control" id="real_email" name="real_email" placeholder="Email address">
		</div>
		<div class="form-group col-md-6 no-padding">
			<textarea class="form-control" id="message" name="message" placeholder="Let us source your hard to find, unique medical products"></textarea>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-contactindex" name="_token" value="{{ csrf_token() }}">Submit</button>
		</div>
	</form>
</div>