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

	public static function getRoles($p_id)
	{
		$result = self::whereId($p_id)->get();
		return collect($result->first()->roles()->get()->toArray());
	}

	public static function addRole($p_menu_id, $p_role_name)
	{
		$menu_id      = $p_menu_id;
		$role         = db_select_one(['id'], 'roles', ['name' => $p_role_name], true);
		$role_id      = $role->id;
		$menu_role_id = \DB::table('menu_role')->insertGetId(compact('menu_id', 'role_id'));

		if (empty($menu_role_id))
		{
			throw new Exception('Falha na inserção da Regra.');
		}
		return $menu_role_id;
	}

	public static function addMenuRoot($p_caption, $p_roles)
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => 0,
				'type'       => 'root',
				'name'       => $p_caption,
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

	public static function addMenuHeader($parent_id, $p_caption, $p_ico, $p_roles)
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => $parent_id,
				'type'       => 'header',
				'name'       => $p_caption,
				'slug'       => str_slugfy($p_caption),
				'ico'        => 'fa-book',
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

	public static function addMenuDashboard($parent_id, $p_caption, $p_color, $p_ico, $p_roles, $p_route, $p_model)
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => $parent_id,
				'type'       => 'dashboard',
				'name'       => $p_caption,
				'slug'       => str_slugfy($p_caption),
				'color'      => $p_color,
				'ico'        => $p_ico,
				'link'       => $p_route,
				'model'      => $p_model,
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

/*

TRUNCATE TABLE blp_menus;
INSERT INTO blp_menus (name, slug, description, parent_id) VALUES
	('Menu'      , 'menu'      , 'Descrição de Menu'      , 0),
	('Tabelas'   , 'tabelas'   , 'Descrição de Tabelas'   , 1),
	('Categorias', 'categorias', 'Descrição de Categorias', 2),
	('Produtos'  , 'produtos'  , 'Descrição de Produtos'  , 2),
	('Sistema'   , 'sistema'   , 'Descrição de Sistema'   , 1),
	('Usuários'  , 'usuarios'  , 'Descrição de Usuários'  , 5),
	('Permissões', 'permissoes', 'Descrição de Permissões', 5)
;
INSERT INTO blp_menus (name, slug, description, parent_id) VALUES
	('Departamentos', 'departamentos', 'Descrição de Departamentos', 0),
	('Roupa'        , 'roupa'        , 'Descrição de Roupa'        , 8),
	('Masculina'    , 'masculina'    , 'Descrição de Masculina'    , 9),
	('Feminina'     , 'feminina'     , 'Descrição de Feminina'     , 9),
	('Eletrônicos'  , 'eletronicos'  , 'Descrição de Eletrônicos'  , 8),
	('Games'        , 'games'        , 'Descrição de Games'        , 12),
	('TVs'          , 'tvs'          , 'Descrição de TVs'          , 12)
;
SELECT * FROM blp_menus;

$menus = App\Models\Menu::nested()->get();
App\Models\Menu::renderAsHtml();
App\Models\Menu::renderAsJson();
App\Models\Menu::renderAsArray();
App\Models\Menu::renderAsDropdown();
App\Models\Menu::ping();

App\Models\Menu::attr(['data-seila' => 'seila'])->renderAsDropdown();
App\Models\Menu::attr(['class' => 'form-control'])->parent(1)->renderAsDropdown();

App\Models\Menu::attr(['name' => 'categories'])->selected(2)->renderAsDropdown();
App\Models\Menu::parent(2)->renderAsArray();
App\Models\Menu::customUrl('product/detail/{slug}')->renderAsHtml();
App\Models\Menu::selected(1)->renderAsDropdown();

*/
