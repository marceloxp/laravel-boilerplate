<?php

namespace App\Console\Makex;

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
		$this->info($this->__getLine());
		$this->info('    __  ___      __       _  __    ______                                          __    ');
		$this->info('   /  |/  /___ _/ /_____ | |/ /   / ____/___  ____ ___  ____ ___  ____ _____  ____/ /____');
		$this->info('  / /|_/ / __ `/ //_/ _ \|   /   / /   / __ \/ __ `__ \/ __ `__ \/ __ `/ __ \/ __  / ___/');
		$this->info(' / /  / / /_/ / ,< /  __/   |   / /___/ /_/ / / / / / / / / / / / /_/ / / / / /_/ (__  ) ');
		$this->info('/_/  /_/\__,_/_/|_|\___/_/|_|   \____/\____/_/ /_/ /_/_/ /_/ /_/\__,_/_/ /_/\__,_/____/  ');
		$this->br();
		$this->info($this->__getLine());
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
		return collect(\Schema::getAllTables())->pluck('tablename')->toArray();
	}

	public function __getFieldsMetadata($p_table)
	{
		return db_get_fields_metadata($p_table);
	}

	public function __getFieldNames($p_schema_name, $p_table, $p_add_comments = false)
	{
		return db_get_field_names($p_schema_name, $p_table, $p_add_comments);
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
		return '-----------------------------------------------------------------------------------------';
	}

	public function __getLine()
	{
		return '=========================================================================================';
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

	public function printSingleArray($p_array, $columns = 1, $p_return_value = false)
	{
		if ($columns == 1)
		{
			if ($p_return_value)
			{
				return implode(PHP_EOL, $p_array);
			}
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

		if ($p_return_value)
		{
			return $this->printSingleArray($result, $columns, $p_return_value);
		}
		$this->printSingleArray($result);
	}
}