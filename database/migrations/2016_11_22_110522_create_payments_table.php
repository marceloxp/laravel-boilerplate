<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 124)->unique()->comment('Nome');
			$table->string('title', 124)->unique()->comment('Título');
			$table->enum('type', ['creditcard','boleto','avista'])->default('avista')->comment('Tipo');
			$table->decimal('discount', 15, 2)->default(0)->comment('Desconto');
			$table->integer('parcs')->default(1)->comment('Parcelas');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['name']);
			$table->index(['type']);
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
		Schema::dropIfExists('payments');
	}
}
