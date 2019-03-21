<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;
use Nestable\NestableTrait;
use App\Traits\TreeModelTrait;

class Menu extends MasterModel
{
	use SoftDeletes;
	use NestableTrait;
	use TreeModelTrait;

	protected $parent = 'parent_id';

	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'parent_id'  => 'required',
			'name'       => 'required|max:255',
			'slug'       => 'required|max:255',
		];
		return Role::_validate($request, $rules, $id);
	}

	/**
	* Retrieve Tags pivot Table
	*/
	public function roles()
	{
		return $this->belongsToMany(\App\Models\Role::class);
	}

	public static function ajustRoles($p_tree_array)
	{
		$p_tree_array->transform
		(
			function ($item, $key)
			{
				$item['roles'] = self::getRoles($item['id'])->toArray();
				if (!empty($item['child']))
				{
					$item['child'] = self::ajustRoles(collect($item['child']))->toArray();
				}
				return $item;
			}
		);
		return $p_tree_array;
	}

	public static function getRoles($p_id)
	{
		$result = self::where('id', $p_id)->get();
		return collect($result->first()->roles()->get()->toArray());
	}

	public static function addRole($p_menu_id, $p_role_name)
	{
		$menu_id      = $p_menu_id;
		$role         = db_select_one(\App\Models\Role::class, ['id'], ['name' => $p_role_name], true);
		$role_id      = $role->id;
		$menu_role_id = \DB::table('menu_role')->insertGetId(compact('menu_id', 'role_id'));

		if (empty($menu_role_id))
		{
			throw new Exception('Falha na inserção da Regra.');
		}
		return $menu_role_id;
	}

	public static function addMenuRoot($p_caption, $p_roles, $p_ico)
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => 0,
				'order'      => 0,
				'type'       => 'root',
				'name'       => $p_caption,
				'ico'        => $p_ico,
				'slug'       => str_slugfy($p_caption),
				'created_at' => $now
			]
		);
		if (!$menu_id) { throw new Exception('Falha na inserção do Menu.'); }

		foreach ($p_roles as $role)
		{
			\App\Models\Menu::addRole($menu_id, $role);
		}

		return $menu_id;
	}

	public static function addMenuHeader($parent_id, $p_caption, $p_ico, $p_roles, $p_order = 0)
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => $parent_id,
				'order'      => $p_order,
				'type'       => 'header',
				'name'       => $p_caption,
				'slug'       => str_slugfy($p_caption),
				'ico'        => $p_ico,
				'created_at' => $now
			]
		);
		if (!$menu_id) { throw new Exception('Falha na inserção do Menu.'); }

		foreach ($p_roles as $role)
		{
			\App\Models\Menu::addRole($menu_id, $role);
		}

		return $menu_id;
	}

	public static function addMenuLink($parent_id, $p_caption, $p_ico, $p_roles, $p_route)
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => $parent_id,
				'type'       => 'link',
				'name'       => $p_caption,
				'slug'       => str_slugfy($p_caption),
				'ico'        => $p_ico,
				'route'      => $p_route,
				'created_at' => $now
			]
		);
		if (!$menu_id) { throw new Exception('Falha na inserção do Menu.'); }

		foreach ($p_roles as $role)
		{
			\App\Models\Menu::addRole($menu_id, $role);
		}

		return $menu_id;
	}

	public static function menuExists($p_parent_id, $p_caption)
	{
		return self::where(['name' => $p_caption, 'parent_id' => $p_parent_id])->exists();
	}

	public static function getMenuId($p_parent_id, $p_caption)
	{
		$result = self::where(['name' => $p_caption, 'parent_id' => $p_parent_id])->get(['id'])->first();
		if (empty($result)) { return null; }
		return $result->id;
	}

	public static function addMenuLinkToTablesItem($p_caption, $p_ico, $p_roles, $p_route)
	{
		$parent_id = db_select_id(self, ['slug' => 'tabelas'], true);
		$menu_id = getMenuId($parent_id, $p_caption);
		if (empty($menu_id))
		{
			$now = \Carbon\Carbon::now();
			$menu_id = \App\Models\Menu::insertGetId
			(
				[
					'parent_id'  => $parent_id,
					'type'       => 'link',
					'name'       => $p_caption,
					'slug'       => str_slugfy($p_caption),
					'ico'        => $p_ico,
					'route'      => $p_route,
					'created_at' => $now
				]
			);
			if (!$menu_id) { throw new Exception('Falha na inserção do Menu.'); }

			foreach ($p_roles as $role)
			{
				\App\Models\Menu::addRole($menu_id, $role);
			}
		}
		return $menu_id;
	}

	public static function addMenuInternalLink($parent_id, $p_caption, $p_ico, $p_roles, $p_link, $p_target = '_self')
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => $parent_id,
				'type'       => 'internal-link',
				'name'       => $p_caption,
				'slug'       => str_slugfy($p_caption),
				'ico'        => $p_ico,
				'link'       => $p_link,
				'target'     => $p_target,
				'created_at' => $now
			]
		);
		if (!$menu_id) { throw new Exception('Falha na inserção do Menu.'); }

		foreach ($p_roles as $role)
		{
			\App\Models\Menu::addRole($menu_id, $role);
		}

		return $menu_id;
	}
}