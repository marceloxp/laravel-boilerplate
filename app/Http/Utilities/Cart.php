<?php

namespace App\Http\Utilities;

use Session;

class Cart
{
	const name = 'cart';
	const prefix = 'cart.';

	private static function getName($p_id)
	{
		return starts_with($p_id, self::prefix) ? $p_id : sprintf('%s%s', self::prefix, $p_id);
	}

	public static function quant($p_id)
	{
		$cart = self::all();
		return (array_key_exists($p_id, $cart)) ? $cart[$p_id] : 1;
	}

	public static function has($p_id)
	{
		$cart = self::all();
		return (array_key_exists($p_id, $cart));
	}

	public static function all()
	{
		return \Session::get(self::name) ?? [];
	}

	public static function get($p_id)
	{
		$name = self::getName($p_id);
		return \Session::get($name) ?? 1;
	}

	public static function add($p_id, $p_quant = 1)
	{
		$name = self::getName($p_id);
		$quant = self::get($name);
		$quant += $p_quant;
		\Session::put($name, $quant);
		return self::all();
	}

	public static function inc($p_id, $p_quant = 1)
	{
		return self::add($p_id, $p_quant = 1);
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