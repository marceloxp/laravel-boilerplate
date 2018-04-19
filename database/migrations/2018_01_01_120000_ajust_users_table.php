<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AjustUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::dropIfExists('users');
		
        Schema::create
		(
			'users',
			function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('name')->comment('Nome');
				$table->string('email')->unique()->comment('E-Mail');
				$table->string('password')->comment('Senha');
				$table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
