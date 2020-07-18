<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakexMasterModel extends \App\Console\Makex\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:mastermodel';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new Single Master Model';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

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

		$folder_name = 'Models/';

		$model_name = $this->ask('Master Model name (Singular)', 'cancel');
		if ($model_name == 'cancel')
		{
			exit;
		}
		$model_name = ucfirst(\Illuminate\Support\Str::camel(strtolower($model_name)));

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

		$file_name = sprintf('%s%s.php', $folder_name, $model_name);
		$file_name = app_path($file_name);

		if (file_exists($file_name))
		{
			if (!$this->confirm('Model file already exists, ovewrite?', 1))
			{
				exit;
			}
		}

		\File::put($file_name, $body);

		$this->info('Done.');
	}
}