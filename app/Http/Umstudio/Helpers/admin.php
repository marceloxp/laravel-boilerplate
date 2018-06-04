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

if (!function_exists('admin_select_simple'))
{
    function admin_select_simple($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select = false)
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