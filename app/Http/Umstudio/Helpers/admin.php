<?php
if (!function_exists('admin_helpers_exists'))
{
    function admin_helpers_exists($value)
	{
		return true;
    }
}

if (!function_exists('admin_label_status'))
{
    function admin_label_status($value)
	{
		$color = (in_array(strtolower($value), ['inativo','não','i','n','no','0','excluido'])) ? 'red' : 'green';
		return sprintf('<small class="label pull-center bg-%s">%s</small>', $color, $value);
    }
}

if (!function_exists('admin_select2_enum'))
{
    function admin_select2_enum($p_field_name, $p_options, $p_field_value, $p_required)
	{
		$required = (!empty($required)) ? 'required' : '';

		$result  = sprintf('<select name="%s" id="%s" class="form-control" %s>', $p_field_name, $p_field_name, $required);
		foreach($p_options as $option)
		{
			$selected = ($p_field_value == $option) ? 'selected' : '';
			$result .= sprintf('<option value="%s" %s>%s</option>', $option, $selected, $option);
		}
		$result .= '</select>';

		return $result;
    }
}