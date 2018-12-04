@php
	use App\Http\Utilities\Datasite;
	use App\Http\Utilities\AutoAssets;
	use App\Http\Utilities\MetaSocial;
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		@section('head')
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
			<meta http-equiv="X-UA-Compatible" content="IE=Edge,Chrome=1" >
			<meta name="env" content="{{ env('APP_ENV', 'undefined') }}">
			<meta name="now" content="{{ date('Y-m-d H:i:s') }}">
			<meta name="framework-version" content="{{ App::VERSION() }}">
			<meta name="app-version" content="{{ app_version() }}">
			<meta name="robots" content="noindex">

			{{ MetaSocial::print() }}

			<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
			<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
			<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

			@section('vendor.css')
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
				<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
				{{ AutoAssets::print('css') }}
			@show

			@section('datasite')
				<script>window.datasite = @json(Datasite::get())</script>
			@show
		@show
	</head>
	<body>
		<div class="container-fluid">
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
		</div>
		
		{{-- Vendor Scripts --}}
		@section('vendor.js')
			<script type="text/javascript" src="{{vasset('/lib/cjsbaseclass.min.js')}}" data-jquery-exclusive="true" data-silent-host="www.site-production.com"></script>
			{{ javascript('/lib/sweetalert.min.js') }}
		@show
		
		{{-- Common Script --}}
		{{ javascript('/js/common.js') }}
		
		{{-- Page Scripts --}}
		@section('scripts')
			{{ AutoAssets::print('js') }}
		@show
	</body>
</html>