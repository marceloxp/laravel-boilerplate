<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;
use App\Traits\TreeModelTrait;

class Category extends MasterModel
{
	use SoftDeletes;
	use TreeModelTrait;

	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function boot()
	{
		parent::boot();

		self::saving
		(
			function($model)
			{
				$model->slug = str_slugify($model->name);
			}
		);

		self::updating
		(
			function($model)
			{
				$model->slug = str_slugify($model->name);
			}
		);
	}

	public static function addRoot($p_name, $p_description)
	{
		return self::create(['parent_id' => 0, 'name' => $p_name, 'description' => $p_description]);
	}

	public static function addSubCategory($parent_id, $p_name, $p_description)
	{
		return self::create(['parent_id' => $parent_id, 'name' => $p_name, 'description' => $p_description]);
	}

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'        => 'required|max:150',
			'slug'        => 'required|max:255',
			'parent_id'   => 'required',
			'description' => 'max:255',
			'image'       => 'max:255',
			'status'      => 'in:Ativo,Inativo|max:7',
		];
		return Role::_validate($request, $rules, $id);
	}
}

