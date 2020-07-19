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
				$table->bigIncrements('id');
				$table->bigInteger('parent_id')->comment('Parent');
				$table->integer('order')->default(0)->comment('Ordem');
				$table->enum('type', ['root','dashboard','header','link','internal-link'])->default('link')->comment('Tipo');
				$table->string('name', 255)->comment('Caption');
				$table->string('slug', 255)->nullable()->comment('Slug');
				$table->string('color', 64)->nullable()->default('bg-green')->comment('Cor');
				$table->string('ico', 64)->default('fa-envelope')->comment('Ícone');
				$table->string('link', 124)->nullable()->comment('Link');
				$table->string('target', 124)->nullable()->comment('Target');
				$table->string('model', 124)->nullable()->comment('Model');
				$table->string('route', 124)->nullable()->comment('Rota');

				$table->timestamps();
				$table->softDeletes();
				$table->index(['deleted_at']);
				$table->unique(['name','parent_id']);
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