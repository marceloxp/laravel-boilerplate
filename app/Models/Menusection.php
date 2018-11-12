<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Menusection extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'   => 'required|max:124',
			'icon'   => 'required|max:24',
			'status' => 'in:Ativo,Inativo|required|max:7',
		];
		return Role::_validate($request, $rules, $id);
	}

	/**
	* Retrieve Tags pivot Table
	*/
	public function roles()
	{
		return $this->belongsToMany(\App\Models\Role::class);
	}

	public function menulinks()
	{
		return $this->hasMany(\App\Models\Menulink::class);
	}
}