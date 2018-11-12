<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuLinksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menulinks', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('menusection_id')->unsigned()->comment('Seção');
			$table->string('name', 124)->unique()->comment('Caption');
			$table->string('icon', 24)->comment('Ícone');
			$table->string('group', 124)->comment('Grupo');
			$table->string('route', 124)->comment('Rota');
			$table->integer('order')->nullable()->default(0)->comment('Ordem');
			$table->enum('status', ['Ativo','Inativo'])->default('Ativo')->comment('Status');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['deleted_at']);
			$table->foreign('menusection_id')->references('id')->on('menusections');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menulinks');
	}
}