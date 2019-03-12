<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakexAddPivotToTable extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:add_pivot_to_table';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add Pivot to Table';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->clear();
		$this->printLogo();
		$this->info(mb_strtoupper($this->description));
		$this->br();

		// ███╗   ███╗███████╗███╗   ██╗██╗   ██╗
		// ████╗ ████║██╔════╝████╗  ██║██║   ██║
		// ██╔████╔██║█████╗  ██╔██╗ ██║██║   ██║
		// ██║╚██╔╝██║██╔══╝  ██║╚██╗██║██║   ██║
		// ██║ ╚═╝ ██║███████╗██║ ╚████║╚██████╔╝
		// ╚═╝     ╚═╝╚══════╝╚═╝  ╚═══╝ ╚═════╝ 

		$tables = $this->__getTables();
		$tables[] = $this->__getSingleLine();
		$tables[] = 'CANCEL';

		$this->printLine('TABLES');
		$this->printSingleArray($tables);
		$target_table = $this->anticipate('Choose Target Table [cancel]', $tables);
		if ( ($target_table === 'CANCEL') || ($target_table === null) || ($target_table === $this->__getSingleLine()) ) { exit; }

		$this->clear();
		$this->printLogo();
		$this->info(mb_strtoupper($this->description));
		$this->br();

		$this->printLine('TABLES');
		$this->printSingleArray($tables);
		$pivot_table = $this->anticipate('Choose Pivot Table [cancel]', $tables);
		if ( ($pivot_table === 'CANCEL') || ($pivot_table === null) || ($pivot_table === $this->__getSingleLine()) ) { exit; }

		$tables = array_sort_ex([$target_table, $pivot_table], true);

		$table1 = $tables[0];
		$table2 = $tables[1];

		$singular_table_name_1 = str_to_singular($table1);
		$singular_table_name_2 = str_to_singular($table2);
		$model_name_1          = db_table_name_to_model($table1);
		$model_name_2          = db_table_name_to_model($table2);
		$lower_model_name_2    = str_to_lower($model_name_2);
		$target_model_name     = db_table_name_to_model($target_table);
		$pivot_model_name      = db_table_name_to_model($pivot_table);
		$pivot_table_name      = sprintf('%s_%s', $singular_table_name_1, $singular_table_name_2);
		$controller_name       = sprintf('%s%s', $model_name_1, $model_name_2);
		$pivot_controller_name = $pivot_model_name;
		$target_field_id       = sprintf('%s_id', str_to_singular($target_table));
		$pivot_field_id        = sprintf('%s_id', str_to_singular($pivot_table));
		$pivot_table_singular  = str_to_singular($pivot_table);

		$body_params = compact('table1','table2','model_name_1','model_name_2','lower_model_name_2','pivot_model_name','target_model_name','singular_table_name_1','singular_table_name_2');

		// ███╗   ███╗██╗ ██████╗ ██████╗  █████╗ ████████╗██╗ ██████╗ ███╗   ██╗
		// ████╗ ████║██║██╔════╝ ██╔══██╗██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║
		// ██╔████╔██║██║██║  ███╗██████╔╝███████║   ██║   ██║██║   ██║██╔██╗ ██║
		// ██║╚██╔╝██║██║██║   ██║██╔══██╗██╔══██║   ██║   ██║██║   ██║██║╚██╗██║
		// ██║ ╚═╝ ██║██║╚██████╔╝██║  ██║██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║
		// ╚═╝     ╚═╝╚═╝ ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝

		$migration_file = '';
		$pure_filename  = sprintf('create_%s%s_table.php', $singular_table_name_1, $singular_table_name_2);
		$glob_find      = database_path(sprintf('migrations/*%s', $pure_filename));
		$files_exists   = glob($glob_find);
		$exists         = (count($files_exists) > 0);
		if ($exists)
		{
			$migration_file = $files_exists[0];
		}

		$create_migration = true;
		if (!empty($migration_file))
		{
			if (!$this->confirm('Migration file (' . basename($migration_file) . ') already exists, overwrite?'))
			{
				$create_migration = false;
				if ($this->confirm('Exit application?'))
				{
					die('Process aborted.');
				}
			}			
		}

		if ((!empty($create_migration)) && (empty($migration_file)))
		{
			$pure_filename = sprintf
			(
				'%s_create_%s%s_table.php',
				\Carbon\Carbon::now()->format('Y_m_d_hms'),
				$singular_table_name_1,
				$singular_table_name_2
			);
			$dest_file = database_path('migrations/' . $pure_filename);
		}
		else
		{
			$dest_file = $migration_file;
		}

		if ($create_migration)
		{
			$source_file = app_path('Console/Makex/pivot_migration_template.php');
			copy($source_file, $dest_file);
			if (!file_exists($dest_file)) { die('Copy error!!!'); }

			$body = file_get_contents($dest_file);
			foreach ($body_params as $key => $value)
			{
				$body = str_replace('{' . $key . '}', $value, $body);
			}
			file_put_contents($dest_file, $body);
		}

		$migration_path = sprintf('database/migrations/%s', basename($migration_file));
		$table_exists = db_table_exists($pivot_table_name);
		$table_dropped = false;
		if ($table_exists)
		{
			if ($this->confirm('Table ' . $pivot_table_name . ' already exists, drop it?', 0))
			{
				$table_dropped = true;
				$command = 'php artisan migrate:rollback --path=' . $migration_path;
				$this->info($command);
				system($command);
			}
		}

		$command = 'php artisan migrate:status --path=' . $migration_path;
		$this->info($command);
		system($command);
		if ($this->confirm('Execute migration?', 0))
		{
			$command = 'php artisan migrate --path=' . $migration_path;
			$this->info($command);
			system($command);
		}

		//  ██████╗ ██████╗ ███╗   ██╗████████╗██████╗  ██████╗ ██╗     ██╗     ███████╗██████╗ 
		// ██╔════╝██╔═══██╗████╗  ██║╚══██╔══╝██╔══██╗██╔═══██╗██║     ██║     ██╔════╝██╔══██╗
		// ██║     ██║   ██║██╔██╗ ██║   ██║   ██████╔╝██║   ██║██║     ██║     █████╗  ██████╔╝
		// ██║     ██║   ██║██║╚██╗██║   ██║   ██╔══██╗██║   ██║██║     ██║     ██╔══╝  ██╔══██╗
		// ╚██████╗╚██████╔╝██║ ╚████║   ██║   ██║  ██║╚██████╔╝███████╗███████╗███████╗██║  ██║
		//  ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝╚═╝  ╚═╝

		$source_file   = app_path('Console/Makex/PivotTableController.php');
		$dest_file     = app_path('Http/Controllers/Admin/' . $model_name_1 . $model_name_2 . 'Controller.php');

		$body = file_get_contents($source_file);
		foreach ($body_params as $key => $value)
		{
			$body = str_replace('{' . $key . '}', $value, $body);
		}

		$write_file = true;
		if (file_exists($dest_file))
		{
			if (!$this->confirm('Destination file (' . basename($dest_file) . ') already exists, overwrite?'))
			{
				if ($this->confirm('Exit application?'))
				{
					die('Process aborted.');
				}
				$write_file = false;
			}
		}

		if ($write_file)
		{
			file_put_contents($dest_file, $body);
		}

		// ████████╗ █████╗ ██████╗  ██████╗ ███████╗████████╗    ███╗   ███╗ ██████╗ ██████╗ ███████╗██╗     
		// ╚══██╔══╝██╔══██╗██╔══██╗██╔════╝ ██╔════╝╚══██╔══╝    ████╗ ████║██╔═══██╗██╔══██╗██╔════╝██║     
		//    ██║   ███████║██████╔╝██║  ███╗█████╗     ██║       ██╔████╔██║██║   ██║██║  ██║█████╗  ██║     
		//    ██║   ██╔══██║██╔══██╗██║   ██║██╔══╝     ██║       ██║╚██╔╝██║██║   ██║██║  ██║██╔══╝  ██║     
		//    ██║   ██║  ██║██║  ██║╚██████╔╝███████╗   ██║       ██║ ╚═╝ ██║╚██████╔╝██████╔╝███████╗███████╗
		//    ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝   ╚═╝       ╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚══════╝╚══════╝

		$target_model_path = sprintf('App\Models\%s', $target_model_name);
		$reflectionClass   = new \ReflectionClass($target_model_path);
		if(!$reflectionClass->hasMethod($pivot_table))
		{
			$arr_function = 
			[
				chr(9) . '',
				chr(9) . '/**',
				chr(9) . '* Retrieve Pivot Table Registers',
				chr(9) . '*/',
				chr(9) . sprintf('public function %s()', $pivot_table),
				chr(9) . '{',
				chr(9) . chr(9) . sprintf('return $this->belongsToMany(\App\Models\%s::class);', $pivot_model_name),
				chr(9) . '}'
			];

			$filename    = $reflectionClass->getFileName();
			$start_line  = $reflectionClass->getStartLine();
			$end_line    = $reflectionClass->getEndLine();
			$cut_line    = ($end_line - 1);
			$model_file  = app_path(sprintf('Models/%s.php', $target_model_name));
			$model_body  = file_to_array($model_file);
			$length_body = count($model_body);
			$top_body    = array_merge($model_body);
			$bottom_body = array_merge($model_body);
			$top_body    = array_splice($top_body, 0, $cut_line);
			$bottom_body = array_splice($bottom_body, ($length_body - $cut_line) * -1);
			$final_body  = array_merge($top_body, $arr_function, $bottom_body);

			array_to_file($final_body, $model_file);
		}

		// ██████╗ ██╗██╗   ██╗ ██████╗ ████████╗    ██████╗  ██████╗ ██╗   ██╗████████╗███████╗
		// ██╔══██╗██║██║   ██║██╔═══██╗╚══██╔══╝    ██╔══██╗██╔═══██╗██║   ██║╚══██╔══╝██╔════╝
		// ██████╔╝██║██║   ██║██║   ██║   ██║       ██████╔╝██║   ██║██║   ██║   ██║   █████╗  
		// ██╔═══╝ ██║╚██╗ ██╔╝██║   ██║   ██║       ██╔══██╗██║   ██║██║   ██║   ██║   ██╔══╝  
		// ██║     ██║ ╚████╔╝ ╚██████╔╝   ██║       ██║  ██║╚██████╔╝╚██████╔╝   ██║   ███████╗
		// ╚═╝     ╚═╝  ╚═══╝   ╚═════╝    ╚═╝       ╚═╝  ╚═╝ ╚═════╝  ╚═════╝    ╚═╝   ╚══════╝

		$file_name = base_path('routes/custom_admin.php');
		$body = file_get_contents($file_name);
		$str_verify = sprintf('// %s - %s', $target_model_name, $pivot_model_name);
		if (strpos($body, $str_verify) == false)
		{
			$route_body = "
	// [target_model_name] - [pivot_model_name]
	Route::group
	(
		['prefix' => '[pivot_table_name]'],
		function()
		{
			Route::get ('{[target_field_id]}'              , '[controller_name]Controller@index' )->name('admin_[pivot_table_name]'       )->group('admin_[pivot_table_name]');
			Route::post('{[target_field_id]}/attach'       , '[controller_name]Controller@store' )->name('admin_[pivot_table_name]_attach')->group('admin_[pivot_table_name]');
			Route::get ('{[target_field_id]}/show/{[pivot_field_id]}', '[pivot_controller_name]Controller@pivot_show')->name('admin_[target_table]_show'     )->group('admin_[pivot_table_singular]');
			Route::post('{[target_field_id]}/detach'       , '[controller_name]Controller@detach')->name('admin_[pivot_table_name]_detach')->group('admin_[pivot_table_name]');
		}
	);";

			$params = compact('target_table','pivot_table_name','target_field_id','controller_name', 'pivot_controller_name','pivot_field_id','pivot_table_singular','target_model_name','pivot_model_name');
			foreach ($params as $key => $value)
			{
				$route_body = str_replace('[' . $key . ']', $value, $route_body);
			}

			print_r($route_body);

			$body .= PHP_EOL . $route_body;
			file_put_contents($file_name, $body);
		}

		// ██████╗ ██╗██╗   ██╗ ██████╗ ████████╗    ███████╗ ██████╗ ██████╗ ██████╗ ███████╗
		// ██╔══██╗██║██║   ██║██╔═══██╗╚══██╔══╝    ██╔════╝██╔════╝██╔═══██╗██╔══██╗██╔════╝
		// ██████╔╝██║██║   ██║██║   ██║   ██║       ███████╗██║     ██║   ██║██████╔╝█████╗  
		// ██╔═══╝ ██║╚██╗ ██╔╝██║   ██║   ██║       ╚════██║██║     ██║   ██║██╔═══╝ ██╔══╝  
		// ██║     ██║ ╚████╔╝ ╚██████╔╝   ██║       ███████║╚██████╗╚██████╔╝██║     ███████╗
		// ╚═╝     ╚═╝  ╚═══╝   ╚═════╝    ╚═╝       ╚══════╝ ╚═════╝ ╚═════╝ ╚═╝     ╚══════╝

		$target_model_path = sprintf('App\Models\%s', $pivot_model_name);
		$reflectionClass   = new \ReflectionClass($target_model_path);
		if(!$reflectionClass->hasMethod(sprintf('scope%s%s', $model_name_1, $model_name_2)))
		{
			$arr_function = 
			[
				chr(9) . '',
				chr(9) . '/**',
				chr(9) . '* Retrieve All Pivots related to One Target',
				chr(9) . '*/',
				chr(9) . sprintf('public function scope%s%s($query, $p_target_id)', $model_name_1, $model_name_2),
				chr(9) . '{',
				chr(9) . chr(9) . sprintf("return \$query->join('%s', '%s.id', '=', '%s.%s')->where('%s.%s', \$p_target_id);", $pivot_table_name, $pivot_table, $pivot_table_name, $pivot_field_id, $pivot_table_name, $target_field_id),
				chr(9) . '}'
			];

			$filename    = $reflectionClass->getFileName();
			$start_line  = $reflectionClass->getStartLine();
			$end_line    = $reflectionClass->getEndLine();
			$cut_line    = ($end_line - 1);
			$model_file  = app_path(sprintf('Models/%s.php', $pivot_model_name));
			$model_body  = file_to_array($model_file);
			$length_body = count($model_body);
			$top_body    = array_merge($model_body);
			$bottom_body = array_merge($model_body);
			$top_body    = array_splice($top_body, 0, $cut_line);
			$bottom_body = array_splice($bottom_body, ($length_body - $cut_line) * -1);
			$final_body  = array_merge($top_body, $arr_function, $bottom_body);

			array_to_file($final_body, $model_file);
		}

		$this->info('DONE');
	}
}