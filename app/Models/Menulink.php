<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Menulink extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'menusection_id' => 'required',
			'name'           => 'required|max:124',
			'icon'           => 'required|max:24',
			'group'          => 'required|max:124',
			'route'          => 'required|max:124',
			'status'         => 'in:Ativo,Inativo|required|max:7',
		];
		return Role::_validate($request, $rules, $id);
	}

	public function menusection()
	{
		return $this->belongsTo(\App\Models\Menusection::class);
	}
}