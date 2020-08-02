<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
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
			'examples.tags',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name',150)->unique()->comment('Tag');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['deleted_at']);
			}
		);
		db_comment_table('examples', 'tags', 'Tags');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('examples.tags');
	}
}