<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionGenerateuniquecode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$prefix = trim(DB::getTablePrefix(), '_');

		$querys = 
		[
			"
				DROP FUNCTION IF EXISTS `" . $prefix . "_generateuniquecode`;
			",
			"
				CREATE FUNCTION `" . $prefix . "_generateuniquecode`() RETURNS varchar(8)
				BEGIN
					DECLARE unqstr varchar(8);
					SET unqstr = CONCAT
					(
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1) 
					);
					WHILE EXISTS(SELECT id FROM `unc_codes` WHERE code = unqstr) DO
						SET unqstr = CONCAT
						(
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1) 
						);
					END WHILE;
					INSERT INTO `unc_codes`(code, created) VALUES (unqstr, now());
					RETURN unqstr;
				END;
			",
			"
				DROP FUNCTION IF EXISTS `" . $prefix . "_generaterandom`;
			",
			"
				CREATE FUNCTION `" . $prefix . "_generaterandom`() RETURNS varchar(8)
				BEGIN
					DECLARE unqstr varchar(8);
					SET unqstr = CONCAT
					(
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),
						substring('abcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1) 
					);
					RETURN unqstr;
				END;
			"
		];

		foreach ($querys as $query)
		{
			DB::unprepared($query);
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$prefix = trim(DB::getTablePrefix(), '_');

		$querys = 
		[
			"DROP FUNCTION IF EXISTS `" . $prefix . "_generateuniquecode`;",
			"DROP FUNCTION IF EXISTS `" . $prefix . "_generaterandom`;"
		];

		foreach ($querys as $query)
		{
			DB::unprepared($query);
		}
    }
}
