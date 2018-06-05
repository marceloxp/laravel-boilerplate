<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodes extends Migration
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
			'codes',
			function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('code',8)->unique()->comment('Código');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['code','deleted_at']);
        	}
		);
        db_comment_table('codes', 'Códigos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
}