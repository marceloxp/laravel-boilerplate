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