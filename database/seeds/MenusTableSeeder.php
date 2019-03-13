<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = \Carbon\Carbon::now();
		$menu_id = \App\Models\Menu::insertGetId
		(
			[
				'parent_id'  => 0,
				'type'       => 'root',
				'name'       => 'Menu',
				'slug'       => 'menu',
				'color'      => 'bg-green',
				'ico'        => 'fa-table',
				'created_at' => $now
			]
		);
		if (!$menu_id) { throw new Exception('Falha na inserção do Menu.'); }
		\App\Models\Menu::addRole($menu_id, 'Developer');
	}
}
