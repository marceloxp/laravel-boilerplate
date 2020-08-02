<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
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
			'examples.videos',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->bigInteger('category_id')->unsigned()->comment('Categoria');
				$table->string('name',150)->comment('Nome');
				$table->string('youtube',150)->comment('YouTube');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['deleted_at']);

				$table->foreign('category_id')->references('id')->on('examples.categories');
			}
		);
		db_comment_table('examples', 'videos', 'Vídeos');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('examples.videos');
	}
}
