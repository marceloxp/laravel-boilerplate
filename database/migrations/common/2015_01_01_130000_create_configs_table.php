<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
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
			'common.configs',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name',150)->unique()->comment('Nome');
				$table->text('value')->comment('Valor');
				$table->integer('flags')->nullable()->comment('Flags');
				$table->enum('status', ['Ativo', 'Inativo'])->default('Ativo')->comment('Status');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['name','deleted_at']);
			}
		);
		db_comment_table('common', 'configs', 'Configurações');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('common.configs');
	}
}
