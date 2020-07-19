<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (!function_exists('db_database_name'))
{
	function db_database_name()
	{
		return env('DB_DATABASE');
	}
}

if (!function_exists('db_schema_name'))
{
	function db_schema_name()
	{
		return env('DB_SCHEMA');
	}
}

if (!function_exists('db_comment_table'))
{
	function db_comment_table($table_name, $table_comment)
	{
		DB::select(sprintf("COMMENT ON TABLE %s.%s IS '%s'", db_schema_name(), $table_name, $table_comment));
	}
}

if (!function_exists('db_get_comment_table'))
{
	function db_get_comment_table($table_name)
	{
		$register = DB::select(sprintf("SELECT obj_description('%s.%s'::regclass) AS Comment;", db_schema_name(), $table_name));
		if (empty($register))
		{
			throw new \Exception(sprintf('Table %s not found!', $table_name));
		}
		$register = $register[0];
		return $register->comment;
	}
}

if (!function_exists('db_get_field_names'))
{
	function db_get_field_names($p_table, $p_add_comments = false)
	{
		$query = sprintf
		(
			"
				SELECT
					column_name,
					col_description((table_schema || '.' || table_name)::regclass::oid, ordinal_position) as caption
				FROM
					information_schema.columns
				WHERE
					table_catalog = '%s'
					AND
					table_schema = '%s'
					AND
					table_name = '%s'
			",
			db_database_name(),
			db_schema_name(),
			$p_table
		);
		$result = DB::select($query);

		if ($p_add_comments)
		{
			return collect($result)->map(function($item) {
				return [$item->column_name => $item->caption];
			})->collapse()->toArray();
		}
		else
		{
			return collect($result)->pluck('column_name')->toArray();
		}
	}
}

if (!function_exists('db_get_fields_metadata'))
{
	function db_get_fields_metadata($p_table)
	{
		$query = sprintf
		(
			"
				SELECT
					column_name, table_catalog, table_schema, table_name,
					col_description((table_schema||'.'||table_name)::regclass::oid, ordinal_position) as caption,
					column_default, is_nullable, data_type, udt_name, character_maximum_length,
					numeric_precision, numeric_precision_radix, numeric_scale,
					(select cc.check_clause
					from information_schema.table_constraints tc
					join information_schema.check_constraints cc
					on tc.constraint_schema = cc.constraint_schema and tc.constraint_name = cc.constraint_name
					join pg_namespace nsp on nsp.nspname = cc.constraint_schema
					join pg_constraint pgc
					on pgc.conname = cc.constraint_name and pgc.connamespace = nsp.oid and pgc.contype = 'c'
					join information_schema.columns col
					on col.table_schema = tc.table_schema and col.table_name = tc.table_name and
					col.ordinal_position = ANY (pgc.conkey)
					where tc.constraint_schema not in ('pg_catalog', 'information_schema')
					and tc.table_name = information_schema.columns.table_name
					and col.column_name = information_schema.columns.column_name
					and tc.table_schema = information_schema.columns.table_schema
					group by tc.table_schema, tc.table_name, tc.constraint_name, cc.check_clause, col.column_name
					order by tc.table_schema, tc.table_name)
				FROM
					information_schema.columns
				WHERE
					table_catalog = '%s'
					AND
					table_schema = '%s'
					AND
					table_name = '%s'
			",
			db_database_name(),
			db_schema_name(),
			$p_table
		);
		$result = DB::select($query);
		collect($result)->transform(function($item)
		{
			$item->hasEnum = false;
			if (strpos($item->check_clause, ')::text = ANY ((ARRAY[') !== false)
			{
				$str = $item->check_clause;
				$str = str_replace('(((' . $item->column_name . ')::text = ANY ((ARRAY[', '', $str);
				$str = str_replace('::character varying', '', $str);
				$str = str_replace('])::text[])))', '', $str);
				$str = str_replace("'", '', $str);
				$str = str_replace(", ", ',', $str);
				$enum = explode(",", $str);
				$item->hasEnum = true;
				$item->options = $enum;
			}

			if (strpos($item->column_default, 'nextval(') !== false)
			{
				$item->column_default = null;
			}

			if (strpos($item->column_default, '::character varying') !== false)
			{
				$item->column_default = str_replace('::character varying', '', $item->column_default);
				$item->column_default = trim($item->column_default, "'");
			}
		});
		return $result;
	}
}

if (!function_exists('db_get_pivot_table_name'))
{
	function db_get_pivot_table_name($p_table_names)
	{
		$sorted = array_sort_ex($p_table_names, true);
		$table_name = sprintf('%s_%s', str_to_singular($sorted[0]), str_to_singular($sorted[1]));
		return $table_name;
	}
}

if (!function_exists('db_get_pivot_scope_name'))
{
	function db_get_pivot_scope_name($p_models)
	{
		$array  = [$p_models[0]::getTableName(), $p_models[1]::getTableName()];
		$table  = array_sort_ex($array, true);
		$table  = str_to_singular($table);
		$table  = str_to_lower($table);
		$result = sprintf('%s%s', $table[0], ucfirst($table[1]));
		return $result;
	}
}

if (!function_exists('db_get_primary_key'))
{
	function db_get_primary_key($table_name)
	{
		$register = DB::select(sprintf('SHOW KEYS FROM %s WHERE Key_name = "PRIMARY"', $table_name));
		$register = $register[0];
		return $register->Column_name;
	}
}

