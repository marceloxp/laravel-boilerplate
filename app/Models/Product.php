<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\Money;
use App\Http\Utilities\MasterModel;

class Product extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function boot()
	{
		parent::boot();
	
		self::saving(function($value){
			$value->price    = (new Money($value->price)   )->value;
			$value->discount = (new Money($value->discount))->value;
		});
	}

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'     => 'required|max:255',
			'price'    => 'required',
			'discount' => 'required',
			'status'   => 'in:Ativo,Inativo|required|max:7',
		];
		return Role::_validate($request, $rules, $id);
	}
}