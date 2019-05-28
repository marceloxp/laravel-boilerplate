<?php
namespace App\Http\Utilities;

class HookPrint
{
	public static $hooks = [];

	public static function add($p_name)
	{
		if (array_key_exists($p_name, self::$hooks))
		{
			self::$hooks[$p_name]++;
		}
		else
		{
			self::$hooks[$p_name] = 1;
		}
    }

	public static function get()
	{
		return self::$hooks;
	}
}