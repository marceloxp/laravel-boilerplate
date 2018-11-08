<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Hook;

class Makex extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:admin_controller';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new Admin Controller';

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

		$models = $this->___getModels();
		$models[] = '-------------------------------------------------------';
		$models[] = 'CANCEL';

		$this->printLine('MODELS');
		$this->printSingleArray($models);

		$model = $this->anticipate('Choose Model Base [cancel]', $models);
		if ( ($model === 'CANCEL') || ($model === null) || ($model === '-------------------------------------------------------') )
		{
			exit;
		}

		$caption = $this->ask('Caption');
		if (empty($caption))
		{
			exit;
		}
		$table_name = str_plural(snake_case($model));

		$controller_name = $this->ask('Controller file name [Input]Controller.php');
		if (empty($controller_name))
		{
			exit;
		}

		$fields = $this->__getFieldNames($table_name);

		$field_names = "'" . implode("','", $fields) . "'";

		$max_length = $this->__getArrayMaxLength($fields);

		$colunmed = [];
		foreach ($fields as $field)
		{
			$field_name = "'" . $field . "'";
			$line = str_pad($field_name, ($max_length+2));
			$line .= ' => 12,';
			$line = str_repeat(chr(9), 5) . $line;
			$colunmed[] = $line;
		}
		$colunmed_str = implode(PHP_EOL, $colunmed);

		$source_file = app_path('Console/Makex/TemplateController.php');
		$dest_file   = app_path('Http/Controllers/Admin/' . $controller_name . 'Controller.php');

		if (file_exists($dest_file))
		{
			if (!$this->confirm('Destination file already exists, overwrite?'))
			{
				exit;
			}
		}

		copy($source_file, $dest_file);

		if (!file_exists($dest_file))
		{
			die('Copy error!!!');
		}

		$body = file_get_contents($dest_file);
		$body = str_replace('[[caption]]'              , $caption, $body);
		$body = str_replace('ModelName'                , $model, $body);
		$body = str_replace('[display_fields]'         , '[' . $field_names . ']', $body);
		$body = str_replace('[columned_display_fields]', $colunmed_str, $body);

		file_put_contents($dest_file, $body);
	}
}