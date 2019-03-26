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

		if ($onetoone)
		{
			$this->ModelOneToOne($model_target, $model_list);
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

		$func       = new \ReflectionClass($class_path_model . $model_target);
		$filename   = $func->getFileName();
		$start_line = $func->getStartLine();
		$end_line   = $func->getEndLine();
		$length     = $end_line - $start_line;
		$tab1       = chr(9);

		if (strpos($string_body, $list_function_name . '(') === false)
		{
			$detail_body = 
			[
				PHP_EOL,
				$tab1 . 'public function ' . $list_function_name . '()',
				$tab1 . '{',
				$tab1.$tab1 . 'return $this->hasOne(' . $class_path_model . $model_list . '::class, \'id\');',
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