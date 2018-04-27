<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{{env('admin_title', 'Admin')}}</title>
		<meta name="framework-version" content="{{ App::VERSION() }}">
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
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<header class="main-header">
				<!-- Logo -->
				<a href="{{route('dashboard')}}" class="logo">
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
								
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<!-- =============================================== -->
			<!-- Left side column. contains the sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="treeview active menu-open">
							<a href="#">
								<i class="fa fa-gears"></i> <span>Sistema</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="active"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							</ul>
						</li>
					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>
			<!-- =============================================== -->
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
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
		<script type="text/javascript" src="{{ vasset('/lib/cjsbaseclass.min.js') }}" data-jquery-exclusive="true" data-silent-host="www.site-production.com"></script>
		<!-- User Scripts -->
		@yield('scripts')
	</body>
</html>