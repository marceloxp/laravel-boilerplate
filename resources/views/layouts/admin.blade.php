@php
	use App\Http\Utilities\AutoAssets;
	use App\Http\Utilities\Datasite;
@endphp

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,Chrome=1" >
		<meta name="env" content="{{ env('APP_ENV', 'undefined') }}">
		<meta name="now" content="{{ date('Y-m-d H:i:s') }}">
		<meta name="framework-version" content="{{ App::VERSION() }}">
		<meta name="app-version" content="{{ app_version() }}">

		<title>{{ env('ADMIN_TITLE', 'Admin') }}</title>
		<link rel="shortcut icon" type="image/png" href="{{ url('/favicon.png') }}"/>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/bootstrap/css/bootstrap.min.css') }}">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/fonts/font-awesome.min.css') }}">
		<!-- Ionicons -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/fonts/ionicons.min.css') }}">
		<!-- Select2 -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/plugins/select2/select2.min.css') }}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/dist/css/AdminLTE.min.css') }}">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
			folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/dist/css/skins/_all-skins.min.css') }}">
		<!-- daterange picker -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
		<!-- summernote -->
		<link rel="stylesheet" href="{{ vasset('/vendor/summernote/summernote.css') }}">
		<!-- <link rel="stylesheet" href="/admin-lte/plugins/daterangepicker/daterangepicker.css"> -->
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="/admin-lte/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="/admin-lte/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		@section('styles')
			
		@show

		@section('datasite')
			<script>window.datasite = @json(Datasite::get())</script>
		@show
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper" style="height: auto; overflow-y: hidden;">
			<header class="main-header">
				<a href="{{ route('admin_dashboard') }}" class="logo">
					<span class="logo-mini">{{ env('ADMIN_SLUG', 'ADM') }}</span>
					<span class="logo-lg">{{ env('ADMIN_CAPTION', 'Admin') }}</span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li>
								<a href="#"><span class="glyphicon glyphicon-user">&nbsp;</span>{{ $user->name }}</a>
							</li>
						</ul>
					</div>
				</nav>
			</header>

			@yield('sidebar')

			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						@yield('content-header')
					</h1>
					@yield('content-description')
				</section>
				<section class="content">
					@yield('content')
				</section>
			</div>
			<div class="control-sidebar-bg"></div>
		</div>

		@include('Admin.includes.modal_search')

		<!-- jQuery 2.2.3 -->
		<script src="{{ vasset('/admin-lte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="{{ vasset('/admin-lte/bootstrap/js/bootstrap.min.js') }}"></script>
		<!-- SlimScroll -->
		<script src="{{ vasset('/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
		<!-- FastClick -->
		<script src="{{ vasset('/admin-lte/plugins/fastclick/fastclick.js') }}"></script>
		<!-- bootstrap datepicker -->
		<script src="{{ vasset('/admin-lte/plugins/daterangepicker/moment.min.js') }}"></script>
		<!-- daterangepicker -->
		<script src="{{ vasset('/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
		<!-- Select2 -->
		<script src="{{ vasset('/admin-lte/plugins/select2/select2.full.min.js') }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ vasset('/admin-lte/dist/js/app.min.js') }}"></script>
		<!-- jQuerySimpleMask -->
		<script src="{{ vasset('/js/admin/jQuery.SimpleMask.min.js') }}"></script>
		<!-- jQueryPriceFormat -->
		<script src="{{ vasset('/js/admin/jquery.priceformat.min.js') }}"></script>
		<!-- Scripts -->
		<script type="text/javascript" src="{{ vasset('/lib/cjsbaseclass.min.js') }}" data-jquery-exclusive="true" data-silent-host="www.site-production.com"></script>
		{{ javascript('/lib/sweetalert.min.js') }}
		@include('Admin.includes.messages')

		<!-- summernote -->
		<script src="{{ vasset('/vendor/summernote/summernote.min.js') }}"></script>

		<!-- User Scripts -->
		@yield('scripts')

		{{ AutoAssets::print('js') }}

		{{ javascript('/js/admin/common.js') }}
		{{ javascript('/js/admin/modal-search.js') }}
	</body>
</html>