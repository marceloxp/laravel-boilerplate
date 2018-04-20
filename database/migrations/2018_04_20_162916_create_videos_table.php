<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
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
			'videos',
			function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('name',150)->comment('Nome');
				$table->string('youtube',150)->comment('YouTube');
				$table->timestamps();
				$table->softDeletes();
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
        Schema::dropIfExists('videos');
    }
}
