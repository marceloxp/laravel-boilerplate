@section('sidebar')
	<aside class="main-sidebar">
		<section class="sidebar">
			<ul class="sidebar-menu">
				@foreach ($menus as $header)
					@php
						$header = collect($header);
						$print_menu = true;
						if ($header->has('roles'))
						{
							if (!empty($header->get('roles')))
							{
								$roles = collect($header->get('roles'))->pluck('name');
								$print_menu = $user->roles->whereIn('name', $roles->toArray())->count() > 0;
							}
						}
						$childs = $header->get('child');
						if ($print_menu)
						{
							$print_menu = (count($childs) > 0);
						}
					@endphp
					@if ($print_menu)
						@php
							$before_menu = '
								<li class="treeview active menu-open">
									<a href="#">
										<i class="fa ' . $header->get('ico') . '"></i>&nbsp;&nbsp;<span>' . $header->get('name') . '</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
							';
							$menus = '';
						@endphp
						@foreach ($childs as $item)
							@php
								$item_id = $item->id;
								$the_roles = \App\Http\Utilities\Cached::get
								(
									sprintf('view_admin_sidebar_%s', $user->id),
									['all',$item->id],
									function() use ($item)
									{
										return $item->roles;
									},
									15
								);
								$the_roles = $the_roles['data'];
								$has_roles = (!empty($the_roles));
								$item = collect($item);
								$print_menu = true;
								if ($has_roles)
								{
									if (!empty($the_roles))
									{
										$roles = collect($the_roles)->pluck('name');
										$print_menu = \App\Http\Utilities\Cached::get
										(
											sprintf('view_admin_sidebar_%s', $user->id),
											['verify',$item_id],
											function() use ($user, $roles)
											{
												return $user->roles->whereIn('name', $roles->toArray())->count() > 0;
											},
											15
										);
										$print_menu = $print_menu['data'];
									}
								}
								if ($print_menu)
								{
									switch($item->get('type'))
									{
										case 'link':
										case 'dashboard':
											$active = 'none';
											if ($item->has('group'))
											{
												$active = (($verify == $item->get('group')) ? 'active' : 'none');
											}
											else if ($item->has('menu'))
											{
												$active = (($verify === $item->get('menu')) ? 'active' : 'none');
											}
											else if ($item->has('route'))
											{
												$active = (($verify === $item->get('route')) ? 'active' : 'none');
											}

											$target = $item->get('target') ?? '_self';
											$link   = $item->get('route') ?? $item->get('link');
											$link   = route($link);
										break;
										case 'internal-link':
											$target = $item->get('target') ?? '_self';
											$link   = url($item->get('link'));
										break;
										default:
											$print_menu = false;
										break;
									}
								}
							@endphp
							@if ($print_menu)
								@php
									$caption   = $item->get('name');
									$hook_name = hook_name(sprintf('admin_sidebar_caption_%s_%s', $header->get('name'), $caption));
									$caption   = Hook::apply_filters($hook_name, $caption);
									$menus .= '<li class="' . $active . '"><a href="' . $link . '" target="' . $target . '"><i class="' . $item->get('ico') . '"></i>&nbsp;&nbsp;' . $caption . '</a></li>';
								@endphp
							@endif
						@endforeach
						@php
							$after_menu = '</ul> </li>';

							if (!empty($menus))
							{
								echo $before_menu . $menus . $after_menu;
							}
						@endphp
					@endif
				@endforeach
			</ul>
		</section>
	</aside>
@endsection