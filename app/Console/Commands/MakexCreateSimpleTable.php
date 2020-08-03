<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakexCreateSimpleTable extends \App\Console\Makex\MakexCommand
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

		$this->string_class = "<?php

namespace App\Models\_schema_model;

use Illuminate\Database\Eloquent\Model;
UseSoftDeletes1
use App\Models\Masters\schema_model_Model;
HasParentId1

class %s extends schema_model_Model
{
	UseSoftDeletes2
	HasParentId2
	protected \$dates   = ['created_at','updated_at','deleted_at'];
	protected \$guarded = ['created_at','updated_at','deleted_at'];
}";

		$this->migration_class = "<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{schema_model}{ClassName}Table extends Migration
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
			'{schema_name}.{table_name}',
			function (Blueprint \$table)
			{
				\$table->bigIncrements('id');
				\$table->string('name', 255)->comment('Nome');

				\$table->timestamps();
				\$table->softDeletes();
				\$table->index(['name', 'deleted_at']);
				\$table->index(['deleted_at']);
			}
		);
		db_comment_table('{table_schema}', '{table_name}', '{table_comment}');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('{schema_name}.{table_name}');
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

		$folder_name = 'Models';
		$model_name = $this->ask('Model name (Singular)', 'cancel');
		if ($model_name == 'cancel')
		{
			exit;
		}
		$schema_name = $this->ask('Schema name', 'cancel');
		if ($schema_name == 'cancel')
		{
			exit;
		}
		$model_name        = ucfirst(\Illuminate\Support\Str::camel(strtolower($model_name)));
		$class_name        = \Illuminate\Support\Str::plural($model_name);
		$model_description = $this->ask('Model description');
		$table_name        = db_model_to_table_name($model_name);
		$model_description = $model_description ?? $table_name;
		$schema_model      = ucfirst($schema_name);
		$folder_name       = $folder_name . '/' . $schema_model . '/';

		$this->info('Schema Name: ' . $schema_model);
		$this->info('Model Name : ' . $model_name);
		$this->info('Schema Name: ' . $schema_name);
		$this->info('Table Name : ' . $table_name);
		$this->info('Description: ' . $model_description);

		$file_search     = sprintf('migrations/*_create_%s_%s_table.php', $schema_name, $table_name);
		$this->info('file_search: ' . $file_search);
		$file_search     = database_path($file_search);
		$file_migrations = \File::glob($file_search);
		print_r($file_migrations);
		$has_migrations  = (count($file_migrations) > 0);

		$changes = [];

		$use_soft_deletes = ($this->confirm('Use SoftDeletes?', 1));
		$changes['UseSoftDeletes1'] = (!$use_soft_deletes) ? '{delete_line}' : 'use Illuminate\Database\Eloquent\SoftDeletes;';
		$changes['UseSoftDeletes2'] = (!$use_soft_deletes) ? '{delete_line}' : 'use SoftDeletes;';

		$use_has_parent_id = ($this->confirm('Table has parent_id?', 0));
		$changes['HasParentId1'] = (!$use_has_parent_id) ? '{delete_line}' : 'use App\Traits\TreeModelTrait;';
		$changes['HasParentId2'] = (!$use_has_parent_id) ? '{delete_line}' : 'use TreeModelTrait;';
		$changes['schema_model_'] = $schema_model;
		$changes['_schema_model'] = $schema_model;

		$file_name = sprintf('%s%s.php', $folder_name, $model_name);
		$file_name = app_path($file_name);

		if (\File::exists($file_name))
		{
			$this->info('Model filename: ' . $file_name);
			if (!$this->confirm('Model file already exists, ovewrite?', 1))
			{
				exit;
			}
			\File::delete($file_name);
		}

		$letter_migration = ($has_migrations) ? '' : '-m';
		$do_migration = (!empty($letter_migration));

		$table_exists = db_table_exists($schema_name, $table_name);
		if ($table_exists)
		{
			system('php artisan migrate:status');
			if ($this->confirm('Table already exists, drop it?', 1))
			{
				\Schema::dropIfExists($table_name);

				system('php artisan migrate:status');
				if ($this->confirm('Execute 1 rollback?', 1))
				{
					$this->info('ROLLBACK PREVIEW');
					system(sprintf('php artisan migrate:rollback --step=%s --pretend', 1));

					if ($this->confirm('Proceed Rollback?'))
					{
						system(sprintf('php artisan migrate:rollback --step=%s', 1));
					}
				}
			}
			else
			{
				exit;
			}
		}


		$this->info('EXECUTING MODEL CREATION');
		$command = sprintf('php artisan make:model %s%s', $folder_name, $model_name);
		$this->info($command);
		$result = system($command);
		$search_message = 'Model created successfully';
		if (strpos($result, $search_message) === false) { die('Migration creation error (1)!'); }

		if ($do_migration)
		{
			$this->info('EXECUTING MIGRATE CREATION');
			$path = base_path() . '/database/migrations/' . $schema_name;
			$this->info('Folder migration: ' . $path);
			\File::makeDirectory($path, 0775, true, true);

			$command = sprintf('php artisan make:migration create_%s_%s_table --create=%s --path=%s', $schema_name, $table_name, $table_name, 'database/migrations/' . $schema_name);
			$this->info($command);
			$result = system($command);

			$search_message = 'Created Migration';
			if (strpos($result, $search_message) === false) { die('Migration creation error (2)!'); }
			$migration_file_name = trim(str_replace('Created Migration: ', '', $result));
			$migration_file_name .= '.php';

			$body_migration_path = $path . '/' . $migration_file_name;
			$this->info('body_migration_path: ' . $body_migration_path);
			$body_migration = file_get_contents($body_migration_path);
			$body_migration = str_replace("'customers'", "'" . $schema_name . ".customers'", $body_migration);
			file_put_contents($body_migration_path, $body_migration);
		}

		$body = sprintf($this->string_class, $model_name);
		foreach ($changes as $key => $value)
		{
			$body = str_replace($key, $value, $body);
		}

		$temp = explode(PHP_EOL, $body);
		$body = [];
		foreach ($temp as $line)
		{
			if (!\Illuminate\Support\Str::contains($line, '{delete_line}'))
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

		if ($do_migration)
		{
			$migration_path = database_path('migrations/' . $schema_name . '/' . $migration_file_name);
			$body = $this->migration_class;

			$body = str_replace('{schema_model}' , $schema_model, $body);
			$body = str_replace('{ClassName}'    , $class_name, $body);
			$body = str_replace('{table_name}'   , $table_name, $body);
			$body = str_replace('{schema_name}'  , $schema_name, $body);
			$body = str_replace('{ModelName}'    , $model_name, $body);
			$body = str_replace('{table_comment}', $model_description, $body);
			$body = str_replace('{table_schema}' , $schema_name, $body);

			if (!$use_soft_deletes)
			{
				$body = str_replace(PHP_EOL . '				$table->softDeletes();', '', $body);
				$body = str_replace(PHP_EOL . '				$table->index([\'deleted_at\']);', '', $body);
			}

			\File::put($migration_path, $body);
		}

		// ███╗   ███╗███████╗███╗   ██╗██╗   ██╗
		// ████╗ ████║██╔════╝████╗  ██║██║   ██║
		// ██╔████╔██║█████╗  ██╔██╗ ██║██║   ██║
		// ██║╚██╔╝██║██╔══╝  ██║╚██╗██║██║   ██║
		// ██║ ╚═╝ ██║███████╗██║ ╚████║╚██████╔╝
		// ╚═╝     ╚═╝╚══════╝╚═╝  ╚═══╝ ╚═════╝ 

		$menu_seeder_file = base_path('database/seeds/MenusTableSeeder.php');
		$body_menu_seeder = file_get_contents($menu_seeder_file);
		$next_auto_menu = '// next_auto_menu';
		$item = sprintf("\$item = \App\Models\Common\Menu::addMenuLink(\$group, '" . $schema_name . "', '" . $model_description . "', 'fas fa-file', \$public, 'admin_" . $schema_name . "_" . $table_name . "');");
		$item = $item . "\n\t\t\t" . $next_auto_menu;
		$body_menu_seeder = str_replace('// next_auto_menu', $item, $body_menu_seeder);
		file_put_contents($menu_seeder_file, $body_menu_seeder);
		$this->call('db:seed', ['--class' => 'MenusTableSeeder']);
		// $command = 'php artisan db:seed --class=MenusTableSeeder';
		// $this->info($command);
		// system($command);

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
		$command = sprintf('php artisan migrate --path=database/migrations/' . $schema_name);
		$this->info('Executing migration: ' . $command);
		system($command);

		//  ██████╗ ███╗   ██╗███████╗    ████████╗ ██████╗      ██████╗ ███╗   ██╗███████╗
		// ██╔═══██╗████╗  ██║██╔════╝    ╚══██╔══╝██╔═══██╗    ██╔═══██╗████╗  ██║██╔════╝
		// ██║   ██║██╔██╗ ██║█████╗         ██║   ██║   ██║    ██║   ██║██╔██╗ ██║█████╗  
		// ██║   ██║██║╚██╗██║██╔══╝         ██║   ██║   ██║    ██║   ██║██║╚██╗██║██╔══╝  
		// ╚██████╔╝██║ ╚████║███████╗       ██║   ╚██████╔╝    ╚██████╔╝██║ ╚████║███████╗
		//  ╚═════╝ ╚═╝  ╚═══╝╚══════╝       ╚═╝    ╚═════╝      ╚═════╝ ╚═╝  ╚═══╝╚══════╝

		$this->call('makex:cached', ['--clear' => true]);
		$model    = sprintf('\App\Models\%s\%s', $schema_model, $model_name);
		$metadata = collect($model::getFieldsMetaData());
		$quant    = $metadata->where('has_relation', 'true')->count();
		if ($quant > 0)
		{
			foreach ($metadata->where('has_relation', 'true') as $field)
			{
				$model_target = db_table_name_to_model($field['relation']['ref_table']);
				$model_list   = db_table_name_to_model($field['relation']['table_name']);
				$this->info(sprintf('MODEL RELATION ONE TO ONE: %s => %s', $model_target, $model_list));
				$call_options =
				[
					'model_target' => $model_target,
					'model_list'   => $model_list,
					'--onetoone'   => true
				];
				dump($call_options);
				$this->call('makex:model', $call_options);
			}
		}

		// ██████╗ ██╗   ██╗██╗     ███████╗███████╗
		// ██╔══██╗██║   ██║██║     ██╔════╝██╔════╝
		// ██████╔╝██║   ██║██║     █████╗  ███████╗
		// ██╔══██╗██║   ██║██║     ██╔══╝  ╚════██║
		// ██║  ██║╚██████╔╝███████╗███████╗███████║
		// ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝

		$command = sprintf('php artisan makex:model_rules %s %s', $schema_name, $model_name);
		system($command);

		//  █████╗ ██████╗ ███╗   ███╗██╗███╗   ██╗     ██████╗ ██████╗ ███╗   ██╗████████╗██████╗  ██████╗ ██╗     ██╗     ███████╗██████╗ 
		// ██╔══██╗██╔══██╗████╗ ████║██║████╗  ██║    ██╔════╝██╔═══██╗████╗  ██║╚══██╔══╝██╔══██╗██╔═══██╗██║     ██║     ██╔════╝██╔══██╗
		// ███████║██║  ██║██╔████╔██║██║██╔██╗ ██║    ██║     ██║   ██║██╔██╗ ██║   ██║   ██████╔╝██║   ██║██║     ██║     █████╗  ██████╔╝
		// ██╔══██║██║  ██║██║╚██╔╝██║██║██║╚██╗██║    ██║     ██║   ██║██║╚██╗██║   ██║   ██╔══██╗██║   ██║██║     ██║     ██╔══╝  ██╔══██╗
		// ██║  ██║██████╔╝██║ ╚═╝ ██║██║██║ ╚████║    ╚██████╗╚██████╔╝██║ ╚████║   ██║   ██║  ██║╚██████╔╝███████╗███████╗███████╗██║  ██║
		// ╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝     ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝╚═╝  ╚═╝

		$controller_name = $model_name;
		$fields = db_get_field_names($schema_name, $table_name);

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

		$source_file = app_path('Console/Makex/TemplateController.php.txt');
		$dest_folder = 'Http/Controllers/Admin/' . $schema_model;
		$dest_file   = app_path($dest_folder . '/' . $controller_name . 'Controller.php');
		
		\File::makeDirectory(app_path($dest_folder), 0775, true, true);
		if (file_exists($dest_file))
		{
			if (!$this->confirm('Destination file (' . $dest_file . ') already exists, overwrite?'))
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
		$body = str_replace('_schema_model'            , $schema_model, $body);
		if (in_array('parent_id', $fields))
		{
			$body = str_replace('return $this->defaultIndex', 'return $this->defaultTreeIndex', $body);
			$body = str_replace("'editable'       => true,", "", $body);
		}

		file_put_contents($dest_file, $body);

		// ██████╗  ██████╗ ██╗   ██╗████████╗███████╗███████╗
		// ██╔══██╗██╔═══██╗██║   ██║╚══██╔══╝██╔════╝██╔════╝
		// ██████╔╝██║   ██║██║   ██║   ██║   █████╗  ███████╗
		// ██╔══██╗██║   ██║██║   ██║   ██║   ██╔══╝  ╚════██║
		// ██║  ██║╚██████╔╝╚██████╔╝   ██║   ███████╗███████║
		// ╚═╝  ╚═╝ ╚═════╝  ╚═════╝    ╚═╝   ╚══════╝╚══════╝

		$route_prefix = $schema_name . '/' . $table_name;
		$route_slug   = $schema_name . '_' . $table_name;

		$template = "
	// Begin " . $schema_model . " " . $model_description . "
	Route::group
	(
		['prefix' => '" . $route_prefix . "'],
		function()
		{
			Route::get ('/'         , '" . $schema_model . "\\" . $controller_name . "Controller@index'  )->name('admin_" . $route_slug . "'       )->group('admin_" . $route_slug . "');
			Route::get ('edit/{id?}', '" . $schema_model . "\\" . $controller_name . "Controller@create' )->name('admin_" . $route_slug . "_edit'  )->group('admin_" . $route_slug . "');
			Route::post('edit/{id?}', '" . $schema_model . "\\" . $controller_name . "Controller@store'  )->name('admin_" . $route_slug . "_save'  )->group('admin_" . $route_slug . "');
			Route::get ('show/{id}' , '" . $schema_model . "\\" . $controller_name . "Controller@show'   )->name('admin_" . $route_slug . "_show'  )->group('admin_" . $route_slug . "');
			Route::post('delete/'   , '" . $schema_model . "\\" . $controller_name . "Controller@destroy')->name('admin_" . $route_slug . "_delete')->group('admin_" . $route_slug . "');
		}
	);
	// End " . $schema_model . " " . $model_description;

		$file_name = base_path('routes/custom_admin.php');
		trim_file($file_name);
		$body = file_get_contents($file_name);
		$body = delete_all_between(sprintf('// Begin %s %s', $schema_model, $model_description), sprintf('// End %s %s', $schema_model, $model_description), $body);
		$body .= PHP_EOL . $template;
		file_put_contents($file_name, $body);
		trim_file($file_name);

		$this->info('DONE');
	}
}