<?php

namespace App\Http\Utilities;

class ProductValue
{
	public $price    = 0.00;
	public $discount = 0.00;
	public $unitary  = 0.00;
	public $quant    = 0.00;
	public $subtotal = 0.00;
	public $total    = 0.00;
	public $payments = [];

	function __construct($p_price = 0.00, $p_quant = 0.00, $p_discount = 0.00)
	{
		if (is_array($p_price))
		{
			$attrs = array_values($p_price);
			$this->set($attrs[0], $attrs[1], $attrs[2]);
		}
		elseif ($p_price == null)
		{
			$this->set(0.00, 0.00, 0.00);
		}
		elseif (is_string($p_price))
		{
			$this->fromJson($p_price);
		}
		else
		{
			$this->set($p_price, $p_quant, $p_discount);
		}
	}

	public function set($p_price, $p_quant, $p_discount = 0.00)
	{
		$this->price    = $p_price;
		$this->quant    = $p_quant;
		$this->discount = $p_discount;
		$this->totalize();
		$this->formatValues();
	}

	public function inc($p_quant = 1)
	{
		$this->quant += $p_quant;
		$this->totalize();
		$this->formatValues();
	}

	public function reset()
	{
		$this->price    = 0.00;
		$this->discount = 0.00;
		$this->quant    = 0.00;
		$this->formatValues();
	}

	private function format($p_value)
	{
		return number_format($p_value, 2, ',', '.');
	}

	private function getUnitaryValue($p_price, $p_discount)
	{
		return round($p_price - (($p_price * $p_discount) / 100), 2);
	}

	private function totalize()
	{
		$this->unitary  = $this->getUnitaryValue($this->price, $this->discount);
		$this->subtotal = $this->price * $this->quant;
		$this->total    = $this->unitary * $this->quant;

		foreach ($this->payments as $key => $payment)
		{
			$this->setPayment($payment['name'], $payment['discount'], $payment['parcs']);
		}
	}

	private function parcelate($p_value, $p_quant, $p_parcs)
	{
		$result = (floor((($p_value * 100) * $p_quant) / floatval($p_parcs)) / 100);
		return $result;
	}

	public function getPayment($p_name)
	{
		if (!array_key_exists($p_name, $this->payments))
		{
			return false;
		}

		return 
		[
			'raw' => $this->payments[$p_name],
			'fmt' => $this->formated->payments[$p_name]
		];
	}

	public function setPayment($p_name, $p_discount, $p_parcs)
	{
		$payment = 
		[
			'name'     => $p_name,
			'discount' => $p_discount,
			'unitary'  => $this->getUnitaryValue($this->price, $p_discount),
			'subtotal' => $this->price * $this->quant,
			'parcs'    => []
		];

		$payment['total'] = $payment['unitary'] * $this->quant;

		$raw_parcs = [];
		$fmt_parcs = [];
		for($k = 0; $k < $p_parcs; $k++)
		{
			$index = ($k+1);
			$raw_parcs[$k] = [];
			$fmt_parcs[$k] = [];

			$raw_parcs[$k]['parc'] = $index;
			$fmt_parcs[$k]['parc'] = $index;

			$raw_parcs[$k]['unitary'] = $this->parcelate($payment['total'], $this->quant, $index);
			$fmt_parcs[$k]['unitary'] = $this->format($raw_parcs[$k]['unitary']);

			$raw_parcs[$k]['total'] = $raw_parcs[$k]['unitary'] * $index;
			$fmt_parcs[$k]['total'] = $this->format($raw_parcs[$k]['total']);
		}

		$this->payments[$p_name] = 
		[
			'discount' => $p_discount,
			'parcs'    => $raw_parcs
		];

		$this->formated->payments[$p_name] = $fmt_parcs;
	}

	private function formatValues()
	{
		$object = 
		[
			'price'    => $this->format($this->price),
			'discount' => $this->format($this->discount),
			'unitary'  => $this->format($this->unitary),
			'quant'    => $this->format($this->quant),
			'subtotal' => $this->format($this->subtotal),
			'total'    => $this->format($this->total),
		];
		$this->formated = (Object)$object;
	}

	public function toJson()
	{
		return json_encode
		(
			[
				'price'    => $this->price,
				'discount' => $this->discount,
				'quant'    => $this->quant,
				'subtotal' => $this->subtotal,
				'total'    => $this->total,
				'formated' => (array)$this->formated
			]
		);
	}

	public function fromJson($p_json)
	{
		$attrs = json_decode($p_json, true);
		$this->set($attrs['price'], $attrs['quant'], $attrs['discount']);
	}
}