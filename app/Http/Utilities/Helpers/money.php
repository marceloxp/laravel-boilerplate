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