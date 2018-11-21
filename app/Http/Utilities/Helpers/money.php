<?php
if (!function_exists('discount'))
{
	function discount($p_price, $p_discount)
	{
		return round($p_price - (($p_price * $p_discount) / 100), 2);
    }
}