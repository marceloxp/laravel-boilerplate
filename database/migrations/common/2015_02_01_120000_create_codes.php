<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodes extends Migration
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
			'common.codes',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name', 32)->unique()->comment('Código');
				$table->integer('attempts')->comment('Tentativas');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['name','deleted_at']);
			}
		);
		db_comment_table('common', 'codes', 'Códigos');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('common.codes');
	}
}