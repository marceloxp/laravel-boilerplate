<?php
if (!function_exists('db_comment_table'))
{
	function db_comment_table($table_name, $table_comment)
	{
		DB::select(sprintf('ALTER TABLE %s%s COMMENT = "%s"', env('DB_TABLE_PREFIX'), $table_name, $table_comment));
	}
}

if (!function_exists('db_get_primary_key'))
{
	function db_get_primary_key($table_name)
	{
		$register = DB::select(sprintf('SHOW KEYS FROM %s%s WHERE Key_name = "PRIMARY"', env('DB_TABLE_PREFIX'), $table_name));
		$register = $register[0];
		return $register->Column_name;
	}
}

if (!function_exists('db_get_name'))
{
	function db_get_name($table_name, $id)
	{
		$register = DB::select(sprintf('SELECT `name` FROM `%s%s` WHERE `id` = "%s";', env('DB_TABLE_PREFIX'), $table_name, $id));
		if (empty($register))
		{
			return '';
		}
		return $register[0]->name;
	}
}