<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('address_types');
		Schema::create('address_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 124)->unique()->comment('Nome');
			$table->enum('status', ['Ativo','Inativo'])->default('Ativo')->comment('Status');
			$table->softDeletes();
			$table->timestamps();

			$table->index(['deleted_at']);
			$table->index(['name']);
		});

		$tipos = ['Não Informado', 'Residencial', 'Comercial'];
		foreach ($tipos as $id => $tipo)
		{
			\App\Models\AddressType::create(['name' => $tipo]);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('address_types');
	}
}
