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
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/bootstrap/css/bootstrap.min.css') }}">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/fonts/font-awesome.min.css') }}">
		<!-- Ionicons -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/fonts/ionicons.min.css') }}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/dist/css/AdminLTE.min.css') }}">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
			folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/dist/css/skins/_all-skins.min.css') }}">
		<!-- Select2 -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/plugins/select2/select2.min.css') }}">
		<!-- daterange picker -->
		<link rel="stylesheet" href="{{ vasset('/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
		<!-- jQuery TreeGrid -->
		<link rel="stylesheet" href="{{ vasset('/jquery-treegrid/css/jquery.treegrid.css') }}">
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
		<div class="wrapper">
			<header class="main-header">
				<a href="{{route('admin_dashboard') }}" class="logo">
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








<div class="box box-success">
	<div class="box-header with-border">
		<div class="row">
			<div class="btn-group col-xs-10">
													<button type="button" id="btn-table-add" class="btn btn-success "><i class="fa fa-fw fa-plus"></i> Adicionar</button>
																		<button type="button" id="btn-table-edt" class="btn btn-info disabled"><i class="fa fa-edit"></i> Editar</button>
																								<button type="button" id="btn-table-viw" class="btn btn-default disabled"><i class="fa fa-eye"></i> Visualizar</button>
											<button type="button" id="btn-table-many" data-parent="categories" data-link="subcategory" class="btn btn-warning disabled"><i class="fa fa-folder-open"></i> Sub Categorias</button>
																					<button type="button" id="btn-table-del" class="btn btn-danger  disabled"><i class="fa fa-close"></i> Excluir</button>
												</div>
																</div>
	</div>
	<div class="box-body">
					
<table class="tree table table-striped table-bordered table-hover table-condensed">
	<tr class="treegrid-1">
		<td><input type="checkbox" class="ck-row" data-ids="3"></td>
		<td>Root node</td><td>Additional info</td>
	</tr>
	<tr class="treegrid-2 treegrid-parent-1">
		<td><input type="checkbox" class="ck-row" data-ids="3"></td>
		<td>Node 1-1</td><td>Additional info</td>
	</tr>
	<tr class="treegrid-3 treegrid-parent-1">
		<td><input type="checkbox" class="ck-row" data-ids="3"></td>
		<td>Node 1-2</td><td>Additional info</td>
	</tr>
	<tr class="treegrid-4 treegrid-parent-3">
		<td><input type="checkbox" class="ck-row" data-ids="3"></td>
		<td>Node 1-2-1</td><td>Additional info</td>
	</tr>
</table>


			</div>
			<div class="box-footer clearfix">
			<span class="pull-left"></span>
			<span class="pull-right">
				<ul class="pagination">
					<li class="page-item">3 registros, página 1 de um total de 1</li>
				</ul>
			</span>
		</div>
	</div>





































































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
		<!-- jQuery TreeGrid -->
		<script type="text/javascript" src="{{ vasset('/jquery-treegrid/js/jquery.treegrid.min.js') }}"></script>
		<script type="text/javascript" src="{{ vasset('/jquery-treegrid/js/jquery.treegrid.bootstrap3.js') }}"></script>
		<!-- Scripts -->
		<script type="text/javascript" src="{{ vasset('/lib/cjsbaseclass.min.js') }}" data-jquery-exclusive="true" data-silent-host="www.site-production.com"></script>
		{{ javascript('/lib/sweetalert.min.js') }}
		@include('Admin.includes.messages')

		<!-- User Scripts -->
		@yield('scripts')

		{{ AutoAssets::print('js') }}

		{{ javascript('/js/admin/common.js') }}
		{{ javascript('/js/admin/modal-search.js') }}

<script type="text/javascript">
  $('.tree').treegrid(
{treeColumn: 1}

  	);
</script>


	</body>
</html>