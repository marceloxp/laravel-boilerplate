<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration
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
			'audits',
			function (Blueprint $table)
			{
				$table->increments('id');
				$table->bigInteger('user_id')->comment('Cód Usuário');
				$table->string('username', 255)->comment('Usuário');
				$table->enum('name', ['created','updated','removed'])->comment('Evento');
				$table->text('url')->comment('URL');
				$table->string('ip', 124)->comment('IP');
				$table->string('useragent', 255)->comment('User-Agent');
				$table->longText('oldvalue')->comment('Anterior');
				$table->longText('newvalue')->comment('Atual');
				$table->integer('flags')->nullable()->comment('Flags');
				$table->timestamps();
				$table->softDeletes();

				$table->index(['name']);
				$table->index(['deleted_at']);
			}
		);
		db_comment_table('audits', 'Auditoria');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('audits');
	}
}