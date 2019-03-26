<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Utilities\Cached;

class MakexCached extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex:cached {--clear : Clear cached files} {--list : List cached files} {--count : Show count od cached files}';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $examples = 
	[
		'php artisan makex:cached --clear',
		'php artisan makex:cached --list',
		'php artisan makex:cached --count'
	];

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Manipulate App\Http\Utilities\Cached';

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

		if ($this->option('clear'))
		{
			$this->info(sprintf('Cache Quant: %s', (\Cache::get('gcache-prefixes') ?? collect([]))->count()));
			$this->info('Clearing...');
			Cached::flush();
			$this->info('Done.');
			$this->info(sprintf('Cache Quant: %s', (\Cache::get('gcache-prefixes') ?? collect([]))->count()));
		}
		elseif ($this->option('list'))
		{
			print_r(Cached::list());
		}
		elseif ($this->option('count'))
		{
			$this->info(sprintf('Cache Quant: %s', (\Cache::get('gcache-prefixes') ?? collect([]))->count()));
		}
		else
		{
			$this->info('Invalid arguments!');
		}

		$this->info($this->__getLine());
		$this->br();
	}
}