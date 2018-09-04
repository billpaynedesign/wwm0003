<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="title" content="Medical Equipment &amp; Supplies">
        <meta name="keyword" content="Medical Equipment &amp; Supplies">
        <meta name="description" content="Medical Equipment &amp; Supplies">
        <title>@section('title') Medical Equipment &amp; Supplies @show</title>

        <meta property="og:title" content="Medical Equipment &amp; Supplies" />
        <meta property="og:image" content="" />
        <meta property="og:description" content="" />


        <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon"/>

        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('/js/lightbox-bootstrap/ekko-lightbox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/dropzone/basic.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/dropzone/dropzone.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Oswald:300,500|Poppins:300,700" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

        <link href="{{ asset('/css/master.css') }}?v=20180904" rel="stylesheet">

        @yield('head')

    </head>
    <body>
        <div class="container-fluid">
            <div id="row-top" class="row">
                <div class="container">
                    <div class="pull-right">
                        @if(Auth::check())
                        <a href="{{ route('user-profile') }}">Welcome, {{ Auth::user()->name }}</a>
                        @if(Auth::user()->admin)
                        <a href="{{ route('admin-dashboard') }}">Admin</a>
                        @endif
                        <a href="{{ route('order-history') }}">Orders</a>
                        <a href="{{ route('user-profile') }}">Profile</a>
                        <a href="{{ url('/auth/logout') }}" title="Log Out">Logout</a>
                        @else
                        <a href="{{ url('/auth/login') }}">Login</a>
                        <a href="{{ url('/auth/register') }}">Register</a>
                        @endif
                        <a href="{{ route('cart') }}" <?php if (session()->has('shipping')): ?> title="{{ session()->get('shipping')->name }} - {{ session()->get('shipping')->address1.' '.session()->get('shipping')->address2.' '.session()->get('shipping')->city.', '. session()->get('shipping')->state.' '.session()->get('shipping')->zip }}" <?php endif; ?>>Shopping Cart <span class="fa fa-shopping-cart" aria-hidden="true"></span> <span id="header_view_cart"></span></a>
                    </div>
                </div>
            </div>
            <div id="header-navbar-bg">
                <div id="row-header" class="row">
                    <div class="container">
                        <div id="logo-holder" class="col-md-3 col-xs-6 no-padding">
                            <a href="{{ url('/') }}" title="World Wide Medical Distributors Home">
                                <img src="{{ asset('images/world-wide-medical-distributors-logo.png') }}" class="img-responsive" alt="World Wide Medical Distributors Logo" />
                            </a>
                        </div>
                        <div id="header-text-holder" class="col-md-9 col-xs-6 no-padding">
                            <h2>Questions or Comments?</h2>
                            <p>Mon-Fri 9am&#8209;5pm&nbsp;EST</p>
                            <p><strong><a href="mailto:bw@wwmdusa.com" title="Email World Wide Medical Distributors">bw@wwmdusa.com</a></strong></p>
                            <h3><a href="tel:9143589878" title="Call World Wide Medical Distributors">914.358.9878</a></h3>
                        </div>
                    </div>
                </div>
                <div id="row-navbar" class="row">
                    <nav id="navbar-default" class="navbar" role="navigation">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse" id="navbar">
                                <ul class="nav navbar-nav">
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('about-us') }}">Who We Are</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Products <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            @foreach(\App\Category::where('active',1)->whereNull('parent_id')->get() as $category)
                                            <li><a href="{{ route('category-show',$category->slug) }}">{{ $category->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('contact-us') }}">Exclusive Offers</a></li>
                                    <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container -->
                    </nav>
                </div>
                <div id="row-subheader" class="row">
                    <div class="container">
                        <div id="search-holder" class="col-md-offset-9 col-md-3 col-xs-offset-7 col-xs-5 no-padding text-right">
                            <select id="main-product-search" name="q" placeholder="Search" class="form-control"></select>
                        </div>
                        @if($special = App\Special::first())
                        @if($special->isValid())
                        <div id="special-holder" class="col-xs-12 no-padding">
                            <a href="{{ $special->url }}">
                                <h2>{{ $special->header }}</h2>
                                <h3>{{ $special->secondary }}</h3>
                            </a>
                            <div class="clearfix"></div>
                            <a href="{{ $special->url }}" class="btn btn-orange">Shop Now</a>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            <div id="row-alert" class="row">
                <div class="container">
                    @if(Session::has('fail'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span></button>
                        {{ Session::get('fail') }}
                    </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span></button>
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(Session::has('info'))
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span></button>
                        {{ Session::get('info') }}
                    </div>
                    @endif
                </div>
            </div>

            @yield('content')

            @include('part.testimonials')

            @include('part.contact')

            <footer id="row-footer" class="row">
                <div class="container">
                    <div id="footer-nav-holder" class="col-xs-6">
                        <ul class="list-unstyled">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ route('about-us') }}">Who We Are</a></li>
                            <li><a href="{{ route('product-all') }}">Products</a></li>
                            <li><a href="{{ route('contact-us') }}">Exclusive Offers</a></li>
                            <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div id="footer-text-holder" class="col-xs-6">
                        Questions or Comments?<br/>
                        Mon-Fri: 9am-5pm EST<br/>
                        Email: <a href="mailto:bw@wwmdusa.com" title="Contact World Wide Medical Distributors">bw@wwmdusa.com</a><br/>
                        Telephone: <a href="tel:9143589879" title="Call World Wide Medical Distributors">914.358.9879</a><br/>
                        Fax: <a href="tel:9143589879" title="Fax World Wide Medical Distributors">914.358.9880</a><br/>
                    </div>
                </div>
                <hr>
                <div class="container">
                    <div id="footer-copy-holder" class="col-xs-12">
                        &copy; {{ date('Y') }} World Wide Medical Distributors. All rights reserved.<br>
                        Web Design &amp; Digital Marketing by <a href="https://drivegroupllc.com" title="Web Design &amp; Digital Marketing by Drive Group, LLC" target="_blank">Drive Group, LLC</a>.
                    </div>
                </div><!--/c-->
            </footer>
        </div><!--/ container-fluid -->

        @yield('modals')

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
        <script src="{{ asset('/js/lightbox-bootstrap/ekko-lightbox.min.js') }}"></script>
        <script src="{{ asset('/js/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
        <script src="{{ asset('/js/dropzone/dropzone.js') }}"></script>
        <!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->
        <script src="{{ asset('js/selectize/js/standalone/selectize.min.js') }}"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
        <script type="text/javascript">
