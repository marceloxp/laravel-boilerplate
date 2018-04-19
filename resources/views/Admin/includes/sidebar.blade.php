@section('sidebar')
	<!-- =============================================== -->
	<!-- Left side column. contains the sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu">
				@foreach ($menus as $header)
					@php
						$print_menu = true;
						if (array_key_exists('roles', $header))
						{
							$print_menu = $user->roles->whereIn('name', $header['roles'])->count() > 0;
						}
					@endphp
					@if ($print_menu)
						<li class="treeview active menu-open">
							<a href="#">
								<i class="fa {{$header['ico']}}"></i> <span>{{$header['caption']}}</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								@foreach ($header['items'] as $item)
									@php
										$active = (($route_section['name'] == $item['route']) ? 'active' : 'none');
										$print_menu = true;
										if (array_key_exists('roles', $item))
										{
											$print_menu = $user->roles->whereIn('name', $item['roles'])->count() > 0;
										}
									@endphp
									@if ($print_menu)
										<li class="{{$active}}"><a href="{{route($item['link'])}}"><i class="fa {{$item['ico']}}"></i> {{$item['caption']}}</a></li>
									@endif
								@endforeach
							</ul>
						</li>
					@endif
				@endforeach
			</ul>
		</section>
		<!-- /.sidebar -->
	</aside>
@endsection