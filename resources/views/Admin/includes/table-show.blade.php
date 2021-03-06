@php
	$image_fields = $image_fields ?? [];
@endphp

<div class="box box-primary">
	<!-- /.box-header -->
	<div class="box-body">
		<table class="table table-bordered">
			@foreach($display_fields as $field_name)
				@php
					$field_label = $fields_schema[$field_name]['comment'];
					if ($fields_schema[$field_name]['has_relation'])
					{
						$field_label = $fields_schema[$field_name]['relation']['comment'];
					}
				@endphp
				<tr>
					<td width="100" align="right">
						<label >{{ $field_label }}</label>
					</td>
					<td>
						@php
							$field_type = $fields_schema[$field_name]['type'];
							
							if ($fields_schema[$field_name]['has_relation'])
							{
								$relat_prop_name = $fields_schema[$field_name]['relation']['prop_name'];
								$display_value = $register->$relat_prop_name->name;
							}
							elseif (in_array($field_name, $image_fields) !== false)
							{
								$display_value = $register->$field_name;
								$display_value = sprintf('<img src="%s" style="max-height:150px; max-width:150px" alt="">', url('uploads/images/' . $display_value));
							}
							else
							{
								$display_value = $register->$field_name;
							}

							switch ($field_type)
							{
								case 'decimal':
									$display_value = (new \App\Http\Utilities\Money(floatval($display_value), 1))->formated;
								break;
								case 'tinyint':
									$display_value = (intval($display_value) === 0) ? '<span class="label label-danger"><i class="fas fa-times-circle"></i></span>' : '<span class="label label-success"><i class="fa fa-fw fa-check"></i></span>';
								break;
								case 'pivot':
									$pivot_model = sprintf('\App\Models\%s', db_table_name_to_model($fields_schema[$field_name]['name']));
									$admin_index_function_exists = method_exists($pivot_model, 'onAdminShow');
									if ($admin_index_function_exists)
									{
										$display_value = $pivot_model::onAdminShow($register);
									}
									else
									{
										$_field = $fields_schema[$field_name]['name'];
										$display_value = $register->$_field->toBootstrapLabels()->toText();
									}
								break;
								case 'enum':
									if ($field_name == 'status')
									{
										$display_value = admin_label_status($display_value);
									}
								break;
							}

							$hook_name     = hook_name(sprintf('admin_show_%s_%s', $table_name, $field_name));
							$display_value = Hook::apply_filters($hook_name, $display_value, $register->toArray());
						@endphp
						<div data-field="{{ $field_name }}" data-type="{{ $field_type }}">
							{!! $display_value !!}
						</div>
					</td>
				</tr>
			@endforeach
		</table>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		<div class="row">
			<div class="col-xs-6">
				<button type="button" class="btn btn-danger" onClick="javascript:history.back();"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;Voltar</button>
			</div>
		</div>
	</div>
</div>