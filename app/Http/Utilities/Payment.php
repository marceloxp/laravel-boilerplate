<?php

namespace App\Http\Utilities;

class Payment
{
	public $price;
	public $quant;
	public $discount;
	public $unitary;
	public $subtotal;
	public $total;
	public $payments = [];

	function __construct($p_price = 0.00, $p_quant = 1.00, $p_discount = 0.00)
	{
		$this->price    = new Money($p_price);
		$this->quant    = new Money($p_quant);
		$this->discount = new Money($p_discount);
		$this->totalize();
	}

	public function add($p_name, $p_title = '', $p_discount = 0, $p_parcs = 1)
	{
		$payment = (Object)
		[
			'name'     => $p_name,
			'caption'  => $p_title,
			'discount' => new Money($p_discount),
			'unitary'  => new Money($this->getUnitaryValue($this->price->value, $p_discount)),
			'subtotal' => $this->price->value * $this->quant->value,
			'parcs'    => []
		];

		$payment->total = $payment->unitary->value * $this->quant->value;

		$parcs = [];
		for($k = 0; $k < $p_parcs; $k++)
		{
			$index = ($k+1);
			$parcs[$k] = [];

			$parcs[$k]['parc'] = $index;
			$parcs[$k]['unitary'] = new Money($this->parcelate($payment->unitary->value, $this->quant->value, $index));
			$parcs[$k]['total']   = new Money($parcs[$k]['unitary']->value * $index);
		}

		$this->payments[$p_name] = 
		[
			'slug'     => $p_name,
			'caption'  => $p_title,
			'price'    => new Money($this->price),
			'discount' => new Money($p_discount),
			'parcs'    => $parcs
		];
	}

	public function setQuant($p_quant = 1)
	{
		$this->quant = $p_quant;
		$this->totalize();
	}

	public function set($p_price, $p_quant, $p_discount = 0.00)
	{
		$this->price    = new Money($p_price);
		$this->quant    = new Money($p_quant);
		$this->discount = new Money($p_discount);
		$this->totalize();
	}

	public function inc($p_quant = 1)
	{
		$this->quant->inc(ensureFloat($p_quant));
		$this->totalize();
	}

	public function reset()
	{
		$this->price    = new Money(0.00);
		$this->discount = new Money(0.00);
		$this->quant    = new Money(0.00);
		$this->totalize();
	}

	private function getUnitaryValue($p_price, $p_discount)
	{
		return round($p_price - (($p_price * $p_discount) / 100), 2);
	}

	private function totalize()
	{
		$this->unitary  = new Money($this->getUnitaryValue($this->price->value, $this->discount->value));
		$this->subtotal = new Money($this->price->value * $this->quant->value);
		$this->total    = new Money($this->unitary->value * $this->quant->value);

		foreach ($this->payments as $key => $payment)
		{
			$this->addPayment($payment['name'], $payment['discount'], $payment['parcs']);
		}
	}

	private function parcelate($p_value, $p_quant, $p_parcs)
	{
		$result = (floor((($p_value * 100) * $p_quant) / floatval($p_parcs)) / 100);
		return $result;
	}
}