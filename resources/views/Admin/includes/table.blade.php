@php
	$current_route = Route::currentRouteName();

	$search_fields = array_merge($display_fields);
	$hook_name     = hook_name(sprintf('admin_index_search_fields_%s', $table_name));
	$search_fields = Hook::apply_filters($hook_name, $search_fields);

	$sort_fields   = array_merge($display_fields);
	$hook_name     = hook_name(sprintf('admin_index_sort_fields_%s', $table_name));
	$sort_fields   = Hook::apply_filters($hook_name, $sort_fields);

	$show_search   = true;
	$hook_name     = hook_name(sprintf('admin_index_show_search_%s', $table_name));
	$show_search   = Hook::apply_filters($hook_name, $show_search);

	$image_fields = $image_fields ?? [];
@endphp

@if ($has_table)
	@if ($show_search)
		<form name="frmTable" method="get" id="frmTable" action="{{url()->current()}}">
			<div class="box box-info collapsed-box">
				<div class="box-header with-border">
					<h3 class="box-title">Ordenação e Busca</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label>Busca</label>
								<div class="input-group">
									<div class="input-group-btn">
										<button id="btn-search-field" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-search"></i>&nbsp;&nbsp;<span>Buscar por</span>&nbsp;
										<span class="fa fa-caret-down"></span></button>
										<ul class="dropdown-menu" id="search-fields-items">
											@foreach($search_fields as $field_name)
												@if (array_key_exists($field_name, $fields_schema))
													<li><a class="search_field" data-field="{{ $field_name }}" data-caption="{{ $fields_schema[$field_name]['comment'] }}" href="#">{{ $fields_schema[$field_name]['comment'] }}</a></li>
												@endif
											@endforeach
											<li class="divider"></li>
											<li><a class="search_field" data-field="___clear" href="#">Limpar Busca</a></li>
										</ul>
									</div>
									<input type="text" name="table_search" id="table_search" class="form-control" placeholder="Busca">
								</div>
							</div>
						</div>
					</div>
					@if (!empty($search_dates))
						<div class="row">
							<div class="col-xs-6 col-md-3">
								<div class="form-group">
									<label>Filtrar por data</label>
									<select id="select-field-date" class="form-control">
										@foreach($search_dates as $field_name)
											<option class="option_search_date" value="{{ $field_name }}">{{ $fields_schema[$field_name]['comment'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-xs-6 col-md-3">
								<label>Período</label><br>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fas fa-calendar"></i>
									</div>
									<input type="text" class="form-control daterangepicker" id="search-date" data-prefix="range">
									<input type="hidden" name="range_ini" id="range_ini" value="">
									<input type="hidden" name="range_end" id="range_end" value="">
								</div>
							</div>
						</div>
					@endif
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<div class="form-group">
								<label>Ordernar por</label>
								<select id="select-field-order" class="form-control">
									<option class="option_search_field" value="0">Selecione</option>
									@foreach($sort_fields as $field_name)
										@if (array_key_exists($field_name, $fields_schema))
											<option class="option_search_field" value="{{ $field_name }}">{{ $fields_schema[$field_name]['comment'] }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-xs-4 col-md-4">
							<label>Adicionar</label><br>
							<div class="btn-group">
								<button type="button" data-dir="down" class="btn btn-default btn-order-add" data-toggle="tooltip" data-original-title="Do menor para o maior (ASC)"  data-placement="bottom"><i class="fas fa-arrow-down"></i></button>
								<button type="button" data-dir="up"   class="btn btn-default btn-order-add" data-toggle="tooltip" data-original-title="Do maior para o menor (DESC)" data-placement="bottom"><i class="fas fa-arrow-up"></i></button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<div class="btn-group" id="div-orders">

								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<div class="form-group">
								<label>&nbsp;</label><br>
								<button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
		</form>
	@endif
@endif

@php
	$include_file   = null;
	$hook_name      = hook_name(sprintf('admin_index_include_file_%s', $current_route));
	$include_file   = Hook::apply_filters($hook_name, $include_file);
	$has_exportable = $print_button = isset($exportable) ? $exportable : true;
	$cols           = ($has_exportable) ? ['col-xs-7','col-xs-2','col-xs-3'] : ['col-xs-10','col','col-xs-2'];
@endphp
@if ($include_file)
	@include($include_file)
@endif

<div class="box box-success">
	<div class="box-header with-border">
		<div class="btn-toolbar justify-content-between" role="toolbar">
			<div class="btn-group">
				@php $buttons_edit = isset($editable) ? $editable : true; @endphp
				@if ($buttons_edit)
					<button type="button" id="btn-table-add" class="btn btn-success {{ $class_pivot }}"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Adicionar</button>
					@if ($has_table)
						@if (!$is_pivot)
							<button type="button" id="btn-table-edt" class="btn-check-one btn btn-info disabled"><i class="fas fa-edit"></i>&nbsp;&nbsp;Editar</button>
						@endif
					@endif
				@endif
				@if ($has_table)
					<button type="button" id="btn-table-viw" class="btn-check-one btn btn-default disabled"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar</button>
					@if (!empty($table_many))
						@foreach ($table_many as $button_table)
							<button type="button" data-parent="{{ $table_name }}" data-link="{{ $button_table['schema'] }}/{{ $button_table['name'] }}" class="btn-table-many btn-check-one btn btn-warning disabled"><i class="{{ $button_table['icon'] }}"></i>&nbsp;&nbsp;{{ $button_table['caption'] }}</button>
						@endforeach
					@endif
					@if (!empty($pivot))
						@foreach($pivot as $pivot_config)
							<button type="button" id="btn-table-pvt" data-link="{{ $pivot_config['name'] }}" class="btn-check-one btn-table-pvt btn btn-warning disabled"><i class="{{ $pivot_config['icon'] }}"></i>&nbsp;&nbsp;{{ $pivot_config['caption'] }}</button>
						@endforeach
					@endif
					@if ($buttons_edit)
						<button type="button" id="btn-table-del" class="btn-check-many btn btn-danger {{ $class_pivot }} disabled"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Excluir</button>
					@endif
				@endif

				@php
					$custom_buttons = [];
					$hook_name      = hook_name(sprintf('admin_index_custom_buttons_%s_%s', $table_name, $route_name));
					$custom_buttons = Hook::apply_filters($hook_name, $custom_buttons, $table);

					if ($custom_buttons)
					{
						foreach ($custom_buttons as $button)
						{
							if ($button === 'separator')
							{
								echo '</div><div class="btn-group">';
							}
							else
							{
								echo admin_index_button
								(
									$button['button_id'],
									$button['type'],
									$button['color_style'],
									$button['disabled'],
									$button['icon'],
									$button['text']
								);
							}
						}
					}
				@endphp
			</div>
			@if ($has_table)
				@php $print_button = isset($exportable) ? $exportable : true; @endphp
				@if ($print_button)
					<div class="btn-group">
						<button type="button" id="btn-table-exp" class="btn btn-success pull-right"><i class="fas fa-file-excel"></i> Exportar</button>
					</div>
				@endif
			@endif
			<div class="btn-group">
				<div class="form-group" style="margin-bottom: 0px;">
					<select class="form-control" id="cb-table-order" name="cb-table-order">
						@foreach(config('admin.index.pagination.perpages', [20,50,100,200,300]) as $item_page)
							<option value="{{ $item_page }}" {{ ($item_page == $perpage) ? 'selected="selected' : '' }}>{{ $item_page }} Registros por Página</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="box-body">
		@if ($has_table)
			<table class="table table-bordered table-striped table-condensed table-hover table-responsive" id="main-table">
				<tbody>
					<tr>
						<th style="width:20px">
							<span class="switch">
								<input type="checkbox" class="switch" id="ch-rows-all">
								<label for="ch-rows-all"></label>
							</span>
						</th>
						@foreach($display_fields as $field_name)
							@php
								if (!array_key_exists($field_name, $fields_schema))
								{
									$hook_name    = hook_name(sprintf('admin_index_custom_field_title_%s_%s', $table_name, $field_name));
									$column_title = Hook::apply_filters($hook_name, $field_name);
								}
								else
								{
									$title_align = 'left';
									$field_type  = $fields_schema[$field_name]['type'];
									switch ($field_type)
									{
										case 'int':
											$title_align = 'center';
										break;
										case 'tinyint':
											$title_align = 'center';
										break;
										case 'decimal':
											$title_align = 'center';
										break;
										case 'enum':
											$title_align = 'center';
										break;
										case 'timestamp':
											$title_align = 'center';
										break;
									}

									$column_title = $fields_schema[$field_name]['comment'];
									$hook_name    = hook_name(sprintf('admin_index_title_caption_%s_%s', $table_name, $field_name));
									$column_title = Hook::apply_filters($hook_name, $column_title);
								}

								$hook_name   = hook_name(sprintf('admin_index_title_align_%s_%s', $table_name, $field_name));
								$title_align = Hook::apply_filters($hook_name, $title_align);
							@endphp
							<th style="text-align: {{ $title_align }};" data-field="{{ $field_name }}">
								@if ( ($field_name == 'id') && ($sortable) )
									<span style="cursor:help" title="Clique e arrasque qualquer registro abaixo para ordenar.">
										{!! fa_ico_v5('fas fa-arrows-alt') !!}
									</span>
								@endif
								{{ $column_title }}
							</i></th>
						@endforeach
					</tr>
					@foreach($table as $register)
					<tr>
						@php
							$show_check = $register->show_check;
							if ($show_check === null)
							{
								$show_check = true;
							}

							$show_check = array_merge($display_fields);
							$hook_name  = hook_name(sprintf('admin_index_show_check_%s', $table_name));
							$show_check = Hook::apply_filters($hook_name, $show_check, $register);
						@endphp
						<td>
							@if ($show_check)
								<span class="switch">
									<input type="checkbox" class="switch ck-row" id="checkbox{{ $register['id'] }}" data-ids="{{ $register['id'] }}">
									<label for="checkbox{{ $register['id'] }}"></label>
								</span>
							@endif
						</td>
						@foreach($display_fields as $field_name)
							@php
								if (!array_key_exists($field_name, $fields_schema))
								{
									$display_value = '';
									$hook_name     = hook_name(sprintf('admin_index_custom_field_%s_%s', $table_name, $field_name));
									$display_value = Hook::apply_filters($hook_name, $display_value, $register->toArray());
								}
								else
								{
									$field_align = 'left';
									$field_type = $fields_schema[$field_name]['type'];
									$display_value = $register[$field_name];
									if (in_array($field_name, $image_fields) !== false)
									{
										$url_image = url('images/admin/no-image.png');
										if ($display_value)
										{
											$url_image = url('uploads/images/' . $display_value);
										}
										$display_value = sprintf('<img src="%s" style="max-height:150px; max-width:150px; border: 1px solid silver;" alt="">', $url_image);
									}
									else
									{
										switch ($field_type)
										{
											case 'appends':
												if (is_a($display_value, App\Http\Utilities\Money::class))
												{
													$display_value = $display_value->formated->value;
													$field_align = 'right';
												}
											break;
											case 'timestamp':
												$field_align = 'right';
											break;
											case 'int':
												if (str_right($field_name, 3) != '_id')
												{
													$field_align = 'right';
												}
											break;
											case 'tinyint':
												$display_value = (intval($display_value) === 0) ? '<span class="label label-danger"><i class="fas fa-times-circle"></i></span>' : '<span class="label label-success"><i class="fa fa-fw fa-check"></i></span>';
											break;
											case 'decimal':
												$display_value = new \App\Http\Utilities\Money($display_value);
												$display_value = $display_value->formated;
												$field_align = 'right';
											break;
											case 'enum':
												$display_value = ($field_name == 'status') ? admin_label_status($display_value) : admin_badge_status($display_value);
												$field_align = 'center';
											break;
											case 'pivot':
												$pivot_table = $fields_schema[$field_name]['name'];
												$pivot_model = sprintf('\App\Models\%s', db_table_name_to_model($table_schema, $fields_schema[$field_name]['name']));
												$admin_index_function_exists = method_exists($pivot_model, 'onAdminIndex');
												if ($admin_index_function_exists)
												{
													$display_value = $pivot_model::onAdminIndex($register);
												}
												else
												{
													$display_value = $register->$pivot_table->toBootstrapLabels()->toText();
												}
											break;
										}
	
										if ($fields_schema[$field_name]['has_relation'])
										{
											$custom_field  = $fields_schema[$field_name]['relation']['custom_field'];
											$display_value = $register->$custom_field;
										}
									}

									$hook_name     = hook_name(sprintf('admin_index_%s_%s', $table_name, $field_name));
									$display_value = Hook::apply_filters($hook_name, $display_value, $register->toArray());

									$hook_name   = hook_name(sprintf('admin_index_field_align_%s_%s', $table_name, $field_name));
									$field_align = Hook::apply_filters($hook_name, $field_align, $register->toArray());

									$sortable_class = ( ($field_name == 'id') && ($sortable) ) ? 'sortable-row' : '';
								}
							@endphp
							<td align="{{ $field_align }}">
								<div class="{{ $field_name }} {{ $sortable_class }}">
									{!! $display_value !!}
								</div>
							</td>
						@endforeach
					</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<p>Não há resultados para esta consulta.</p>
		@endif
	</div>
	@if ($has_table)
		<div class="box-footer clearfix">
			<span class="pull-left">{!! $paginate !!}</span>
			<span class="pull-right">
				<ul class="pagination">
					<li class="page-item">
						{{ $table->count() }} de {{ $table->total() }} registro{{ $table->total() > 1 ? 's' : '' }}, página {{ $table->currentPage() }} de um total de {{ $table->lastPage() }} página{{ $table->lastPage() > 1 ? 's' : '' }}
					</li>
				</ul>
			</span>
		</div>
	@endif
</div>