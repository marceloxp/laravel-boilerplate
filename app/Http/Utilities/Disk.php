<?php

namespace App\Http\Utilities;

class Disk
{
	private static $disk = [];

	public static function disk($name_or_variable, $value_or_null = null)
	{
		if (is_array($name_or_variable))
		{
			self::$datasite = array_merge_recursive(self::$datasite, $name_or_variable);
		}
		else
		{
			self::$datasite[$name_or_variable] = $value_or_null;
		}
    }

	public static function get()
	{
		return self::$datasite;
	}
}