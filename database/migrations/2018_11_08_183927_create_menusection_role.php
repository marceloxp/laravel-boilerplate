<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusectionRole extends Migration
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
			'menusection_role', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('role_id')->unsigned();
				$table->integer('menusection_id')->unsigned();
				$table->timestamps();

				$table->foreign('role_id')->references('id')->on('roles');
				$table->foreign('menusection_id')->references('id')->on('menusections');
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
		Schema::dropIfExists('menusection_role');
	}
}