<?php

namespace App\Http\Utilities;

class Money
{
	public $value    = 0.00;
	public $formated = '0,00';

	function __construct($p_value = 0.00)
	{
		$this->set($p_value);
	}

	function set($p_value)
	{
		if (is_a($p_value, \App\Http\Utilities\Money::class))
		{
			$p_value = $p_value->value;
		}
		elseif (is_a($p_value, \App\Http\Utilities\Payment::class))
		{
			$p_value = $p_value->price->value;
		}
		elseif (is_string($p_value))
		{
			if (strpos($p_value, ',') !== false)
			{
				$p_value = str_replace('.', '', $p_value);
				$p_value = str_replace(',', '.', $p_value);
				$p_value = floatval($p_value);
			}
		}

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

	public function getRaw()
	{
		return preg_replace( '/[^0-9]/', '', $this->formated);
	}
}