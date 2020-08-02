<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\CommonModel;

class Role extends CommonModel
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

		return self::_validate($request, $rules, $id);
    }

	public static function getColorBg($p_color_name)
	{
		$colors = config('colors.bg');

		if (array_key_exists($p_color_name, $colors))
		{
			return $colors[$p_color_name];
		}
		return $p_color_name;
	}

	public static function getColorByRole($p_role_name)
	{
		$result = Role::select('color')->where('name', $p_role_name)->first();
		return ($result->color ?? 'Vermelho');
	}

	public static function getBgColorByRole($p_role_name)
	{
		$register = Role::select('color')->where('name', $p_role_name)->first();
		$color_name = ($register->color ?? 'Vermelho');
		return Role::getColorBg($color_name);
	}

	public function users()
	{
		return $this->belongsToMany(\App\Models\Common\User::class);
	}
	
	public function menus()
	{
		return $this->belongsToMany(\App\Models\Common\Menu::class);
	}
	
	/**
	* Retrieve All Pivots related to One Target
	*/
	public function scopeMenuRole($query, $p_target_id)
	{
		return $query->join('menu_role', 'roles.id', '=', 'menu_role.role_id')->where('menu_role.menu_id', $p_target_id);
	}

	public static function ajustCollectionRolesColor($p_collection)
	{
		$p_collection->transform
		(
			function($role, $key)
			{
				$role['color'] = \App\Models\Common\Role::getBgColorByRole($role['name']);
				return $role;
			}
		);
		return $p_collection;
	}
}