<?php
use \App\Http\Utilities\Datasite;

if (!function_exists('datasite_add'))
{
	function datasite_add($name_or_variable, $value_or_null = null)
	{
		if (is_array($name_or_variable))
		{
			Datasite::$datasite = array_merge_recursive(Datasite::$datasite, $name_or_variable);
		}
		else
		{
			Datasite::$datasite[$name_or_variable] = $value_or_null;
		}
    }
}

if (!function_exists('datasite_get'))
{
	function datasite_get()
	{
		return Datasite::$datasite;
    }
}