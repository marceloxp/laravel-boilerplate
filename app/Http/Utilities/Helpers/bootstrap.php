<?php
if (!function_exists('alert_success'))
{
	function alert_success($p_string)
	{
		return sprintf('<div class="alert alert-success" role="alert">%s</div>', $p_string);
	}
}

if (!function_exists('alert_danger'))
{
	function alert_danger($p_string)
	{
		return sprintf('<div class="alert alert-danger" role="alert">%s</div>', $p_string);
	}
}

if (!function_exists('print_alert'))
{
	function print_alert()
	{
		$error   = \Session::pull('error');   if ($error)   { echo alert_danger($error);    }
		$success = \Session::pull('success'); if ($success) { echo alert_success($success); }
	}
}

if (!function_exists('bs_label'))
{
	function bs_label($p_id, $p_name, $p_color = 'bg-gray-active')
	{
		return sprintf('<small data-ids="%s" class="label %s">%s</small>', $p_id, $p_color, $p_name);
	}
}

if (!function_exists('fa_ico'))
{
	// echo fa_ico('fa-table', 'Table');
	function fa_ico($p_icon, $p_text = '')
	{
		return sprintf('<i class="fa fa-fw %s"></i> ', $p_icon) . $p_text;
	}
}

if (!function_exists('fa_ico_v5'))
{
	// echo fa_ico_v5('fas fa-table', 'Table');
	function fa_ico_v5($p_icon, $p_text = '')
	{
		return sprintf('<i class="%s"></i>&nbsp;&nbsp;', $p_icon) . $p_text;
	}
}

if (!function_exists('combo_fa_ico_v5'))
{
	// echo combo_fa_ico_v5('fa-table');
	$combo_fa_ico_scripts = false;
	function combo_fa_ico_v5($p_name, $p_icon = '')
	{
		global $combo_fa_ico_v5_scripts;
		if (!$combo_fa_ico_v5_scripts)
		{
			$combo_fa_ico_v5_scripts = true;
			$result = '
				<div class="input-group">
					<input type="text" name="' . $p_name . '" id="' . $p_name . '" data-placement="bottomRight" class="form-control icp icp-auto" value="' .  $p_icon. '"/>
					<span class="input-group-addon"></span>
				</div>
			';
		}

		return $result;
	}
}