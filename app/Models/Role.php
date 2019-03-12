<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Role extends MasterModel
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
		return $this->belongsToMany(\App\Models\User::class);
	}

	public function scopeMenusectionRole($query, $p_role_id)
	{
		return $this->scopeMasterMany($query, \App\Models\Menusection::class, \App\Models\Role::class, $p_role_id);
	}

	/**
	* Retrieve All Roles related to One Menu
	*/
	public function scopeMenuRole($query, $p_menu_id)
	{
		return $query->join('menu_role', 'roles.id', '=', 'menu_role.role_id')->where('menu_role.menu_id', $p_menu_id);
	}
	
	/**
	* Retrieve All Pivots related to One Target
	*/
	public function scopeRoleVideo($query, $p_target_id)
	{
		return $query->join('role_video', 'roles.id', '=', 'role_video.role_id')->where('role_video.video_id', $p_target_id);
	}
}