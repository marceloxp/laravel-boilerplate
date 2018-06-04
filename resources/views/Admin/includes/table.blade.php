@php
	$search_fields = array_merge($display_fields);
	$hook_name     = hook_name(sprintf('admin_index_search_fields_%s', $table_name));
	$search_fields = Hook::apply_filters($hook_name, $search_fields);

	$sort_fields   = array_merge($display_fields);
	$hook_name     = hook_name(sprintf('admin_index_sort_fields_%s', $table_name));
	$sort_fields   = Hook::apply_filters($hook_name, $sort_fields);


	$image_fields = $image_fields ?? [];
@endphp

<form name="frmTable" method="get" id="frmTable" action="{{url()->current()}}">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Ordenação e Busca</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label>Busca</label>
						<div class="input-group">
							<div class="input-group-btn">
								<button id="btn-search-field" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-search"></i> <span>Buscar por</span>&nbsp;
								<span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu" id="search-fields-items">
									@foreach($search_fields as $field_name)
										<li><a class="search_field" data-field="{{$field_name}}" data-caption="{{ $fields_schema[$field_name]['comment'] }}" href="#">{{ $fields_schema[$field_name]['comment'] }}</a></li>
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
							<label>Filtrar por data:</label>
							<select id="select-field-date" class="form-control">
								@foreach($search_dates as $field_name)
									<option class="option_search_date" value="{{$field_name}}">{{ $fields_schema[$field_name]['comment'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xs-6 col-md-3">
						<label>Período:</label><br>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
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
						<label>Ordernar por:</label>
						<select id="select-field-order" class="form-control">
							<option class="option_search_field" value="0">Selecione</option>
							@foreach($sort_fields as $field_name)
								<option class="option_search_field" value="{{$field_name}}">{{ $fields_schema[$field_name]['comment'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xs-4 col-md-4">
					<label>Adicionar:</label><br>
					<div class="btn-group">
						<button type="button" data-dir="down" class="btn btn-default btn-order-add" data-toggle="tooltip" data-original-title="Do menor para o maior (ASC)"  data-placement="bottom"><i class="fa fa-fw fa-arrow-down"></i></button>
						<button type="button" data-dir="up"   class="btn btn-default btn-order-add" data-toggle="tooltip" data-original-title="Do maior para o menor (DESC)" data-placement="bottom"><i class="fa fa-fw fa-arrow-up"></i></button>
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
						<button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-filter"></i> Filtrar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /.box-body -->
	</div>
</form>

<div class="box box-success">
	<div class="box-header with-border">
		<div class="row">
			<div class="btn-group col-xs-10">
				@php $buttons_edit = isset($editable) ? $editable : true; @endphp
				@if ($buttons_edit)
					<button type="button" id="btn-table-add" class="btn btn-success"><i class="fa fa-fw fa-plus"></i> Adicionar</button>
					<button type="button" id="btn-table-edt" class="btn btn-info disabled"><i class="fa fa-edit"></i> Editar</button>
				@endif
				<button type="button" id="btn-table-viw" class="btn btn-default disabled"><i class="fa fa-eye"></i> Visualizar</button>
				@if ($buttons_edit)
					<button type="button" id="btn-table-del" class="btn btn-danger disabled"><i class="fa fa-close"></i> Excluir</button>
				@endif
			</div>
			@php $print_button = isset($exportable) ? $exportable : true; @endphp
			@if ($print_button)
				<div class="btn-group col-xs-2">
					<button type="button" id="btn-table-exp" class="btn btn-success pull-right"><i class="fa fa-fw fa-file-excel-o"></i> Exportar</button>
				</div>
			@endif
		</div>
	</div>
	<div class="box-body">
		@if ($has_table)
			<table class="table table-bordered table-striped table-condensed table-hover table-responsive">
				<tbody>
					<tr>
						<th style="width:20px"><input id="ch-rows-all" type="checkbox"></th>
						@foreach($display_fields as $field_name)
							<th>{{ $fields_schema[$field_name]['comment'] }}</i></th>
						@endforeach
					</tr>
					@foreach($table as $register)
					<tr>
						<td><input type="checkbox" class="ck-row" data-ids="{{$register['id']}}"></td>
						@foreach($display_fields as $field_name)
							@php
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
										case 'tinyint':
											$display_value = (intval($display_value) === 0) ? '<span class="label label-danger"><i class="fa fa-fw fa-close"></i></span>' : '<span class="label label-success"><i class="fa fa-fw fa-check"></i></span>';
										break;
										case 'enum':
											if ($field_name == 'status')
											{
												$display_value = admin_label_status($display_value);
											}
										break;
									}
								}

								if ($fields_schema[$field_name]['has_relation'])
								{
									$custom_field  = $fields_schema[$field_name]['relation']['custom_field'];
									$display_value = $register->$custom_field;
								}

								$hook_name     = hook_name(sprintf('admin_index_%s_%s', $table_name, $field_name));
								$display_value = Hook::apply_filters($hook_name, $display_value, $register->toArray());
							@endphp
							<td>{!! $display_value !!}</td>
						@endforeach
					</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<p>Não há resultados para esta consulta.</p>
		@endif
	</div>
	<div class="box-footer clearfix">
		{!! $paginate !!}
	</div>
</div>