<?php
if (!function_exists('discount'))
{
	function discount($p_price, $p_discount)
	{
		return round($p_price - (($p_price * $p_discount) / 100), 2);
	}
}

if (!function_exists('ensureFloat'))
{
	function ensureFloat($p_value)
	{
		if (is_string($p_value))
		{
			if (substr($p_value, -3, 1) == ',')
			{
				$p_value = str_replace('.', '', $p_value);
				$p_value = str_replace(',', '.', $p_value);
				return floatval($p_value);
			}
		}
		return floatval($p_value);
	}
}

if (!function_exists('parcelate'))
{
	function parcelate($p_value, $p_quant, $p_parcs)
	{
		return (floor((($p_value * 100) * $p_quant) / floatval($p_parcs)) / 100);
	}
}

if (!function_exists('parcelate_money'))
{
	function parcelate_money($p_value, $p_quant, $p_parcs)
	{
		return new \App\Http\Utilities\Money(floor((($p_value * 100) * $p_quant) / floatval($p_parcs)) / 100);
	}
}

if (!function_exists('moneyf'))
{
	function moneyf($p_value = 0)
	{
		$result = new \App\Http\Utilities\Money($p_value);
		return $result->formated;
	}
}

if (!function_exists('moneyv'))
{
	function moneyv($p_value = 0)
	{
		$result = new \App\Http\Utilities\Money($p_value);
		return $result->value;
	}
}

if (!function_exists('moneyr'))
{
	function moneyr($p_value = 0)
	{
		$result = new \App\Http\Utilities\Money($p_value);
		return $result->getRaw();
	}
}