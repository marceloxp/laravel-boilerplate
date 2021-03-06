@php
	$image_fields = $image_fields ?? [];
	$colt = 0;

	$ark = array_keys($display_fields);
	$index = array_shift($ark);
	if ($index === 0)
	{
		$display_fields = collect($display_fields);
		$display_fields = $display_fields->mapWithKeys
		(
			function ($item)
			{
				return [$item => 12];
			}
		);
		$display_fields = $display_fields->all();
	}
@endphp

<div class="box box-primary">
	<!-- /.box-header -->
	<!-- form start -->
	<form name="frmTable" id="frmTable" method="post" enctype="multipart/form-data" action="{{ url()->current() }}">
		{{ csrf_field() }}
		<div class="box-body">

			<div class="row">

				@foreach($display_fields as $field_name => $field_width)
					@php
						if ( ($field_width === false) || ($field_width === null) ):
							continue;
						endif;
						$is_new      = (empty($register->id));
						$is_edit     = (!$is_new);
						$row_visible = 'block';
						$field_value = $register->$field_name;

						if (!array_key_exists($field_name, $fields_schema))
						{
							$display_value = '';
							$hook_name     = hook_name(sprintf('admin_edit_custom_field_%s_%s', $table_name, $field_name));
							$input         = Hook::apply_filters($hook_name, $register->toArray());
						}
						else
						{
							$field_type  = $fields_schema[$field_name]['type'];
							$input_type  = 'text';
							$input       = '<input type="text" class="form-control" placeholder="render error!!!">';
							$required    = (!$fields_schema[$field_name]['nullable']) ? 'required' : '';
							$asterisk    = ($required) ? '&nbsp;*' : '';
							$maxlength   = $fields_schema[$field_name]['max_length'];
							$relation    = $fields_schema[$field_name]['relation'] ?? [];
							$items       = $fields_schema[$field_name]['relation']['items'] ?? [];
							$field_label = $fields_schema[$field_name]['comment'];

							if ($items)
							{
								$input  = sprintf('<select name="%s" id="%s" autocomplete="no" class="form-control" %s>', $field_name, $field_name, $required);
								$input .= '<option value="0">Selecione</option>';
								foreach($items as $id => $text)
								{
									$selected = ((old($field_name) ?? $register->$field_name) == $id) ? 'selected' : '';
									$input   .= sprintf('<option value="%s" %s>%s</option>', $id, $selected, $text);
								}
								$input .= '</select>';
							}
							elseif (in_array($field_name, ['password','pwd','senha']))
							{
								if ($register->id)
								{
									$required = false;
								}
								$input = sprintf
								(
									'<input type="password" class="form-control" name="%s" id="%s" maxlength="%s" autocomplete="no" placeholder="" value="" %s>',
									$field_name,
									$field_name,
									$maxlength,
									$required
								);
							}
							elseif (in_array($field_name, ['parent_id']))
							{
								$array_tree = $model::getTree(['id','name','slug'], $fields_schema);

								$input = array_to_dropdown
								(
									$array_tree,
									[
										'name'      => 'parent_id',
										'value'     => $field_value,
										'optgroup'  => false,
										'attr'      => [ 'class' => 'form-control' ]
									]
								);
							}
							elseif (in_array($field_name, $image_fields) !== false)
							{
								$url_image = url('images/admin/no-image.png');
								if ($register->$field_name)
								{
									$url_image = uploaded_file_url($register->$field_name);
									$hook_name = hook_name(sprintf('admin_edit_%s_%s_image_src', $table_name, $field_name));
									$url_image = Hook::apply_filters($hook_name, $url_image, $register);
								}

								$preview_image = sprintf
								(
									'<div>' .
									'	<a href="javascript:window.open(\'%s\', \'preview_image\')">' . 
									'		<img class="uploaded_image" src="%s" style="max-height:150px; max-width:150px; border: 1px solid silver;" alt=""><br><br>' . 
									'	</a>' . 
									'</div>',
									$url_image,
									$url_image
								);

								if ( (!empty($required)) && ($is_edit == true) )
								{
									$required = '';
									$asterisk = '';
								}
								
								$input = sprintf
								(
									'<div class="image_form_group">%s<input type="file" name="%s" class="input_image_file" id="%s" %s></div>',
									$preview_image,
									$field_name,
									$field_name,
									$required
								);
							}
							elseif ($fields_schema[$field_name]['has_relation'])
							{
								$ref_model     = $fields_schema[$field_name]['relation']['ref_model'];
								$ref_table     = $fields_schema[$field_name]['relation']['ref_table'];
								$field_label   = $fields_schema[$field_name]['comment'];
								$rel_parent_id = $fields_schema[$field_name]['relation']['has_parent_id'];

								if ($rel_parent_id)
								{
									$ref_model_path    = db_table_name_to_model_path($table_schema, $ref_table);
									$ref_fields_schema = $ref_model_path::getFieldsMetaData();
									$array_tree        = $ref_model_path::getTree(['id','name','slug'], $ref_fields_schema);

									$input = array_to_dropdown
									(
										$array_tree,
										[
											'add_first' => $is_new,
											'name'      => $field_name,
											'value'     => $field_value,
											'optgroup'  => false,
											'attr'      => [ 'class' => 'form-control' ]
										]
									);
								}
								else
								{
									if ($one_table->field == $field_name)
									{
										$display_text = sprintf('%s - %s', $one_table->id, db_get_name($one_table->schema, $one_table->name, $one_table->id));
										$input = sprintf
										(
											'<input type="text" data-type="%s" class="form-control" name="show_%s" id="show_%s" maxlength="%s" autocomplete="no" placeholder="" value="%s" %s %s>',
											$field_type,
											$field_name,
											$field_name,
											$maxlength,
											$display_text,
											false,
											' disabled="disabled" '
										);

										$input .= sprintf
										(
											'<input type="hidden" name="%s" id="%s" value="%s">',
											$field_name,
											$field_name,
											$one_table->id
										);
									}
									else
									{
										$display_text = '';
										if (!$is_creating)
										{
											$display_text = ($register->$ref_model->name) ? $register->$ref_model->name : $register->$ref_model->description;
										}
										$field_text  = old(sprintf('%s_text', $field_name)) ?? (($register->id) ? sprintf('%s - %s', $register->$field_name, $display_text) : '');
										$field_value = old($field_name) ?? $register->$field_name;

										$input = sprintf
										(
											'
												<div class="input-group">
													<input type="text" class="form-control dontsend" readonly="readonly" disabled id="%s_text" name="%s_text" value="%s">
													<span class="input-group-btn">
														<button data-field="%s" data-model="%s" data-caption="%s" type="button" class="btn btn-primary btn-flat search-modal-field"><i class="fa fa-fw fa-search"></i> Procurar</button>
													</span>
												</div>
												<input type="hidden" readonly="readonly" id="%s" name="%s" value="%s">
											',
											// input with formated value "id - name"
											$field_name,
											$field_name,
											$field_text,
											
											// data values to modal search
											$field_name,
											$ref_model,
											$field_label,

											// oficial input with value
											$field_name,
											$field_name,
											$field_value
											// $register->$field_name
										);
									}
								}
							}
							else
							{
								switch ($field_type)
								{
									case 'enum':
										$input = admin_select_simple($field_name, $fields_schema[$field_name]['options'], $fields_schema[$field_name]['default_value'], (old($field_name) ?? $register->$field_name), $required);
									break;
									case 'tinyint':
									case 'boolean':
									case 'bool':
										$options = ['Não', 'Sim'];
										$input = admin_select($field_name, $options, (old($field_name) ?? $register->$field_name), $required);
									break;
									case 'longtext':
										$input = sprintf
										(
											'<textarea name="%s" id="%s" maxlength="%s" class="summernote" %s>%s</textarea>',
											$field_name,
											$field_name,
											$maxlength,
											$required,
											(old($field_name) ?? $register->$field_name)
										);
									break;
									case 'text':
										$input = sprintf
										(
											'<textarea class="form-control" name="%s" id="%s" maxlength="%s" rows="10" %s>%s</textarea>',
											$field_name,
											$field_name,
											$maxlength,
											$required,
											(old($field_name) ?? $register->$field_name)
										);
									break;
									case 'decimal':
										$field_value = new \App\Http\Utilities\Money((old($field_name) ?? $register->$field_name));
										$input = sprintf
										(
											'<input type="text" data-type="%s" class="form-control" name="%s" id="%s" maxlength="%s" autocomplete="no" placeholder="" value="%s" %s %s>',
											$field_type,
											$field_name,
											$field_name,
											$maxlength,
											$field_value->getRaw(),
											$required,
											$is_disabled
										);
									break;
									case 'pivot':
										$pivot_model = db_table_name_to_model_path($table_schema, $fields_schema[$field_name]['name']);
										$table_options = $pivot_model::get(['id','name']);
										$list_field_id = $fields_schema[$field_name]['list_field_id'];

										$field_value->transform
										(
											function($item, $key) use ($list_field_id)
											{
												return (string)$item->pivot->$list_field_id;
											}
										);

										$table_options->transform
										(
											function($item, $key) use ($field_value)
											{
												$selected = collect($field_value)->contains((string)$item->id) ? ' selected="selected"' : '';
												$result = sprintf('<option value="%s" %s>%s</option>', $item->id, $selected, $item->name);
												return $result;
											}
										);
										$table_options = $table_options->toText(PHP_EOL);
										$placeholder = 'Selecione um ou mais registros';
										$input = sprintf('<select class="form-control select2" name="%s[]" id="%s" multiple="multiple" data-placeholder="%s" style="width: 100%%;">', $field_name, $field_name, $placeholder);
										$input .= $table_options;
										$input .= '</select>';
									break;
									default:
										$value = (old($field_name) ?? $register->$field_name);
										$is_disabled = in_array($field_name, $disabled) ? ' disabled="disabled" ' : '';
										if (!is_array($value))
										{
											$input = sprintf
											(
												'<input type="text" data-type="%s" class="form-control" name="%s" id="%s" maxlength="%s" autocomplete="no" placeholder="" value="%s" %s %s>',
												$field_type,
												$field_name,
												$field_name,
												$maxlength,
												(old($field_name) ?? $register->$field_name),
												$required,
												$is_disabled
											);
										}
									break;
								}

								if ($field_name == 'id')
								{
									$input_type = 'hidden';
									$input = sprintf('<input type="hidden" name="%s" id="%s" value="%s" %s>', $field_name, $field_name, (old($field_name) ?? $register->$field_name), $required);
								}

								if ( (substr($field_name, -3) == '_id') && (!in_array($field_name, ['parent_id'])) )
								{
									if (isset($$field_name))
									{
										$_field_value = $$field_name;
										$input_type = 'hidden';
										$input = sprintf('<input type="hidden" name="%s" id="%s" value="%s" %s>', $field_name, $field_name, (old($field_name) ?? $register->$field_name ?? $_field_value), $required);
									}
								}

								if ($input_type == 'hidden')
								{
									$row_visible = 'none';
								}

								$field_value = (old($field_name) ?? $register->$field_name);
								$hook_name   = hook_name(sprintf('admin_edit_%s_%s', $table_name, $field_name));
								$input       = Hook::apply_filters($hook_name, $input, $field_value, $register, $fields_schema[$field_name]);

								if ($field_width == 0)
								{
									$row_visible = 'none';
								}
							}
						}
					@endphp

					<div class="field col-md-{{ $field_width }}">
						<div class="form-group" style="display: {{ $row_visible }}">
							<label for="{{ $field_name }}">{{ $field_label }}{!! $asterisk !!}</label>
							{!! $input !!}
						</div>
					</div>

					@php
						if ($row_visible != 'none')
						{
							$colt += $field_width;
							if ($colt >= 12)
							{
								$colt = 0;
								echo '</div><div class="row">';
							}
						}
					@endphp

				@endforeach

			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<div class="row">
				<div class="col-xs-6">
					<button type="button" class="btn btn-danger" onClick="javascript:history.back();"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;Voltar</button>
				</div>
				<div class="col-xs-6">
					<button type="submit" class="btn btn-success pull-right"><i class="fas fa-save"></i>&nbsp;&nbsp;Gravar</button>
				</div>
			</div>
		</div>
	</form>
</div>