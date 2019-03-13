<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymenttypeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paymenttypes', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 124)->unique()->comment('Nome');
			$table->string('description', 124)->unique()->comment('Descrição');
			$table->timestamps();
			$table->softDeletes();

			$table->index(['deleted_at']);
		});
		db_comment_table('paymenttypes', 'Tipos de Pagamentos');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('paymenttypes');
	}
}