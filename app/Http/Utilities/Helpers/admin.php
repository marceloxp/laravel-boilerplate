<?php
if (!function_exists('admin_label_status'))
{
	function admin_label_status($value)
	{
		$color = (in_array(strtolower($value), ['inativo','não','i','n','no','0','excluido'])) ? 'red' : 'green';
		return sprintf('<small class="label pull-center bg-%s">%s</small>', $color, $value);
	}
}

if (!function_exists('admin_badge_status'))
{
	function admin_badge_status($value)
	{
		return sprintf('<span class="badge">%s</span>', $value);
	}
}

if (!function_exists('admin_select'))
{
	function admin_select($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select = false)
	{
		$required = (!empty($p_required)) ? 'required' : '';

		$result  = sprintf('<select name="%s" id="%s" class="form-control" %s>', $p_field_name, $p_field_name, $required);
		if ($p_add_text_select)
		{
			$selected = (empty($p_field_value)) ? 'selected' : '';
			$result .= sprintf('<option value="" %s>%s</option>', $selected, 'Selecione');
		}

		foreach($p_options as $value => $text)
		{
			$selected = ($p_field_value == $value) ? 'selected' : '';
			$result .= sprintf('<option value="%s" %s>%s</option>', $value, $selected, $text);
		}
		$result .= '</select>';

		return $result;
	}
}

if (!function_exists('admin_select_simple'))
{
	function admin_select_simple($p_field_name, $p_options, $p_default_value, $p_field_value, $p_required, $p_add_text_select = false)
	{
		$required = (!empty($p_required)) ? 'required' : '';

		$result  = sprintf('<select name="%s" id="%s" class="form-control" %s>', $p_field_name, $p_field_name, $required);
		if ($p_add_text_select)
		{
			$selected = (empty($p_field_value)) ? 'selected' : '';
			$result .= sprintf('<option value="" %s>%s</option>', $selected, 'Selecione');
		}

		foreach($p_options as $option)
		{
			$selected = ($p_field_value == $option) ? 'selected' : '';
			if (empty($selected))
			{
				$selected = ($option == $p_default_value) ? 'selected' : '';
			}
			$result .= sprintf('<option value="%s" %s>%s</option>', $option, $selected, $option);
		}
		$result .= '</select>';

		return $result;
	}
}

if (!function_exists('admin_select_simple_with_add_button'))
{
	function admin_select_simple_with_add_button($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select = false)
	{
		$result = admin_select_simple($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select);
		
		$result = '
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					' . $result . '
				</div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
					<button type="button" class="btn btn-block btn-primary btn-select-add"><i class="fa fa-fw fa-plus"></i> Adicionar</button>
				</div>
			</div>
		';
		return $result;
	}
}

if (!function_exists('admin_table_config_set'))
{
	// admin_table_config_set('users', [ 'button' => [ 'name' => 'Send Mail' ] ] ); admin_table_config_set('users', [ 'printpreview' => true ] ); admin_table_config_set('users', [ 'button' => [ 'color' => 'blue' ] ] );
	// admin_table_config_set('customers', [ 'button' => [ 'name' => 'Send Mail' ] ] ); admin_table_config_set('customers', [ 'printpreview' => true ] ); admin_table_config_set('customers', [ 'button' => [ 'color' => 'blue' ] ] );
	function admin_table_config_set($p_table_name, $p_values)
	{
		$config_name = sprintf('tables.%s', $p_table_name);
		$old_value = config($config_name) ?? [];
		$new_value = array_merge_recursive_distinct($old_value, $p_values);
		if ($new_value !== $old_value)
		{
			config([$config_name => $new_value]);
			config(['tables.updated_at' => \Carbon\Carbon::now()]);
			$content = '<?php return' . PHP_EOL . var_export(config('tables'), true) . ';';
			File::put(config_path('tables.php'), $content);
		}
		return config($config_name) ?? [];
	}

	function admin_table_config_clear()
	{
		config(['tables' => []]);
		config(['tables.updated_at' => \Carbon\Carbon::now()]);
		$content = '<?php return' . PHP_EOL . var_export(config('tables'), true) . ';';
		File::put(config_path('tables.php'), $content);
		return true;
	}

	// admin_table_index_set_button('users', 'btn-custom-action', 'one', 'btn-info', true, 'fas fa-times-circle', 'More info', '');
	// admin_table_index_set_button('users', 'btn-send-mail', 'many', 'btn-success', true, 'fas fa-envelope', 'Send Mail', 'Confirma envio?');
	function admin_table_index_set_button($p_table, $p_button_id, $p_type, $p_color_style, $p_disabled, $p_icon, $p_text, $p_confirm_text)
	{
		$button = 
		[
			'button_id'    => $p_button_id,
			'type'         => $p_type,
			'color_style'  => $p_color_style,
			'disabled'     => $p_disabled,
			'icon'         => $p_icon,
			'text'         => $p_text,
			'confirm_text' => $p_confirm_text
		];

		$config = 
		[
			'admin' =>
			[
				'index' =>
				[
					'buttons' =>
					[
						$p_button_id => $button
					]
				]
			]
		];

		return admin_table_config_set($p_table, $config);
	}

	function admin_table_index_remove_button($p_table, $p_button_id)
	{
		$config = config(sprintf('tables.%s.admin.index.buttons', $p_table));
		if (array_key_exists($p_button_id, $config))
		{
			unset($config[$p_button_id]);
			config([sprintf('tables.%s.admin.index.buttons', $p_table) => $config]);
			config(['tables.updated_at' => \Carbon\Carbon::now()]);
			$content = '<?php return' . PHP_EOL . var_export(config('tables'), true) . ';';
			File::put(config_path('tables.php'), $content);
		}
		return true;
	}
}