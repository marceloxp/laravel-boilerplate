<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleries extends Migration
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
			'examples.galleries',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('category',150)->comment('Categoria');
				$table->string('name',150)->comment('Slug');
				$table->string('description',255)->nullable()->comment('Descrição');
				$table->string('image',255)->nullable()->comment('Imagem');
				$table->enum('status', ['Ativo', 'Inativo'])->default('Ativo')->comment('Status');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['deleted_at']);
			}
		);
		db_comment_table('examples', 'galleries', 'Galeria de Imagens');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('examples.galleries');
	}
}
