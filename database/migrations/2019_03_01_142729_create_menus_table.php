<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
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
			'menus',
			function (Blueprint $table)
			{
				$table->increments('id');
				$table->bigInteger('parent_id')->comment('Parent');
				$table->string('name', 255)->comment('Nome');
				$table->string('slug', 255)->comment('Slug');

				$table->timestamps();
				$table->softDeletes();
				$table->index(['deleted_at']);
			}
		);
		db_comment_table('menus', 'Admin Menu');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menus');
	}
}