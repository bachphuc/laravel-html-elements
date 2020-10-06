<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin') }}</title>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    @include('bachphuc.elements::admin.components.css-headers')

    <script type="text/javascript" src="{{asset('assets/admin/js/components.js')}}"></script>

	{{-- <script type="text/javascript" src="{{asset('js/angular.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/admin/main.js')}}"></script> --}}

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

		window.baseUrl = '{{url('')}}';
    </script>

	<script src="{{asset('assets/tinymce/tinymce.min.js')}}"></script>
	
	<style type="text/css">
		table thead tr th {
			font-weight: bold !important;
			color: #8e24aa !important;
		}
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
		@if(!Auth::guest())
	    <div class="sidebar" data-active-color="rose" data-background-color="black" data-color="purple" data-image="{{asset('assets/img/sidebar-1.jpg')}}">
			<!--
		        Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

		        Tip 2: you can also add an image using data-image tag
		    -->

			<div class="logo">
				<a href="{{url('')}}" class="simple-text">{{ config('app.name', 'Laravel') }}</a>
			</div>
			
	    	<div class="sidebar-wrapper">
                <div class="fake-scroller">
                    @include('bachphuc.elements::admin.components.navigation-menus')
                </div>
	    	</div>
	    </div>
		@endif

	    <div class="main-panel {{Auth::guest() ? 'main-panel-clear' : ''}}">
			<nav class="navbar navbar-transparent navbar-absolute">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">@lang('lang.dashboard')</a>
					</div>
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                    @if(!Auth::guest())<span class="txt-trans-normal">{{ Auth::user()->name }}</span>@endif
	 							   <i class="material-icons">person</i>
		 						</a>
                                 @if(Auth::guest())
                                 <ul class="dropdown-menu">
									<li><a href="{{ route('login') }}">Login</a></li>
								</ul>
                                @else
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                                        </li>
                                    </ul>
                                @endif
							</li>
						</ul>

						<form class="navbar-form navbar-right" role="search">
							<div class="form-group  is-empty">
								<input type="text" class="form-control" placeholder="Search">
								<span class="material-input"></span>
							</div>
							<button type="submit" class="btn btn-white btn-round btn-just-icon">
								<i class="material-icons">search</i><div class="ripple-container"></div>
							</button>
						</form>
					</div>
				</div>
			</nav>

			<div class="content">
                @include('bachphuc.elements::admin.components.breadcrumbs')
				@if (isset($message))
				<div class="alert alert-success">
					<ul>
						<li>{{ $message }}</li>
					</ul>
				</div> 
                @endif
                @if (\Session::has('error'))
				<div class="alert alert-danger">
					<ul>
						<li>{{ session('error') }}</li>
					</ul>
				</div> 
				@endif
                
                @if(isset($elements) && !empty($elements))
                    @foreach($elements as $ele)
                    {!! $ele->render() !!}
                    @endforeach      
                @endif

			</div>

			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul>
							<li><a href="{{url('')}}">@lang('lang.home')</a></li>
						</ul>
					</nav>
					<p class="copyright pull-right">
						&copy; <script>document.write(new Date().getFullYear())</script> <a href="https://www.dmobisoft.com">DMobiSoft</a>, made with love for a better web
					</p>
				</div>
			</footer>
		</div>
	</div>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    <!--   Core JS Files   -->
	@include('bachphuc.elements::admin.components.core-js')
    <!-- Modal -->
    
    <!-- modals components -->
    @stack('modals')
    @stack('endbody')
    
    @stack('scripts')
</body>
</html>
