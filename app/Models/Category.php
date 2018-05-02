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

	public function video()
	{
		return $this->hasMany('App\Models\Video');
	}
}