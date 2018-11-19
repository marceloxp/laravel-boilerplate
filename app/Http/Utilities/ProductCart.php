<?php

namespace App\Http\Utilities;

use Session;

class ProductCart
{
	const name = 'cart';
	const prefix = 'cart.';

	private static function getName($p_id)
	{
		return starts_with($p_id, self::prefix) ? $p_id : sprintf(self::prefix . $p_id);
	}

	public static function get($p_id)
	{
		$name = self::getName($p_id);
		return \Session::get($name) ? 1;
	}

	public static function add($p_id, $p_quant = 1)
	{
		$name = self::getName($p_id);
		$quant = self::get($name);
		$quant += $p_quant;
		\Session::put($name, $quant);
		return $value;
	}

	public static function inc($p_id, $p_quant)
	{
		$name = self::getName($p_id);
		$value = self::get($name);
		$value->inc($p_quant);
		\Session::put($name, $value->toJson());
		return $value;
	}

	public static function set($p_id, $p_value = 0.00, $p_quant = 1.00)
	{
		$name = self::getName($p_id);
		$value = new Money($p_value, $p_quant);
		\Session::put($name, $value->toJson());
		return $value;
	}

	public static function del($p_id)
	{
		$name = self::getName($p_id);
		\Session::forget($name);
	}

	public static function reset()
	{
		\Session::forget(self::name);
	}
}