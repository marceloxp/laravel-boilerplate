<?php
namespace App\Models;

use App\Http\Utilities\Cart;
use App\Http\Utilities\Money;
use App\Http\Utilities\ProductValue;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Product extends MasterModel
{
	public $appends = ['cash'];

	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'     => 'required|max:255',
			'price'    => 'required',
			'status'   => 'in:Ativo,Inativo|required|max:7',
		];
		return Role::_validate($request, $rules, $id);
	}

	public function getCashAttribute()
	{
		$result = new \App\Http\Utilities\Payment($this->price, \App\Http\Utilities\Cart::quant($this->id), $this->discount);
		$payments = \App\Models\Payment::all();
		foreach ($payments as $payment)
		{
			$result->add($payment->name, $payment->paymenttype->name, $payment->description, $payment->discount, $payment->parcs);
		}
		return $result;
	}
}