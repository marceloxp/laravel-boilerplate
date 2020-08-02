<?php

namespace App\Models\Examples;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\ExamplesModel;

class Gallery extends ExamplesModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function boot()
	{
		parent::boot();

		self::creating(function($model){
			$model->name = \Illuminate\Support\Str::slug($model->name);
		});

		self::updating(function($model){
			$model->name = \Illuminate\Support\Str::slug($model->name);
		});
	}

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'        => 'required|max:150',
			'description' => 'max:255',
			'status'      => 'required|in:Ativo,Inativo'
		];

		if (empty($id)) { $rules['image'] = 'required|max:3000'; }

		return Gallery::_validate($request, $rules, $id);
	}
}
