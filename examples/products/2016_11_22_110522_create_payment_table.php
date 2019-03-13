<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
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
			$table->integer('paymenttype_id')->unsigned()->comment('Tipo de Pagto');
			$table->string('name', 124)->unique()->comment('Nome');
			$table->string('description', 124)->unique()->comment('Descrição');
			$table->decimal('discount', 15, 2)->default(0)->comment('Desconto');
			$table->integer('parcs')->default(1)->comment('Parcelas');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['name']);
			$table->index(['paymenttype_id']);
			$table->index(['deleted_at']);
			$table->foreign('paymenttype_id')->references('id')->on('paymenttypes');
		});
		db_comment_table('payments', 'Pagamentos');
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