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
}

/*

TRUNCATE TABLE blp_menus;
INSERT INTO blp_menus (name, slug, parent_id) VALUES
	('Menu', 'menu', 0),
	('Tabelas', 'tabelas', 1),
	('Categorias', 'categorias', 2),
	('Produtos', 'produtos', 2),
	('Sistema', 'sistema', 1),
	('Usuários', 'usuarios', 5),
	('Permissões', 'permissoes', 5)
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
