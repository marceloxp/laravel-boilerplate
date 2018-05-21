@php
	$image_fields = $image_fields ?? [];
	// dump(old());
@endphp

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa {{$panel_title[2]}}"></i> {{$panel_title[1]}}</h3>
	</div>
	<!-- /.box-header -->
	<!-- form start -->
	<form name="frmTable" id="frmTable" method="post" enctype="multipart/form-data" action="{{url()->current()}}">
		{{ csrf_field() }}
		<div class="box-body">
			@foreach($display_fields as $field_name)
				@php
					$row_visible  = 'block';
					$field_type   = $fields_schema[$field_name]['type'];
					$input_type   = 'text';
					$input        = '<input type="text" class="form-control" placeholder="render error!!!">';
					$required     = (!$fields_schema[$field_name]['nullable']) ? 'required' : '';
					$maxlength    = $fields_schema[$field_name]['max_length'];
					$relation     = $fields_schema[$field_name]['relation'] ?? [];
					$items        = $fields_schema[$field_name]['relation']['items'] ?? [];
					$field_label  = $fields_schema[$field_name]['comment'];
					
					if ($items)
					{
						$input = sprintf('<select name="%s" id="%s" autocomplete="no" class="form-control" %s>', $field_name, $field_name, $required);
						$input .= '<option value="0">Selecione</option>';
						foreach($items as $id => $text)
						{
							$selected = ((old($field_name) ?? $register->$field_name) == $id) ? 'selected' : '';
							$input .= sprintf('<option value="%s" %s>%s</option>', $id, $selected, $text);
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
					elseif (in_array($field_name, $image_fields) !== false)
					{
						$url_image = url('images/admin/no-image.png');
						if ($register->$field_name)
						{
							$url_image = uploaded_image($register->$field_name);
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
						$ref_model   = $fields_schema[$field_name]['relation']['ref_model'];
						$field_label = $fields_schema[$field_name]['relation']['comment'];
						$field_text  = old(sprintf('%s_text', $field_name)) ?? (($register->id) ? sprintf('%s - %s', $register->$field_name, $register->$ref_model->name) : '');
						$field_value = old($field_name) ?? $register->$field_name;

						$input = sprintf
						(
							'
								<div class="input-group">
									<input type="text" class="form-control dontsend" readonly="readonly" id="%s_text" name="%s_text" value="%s">
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
					else
					{
						switch ($field_type)
						{
							case 'enum':
								$input = admin_select2_enum($field_name, $fields_schema[$field_name]['options'], (old($field_name) ?? $register->$field_name), $required);
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
							default:
								$value = (old($field_name) ?? $register->$field_name);
								if (!is_array($value))
								{
									$input = sprintf
									(
										'<input type="text" class="form-control" name="%s" id="%s" maxlength="%s" autocomplete="no" placeholder="" value="%s" %s>',
										$field_name,
										$field_name,
										$maxlength,
										(old($field_name) ?? $register->$field_name),
										$required
									);
								}
							break;
						}
					}

					if ($field_name == 'id')
					{
						$input_type = 'hidden';
						$input = sprintf('<input type="hidden" name="%s" id="%s" value="%s" %s>', $field_name, $field_name, (old($field_name) ?? $register->$field_name), $required);
					}

					if ($input_type == 'hidden')
					{
						$row_visible = 'none';
					}

					$input = Hook::get(sprintf('admin_edit_%s_%s', $table_name, $field_name),[$input, $fields_schema[$field_name], (old($field_name) ?? $register->$field_name)],function($input){ return $input; });
				@endphp
				<div class="form-group" style="display: {{$row_visible}}">
					<label for="{{$field_name}}">{{ $field_label }}</label>
					{!!$input!!}
				</div>
			@endforeach
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<div class="row">
				<div class="col-xs-6">
					<button type="button" class="btn btn-danger" onClick="javascript:history.back();"><i class="fa fa-fw fa-arrow-left"></i> Voltar</button>
				</div>
				<div class="col-xs-6">
					<button type="submit" class="btn btn-success pull-right"><i class="fa fa-fw fa-save"></i> Gravar</button>
				</div>
			</div>
		</div>
	</form>
</div>