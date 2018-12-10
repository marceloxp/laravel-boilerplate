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
		$this->value    = $this->getValue($p_value);
		$this->formated = number_format($this->value, 2, ',', '.');
		return $this;
	}

	public function inc($p_value)
	{
		$this->set($this->value + $this->getValue($p_value));
		return $this;
	}

	public function reset()
	{
		$this->set(0.00);
		return $this;
	}

	public function getRaw()
	{
		return preg_replace( '/[^0-9]/', '', $this->formated);
	}

	private function getValue($p_value)
	{
		if (is_a($p_value, \App\Http\Utilities\Money::class))
		{
			return $p_value->value;
		}
		elseif (is_a($p_value, \App\Http\Utilities\Payment::class))
		{
			return $p_value->price->value;
		}
		elseif (is_string($p_value))
		{
			if (strpos($p_value, ',') !== false)
			{
				$p_value = str_replace('.', '', $p_value);
				$p_value = str_replace(',', '.', $p_value);
				return floatval($p_value);
			}
		}
		
		return ensureFloat($p_value);
	}
}