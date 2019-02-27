<?php
if (!function_exists('db_database_name'))
{
	function db_database_name()
	{
		return env('DB_DATABASE');
	}
}

if (!function_exists('db_prefix'))
{
	function db_prefix()
	{
		return env('DB_TABLE_PREFIX');
	}
}

if (!function_exists('db_comment_table'))
{
	function db_comment_table($table_name, $table_comment)
	{
		DB::select(sprintf('ALTER TABLE %s COMMENT = "%s"', db_prefixed_table($table_name), $table_comment));
	}
}

if (!function_exists('db_get_comment_table'))
{
	function db_get_comment_table($table_name)
	{
		$register = DB::select(sprintf('SHOW TABLE STATUS WHERE Name="%s"', db_prefixed_table($table_name)));
		$register = $register[0];
		return $register->Comment;
	}
}

if (!function_exists('db_get_primary_key'))
{
	function db_get_primary_key($table_name)
	{
		$register = DB::select(sprintf('SHOW KEYS FROM %s WHERE Key_name = "PRIMARY"', db_prefixed_table($table_name)));
		$register = $register[0];
		return $register->Column_name;
	}
}

if (!function_exists('db_get_name'))
{
	function db_get_name($table_name, $id)
	{
		$register = DB::select(sprintf('SELECT `name` FROM `%s` WHERE `id` = "%s";', db_prefixed_table($table_name), $id));
		if (empty($register))
		{
			return '';
		}
		return $register[0]->name;
	}
}

if (!function_exists('db_model_to_table_name'))
{
	function db_model_to_table_name($model_name)
	{
		return str_plural(mb_strtolower($model_name));
	}
}

if (!function_exists('db_table_name_to_model'))
{
	function db_table_name_to_model($table_name)
	{
		return str_singular(ucfirst(mb_strtolower($table_name)));
	}
}

if (!function_exists('db_trim_table_prefix'))
{
	function db_trim_table_prefix($table_name)
	{
		$temp = explode(db_prefix(), $table_name);
		return array_pop($temp);
	}
}

if (!function_exists('ln'))
{
	function ln()
	{
		echo PHP_EOL;
	}
}

if (!function_exists('db_prefixed_table'))
{
	function db_prefixed_table($table_name)
	{
		return sprintf('%s%s', db_prefix(), db_trim_table_prefix($table_name));
	}
}

if (!function_exists('db_table_exists'))
{
	function db_table_exists($table_name)
	{
		return \Schema::hasTable($table_name);
	}
}