<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
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
			'examples.categories',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name',150)->comment('Nome');
				$table->string('slug', 255)->comment('Slug');
				$table->bigInteger('parent_id')->comment('Parent');
				$table->string('description',255)->nullable()->comment('Descrição');
				$table->string('image',255)->nullable()->comment('Imagem');
				$table->enum('status', ['Ativo', 'Inativo'])->default('Ativo')->comment('Status');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['deleted_at']);
				$table->unique(['name','parent_id']);
			}
		);
		db_comment_table('examples', 'categories', 'Categorias');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('examples.categories');
	}
}
