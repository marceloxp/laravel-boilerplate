<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Utilities\Cached;

class MakexModelRules extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:model_rules {model_target}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add Rules Validation to Model';

	/**
	 * The console command example.
	 *
	 * @var string
	 */
	protected $example = 'php artisan makex:model_rules Post';

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

		$this->model_target = $this->argument('model_target');
		$this->addRulesToModel();

		$this->info($this->__getLine());
		$this->br();
	}

	private function addRulesToModel()
	{
		$folder_model  = 'Models';
		$folder_model  = (empty($folder_model)) ? 'Models' : $folder_model;
		$folder_model .= '/';

		$class_path_model = '\\App\\' . str_replace('/', '\\', $folder_model);

		$list_function_name = 'validate';
		$list_model_path = 

		// MASTER
		$master_path = app_path(sprintf('%s%s.php', $folder_model, $this->model_target));
		$string_body = \File::get($master_path);
		$master_body = explode(PHP_EOL, $string_body);

		$func       = new \ReflectionClass($class_path_model . $this->model_target);
		$filename   = $func->getFileName();
		$start_line = $func->getStartLine();
		$end_line   = $func->getEndLine();
		$length     = $end_line - $start_line;

		if (strpos($string_body, $list_function_name . '(') === false)
		{
			$str_rules = $this->getRules();

			$function_body = 
			[
				PHP_EOL,
				"	public static function validate(\$request, \$id = '')",
				"	{",
				"		\$rules = ",
				"		[",
				"	" . $str_rules,
				"		];",
				"		return Role::_validate(\$request, \$rules, \$id);",
				"	}",
				"}",
				PHP_EOL,
			];

			$new_body = 
			[
				array_slice($master_body, 0, $end_line - 1),
				$function_body,
				array_slice($master_body, $end_line + 1)
			];
			$final_body = implode(PHP_EOL, $new_body[0]) . implode(PHP_EOL, $new_body[1]) . implode(PHP_EOL, $new_body[2]);

			\File::put($master_path, $final_body);
			$this->info(sprintf('File %s saved.', $master_path));
		}
		else
		{
			$this->info('Function "' . $list_function_name . '()" already exists in ' . $this->model_target . '.');
		}		
	}

	private function getRules()
	{
		$table = str_plural(strtolower($this->model_target));
		$fields = $this->__getFieldsMetadata($table);

		$data = [];
		foreach ($fields as $field)
		{
			$field_name        = $field['COLUMN_NAME'];
			$field_length      = $field['CHARACTER_MAXIMUM_LENGTH'];
			$field_required    = ($field['IS_NULLABLE'] == 'NO');
			$field_enum        = (substr($field['COLUMN_TYPE'], 0, 4) == 'enum');
			$data[$field_name] = [];

			if ($field_enum)
			{
				preg_match("/^enum\(\'(.*)\'\)$/", $field['COLUMN_TYPE'], $matches);
				$options = explode("','", $matches[1]);
				$options = implode(',', $options);
				$data[$field_name][] = sprintf('in:%s', $options);
			}

			if (!empty($field_required))
			{
				$data[$field_name][] = 'required';
			}

			if (!empty($field_length))
			{
				$data[$field_name][] = sprintf('max:%s', $field_length);
			}
		}

		$max_length = $this->__getArrayKeyMaxLength($data);

		$result = [];
		reset($data);
		$k = 0;
		foreach ($data as $field_name => $value)
		{
			if (!empty($value))
			{
				if ($field_name != 'id')
				{
					$prefix = chr(9).chr(9);
					if ($k > 0)
					{
						$prefix .= chr(9);
					}
					$result[] = sprintf($prefix . "'%s'%s=> '%s',", $field_name, str_pad('', ($max_length + 1 - strlen($field_name) )), implode('|', $value));
					$k++;
				}
			}
		}

		$this->info('RULES GENERATED - TABLE: ' . $table);
		$this->br();

		$str_rules = $this->printSingleArray($result, 1, true);
		return $str_rules;
	}
}