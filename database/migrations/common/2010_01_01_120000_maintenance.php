<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Maintenance extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (db_table_exists('common', 'users'))
		{
			Schema::table
			(
				'common.users',
				function(Blueprint $table)
				{
					$table->string('email')->comment('E-Mail')->change();
					if (db_table_has_index('common', 'users', 'users_email_unique'))
					{
						$table->dropUnique('users_email_unique');
					}
				}
			);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
	}
}