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
		$public = [];
		$developer = ['Developer'];
		$master_developer = ['Master','Developer'];
		$master_admin_developer = ['Master','Admin','Developer'];

		\DB::select(sprintf('DELETE FROM %s WHERE id >= 0', db_prefixed_table('menu_role')));
		\DB::select(sprintf('DELETE FROM %s WHERE id >= 0', db_prefixed_table('menus')));

		$group_order = 0;
		
		$menu = 0;
		
		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Páginas', 'fa-book', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Dashboard', 'fa-dashboard', $public, 'admin_dashboard');
		
		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Tabelas', 'fa-table', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Categorias', 'fa-folder', $public, 'admin_category');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Cache', 'fa-rocket', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Listar', 'fa-list', $public, 'admin_cache_list');
			$item = \App\Models\Menu::addMenuLink($group, 'Configurar', 'fa-gears', $public, 'admin_cache');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Developer', 'fa-terminal', $developer, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Admin Menu', 'fa-list-ul', $master_developer, 'admin_menu');
			$item = \App\Models\Menu::addMenuLink($group, 'phpinfo', 'fa-info-circle', $developer, 'admin_phpinfo');
			$item = \App\Models\Menu::addMenuInternalLink($group, 'Adminer', 'fa-database', $developer, 'adminer', '_blank');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Comum', 'fa-circle', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Galeria', 'fa-picture-o', $public, 'admin_gallery');
			$item = \App\Models\Menu::addMenuLink($group, 'Tags', 'fa-tags', $public, 'admin_tag');
			$item = \App\Models\Menu::addMenuLink($group, 'Vídeos', 'fa-youtube', $public, 'admin_video');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Sistema', 'fa-gears', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Configurações', 'fa-gear', $master_admin_developer, 'admin_config');
			$item = \App\Models\Menu::addMenuLink($group, 'Permissões', 'fa-unlock-alt', $master_admin_developer, 'admin_role');
			$item = \App\Models\Menu::addMenuLink($group, 'Usuários', 'fa-user', $master_admin_developer, 'admin_users');
			$item = \App\Models\Menu::addMenuLink($group, 'Ir ao Site', 'fa-home', $public, 'home');
			$item = \App\Models\Menu::addMenuLink($group, 'Sair', 'fa-close', $public, 'logout');
	}
}
