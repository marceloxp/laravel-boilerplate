<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusectionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menusections', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 124)->unique()->comment('Seção');
			$table->string('icon', 24)->comment('Ícone');
			$table->integer('order')->nullable()->default(0)->comment('Ordem');
			$table->enum('status', ['Ativo','Inativo'])->default('Ativo')->comment('Status');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['deleted_at']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menusections');
	}
}