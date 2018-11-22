<?php

namespace App\Http\Utilities;

class Money
{
	public $value    = 0.00;
	public $formated = '0,00';

	function __construct($p_value = 0.00)
	{
		if (is_a($p_value, \App\Http\Utilities\Money::class))
		{
			$this->set($p_value->value);
		}
		else
		{
			$this->set($p_value);
		}
	}

	public function set($p_value)
	{
		$this->value    = ensureFloat($p_value);
		$this->formated = number_format($p_value, 2, ',', '.');
	}

	public function inc($p_value)
	{
		$this->set($this->value + ensureFloat($p_value));
	}

	public function reset()
	{
		$this->set(0.00);
	}
}