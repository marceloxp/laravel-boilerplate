<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{model_name_1}{lower_model_name_2}Table extends Migration
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
			'{singular_table_name_1}_{singular_table_name_2}',
			function(Blueprint $table)
			{
				$table->increments('id');
				$table->unsignedInteger('{singular_table_name_1}_id')->index();
				$table->unsignedInteger('{singular_table_name_2}_id')->index();
				$table->timestamps();

				$table->unique(['{singular_table_name_1}_id','{singular_table_name_2}_id']);

				$table->foreign('{singular_table_name_1}_id')->references('id')->on('{table1}');
				$table->foreign('{singular_table_name_2}_id')->references('id')->on('{table2}');
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('{singular_table_name_1}_{singular_table_name_2}');
	}
}