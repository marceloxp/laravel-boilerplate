<?php

namespace App\Http\Utilities;

class Money
{
	public $value = 0.00;
	public $quant = 0.00;
	public $total = 0.00;

	function __construct($p_value = 0.00, $p_quant = 0.00)
	{
		if (is_array($p_value))
		{
			$attrs = array_values($p_value);
			$this->set($attrs[0], $attrs[1]);
		}
		elseif ($p_value == null)
		{
			$this->set(0.00, 0.00);
		}
		elseif (is_string($p_value))
		{
			if (str_contains($p_value, '{'))
			{
				$this->fromJson($p_value);
			}
			else
			{
				$p_value = str_replace('.', '', $p_value);
				$p_value = str_replace(',', '.', $p_value);
				$p_value = floatval($p_value);
				$this->set($p_value, $p_quant);
			}
		}
		else
		{
			$this->set($p_value, $p_quant);
		}
	}

	public function set($p_value, $p_quant)
	{
		$this->value = $p_value;
		$this->quant = $p_quant;
		$this->total = ($p_value * $p_quant);
		$this->formatValues();
	}

	public function add($p_value)
	{
		$this->value += $p_value;
		$this->total = ($this->value * $this->quant);
		$this->formatValues();
	}

	public function inc($p_quant)
	{
		$this->quant += $p_quant;
		$this->total = ($this->value * $this->quant);
		$this->formatValues();
	}

	public function reset()
	{
		$this->value = 0.00;
		$this->quant = 0.00;
		$this->value = 0.00;
		$this->formatValues();
	}

	private function format($p_value)
	{
		return number_format($p_value, 2, ',', '.');
	}

	private function formatValues()
	{
		$object = 
		[
			'value' => $this->format($this->value),
			'quant' => $this->format($this->quant),
			'total' => $this->format($this->total),
		];
		$this->formated = (Object)$object;
	}

	public function toJson()
	{
		return json_encode
		(
			[
				'value'    => $this->value,
				'quant'    => $this->quant,
				'total'    => $this->total,
				'formated' => (array)$this->formated
			]
		);
	}

	public function fromJson($p_json)
	{
		$attrs = json_decode($p_json, true);
		$this->set($p_json['value'], $p_json['quant']);
	}
}