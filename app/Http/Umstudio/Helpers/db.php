<?php
if (!function_exists('db_comment_table'))
{
	function db_comment_table($table_name, $table_comment)
	{
		DB::select(sprintf('ALTER TABLE %s%s COMMENT = "%s"', env('DB_PREFIX'), $table_name, $table_comment));
	}
}