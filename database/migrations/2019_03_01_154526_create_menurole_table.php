<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuroleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create
		(
			'menu_role',
			function(Blueprint $table)
			{
				$table->increments('id');
				$table->unsignedInteger('menu_id')->index();
				$table->unsignedInteger('role_id')->index();
				$table->timestamps();

				$table->unique(['menu_id','role_id']);

				$table->foreign('menu_id')->references('id')->on('menus');
				$table->foreign('role_id')->references('id')->on('roles');
			}
		);

		$menu_id = \DB::table('menus')->select('id')->where('slug', 'menu')->first()->id;
		$role_id = \DB::table('roles')->select('id')->where('name', 'Developer')->first()->id;

		\DB::table('menu_role')->insert
		(
			[
				'menu_id' => $menu_id,
				'role_id' => $role_id,
			]
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menu_role');
	}
}