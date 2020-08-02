<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('common.users');
		
		Schema::create
		(
			'common.users',
			function(Blueprint $table)
			{
				$table->bigIncrements('id');
				$table->string('name')->comment('Nome');
				$table->string('email')->comment('E-Mail');
				$table->string('password')->comment('Senha');
				$table->rememberToken();
				$table->timestamps();
				$table->softDeletes();

				$table->index(['name','deleted_at']);
				$table->unique(['email','deleted_at']);
			}
		);
		db_comment_table('common', 'users', 'Usuários');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('common.users');
	}
}