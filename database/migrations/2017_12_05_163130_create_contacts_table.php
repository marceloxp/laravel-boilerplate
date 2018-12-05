<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
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
			'contacts',
			function (Blueprint $table)
			{
				$table->increments('id');
				$table->string('name', 255)->comment('Nome');
				$table->string('subject', 255)->comment('Assunto');
				$table->enum('state', ['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RO','RS','RR','SC','SE','SP','TO'])->comment('Estado');
				$table->string('city', 128)->comment('Cidade');
				$table->string('email', 128)->comment('E-Mail');
				$table->string('phone', 128)->comment('Telefone');
				$table->text('message')->comment('Mensagem');
				$table->timestamps();
				$table->softDeletes();
				$table->index(['deleted_at']);
			}
		);
		db_comment_table('contacts', 'Contato');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('contacts');
	}
}