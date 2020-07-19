<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagvideosTable extends Migration
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
			'tag_video',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->bigInteger('video_id')->index();
				$table->bigInteger('tag_id')->index();
				$table->timestamps();

				$table->unique(['video_id','tag_id']);

				$table->foreign('video_id')->references('id')->on('videos');
				$table->foreign('tag_id')->references('id')->on('tags');
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
		Schema::dropIfExists('tag_video');
	}
}