<div id="row-form" class="row">
    <div class="container">
        <div id="contact-form">
            @if(session()->has('mail-sent'))
                <!-- {{ session()->pull('mail-sent') }} -->
	            <h2>Contact World Wide Medical Distributors by calling <a href="tel:9143589879" title="Call World Wide Medical Distributors">914.358.9879</a>, or...</h2>
	            <p>Email us at <a href="mailto:bw@wwmdusa.com">bw@wwmdusa.com</a>.</p>
	            <div class="alert alert-success">
	            	Thank you! Your message has been successfully sent.
	            </div>
            @else
	            <h2>Contact World Wide Medical Distributors by calling <a href="tel:9143589879" title="Call World Wide Medical Distributors">914.358.9879</a>, or...</h2>
	            <p>Email us at <a href="mailto:bw@wwmdusa.com">bw@wwmdusa.com</a>. Fill out the form below and we will get in touch with you.</p>
		        <form class="form" action="{{ route('contact-us-submit') }}" method="post" role="form">
		            <div class="row">
		                <div class="col-sm-4">
		                    <div class="form-group">
		                        <input name="email" class="hidden" type="email">
		                        <input name="first_name" class="hidden" type="text">
		                        <input name="last_name" class="hidden" type="text">
		                        <input placeholder="Name" name="name" id="name" class="form-control" required type="text">
		                    </div><!--/form-group-->
		                    <div class="form-group">
		                        <input placeholder="Email" name="real_email" id="email" class="form-control" required type="email">
		                    </div><!--/form-group-->
		                    <div class="form-group">
		                        <input placeholder="Phone" name="phone" id="phone" class="form-control" required type="text" pattern="\d{3}[\-]\d{3}[\-]\d{4}" title="123-456-7890">
		                    </div><!--/form-group-->
		                </div><!--/col-->
		                <div class="col-sm-8">
		                    <div class="form-group">
		                        <textarea placeholder="Message" name="message" id="message" class="form-control" required></textarea>
		                    </div><!--/form-group-->
		                </div><!--/col-->
		            </div><!--/row-->
		            <div class="row">
		                <div class="col-sm-4">
		                    <p>I am interested in:</p>
		                </div><!--/col-->
		                <div class="col-sm-8">
		                	<div class="form-group form-inline">
								@foreach(\App\Category::where('active',1)->whereNull('parent_id')->get() as $category)
									<div class="checkbox">
										<label for="checkbox-{{ $category->slug }}">
											<input type="checkbox" class="form-checkbox" id="checkbox-{{ $category->slug }}" name="checkboxes[]" value="{{ ucwords(strtolower($category->name),"/ ") }}">
											{{ ucwords(strtolower($category->name),"/ ") }}
										</label>
									</div>
								@endforeach
			                </div>
		                </div><!--/col-->
		            </div><!--/row-->
		            <div class="row">
		                <div class="col-xs-12">
		                    <div class="form-group">
		                        {!! csrf_field() !!}
		                        <button type="submit" name="contact_form" id="contact_form" class="btn btn-default btn-block">Submit</button>
		                    </div><!--/form-group-->
		                </div>
		            </div>
		        </form>
		    @endif
        </div><!--/contact-form-->
    </div><!--/container-->
</div>