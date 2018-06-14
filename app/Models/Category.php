<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Umstudio\MasterModel;

class Category extends MasterModel
{
	use SoftDeletes;
    protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

    public static function validate($request, $id = '')
    {
		$rules = 
		[
			'name'        => 'required|min:5|max:150|unique:roles,name,' . $id,
			'description' => 'required|min:5|max:255'
		];

		return Role::_validate($request, $rules, $id);
    }

    public function videos()
    {
    	return $this->hasMany(\App\Models\Video::class);
    }
}