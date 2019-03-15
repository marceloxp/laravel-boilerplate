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