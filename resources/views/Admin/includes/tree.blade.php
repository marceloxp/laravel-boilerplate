@php
	$search_fields = array_merge($display_fields);
	$hook_name     = hook_name(sprintf('admin_index_search_fields_%s', $table_name));
	$search_fields = Hook::apply_filters($hook_name, $search_fields);

	$sort_fields   = array_merge($display_fields);
	$hook_name     = hook_name(sprintf('admin_index_sort_fields_%s', $table_name));
	$sort_fields   = Hook::apply_filters($hook_name, $sort_fields);

	$image_fields = $image_fields ?? [];
@endphp

<div class="box box-success">
	<div class="box-header with-border">
		<div class="row">
			<div class="btn-group col-xs-10">
				@php $buttons_edit = isset($editable) ? $editable : true; @endphp
				@if ($buttons_edit)
					<button type="button" id="btn-table-add" class="btn btn-success"><i class="fa fa-fw fa-plus"></i> Adicionar</button>
					@if ($has_table)
						<button type="button" id="btn-table-edt" class="btn btn-info disabled"><i class="fa fa-edit"></i> Editar</button>
					@endif
				@endif
				@if ($has_table)
					<button type="button" id="btn-table-viw" class="btn btn-default disabled"><i class="fa fa-eye"></i> Visualizar</button>
					@if (!empty($table_many))
						<button type="button" id="btn-table-many" data-parent="{{ $table_name }}" data-link="{{ $table_many['name'] }}" class="btn btn-warning disabled"><i class="fa {{ $table_many['icon'] }}"></i> {{ $table_many['caption'] }}</button>
					@endif
					@if ($buttons_edit)
						<button type="button" id="btn-table-del" class="btn btn-danger disabled"><i class="fa fa-close"></i> Excluir</button>
					@endif
				@endif
			</div>
			@if ($has_table)
				@php $print_button = isset($exportable) ? $exportable : true; @endphp
				@if ($print_button)
					<div class="btn-group col-xs-2">
						<button type="button" id="btn-table-exp" class="btn btn-success pull-right"><i class="fa fa-fw fa-file-excel-o"></i> Exportar</button>
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
								$title_align = 'left';
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
									$field_align = 'left';
									$display_value = $register[$field_name];
									$prefix = ($field_name == 'name') ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $register['level']) . '&nbsp;' : '';
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