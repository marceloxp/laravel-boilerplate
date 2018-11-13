<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Chumper\Zipper\Zipper;

class MakexUpdateCore extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:updatecore';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update framework core files';

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

		// if (!$this->confirm('Update Core?')) { exit; }

		$zip_path = base_path('app/Console/Commands/master.zip');

		if (!file_exists($zip_path))
		{
			$this->info('Downloading file...');
			$zip_url  = 'https://github.com/marceloxp/laravel-boilerplate/archive/master.zip';
			$client   = new \GuzzleHttp\Client();
			$result   = $client->request('GET', $zip_url, ['save_to' => $zip_path]);
			$this->info('Done.');
		}
		
		$core_files = 
		[
			'laravel-boilerplate-master/app/Http/Controllers/Admin/AdminController.php',
			'laravel-boilerplate-master/app/Http/Controllers/Admin/CacheController.php',
			'laravel-boilerplate-master/app/Http/Controllers/Admin/ConfigsController.php',
			'laravel-boilerplate-master/app/Http/Controllers/Admin/MasterManyController.php',
			'laravel-boilerplate-master/app/Http/Controllers/Admin/SearchmodalController.php',
			'laravel-boilerplate-master/app/Http/Utilities',
			'laravel-boilerplate-master/app/Providers/BrFakerServiceProvider',
			'laravel-boilerplate-master/app/config/brasil.php',
			'laravel-boilerplate-master/app/config/cep.php',
			'laravel-boilerplate-master/resources/views/Admin/',
			'laravel-boilerplate-master/resources/views/layouts/admin.blade.php',
			'laravel-boilerplate-master/app/routes/admin.php',
			'laravel-boilerplate-master/app/Console/Commands/Makex',
			'laravel-boilerplate-master/app/Console/Commands/Makex.php',
			'laravel-boilerplate-master/app/Console/Commands/MakexUpdateCore.php',
		];

		$this->info('Extracting zip...');
		$zipper = new Zipper;
		$zipper->make($zip_path)->extractTo
		(
			base_path('app/Console/Commands/Extracted'),
			$core_files,
			Zipper::WHITELIST
		);
		$zipper->close();
		$this->info('Done.');

		$this->info('Coping files...');
		print_r([base_path('app/Console/Commands/Extracted/laravel-boilerplate-master/'), base_path('/')]);
		\File::copyDirectory(base_path('app/Console/Commands/Extracted/laravel-boilerplate-master/'), base_path(''));
		$this->info('Done.');

		$this->info('Remove extracted folder...');
		\File::deleteDirectory(base_path('app/Console/Commands/Extracted/'));
		$this->info('Done.');

		$this->info('Remove zip file...');
		\File::delete($zip_path);
		$this->info('Done.');
	}
}