<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\CommonModel;

class Lockregister extends CommonModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];
	
	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'       => 'required|max:255',
			'meme_id'    => 'required',
			'user_id'    => 'required'
		];
		return self::_validate($request, $rules, $id);
	}
}