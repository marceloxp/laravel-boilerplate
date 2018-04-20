<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Umstudio\MasterModel;

class Video extends MasterModel
{
	use SoftDeletes;
    protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

    public static function validate($request, $id = null)
    {
		$rules = 
		[
			'name'    => 'required|max:150',
			'youtube' => 'required|max:150'
		];

		return Role::_validate($request, $rules, $id);
    }
}
