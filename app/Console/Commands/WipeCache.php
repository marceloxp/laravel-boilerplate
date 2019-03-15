<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WipeCache extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cache:wipe';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete all files from cache folder';

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

		if ($this->confirm('delete all files and folders from "storage/framework/cache/data"?'))
		{
			$this->info('Wiping...');
			system('sudo rm -f -R storage/framework/cache/data');
			system('mkdir storage/framework/cache/data');
			$this->info('Done');
		}
	}
}
