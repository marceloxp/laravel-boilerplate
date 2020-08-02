<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
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
			'common.states',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name',150)->comment('Estado');
				$table->string('uf',2)->comment('UF');
				$table->string('code', 32)->comment('Código');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['code']);
				$table->index(['deleted_at']);
			}
		);
		db_comment_table('common', 'states', 'Estados');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('common.states');
	}
}
