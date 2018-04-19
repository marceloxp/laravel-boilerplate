<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Hook;

class CheckSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Site enviroments and configurations.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

	private function br()
	{
		$this->info('');
	}

	private function checkConfigValue($p_config_name, $p_config_expected)
	{
    	$this->info(sprintf('? Check config value [%s]', $p_config_name));
		$config_value = config($p_config_name, '');
		if ($config_value != $p_config_expected)
		{
    		$this->error(sprintf('! Config expected: [%s]', $p_config_expected));
			die;
		}
		else
		{
			$this->info(sprintf('> OK: [%s]', $config_value));
		}
		$this->br();
	}

	private function checkConfigExists($p_config_name, $p_config_example)
	{
    	$this->info(sprintf('? Check config exists [%s]', $p_config_name));
		$config_value = config($p_config_name, null);
		if ($config_value == null)
		{
    		$this->error('! Please create a config. Example:');
    		$this->error('  ' . $p_config_example);
			die;
		}
		else
		{
			$this->info(sprintf('> OK: [%s]', $config_value));
		}
		$this->br();
	}

	private function checkEnvExists($p_env_name, $p_env_example)
	{
    	$this->info(sprintf('? Check env exists [%s]', $p_env_name));
		$env_value = env($p_env_name, null);
		if ($env_value == null)
		{
    		$this->error('! Please create a env variable. Example:');
    		$this->error('  ' . $p_env_example);
			die;
		}
		else
		{
			$this->info(sprintf('> OK: [%s]', $env_value));
		}
		$this->br();
	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$this->info('- Checking project');
		$this->br();

		$config_values = 
		[
			'app.log'      => 'daily',
			'app.timezone' => 'America/Sao_Paulo',
			'app.locale'   => 'pt-br',
		];

		foreach($config_values as $config_name => $config_value)
		{
			$this->checkConfigValue($config_name, $config_value);
		}

		$config_exists = 
		[
			'app.version'  => "'version' => '0.0.1'"
		];

		foreach($config_exists as $config_name => $config_value)
		{
			$this->checkConfigExists($config_name, $config_value);
		}

		$this->info('> OK');
		$this->br();

		$this->info('? Check Package Hook');
		if (!class_exists(Hook::class))
		{
			$this->error('! Please install a package [esemve/hook]');
			die;
		}
		$this->info('> OK');
		$this->br();

		$this->br();
		$this->info(' _____ _____ _____ _____   _____ _   ___ _ _ ');
		$this->info('/  ___|_   _|_   _|  ___| |  _  | | / / | | |');
		$this->info('\ `--.  | |   | | | |__   | | | | |/ /| | | |');
		$this->info(' `--. \ | |   | | |  __|  | | | |    \| | | |');
		$this->info('/\__/ /_| |_  | | | |___  \ \_/ / |\  \_|_|_|');
		$this->info('\____/ \___/  \_/ \____/   \___/\_| \_(_|_|_)');
		$this->info('');
    }
}