$(function(){
var cart_count = {{ Cart::count() }};
if (cart_count > 0) $('#header_view_cart').html('(' + cart_count + ')');
});
        </script>
        <!-- Scripts -->
        <script type="text/javascript">
            var root = '{{url("/")}}';
        </script>
        <script type="text/javascript">
            /*tinymce.init({ selector:'.tinymce' });*/
            var table;
            $(document).ready(function(){
            table = $('.tablesorter').DataTable();
            @if (Session::has('modal'))
                    $('#{{ Session::get("modal") }}').modal('show');
            @endif

                    $('#main-product-search').selectize({
            valueField: 'url',
                    labelField: 'name',
                    searchField: ['name'],
                    maxOptions: 10,
                    options: [],
                    create: false,
                    render: {
                    option: function(item, escape) {
                    var picturespath = '{{ asset("/pictures") }}/';
                    var noimage = '{{ asset("/images") }}/noimg.gif';
                    if (item.picture){
                    var picture = picturespath + item.picture;
                    }
                    else{
                    var picture = noimage;
                    }
                    return '<div><img src="' + picture + '" style="max-width:50px; max-height: 50px; margin-right:5px;">' + item.name + '</div>';
                    }
                    },
                    optgroups: [
                    {value: 'product', label: 'Products'},
                    {value: 'category', label: 'Categories'},
                    {value: 'item_number', label: 'Item #'}
                    ],
                    optgroupField: 'class',
                    optgroupOrder: ['product', 'category', 'item_number'],
                    load: function(query, callback) {
                    if (!query.length) return callback();
                    $.ajax({
                    url: root + '/api/search',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                            q: query
                            },
                            error: function() {
                            callback();
                            },
                            success: function(res) {
                            callback(res.data);
                            }
                    });
                    },
                    onChange: function(){
                    window.location = this.items[0];
                    }
            });
            });
            $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
            });
            function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
                    return !(charCode > 31 && (charCode < 48 || charCode > 57));
            }
            function printElement(elem, thisbutton) {
            var $btn = $(thisbutton).button('loading');
            var domClone = elem.cloneNode(true);
            var $printSection = document.getElementById("printSection");
            if (!$printSection) {
            var $printSection = document.createElement("div");
            $printSection.id = "printSection";
            document.body.appendChild($printSection);
            }

            $printSection.innerHTML = "";
            $printSection.appendChild(domClone);
            setTimeout(function(){window.print(); $btn.button('reset')}, 3000);
            }
        </script>
        @yield('scripts')
    </body>
</html>
