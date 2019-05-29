<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Utilities\Cached;

class MakexModelOneToOne extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:model {model_target} {model_list} {--onetoone : Add List Model to Target Model}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ajust Model Relationships';

	/**
	 * The console command example.
	 *
	 * @var string
	 */
	protected $example = 'php artisan makex:model Post Category --onetoone';


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
		$this->br();
		$this->printLogo();
		$this->info(mb_strtoupper($this->description));
		$this->br();

		$model_target = $this->argument('model_target');
		$model_list   = $this->argument('model_list');
		$onetoone     = $this->option('onetoone');

		$this->info('Model Target: ' . $model_target);
		$this->info('Model List  : ' . $model_list);

		if ($onetoone)
		{
			// MODEL ONE TO ONE
			$this->ModelOneToOne($model_target, $model_list);

			// AJUSTING CONTROLLER
			$this->br();
			$this->info('Ajusting Controller');

			$table_target = db_model_to_table_name($model_target);
			$table_list   = db_model_to_table_name($model_list);

			$comment_target = db_get_comment_table($table_target);
			$comment_list   = db_get_comment_table($table_list);

			$controller_name = sprintf('%sController', $model_target);
			$controller_file = app_path('Http/Controllers/Admin/' . $controller_name . '.php');

			$table_name = db_model_to_table_name($model_list);
			$caption    = db_get_comment_table($table_name);
			$line       = sprintf("'table_many'     => ['name' => '%s', 'caption' => '%s', 'icon' => 'far fa-folder'],", $table_name, $caption);
			$body       = file_get_contents($controller_file);
			$body       = str_replace("'table_many'     => null,", $line, $body);
			file_put_contents($controller_file, $body);
			$this->info($controller_file . ' saved.');

			// AJUST ROUTE
			$route_prefix      = $table_target;
			$model_description = sprintf('%s_%s', $comment_target, $comment_list);
			$controller_name   = $model_list;
			$name_group        = sprintf('%s_%s', $table_target, $table_list);

			$template = "
	// Begin " . $model_description . "
	Route::group
	(
		['prefix' => '" . $route_prefix . "'],
		function()
		{
			Route::get ('{one_table_id}/" . $table_list . "'           , '" . $controller_name . "Controller@index'  )->name('admin_" . $name_group . "'       )->group('admin_" . $name_group . "');
			Route::get ('{one_table_id}/" . $table_list . "/edit/{id?}', '" . $controller_name . "Controller@create' )->name('admin_" . $name_group . "_edit'  )->group('admin_" . $name_group . "');
			Route::post('{one_table_id}/" . $table_list . "/edit/{id?}', '" . $controller_name . "Controller@store'  )->name('admin_" . $name_group . "_save'  )->group('admin_" . $name_group . "');
			Route::get ('{one_table_id}/" . $table_list . "/show/{id}' , '" . $controller_name . "Controller@show'   )->name('admin_" . $name_group . "_show'  )->group('admin_" . $name_group . "');
			Route::post('{one_table_id}/" . $table_list . "/delete/'   , '" . $controller_name . "Controller@destroy')->name('admin_" . $name_group . "_delete')->group('admin_" . $name_group . "');
		}
	);
	// End " . $model_description;

			$file_name = base_path('routes/custom_admin.php');
			trim_file($file_name);
			$body = file_get_contents($file_name);
			$body = delete_all_between(sprintf('// Begin %s', $model_description), sprintf('// End %s', $model_description), $body);
			$body .= PHP_EOL . $template;
			file_put_contents($file_name, $body);
			trim_file($file_name);
		}

		$this->info($this->__getLine());
		$this->br();
	}

	private function ModelOneToOne($model_target, $model_list)
	{
		$folder_model  = 'Models';
		$folder_model  = (empty($folder_model)) ? 'Models' : $folder_model;
		$folder_model .= '/';

		$class_path_model   = '\\App\\' . str_replace('/', '\\', $folder_model);
		$list_function_name = strtolower($model_list);
		$master_path        = app_path(sprintf('%s%s.php', $folder_model, $model_target));
		$string_body        = \File::get($master_path);
		$master_body        = explode(PHP_EOL, $string_body);
		$target_relation_id = sprintf('%s_id', mb_strtolower($model_list));

		$func       = new \ReflectionClass($class_path_model . $model_target);
		$filename   = $func->getFileName();
		$start_line = $func->getStartLine();
		$end_line   = $func->getEndLine();
		$length     = $end_line - $start_line;
		$tab1       = chr(9);

		if (strpos($string_body, $list_function_name . '(') === false)
		{
			$has_one_line = sprintf
			(
				'return $this->hasOne(%s%s::class, \'id\', \'%s\');',
				$class_path_model,
				$model_list,
				$target_relation_id
			);

			$detail_body = 
			[
				PHP_EOL,
				$tab1 . 'public function ' . $list_function_name . '()',
				$tab1 . '{',
				$tab1.$tab1 . $has_one_line,
				$tab1 . '}',
				'}',
				PHP_EOL,
			];

			$new_body = 
			[
				array_slice($master_body, 0, $end_line - 1),
				$detail_body,
				array_slice($master_body, $end_line + 1)
			];
			$final_body = implode(PHP_EOL, $new_body[0]) . implode(PHP_EOL, $new_body[1]) . implode(PHP_EOL, $new_body[2]);

			\File::put($master_path, $final_body);
			$this->info(sprintf('File %s saved.', $master_path));
		}
		else
		{
			$this->info('Function "' . $list_function_name . '()" already exists in ' . $model_target . '.');
		}		
	}
}