<?php

namespace App\Http\Utilities;

use Session;

class Pocket
{
	const name = 'pocket';
	const prefix = 'pocket.';

	private static function getName($p_name)
	{
		return starts_with($p_name, self::prefix) ? $p_name : sprintf(self::prefix . $p_name);
	}

	public static function get($p_name)
	{
		$name = self::getName($p_name);
		$value = \Session::get($name);
		$result = new Money($value);
		return $result;
	}

	public static function add($p_name, $p_value)
	{
		$name = self::getName($p_name);
		$value = self::get($name);
		$value->add($p_value);
		\Session::put($name, $value->toJson());
		return $value;
	}

	public static function inc($p_name, $p_quant)
	{
		$name = self::getName($p_name);
		$value = self::get($name);
		$value->inc($p_quant);
		\Session::put($name, $value->toJson());
		return $value;
	}

	public static function set($p_name, $p_value = 0.00, $p_quant = 1.00)
	{
		$name = self::getName($p_name);
		$value = new Money($p_value, $p_quant);
		\Session::put($name, $value->toJson());
		return $value;
	}

	public static function del($p_name)
	{
		$name = self::getName($p_name);
		\Session::forget($name);
	}

	public static function reset()
	{
		\Session::forget(self::name);
	}
}