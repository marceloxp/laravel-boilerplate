<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressTypeToCustomers extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customers', function (Blueprint $table) {
			if (Schema::hasColumn('customers', 'address_type_id'))
			{
				Schema::table('customers', function (Blueprint $table)
				{
					$table->dropColumn('address_type_id');
				});
			}
			$table->integer('address_type_id')->unsigned()->default(1)->comment('Tipo de Endereço')->after('id');
			$table->foreign('address_type_id')->references('id')->on('address_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customers', function (Blueprint $table) {
			$table->dropColumn('address_type_id');
		});
	}
}
