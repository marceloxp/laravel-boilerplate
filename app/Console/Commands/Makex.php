<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Hook;

class Makex extends \App\Console\MakexCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'makex';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Choose MakeX Command';

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

		$options = 
		[
			'Create a new Admin Controller',
			'Create a new Table, Migration, Model and Admin Page',
			'Create a new Single Master Model',
			'Update framework core files',
			'-------------------------------------------------------',
			'X' => 'Cancelar'
		];

		$commands = 
		[
			'makex:admin_controller',
			'makex:create_simple_table',
			'makex:mastermodel',
			'makex:updatecore',
		];

		$this->printLine('COMMANDS');
		$this->printSingleArray($options);

		$defaultIndex = 'X';
		$option = $this->choice('Choose Command', $options, $defaultIndex);
		if ( ($option === 'X') || ($option === null) || ($option >= 4 ) )
		{
			exit;
		}

		$command = sprintf('php artisan %s',  $commands[$option]);

		$this->call($commands[$option]);
	}
}