<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
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
			'common.role_user',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->bigInteger('role_id')->unsigned();
				$table->bigInteger('user_id')->unsigned();
				$table->timestamps();

				$table->foreign('role_id')->references('id')->on('common.roles');
				$table->foreign('user_id')->references('id')->on('common.users');
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
		Schema::dropIfExists('common.role_user');
	}
}