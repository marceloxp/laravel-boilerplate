<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Utilities\Cached;

class MakexClearCached extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:clear_cached';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear App\Http\Utilities\Cached';

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
		
		$this->info(sprintf('Cache Quant: %s', (\Cache::get('gcache-prefixes') ?? collect([]))->count()));
		$this->info('Clearing...');
		Cached::flush();
		$this->info('Done.');
		$this->info(sprintf('Cache Quant: %s', (\Cache::get('gcache-prefixes') ?? collect([]))->count()));
	}
}