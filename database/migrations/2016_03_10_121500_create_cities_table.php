<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
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
			'cities',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->bigInteger('state_id')->unsigned()->comment('Estado');
				$table->integer('position')->unsigned()->default(0)->comment('Posição');
				$table->string('name',150)->comment('Cidade');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['deleted_at']);
				$table->unique(['position']);

				$table->foreign('state_id')->references('id')->on('states');
			}
		);
		db_comment_table('cities', 'Cidades');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('cities');
	}
}
