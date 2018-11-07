<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class AddressType extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'   => 'required|max:124',
			'status' => 'in:Ativo,Inativo|required|max:7',
		];

		return Role::_validate($request, $rules, $id);
	}
}