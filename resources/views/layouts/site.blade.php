@php
	use App\Http\Umstudio\Datasite;
	use App\http\Umstudio\AutoAssets;
	use App\http\Umstudio\Metasocial;
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		@section('head')
			<title>{{env('HEAD_TITLE', 'Novo Site')}}</title>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
			<meta http-equiv="X-UA-Compatible" content="IE=Edge,Chrome=1" >
			<meta name="theme-color" content="{{ config('theme_color', 'gray') }}">
			<meta name="env" content="{{env('APP_ENV', 'undefined')}}">
			<meta name="now" content="{{date('Y-m-d H:i:s')}}">
			<meta name="framework-version" content="{{ App::VERSION() }}">
			<meta name="app-version" content="{{ app_version() }}">

			{{Metasocial::print()}}

			<link href="{{url('/favicon.ico')}}" type="image/x-icon" rel="icon"/>
			<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
			<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
			<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

			@section('vendor.css')
				{{AutoAssets::print('css')}}
			@show

			@section('datasite')
				<script>window.datasite = @json(Datasite::get())</script>
			@show
		@show
	</head>
	<body>
		@section('header')
			<header>
				@include('site/includes/header')
				@section('menu')
					@include('site/includes/menu')
				@show
			</header>
		@show

		<div id="container">
			@yield('content')
		</div>

		<footer>
			@section('footer')
				@include('site/includes/footer')
			@show
		</footer>
		
		<!-- Vendor Scripts -->
		@section('vendor.js')
			<script type="text/javascript" src="{{vasset('/js/cjsbaseclass.min.js')}}" data-jquery-exclusive="true" data-silent-host="www.site-production.com"></script>
		@show
		
		<!-- Page Scripts -->
		@section('scripts')
			{{AutoAssets::print('js')}}
		@show
	</body>
</html>