<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
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
			'cities',
			function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('state_id')->unsigned()->comment('Cidade');
				$table->string('name',150)->comment('Estado');
				$table->timestamps();
				$table->softDeletes();

				$table->foreign('state_id')->references('id')->on('cities');
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
        Schema::dropIfExists('cities');
    }
}