if (!function_exists('db_field_as_unique_index'))
{
	function db_field_as_unique_index($table_name, $field_names)
	{
		$fields = \Illuminate\Support\Collection::wrap($field_names)->implode('_');
		$index_name = sprintf('%s_%s_unique', $table_name, $fields);
		$sm = Schema::getConnection()->getDoctrineSchemaManager();
		$indexes = \Illuminate\Support\Collection::wrap($sm->listTableIndexes($table_name));
		$result = $indexes->has($index_name);
		return $result;
	}
}

if (!function_exists('db_table_has_index'))
{
	function db_table_has_index($table_name, $index_name)
	{
		$sm = Schema::getConnection()->getDoctrineSchemaManager();
		$indexes = \Illuminate\Support\Collection::wrap($sm->listTableIndexes($table_name));
		$result = $indexes->has($index_name);
		return $result;
	}
}

if (!function_exists('db_get_name'))
{
	function db_get_name($table_name, $id)
	{
		$register = DB::select(sprintf('SELECT name FROM %s WHERE id = %s;', $table_name, $id));
		if (empty($register))
		{
			return '';
		}
		return $register[0]->name;
	}
}

if (!function_exists('db_select_one'))
{
	function db_select_one($p_model, $p_fields, $p_where, $raise_if_empty = false)
	{
		$result = $p_model::where($p_where)->get($p_fields)->first();
		if ($raise_if_empty)
		{
			if (empty($result))
			{
				throw new Exception('Falha na captura dos dados solicitados (1).');
			}
		}
		return $result;
	}
}

if (!function_exists('db_select_id'))
{
	// db_select_id(\App\Models\Menu::class, ['slug' => 'tabelas'], true);
	function db_select_id($p_model, $p_where, $raise_if_empty = false)
	{
		$result = $p_model::where($p_where)->get(['id'])->take(1)->first();
		if ($raise_if_empty && empty($result))
		{
			logger('Falha na captura dos dados solicitados (2).', [$p_model, $p_where, $raise_if_empty]);
			throw new Exception('Falha na captura dos dados solicitados (2).');
		}
		return (!empty($result)) ? $result->id : null;
	}
}

if (!function_exists('db_model_to_table_name'))
{
	function db_model_to_table_name($model_name)
	{
		return \Illuminate\Support\Str::plural(mb_strtolower($model_name));
	}
}

if (!function_exists('db_table_name_to_model'))
{
	function db_table_name_to_model($table_name)
	{
		return \Illuminate\Support\Str::singular(ucfirst(mb_strtolower($table_name)));
	}
}

if (!function_exists('db_table_name_to_model_path'))
{
	function db_table_name_to_model_path($table_name)
	{
		return sprintf('\App\Models\%s', db_table_name_to_model($table_name));
	}
}

if (!function_exists('db_table_name_to_field_id'))
{
	function db_table_name_to_field_id($table_name)
	{
		return sprintf('%s_id', str_to_singular($table_name));
	}
}

if (!function_exists('db_table_exists'))
{
	function db_table_exists($table_name)
	{
		$result = \Schema::hasTable($table_name);
		return (!empty($result)) ? $result : false;
	}
}

if (!function_exists('ln'))
{
	function ln()
	{
		echo PHP_EOL;
	}
}

if (!function_exists('generate_unique_code'))
{
	function generate_unique_code()
	{
		$codelength = config('codetrait.length', 10);
		$code = null;
		$k = 0;
		$valid = false;
		while (!$valid)
		{
			try
			{
				$k++;
				$code  = md5(\Carbon\Carbon::now()->format('Ym') . \Illuminate\Support\Str::random($codelength));
				$id    = \DB::table('codes')->insertGetId(['name' => $code, 'attempts' => $k]);
				$valid = ($id > 0);
			}
			catch (\Exception $e)
			{

			}
		}

		return $code;
	}
}

if (!function_exists('cep_to_address'))
{
	function cep_to_address($cep)
	{
		return \App\Http\Utilities\Cached::get
		(
			'brasil_cep',
			[$cep],
			function() use ($cep)
			{
				$query = 'call cep_endereco(?);';
				$address = collect(DB::select($query, [str_to_formatted_cep($cep)]))->first();
				if (empty($address->endereco))
				{
					return \App\Http\Utilities\Result::error('Endereço não localizado.');
				}
				return \App\Http\Utilities\Result::success('Endereço localizado', collect($address)->toArray());
			},
			60
		);
	}
}

if (!function_exists('db_log_info'))
{
	function db_log_info($message, $context = [])
	{
		$filename = sprintf('/storage/logs/mysql.%s.log', \App\Http\Utilities\Carbex::now()->toSqlDate());
		$view_log = new Logger('View Logs');
		$view_log->pushHandler(new StreamHandler(app_path($filename), Logger::INFO));
		$view_log->addInfo($message, $context);
	}
}

if (!function_exists('db_log_slow'))
{
	function db_log_slow($message, $context = [])
	{
		$filename = sprintf('/storage/logs/mysql.slow.%s.log', \App\Http\Utilities\Carbex::now()->toSqlDate());
		$view_log = new Logger('View Logs');
		$view_log->pushHandler(new StreamHandler(app_path($filename), Logger::INFO));
		$view_log->addInfo($message, $context);
	}
}