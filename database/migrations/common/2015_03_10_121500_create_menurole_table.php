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
			'common.menu_role',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->bigInteger('menu_id')->index();
				$table->bigInteger('role_id')->index();
				$table->timestamps();

				$table->unique(['menu_id','role_id']);

				$table->foreign('menu_id')->references('id')->on('common.menus');
				$table->foreign('role_id')->references('id')->on('common.roles');
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('common.menu_role');
	}
}