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
		$this->showMenu();
	}

	public function showMenu()
	{
		$this->clear();
		$this->printLogo();

		$options = 
		[
			'Create a new Table, Migration, Model and Admin Page',
			'Add Pivot to Table',
			'Clear Cached',
			'Update framework core files',
			'-------------------------------------------------------',
			'X' => 'Cancelar'
		];

		$commands = 
		[
			'makex:create_simple_table',
			'makex:add_pivot_to_table',
			['makex:cached', ['--clear' => true]],
			'makex:updatecore',
		];

		$this->printLine('COMMANDS');

		$defaultIndex = 'X';
		$option = $this->choice('Choose Command', $options, $defaultIndex);
		if ( ($option === 'X') || ($option === null) || ($option >= 4 ) )
		{
			exit;
		}
		$this->clear();

		$command = $commands[$option];
		if (!is_array($command))
		{
			$this->call($command);
		}
		else
		{
			$this->call($command[0], $command[1]);
		}

		$this->waitKey();
		$this->showMenu();
	}
}