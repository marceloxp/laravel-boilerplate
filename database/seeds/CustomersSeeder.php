<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Console\Command;
use Faker\Factory as Faker;

class CustomersSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now   = \Carbon\Carbon::now();
		$faker = app('Faker');
		$quant = 250;
		$bar   = $this->command->getOutput()->createProgressBar($quant);
		
		for ($k=0; $k < $quant; $k++)
		{
			$address = $faker->address;
			$data = array
			(
				array
				(
					'name'           => $faker->name,
					'username'       => $faker->username,
					'born'           => $faker->date($format = 'Y-m-d', $max = 'now'),
					'cpf'            => $faker->unique()->cpf,
					'email'          => $faker->unique()->email,
					'phone_prefix'   => $faker->areaCode,
					'phone'          => $faker->phone,
					'cep'            => $faker->cep,
					'state'          => $address['state'],
					'city'           => $address['city'],
					'address'        => $address['address'],
					'address_number' => $address['number'],
					'complement'     => $address['complement'],
					'neighborhood'   => $address['neighborhood'],
					'password'       => $faker->password,
					'newsletter'     => true,
					'rules'          => true,
					'created_at'     => $now
				),
			);
			Customer::insert($data);
			$bar->advance();
		}
		$bar->finish();
	}
}