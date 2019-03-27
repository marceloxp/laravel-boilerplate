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
		$this->printLogo();
		$this->info(mb_strtoupper($this->description));
		$this->br();

		if (!$this->confirm('Update Core?')) { exit; }

		$zip_path = base_path('app/Console/Commands/stable.zip');

		if (!file_exists($zip_path))
		{
			$this->info('Downloading file...');
			$zip_url  = 'https://github.com/marceloxp/laravel-boilerplate/archive/stable.zip';
			$client   = new \GuzzleHttp\Client();
			$result   = $client->request('GET', $zip_url, ['save_to' => $zip_path]);
			$this->info('Done.');
		}
		
		$core_files = 
		[
			'laravel-boilerplate-stable/README.md',
			'laravel-boilerplate-stable/app/Console/Commands',
			'laravel-boilerplate-stable/app/Console/Makex',
			'laravel-boilerplate-stable/app/Http/Utilities',
			'laravel-boilerplate-stable/resources/views/Admin',
			'laravel-boilerplate-stable/app/config/app.php',
			'laravel-boilerplate-stable/app/config/brasil.php',
			'laravel-boilerplate-stable/app/config/cep.php',
			'laravel-boilerplate-stable/app/Http/Controllers/Admin/AdminController.php',
			'laravel-boilerplate-stable/app/Http/Controllers/Admin/CacheController.php',
			'laravel-boilerplate-stable/app/Http/Controllers/Admin/ConfigController.php',
			'laravel-boilerplate-stable/app/Http/Controllers/Admin/MasterManyController.php',
			'laravel-boilerplate-stable/app/Http/Controllers/Admin/SearchmodalController.php',
			'laravel-boilerplate-stable/app/Http/Controllers/Site/SiteController.php',
			'laravel-boilerplate-stable/app/Http/Kernel.php',
			'laravel-boilerplate-stable/app/Http/Middleware/Shopping.php',
			'laravel-boilerplate-stable/app/Providers/BrFakerServiceProvider',
			'laravel-boilerplate-stable/app/Providers/XpCollectionServiceProvider.php',
			'laravel-boilerplate-stable/app/routes/admin.php',
			'laravel-boilerplate-stable/public/js/admin/jquery.priceformat.min.js',
			'laravel-boilerplate-stable/public/js/admin/jQuery.SimpleMask.min.js',
			'laravel-boilerplate-stable/resources/views/layouts/admin.blade.php',
			'laravel-boilerplate-stable/public/admin-lte-custom/edit.js'
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

		$this->info('Copying files...');
		\File::copyDirectory(base_path('app/Console/Commands/Extracted/laravel-boilerplate-stable/'), base_path(''));
		$this->info('Done.');

		$this->info('Removing extracted folder...');
		\File::deleteDirectory(base_path('app/Console/Commands/Extracted/'));
		$this->info('Done.');

		$this->info('Removing zip file...');
		\File::delete($zip_path);
		$this->info('Done.');
	}
}