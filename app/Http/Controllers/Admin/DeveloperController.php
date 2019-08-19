<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DeveloperController extends AdminController
{
	public function rebuildmenu(Request $request)
	{
		\Artisan::call('db:seed', ['--class' => 'MenusTableSeeder']);
		dump(\Artisan::output());

		\Artisan::call('makex:cached', ['--clear' => true]);
		dump(\Artisan::output());

		echo sprintf('<a href="%s">Voltar</a>', route('admin_dashboard'));
	}
}