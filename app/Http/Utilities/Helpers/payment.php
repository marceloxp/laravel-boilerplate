<?php
if (!function_exists('is_creditcard'))
{
	function is_creditcard($p_value)
	{
		return in_array
		(
			strtolower($p_value),
			[
				'cartão',
				'cartao',
				'cartão de crédito',
				'cartão de crédito',
				'cartão crédito',
				'cartão crédito',
				'crédito',
				'crédito',
				'creditcard',
				'credit card',
			]
		);
	}
}