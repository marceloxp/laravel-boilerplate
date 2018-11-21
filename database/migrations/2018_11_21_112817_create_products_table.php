<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255)->comment('Produto');
			$table->decimal('price', 15, 2)->comment('Preço');
			$table->decimal('discount', 15, 2)->comment('Desconto');
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
		Schema::dropIfExists('products');
	}
}
