<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakexCommand extends Command
{
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function br()
	{
		$this->info('');
	}

	public function printLogo()
	{
		$this->info('=========================================================================================');
		$this->info('    __  ___      __       _  __    ______                                          __    ');
		$this->info('   /  |/  /___ _/ /_____ | |/ /   / ____/___  ____ ___  ____ ___  ____ _____  ____/ /____');
		$this->info('  / /|_/ / __ `/ //_/ _ \|   /   / /   / __ \/ __ `__ \/ __ `__ \/ __ `/ __ \/ __  / ___/');
		$this->info(' / /  / / /_/ / ,< /  __/   |   / /___/ /_/ / / / / / / / / / / / /_/ / / / / /_/ (__  ) ');
		$this->info('/_/  /_/\__,_/_/|_|\___/_/|_|   \____/\____/_/ /_/ /_/_/ /_/ /_/\__,_/_/ /_/\__,_/____/  ');
		$this->br();
		$this->info('=========================================================================================');
		$this->br();
	}

	public function clear()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { $this->breakLine(5); } else { system('clear'); }
	}

	public function ___getModels()
	{
		$result = [];
		$path = app_path('Models');
		$files = File::allFiles($path);
		foreach ($files as $file)
		{
			$filename = $file->getPathname();
			$path_parts = pathinfo($filename);
			$result[] = $path_parts['filename'];
		}
		sort($result);

		return $result;
	}

	public function __getTables()
	{
		$tables_in_db = \DB::select('SHOW TABLES');
		$db = sprintf('Tables_in_%s', env('DB_DATABASE'));
		$table_prefix = env('DB_TABLE_PREFIX');
		$tables = [];
		foreach($tables_in_db as $table)
		{
			$table_name = str_replace($table_prefix, '', $table->{$db});
			$tables[] = $table_name;
		}

		return $tables;
	}

	public function __getFieldsMetadata($p_table)
	{
		$query = sprintf
		(
			'SELECT * FROM `information_schema`.`COLUMNS` WHERE `table_schema` = "%s" AND table_name = "%s%s"',
			env('DB_DATABASE'),
			env('DB_TABLE_PREFIX'),
			$p_table
		);
		$result = \DB::select($query);
		$result = collect($result)->map(function($x){ return (array) $x; })->toArray();

		return $result;
	}

	public function __getFieldNames($p_table, $p_add_comments = false)
	{
		$fields = $this->__getFieldsMetadata($p_table);
		if (empty($fields))
		{
			return null;
		}
		$result = [];
		foreach ($fields as $field)
		{
			if ($p_add_comments)
			{
				$result[$field['COLUMN_NAME']] = $field['COLUMN_COMMENT'];
			}
			else
			{
				$result[] = $field['COLUMN_NAME'];
			}
		}

		return $result;
	}

	public function printSingleLine()
	{
		$this->info($this->__getSingleLine());
	}

	public function printLine($left = '', $center = '', $right = '')
	{
		$line = $this->__getLine();
		$lcount = strlen($line);

		if (!empty($left))
		{
			$left = ' ' . $left . ' ';
			$line = substr_replace($line, $left, 2, strlen($left));
		}

		if (!empty($center))
		{
			$center = ' ' . $center . ' ';
			$line = substr_replace($line, $center, ceil($lcount / 2)-(strlen($center) / 2), strlen($center));
		}

		if (!empty($right))
		{
			$right = ' ' . $right . ' ';
			$line = substr_replace($line, $right, $lcount-strlen($right)-2, strlen($right));
		}

		$this->info($line);
	}

	public function breakLine($p_lines = 1)
	{
		for($k = 0; $k < $p_lines; $k++)
		{
			$this->info('');
		}
	}

	public function waitKey()
	{
		$this->printLine();
		readline('Press any key to continue.');
	}

	public function __getSingleLine()
	{
		return '-------------------------------------------------------------------------------------------------------------------';
	}

	public function __getLine()
	{
		return '===================================================================================================================';
	}

	public function __getArrayKeyMaxLength($p_array)
	{
		$keys    = array_keys($p_array);
		$lengths = array_map('strlen', $keys);
		return max($lengths);
	}

	public function __getArrayMaxLength($p_array)
	{
		$values  = array_values($p_array);
		$lengths = array_map('strlen', $values);
		return max($lengths);
	}

	public function printAssocArrayToList($p_array)
	{
		$keys       = array_keys($p_array);
		$lengths    = array_map('strlen', $keys);
		$max_length = max($lengths) + 3;

		foreach ($p_array as $key => $value)
		{
			$line = sprintf('%s%s', str_pad($key.' ', $max_length, '.'), $value);
			$this->info($line);
		}
	}

	public function printSingleArray($p_array, $columns = 1)
	{
		if ($columns == 1)
		{
			print_r( implode(PHP_EOL, $p_array) );
			$this->breakLine();
			return;
		}

		$pieces = array_chunk($p_array, ceil(count($p_array) / $columns));

		$maxlengths = [];
		foreach ($pieces as $column)
		{
			$lengths = array_map('strlen', $column);
			$max_length = max($lengths);
			$maxlengths[] = $max_length;
		}

		reset($pieces);
		foreach ($pieces as $index => $piece)
		{
			foreach ($piece as $key => $value)
			{
				$pieces[$index][$key] = str_pad($pieces[$index][$key], ($maxlengths[$index] + 3), ' ');
			}
		}
		
		$k = 0;
		$result = array_shift($pieces);
		while (count($pieces) > 0)
		{
			$temp = array_shift($pieces);
			foreach ($temp as $key => $value)
			{
				$result[$key] .= $value;
			}
			if ($k > 100)
			{
				die('Stack overflow!');
			}
		}

		$this->printSingleArray($result);
	}
}