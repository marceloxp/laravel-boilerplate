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

		\DB::select('DELETE FROM blp_menu_role WHERE id >= 0');
		\DB::select('DELETE FROM blp_menus WHERE id >= 0');
		
		$menu = \App\Models\Menu::addMenuRoot('Menu', [], 'fa-table');
		
		$group = \App\Models\Menu::addMenuHeader($menu, 'Páginas', 'fa-book', $public);
			$item = \App\Models\Menu::addMenuDashboard($group, 'Dashboard', 'bg-green', 'fa-video-camera', $master_admin_developer, 'admin_video', 'Video');
		
		$group = \App\Models\Menu::addMenuHeader($menu, 'Tabelas', 'fa-table', $master_developer);
			$item = \App\Models\Menu::addMenuLink($group, 'Admin Menu', 'fa-list-ul', $master_developer, 'admin_menu');
			$item = \App\Models\Menu::addMenuLink($group, 'Categorias', 'fa-folder', $master_developer, 'admin_category');

		$group = \App\Models\Menu::addMenuHeader($menu, 'Cache', 'fa-rocket', $master_developer);
			$item = \App\Models\Menu::addMenuLink($group, 'Listar', 'fa-list', $master_developer, 'admin_cache_list');
			$item = \App\Models\Menu::addMenuLink($group, 'Configurar', 'fa-gears', $master_developer, 'admin_cache');

		$group = \App\Models\Menu::addMenuHeader($menu, 'Developer', 'fa-terminal', $developer);
			$item = \App\Models\Menu::addMenuLink($group, 'phpinfo', 'fa-info-circle', $developer, 'admin_phpinfo');
			$item = \App\Models\Menu::addMenuInternalLink($group, 'Adminer', 'fa-database', $developer, 'adminer', '_blank');

		$group = \App\Models\Menu::addMenuHeader($menu, 'Sistema', 'fa-gears', $public);
			$item = \App\Models\Menu::addMenuLink($group, 'Galeria', 'fa-picture-o', $master_admin_developer, 'admin_gallery');
			$item = \App\Models\Menu::addMenuLink($group, 'Configurações', 'fa-gear', $master_developer, 'admin_config');
			$item = \App\Models\Menu::addMenuLink($group, 'Permissões', 'fa-unlock-alt', $master_developer, 'admin_role');
			$item = \App\Models\Menu::addMenuLink($group, 'Tags', 'fa-tags', $public, 'admin_tag');
			$item = \App\Models\Menu::addMenuLink($group, 'Vídeos', 'fa-youtube', $public, 'admin_video');
			$item = \App\Models\Menu::addMenuLink($group, 'Usuários', 'fa-user', $master_developer, 'admin_users');
			$item = \App\Models\Menu::addMenuLink($group, 'Ir ao Site', 'fa-home', $public, 'home');
			$item = \App\Models\Menu::addMenuLink($group, 'Sair', 'fa-close', $public, 'logout');
	}
}
