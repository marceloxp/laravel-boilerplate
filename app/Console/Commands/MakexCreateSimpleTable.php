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

		$command = sprintf('Create Model %s%s?', $folder_name, $model_name);
		if (!$this->confirm($command, 1))
		{
			exit;
		}

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
		if ($this->confirm($command, 1))
		{
			$this->info('EXECUTING MIGRATE AND MODEL CREATION');
			$result = system($command);
			if (strpos($result, 'Created Migration') === false)
			{
				die('Migration creation error!');
			}
			$migration_file_name = trim(str_replace('Created Migration: ', '', $result));
			$migration_file_name .= '.php';
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

		$admin_menu = '// ADMIN ADD ITEM DON\'T REMOVE THIS LINE' . PHP_EOL . $this->admin_menu;
		$admin_menu = str_replace('{Caption}', $model_description, $admin_menu);
		$admin_menu = str_replace('{model_singular}', str_singular(db_model_to_table_name($model_name)), $admin_menu);

		$body = \File::get($config_admin_path);
		$body = str_replace('// ADMIN ADD ITEM DON\'T REMOVE THIS LINE', $admin_menu, $body);

		\File::replace($config_admin_path, $body);

		$this->info('DONE');
	}
}