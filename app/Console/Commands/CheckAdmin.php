<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Admin Site enviroments and configurations.';

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
		$this->info('- Checking Admin Project');
		$this->br();

		$env_exists = 
		[
			'ADMIN_TITLE'   => 'ADMIN_TITLE="Nome do Título do Admin"',
			'ADMIN_CAPTION' => 'ADMIN_CAPTION="Nome Curto Admin"',
			'ADMIN_SLUG'    => 'ADMIN_SLUG="NTA"',
		];

		foreach($env_exists as $env_name => $env_value)
		{
			$this->checkEnvExists($env_name, $env_value);
		}

		$this->info('? Check Admin Helper exists');
		if (!function_exists('admin_helpers_exists'))
		{
			$this->error('! File [/www/app/Http/Helpers/admin.php] not found or not loaded.');
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