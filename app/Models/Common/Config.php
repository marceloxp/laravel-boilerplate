<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\Cached;
use App\Models\Masters\CommonModel;

class Config extends CommonModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function boot()
	{
		parent::boot();

		self::creating(function($model){
			Cached::forget('admin', ['config', 'get']);
		});

		self::created(function($model){
			// ... code here
		});

		self::updating(function($model){
			Cached::forget('admin', ['config', 'get']);
		});

		self::updated(function($model){
			// ... code here
		});

		self::deleting(function($model){
			Cached::forget('admin', ['config', 'get']);
		});

		self::deleted(function($model){
			// ... code here
		});
	}

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'   => 'required|max:150',
			'value'  => 'required|max:255',
			'status' => 'required|in:Ativo,Inativo'
		];

		return Config::_validate($request, $rules, $id);
	}
}