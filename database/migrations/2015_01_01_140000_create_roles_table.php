<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
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
			'roles',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name',150)->comment('Nome');
				$table->string('description',255)->comment('Descrição');
				$table->enum('color', ['Azul','Azul Escuro','Roxo','Vermelho','Verde','Laranja','Cinza','Preto'])->default('Azul')->comment('Cor');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['name','deleted_at']);
			}
		);
		db_comment_table('roles', 'Regras');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('roles');
	}
}