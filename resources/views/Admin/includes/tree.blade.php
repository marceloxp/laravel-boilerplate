@php
	$hook_name      = hook_name(sprintf('admin_index_display_fields_%s', $table_name));
	$display_fields = Hook::apply_filters($hook_name, $display_fields);
@endphp
<div class="box box-success">
	<div class="box-header with-border">
		<div class="row">
			<div class="btn-group col-xs-10">
				@php $buttons_edit = isset($editable) ? $editable : true; @endphp
				@if ($buttons_edit)
					<button type="button" id="btn-table-add" class="btn btn-success"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Adicionar</button>
					@if ($has_table)
						<button type="button" id="btn-table-edt" class="btn btn-info disabled"><i class="fas fa-edit"></i>&nbsp;&nbsp;Editar</button>
					@endif
				@endif
				@if ($has_table)
					<button type="button" id="btn-table-viw" class="btn btn-default disabled"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar</button>
					@if (!empty($table_many))
						<button type="button" id="btn-table-many" data-parent="{{ $table_name }}" data-link="{{ $table_many['name'] }}" class="btn btn-warning disabled"><i class="{{ $table_many['icon'] }}"></i>&nbsp;&nbsp;{{ $table_many['caption'] }}</button>
					@endif
					@if (!empty($pivot))
						@foreach($pivot as $pivot_config)
							<button type="button" id="btn-table-pvt" data-link="{{ $pivot_config['name'] }}" class="btn-table-pvt btn btn-warning disabled"><i class="{{ $pivot_config['icon'] }}"></i>&nbsp;&nbsp;{{ $pivot_config['caption'] }}</button>
						@endforeach
					@endif
					@if ($buttons_edit)
						<button type="button" id="btn-table-del" class="btn btn-danger disabled"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Excluir</button>
					@endif
				@endif
			</div>
			@if ($has_table)
				@php $print_button = isset($exportable) ? $exportable : true; @endphp
				@if ($print_button)
					<div class="btn-group col-xs-2">
						<button type="button" id="btn-table-exp" class="btn btn-success pull-right"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;Exportar</button>
					</div>
				@endif
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
							@php
								$title_align  = 'left';
								$column_title = $field_name;
								$column_title = $fields_schema[$field_name]['comment'];
							@endphp
							<th style="text-align: {{ $title_align }};" data-field="{{ $field_name }}">{{ $column_title }}</i></th>
						@endforeach
					</tr>
					@foreach($table as $register)
						@php
							$class = sprintf('treegrid-%s', $register['id']);
							if (!empty($register['parent_id']))
							{
								$class .= sprintf(' treegrid-parent-%s', $register['parent_id']);
							}
						@endphp
						<tr class="{{ $class }}">
							<td><input type="checkbox" class="ck-row" data-ids="{{ $register['id'] }}"></td>
							@foreach($display_fields as $field_name)
								@php
									$field_align   = 'left';
									$field_type    = $fields_schema[$field_name]['type'];
									$display_value = $register[$field_name];
									$prefix        = ($field_name == 'name') ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $register['level']) . '&nbsp;' : '';

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
											if ($field_name != 'id')
											{
												$field_align = 'right';
											}
										break;
										case 'tinyint':
											$display_value = (intval($display_value) === 0) ? '<span class="label label-danger"><i class="fa fa-fw fa-close"></i></span>' : '<span class="label label-success"><i class="fa fa-fw fa-check"></i></span>';
										break;
										case 'pivot':
											$pivot_table = $fields_schema[$field_name]['name'];
											$pivot_model = sprintf('\App\Models\%s', db_table_name_to_model($fields_schema[$field_name]['name']));
											$admin_index_function_exists = method_exists($pivot_model, 'onAdminShow');
											if ($admin_index_function_exists)
											{
												$display_value = $pivot_model::onAdminShow($register);
											}
											else
											{
												if ($register->$pivot_table)
												{
													$display_value = $register->$pivot_table->toBootstrapLabels()->toText();
												}
												else
												{
													$display_value = '&nbsp;';
												}
											}
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
									}

									$hook_name     = hook_name(sprintf('admin_index_%s_%s', $table_name, $field_name));
									$display_value = Hook::apply_filters($hook_name, $display_value, $register);
								@endphp


								<td align="{{ $field_align }}">{!! $prefix !!}{!! $display_value !!}</td>
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
			<span class="pull-left">&nbsp;</span>
			<span class="pull-right">
				<ul class="pagination">
					<li class="page-item">
						{{ $table->count() }} de {{ $table->count() }} registro{{ $table->count() > 1 ? 's' : '' }}, página 1 de um total de 1 página
					</li>
				</ul>
			</span>
		</div>
	@endif
</div>