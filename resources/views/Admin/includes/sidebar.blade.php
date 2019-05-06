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
					@endphp
					@if ($print_menu)
						<li class="treeview active menu-open">
							<a href="#">
								<i class="fa {{ $header->get('ico') }}"></i>&nbsp;&nbsp;<span>{{ $header->get('name') }}</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								@foreach ($childs as $item)
									@php
										$item = collect($item);
										$print_menu = true;
										if ($item->has('roles'))
										{
											if (!empty($item->get('roles')))
											{
												$roles = collect($item->get('roles'))->pluck('name');
												$print_menu = $user->roles->whereIn('name', $roles->toArray())->count() > 0;
												$print_menu = $user->roles->whereIn('name', $roles->toArray())->count() > 0;
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
										<li class="{{ $active }}"><a href="{{ $link }}" target="{{ $target }}"><i class="{{ $item->get('ico') }}"></i>&nbsp;&nbsp;{{ $item->get('name') }}</a></li>
									@endif
								@endforeach
							</ul>
						</li>
					@endif
				@endforeach
			</ul>
		</section>
	</aside>
@endsection