@php
	use App\http\Umstudio\AutoAssets;
@endphp

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,Chrome=1" >
		<meta name="env" content="{{env('APP_ENV', 'undefined')}}">
		<meta name="now" content="{{date('Y-m-d H:i:s')}}">
		<meta name="framework-version" content="{{ App::VERSION() }}">
		<meta name="app-version" content="{{ app_version() }}">

		<title>{{env('ADMIN_TITLE', 'Admin')}}</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/bootstrap/css/bootstrap.min.css')}}">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/fonts/font-awesome.min.css')}}">
		<!-- Ionicons -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/fonts/ionicons.min.css')}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/dist/css/AdminLTE.min.css')}}">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
			folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/dist/css/skins/_all-skins.min.css')}}">
		<!-- Select2 -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/plugins/select2/select2.min.css')}}">
		<!-- daterange picker -->
		<link rel="stylesheet" href="{{vasset('/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.css')}}">
		<!-- <link rel="stylesheet" href="/admin-lte/plugins/daterangepicker/daterangepicker.css"> -->
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="/admin-lte/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="/admin-lte/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		@section('styles')
			
		@show
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<header class="main-header">
				<!-- Logo -->
				<a href="{{route('admin_dashboard')}}" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini">{{env('ADMIN_SLUG', 'ADM')}}</span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg">{{env('ADMIN_CAPTION', 'Admin')}}</span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
					<!-- Sidebar toggle button-->
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

			<!-- =============================================== -->
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				@include('Admin.includes.messages')

				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						@yield('content-header')
					</h1>
				</section>
				<!-- Main content -->
				<section class="content">
					@yield('content')
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<!-- Add the sidebar's background. This div must be placed
				immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
		</div>
		<!-- ./wrapper -->
		<!-- jQuery 2.2.3 -->
		<script src="{{vasset('/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="{{vasset('/admin-lte/bootstrap/js/bootstrap.min.js')}}"></script>
		<!-- SlimScroll -->
		<script src="{{vasset('/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
		<!-- FastClick -->
		<script src="{{vasset('/admin-lte/plugins/fastclick/fastclick.js')}}"></script>
		<!-- bootstrap datepicker -->
		<script src="{{vasset('/admin-lte/plugins/daterangepicker/moment.min.js')}}"></script>
		<!-- daterangepicker -->
		<script src="{{vasset('/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
		<!-- Select2 -->
		<script src="{{vasset('/admin-lte/plugins/select2/select2.full.min.js')}}"></script>
		<!-- AdminLTE App -->
		<script src="{{vasset('/admin-lte/dist/js/app.min.js')}}"></script>
		<!-- Scripts -->
		<script>
			window.datasite = 
			{
				'url': 
				{
					'base'   : '{{env('APP_URL')}}',
					'admin'  : '{{env('APP_URL')}}/admin',
					'current': '{{url()->current()}}'
				},
				'_token' : '{{ csrf_token() }}'
			};
		</script>
		<script type="text/javascript" src="{{vasset('/js/cjsbaseclass.min.js')}}" data-jquery-exclusive="true" data-silent-host="www.site-production.com"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<!-- User Scripts -->
		@yield('scripts')

		{{ AutoAssets::print('js') }}
	</body>
</html>