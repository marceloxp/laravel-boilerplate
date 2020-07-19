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
				$table->bigIncrements('id');
				$table->bigInteger('menu_id')->index();
				$table->bigInteger('role_id')->index();
				$table->timestamps();

				$table->unique(['menu_id','role_id']);

				$table->foreign('menu_id')->references('id')->on('menus');
				$table->foreign('role_id')->references('id')->on('roles');
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
		Schema::dropIfExists('menu_role');
	}
}