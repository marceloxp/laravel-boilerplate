<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakexCreateSimpleTable extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:create_simple_table';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new Table, Migration, Model and Admin Page';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->admin_menu = "				[
					'type'    => 'link',
					'caption' => '{Caption}',
					'ico'     => 'fa-folder',
					'group'   => 'admin_{model_singular}',
					'route'   => 'admin_{model_singular}'
				],"
		;

		$this->string_class = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
UseSoftDeletes1
use App\Http\Utilities\MasterModel;

class %s extends MasterModel
{
	UseSoftDeletes2
	protected \$dates   = ['created_at','updated_at','deleted_at'];
	protected \$guarded = ['created_at','updated_at','deleted_at'];
}";

		$this->migration_class = "<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{ClassName}Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create
		(
			'{table_name}',
			function (Blueprint \$table)
			{
				\$table->increments('id');
				\$table->string('name', 255)->comment('Nome');

				\$table->timestamps();
				\$table->softDeletes();
				\$table->index(['deleted_at']);
			}
		);
		db_comment_table('{table_name}', '{table_comment}');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('{table_name}');
	}
}";
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

		$folder_name = $this->ask('Folder name (ex: Models)', 'Models');
		$folder_name = (empty($folder_name)) ? 'Models' : $folder_name;
		$folder_name .= '/';

		$model_name = $this->ask('Master Model name (Singular)', 'cancel');
		if ($model_name == 'cancel')
		{
			exit;
		}
		$model_name = ucfirst(camel_case(strtolower($model_name)));
		$class_name = str_plural($model_name);
		$model_description = $this->ask('Model description');

		$this->info('Model Name: ' . $model_name);
		$this->info('Description: ' . $model_description);

		$use_soft_deletes = ($this->confirm('Use SoftDeletes?', 1));
		$changes = 
		[
			'UseSoftDeletes1' => (!$use_soft_deletes) ? '{delete_line}' : 'use Illuminate\Database\Eloquent\SoftDeletes;',
			'UseSoftDeletes2' => (!$use_soft_deletes) ? '{delete_line}' : 'use SoftDeletes;'
		];

		$file_name = sprintf('%s%s.php', $folder_name, $model_name);
		$file_name = app_path($file_name);

		if (\File::exists($file_name))
		{
			if (!$this->confirm('Model file already exists, ovewrite?', 1))
			{
				exit;
			}
			\File::delete($file_name);
		}

		$command = sprintf('php artisan make:model %s%s -m', $folder_name, $model_name);
		// if ($this->confirm($command, 1))
		// {
			$this->info('EXECUTING MIGRATE AND MODEL CREATION');
			$result = system($command);
			if (strpos($result, 'Created Migration') === false)
			{
				die('Migration creation error!');
			}
			$migration_file_name = trim(str_replace('Created Migration: ', '', $result));
			$migration_file_name .= '.php';
		// }

		$body = sprintf($this->string_class, $model_name);

		foreach ($changes as $key => $value)
		{
			$body = str_replace($key, $value, $body);
		}

		$temp = explode(PHP_EOL, $body);
		$body = [];
		foreach ($temp as $line)
		{
			if (!str_contains($line, '{delete_line}'))
			{
				$body[] = $line;
			}
		}
		$body = implode(PHP_EOL, $body);

		\File::put($file_name, $body);

		// ███╗   ███╗██╗ ██████╗ ██████╗  █████╗ ████████╗██╗ ██████╗ ███╗   ██╗     █████╗      ██╗██╗   ██╗███████╗████████╗
		// ████╗ ████║██║██╔════╝ ██╔══██╗██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║    ██╔══██╗     ██║██║   ██║██╔════╝╚══██╔══╝
		// ██╔████╔██║██║██║  ███╗██████╔╝███████║   ██║   ██║██║   ██║██╔██╗ ██║    ███████║     ██║██║   ██║███████╗   ██║   
		// ██║╚██╔╝██║██║██║   ██║██╔══██╗██╔══██║   ██║   ██║██║   ██║██║╚██╗██║    ██╔══██║██   ██║██║   ██║╚════██║   ██║   
		// ██║ ╚═╝ ██║██║╚██████╔╝██║  ██║██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║    ██║  ██║╚█████╔╝╚██████╔╝███████║   ██║   
		// ╚═╝     ╚═╝╚═╝ ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝    ╚═╝  ╚═╝ ╚════╝  ╚═════╝ ╚══════╝   ╚═╝   

		$migration_path = database_path('migrations/' . $migration_file_name);
		$body = $this->migration_class;

		$body = str_replace('{ClassName}'    , $class_name, $body);
		$body = str_replace('{table_name}'   , db_model_to_table_name($model_name), $body);
		$body = str_replace('{ModelName}'    , $model_name, $body);
		$body = str_replace('{table_comment}', $model_description, $body);

		if (!$use_soft_deletes)
		{
			$body = str_replace(PHP_EOL . '				$table->softDeletes();', '', $body);
			$body = str_replace(PHP_EOL . '				$table->index([\'deleted_at\']);', '', $body);
		}

		\File::put($migration_path, $body);

		// ███╗   ███╗███████╗███╗   ██╗██╗   ██╗
		// ████╗ ████║██╔════╝████╗  ██║██║   ██║
		// ██╔████╔██║█████╗  ██╔██╗ ██║██║   ██║
		// ██║╚██╔╝██║██╔══╝  ██║╚██╗██║██║   ██║
		// ██║ ╚═╝ ██║███████╗██║ ╚████║╚██████╔╝
		// ╚═╝     ╚═╝╚══════╝╚═╝  ╚═══╝ ╚═════╝ 

		$config_admin_path = config_path('admin.php');

		$table_name = db_model_to_table_name($model_name);

		$admin_menu = '// ADMIN ADD ITEM - DON\'T REMOVE THIS LINE' . PHP_EOL . $this->admin_menu;
		$admin_menu = str_replace('{Caption}', $model_description, $admin_menu);
		$admin_menu = str_replace('{model_singular}', $table_name, $admin_menu);

		$body = \File::get($config_admin_path);
		$body = str_replace('// ADMIN ADD ITEM - DON\'T REMOVE THIS LINE', $admin_menu, $body);

		\File::replace($config_admin_path, $body);

		//  ██████╗ ██████╗ ███╗   ██╗███████╗██╗ ██████╗     ███╗   ███╗██╗ ██████╗ ██████╗  █████╗ ████████╗██╗ ██████╗ ███╗   ██╗
		// ██╔════╝██╔═══██╗████╗  ██║██╔════╝██║██╔════╝     ████╗ ████║██║██╔════╝ ██╔══██╗██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║
		// ██║     ██║   ██║██╔██╗ ██║█████╗  ██║██║  ███╗    ██╔████╔██║██║██║  ███╗██████╔╝███████║   ██║   ██║██║   ██║██╔██╗ ██║
		// ██║     ██║   ██║██║╚██╗██║██╔══╝  ██║██║   ██║    ██║╚██╔╝██║██║██║   ██║██╔══██╗██╔══██║   ██║   ██║██║   ██║██║╚██╗██║
		// ╚██████╗╚██████╔╝██║ ╚████║██║     ██║╚██████╔╝    ██║ ╚═╝ ██║██║╚██████╔╝██║  ██║██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║
		//  ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚═╝     ╚═╝ ╚═════╝     ╚═╝     ╚═╝╚═╝ ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝

		if (!$this->confirm('Waiting to user configure migration file. Execute migration now?', 1))
		{
			die('Process aborted');
		}

		$this->info('EXECUTING MIGRATION');
		system('php artisan migrate');

		// ██████╗ ██╗   ██╗██╗     ███████╗███████╗
		// ██╔══██╗██║   ██║██║     ██╔════╝██╔════╝
		// ██████╔╝██║   ██║██║     █████╗  ███████╗
		// ██╔══██╗██║   ██║██║     ██╔══╝  ╚════██║
		// ██║  ██║╚██████╔╝███████╗███████╗███████║
		// ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝

		$table = str_plural(strtolower($model_name));
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
		foreach ($data as $field_name => $value)
		{
			if (!empty($value))
			{
				if ($field_name != 'id')
				{
					$result[] = sprintf("		'%s'%s=> '%s',", $field_name, str_pad('', ($max_length + 1 - strlen($field_name) )), implode('|', $value));
				}
			}
		}

		$this->info('RULES GENERATED - TABLE: ' . $table);
		$this->br();

		$rules = $this->printSingleArray($result, 1, false);

		$function_body = 
		[
			PHP_EOL,
			"	public static function validate(\$request, \$id = '')",
			"	{",
			"		\$rules = ",
			"		[",
			"	" . $rules,
			"		];",
			"		return Role::_validate(\$request, \$rules, \$id);",
			"	}",
			"}",
			PHP_EOL,
		];

		// MODEL FILE
		$model_path  = app_path(sprintf('%s%s.php', $folder_name, $model_name));
		$string_body = \File::get($model_path);
		$model_body  = explode(PHP_EOL, $string_body);

		$class_path_model = '\\App\\' . str_replace('/', '\\', $folder_name);

		$func       = new \ReflectionClass($class_path_model . $model_name);
		$filename   = $func->getFileName();
		$start_line = $func->getStartLine();
		$end_line   = $func->getEndLine();
		$length     = $end_line - $start_line;

		if (strpos($string_body, 'validate(') === false)
		{
			$new_body = 
			[
				array_slice($model_body, 0, $end_line - 1),
				$function_body,
				array_slice($model_body, $end_line + 1)
			];
			$final_body = implode(PHP_EOL, $new_body[0]) . implode(PHP_EOL, $new_body[1]) . implode(PHP_EOL, $new_body[2]);

			\File::put($model_path, $final_body);
			$this->info(sprintf('File %s saved.', $model_path));
		}
		else
		{
			$this->info('Function "validate()" already exists in ' . $model_name . '.');
			$this->waitKey();
			die;
		}

		//  █████╗ ██████╗ ███╗   ███╗██╗███╗   ██╗     ██████╗ ██████╗ ███╗   ██╗████████╗██████╗  ██████╗ ██╗     ██╗     ███████╗██████╗ 
		// ██╔══██╗██╔══██╗████╗ ████║██║████╗  ██║    ██╔════╝██╔═══██╗████╗  ██║╚══██╔══╝██╔══██╗██╔═══██╗██║     ██║     ██╔════╝██╔══██╗
		// ███████║██║  ██║██╔████╔██║██║██╔██╗ ██║    ██║     ██║   ██║██╔██╗ ██║   ██║   ██████╔╝██║   ██║██║     ██║     █████╗  ██████╔╝
		// ██╔══██║██║  ██║██║╚██╔╝██║██║██║╚██╗██║    ██║     ██║   ██║██║╚██╗██║   ██║   ██╔══██╗██║   ██║██║     ██║     ██╔══╝  ██╔══██╗
		// ██║  ██║██████╔╝██║ ╚═╝ ██║██║██║ ╚████║    ╚██████╗╚██████╔╝██║ ╚████║   ██║   ██║  ██║╚██████╔╝███████╗███████╗███████╗██║  ██║
		// ╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝     ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝╚═╝  ╚═╝

		$controller_name = $model_name;
		$fields = $this->__getFieldNames($table_name);

		$field_names = "'" . implode("','", $fields) . "'";
		$max_length = $this->__getArrayMaxLength($fields);

		$colunmed = [];
		foreach ($fields as $field)
		{
			$field_name  = "'" . $field . "'";
			$line        = str_pad($field_name, ($max_length+2));
			$line       .= in_array($field, ['created_at','updated_at','deleted_at']) ? ' => null,' : ' => 12,';
			$line        = str_repeat(chr(9), 5) . $line;
			$colunmed[]  = $line;
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
		$body = str_replace('[[caption]]'              , $model_description, $body);
		$body = str_replace('ModelName'                , $model_name, $body);
		$body = str_replace('[display_fields]'         , '[' . $field_names . ']', $body);
		$body = str_replace('[columned_display_fields]', $colunmed_str, $body);
		$body = str_replace('UserCustomInput'          , $controller_name, $body);

		file_put_contents($dest_file, $body);

		$route_prefix = $table_name;

		$template = "
	// " . $model_description . "
	Route::group
	(
		['prefix' => '" . $route_prefix . "'],
		function()
		{
			Route::get ('/'         , '" . $controller_name . "Controller@index'  )->name('admin_" . $route_prefix . "'       )->group('admin_" . $route_prefix . "');
			Route::get ('edit/{id?}', '" . $controller_name . "Controller@create' )->name('admin_" . $route_prefix . "_edit'  )->group('admin_" . $route_prefix . "');
			Route::post('edit/{id?}', '" . $controller_name . "Controller@store'  )->name('admin_" . $route_prefix . "_save'  )->group('admin_" . $route_prefix . "');
			Route::get ('show/{id}' , '" . $controller_name . "Controller@show'   )->name('admin_" . $route_prefix . "_show'  )->group('admin_" . $route_prefix . "');
			Route::post('delete/'   , '" . $controller_name . "Controller@destroy')->name('admin_" . $route_prefix . "_delete')->group('admin_" . $route_prefix . "');
		}
	);";

		$file_name = base_path('routes/custom_admin.php');

		$body = file_get_contents($file_name);
		$body .= PHP_EOL . $template;
		file_put_contents($file_name, $body);

		$this->info('DONE');
	}
}