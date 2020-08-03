<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonGenericlistsTable extends Migration
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
			'common.genericlists',
			function (Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name', 255)->comment('Nome');
				$table->string('group', 255)->nullable()->comment('Grupo');
				$table->string('value', 255)->nullable()->comment('Valor');
				$table->longText('text')->nullable()->comment('Texto');

				$table->timestamps();
				$table->softDeletes();
				$table->index(['group', 'deleted_at']);
				$table->index(['name', 'deleted_at']);
				$table->index(['deleted_at']);
			}
		);
		db_comment_table('common', 'genericlists', 'Listas Auxiliares');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('common.genericlists');
	}
}