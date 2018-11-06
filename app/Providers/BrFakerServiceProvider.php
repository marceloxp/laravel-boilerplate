<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Faker\Factory as Faker;

class BrFakerServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton
		(
			'Faker',
			function($app)
			{
				$faker = Faker::create(config('app.faker_locale', 'pt_BR'));
				$newClass = new class($faker) extends \Faker\Provider\Base
				{
					private function generateCep($p_state = null)
					{
						$faixas = collect(config('cep.faixas'));
						$state  = (empty($p_state)) ? $this->generateState() : $p_state;
						$faixa  = $faixas->get($state);

						if (is_array($faixa[0]))
						{
							$faixa = $faixa[0];
						}

						return $this->numberBetween($faixa[0], $faixa[1]);
					}

					private function generateState()
					{
						$faixas = collect(config('cep.faixas'));
						$state  = $faixas->keys()->random();
						return $state;
					}

					private function generateAddress()
					{
						$faker        = Faker::create(config('app.faker_locale', 'pt_BR'));
						$faixas       = collect(config('cep.faixas'));
						$state        = $this->generateState();
						$cep          = $this->generateCep($state);
						$city         = $faker->city;
						$address      = $faker->streetName;
						$number       = $faker->buildingNumber;
						$neighborhood = collect(['Centro','Bela Vista','São José','Santo Antônio','São Francisco','Vila Nova','Boa Vista','Industrial','São Cristóvão','Planalto'])->random();
						$complement   = collect(['','','Casa ' . $faker->numberBetween(1,100),'Apto ' . $faker->numberBetween(100,900)])->random();

						return compact('state','cep','city','address','number','complement','neighborhood');
					}

					// ******************************************************************************

					public function cep($p_state = null)
					{
						return $this->generateCep($p_state);
					}

					public function state()
					{
						return $this->generateState();
					}

					public function address()
					{
						return $this->generateAddress();
					}
				};

				$faker->addProvider($newClass);
				return $faker;
			}
		);
	}
}