<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\CommonModel;

class Audit extends CommonModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'user_id'    => 'required',
			'username'   => 'required|max:255',
			'name'       => 'in:created,updated,removed|required|max:7',
			'url'        => 'required|max:65535',
			'ip'         => 'required|max:124',
			'useragent'  => 'required|max:255',
			'oldvalue'   => 'required|max:4294967295',
			'newvalue'   => 'required|max:4294967295',
		];
		return self::_validate($request, $rules, $id);
	}
}

