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
		$public = ['Public'];
		$developer = ['Developer'];
		$master_developer = ['Master','Developer'];
		$master_admin_developer = ['Master','Admin','Developer'];

		\DB::select(sprintf('DELETE FROM %s WHERE id >= 0', db_prefixed_table('menu_role')));
		\DB::select(sprintf('DELETE FROM %s WHERE id >= 0', db_prefixed_table('menus')));

		$group_order = 0;
		
		$menu = 0;
		
		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Páginas', 'fas fa-book', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Dashboard', 'fas fa-tachometer-alt', $public, 'admin_dashboard');
		
		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Tabelas', 'fas fa-table', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Categorias', 'far fa-folder', $public, 'admin_category');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Cache', 'fas fa-rocket', $master_admin_developer, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Listar', 'fas fa-list', $master_admin_developer, 'admin_cache_list');
			$item = \App\Models\Menu::addMenuLink($group, 'Configurar', 'fas fa-cogs', $master_admin_developer, 'admin_cache_index');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Developer', 'fas fa-terminal', $master_developer, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Admin Menu', 'fas fa-bars', $master_developer, 'admin_menu');
			$item = \App\Models\Menu::addMenuLink($group, 'phpinfo', 'fas fa-info-circle', $master_developer, 'admin_phpinfo');
			$item = \App\Models\Menu::addMenuInternalLink($group, 'Adminer', 'fas fa-database', $master_developer, 'adminer', '_blank');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Comum', 'fas fa-circle', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Galeria', 'fas fa-images', $public, 'admin_gallery');
			$item = \App\Models\Menu::addMenuLink($group, 'Tags', 'fas fa-tags', $public, 'admin_tag');
			$item = \App\Models\Menu::addMenuLink($group, 'Vídeos', 'fab fa-youtube', $public, 'admin_video');
			$item = \App\Models\Menu::addMenuLink($group, 'Cidades', 'fas fa-globe-americas', $public, 'admin_city');

		$group_order += 10;
		$group = \App\Models\Menu::addMenuHeader($menu, 'Sistema', 'fas fa-cogs', $public, $group_order);
			$item = \App\Models\Menu::addMenuLink($group, 'Configurações', 'fas fa-cogs', $master_admin_developer, 'admin_config');
			$item = \App\Models\Menu::addMenuLink($group, 'Permissões', 'fas fa-unlock-alt', $master_admin_developer, 'admin_role');
			$item = \App\Models\Menu::addMenuLink($group, 'Usuários', 'fas fa-user-friends', $master_admin_developer, 'admin_user');
			$item = \App\Models\Menu::addMenuLink($group, 'Ir ao Site', 'fas fa-home', $public, 'home', true);
			$item = \App\Models\Menu::addMenuLink($group, 'Sair', 'fas fa-times', $public, 'logout');
	}
}
