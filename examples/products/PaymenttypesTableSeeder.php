<?php

use Illuminate\Database\Seeder;

class PaymenttypesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = \Carbon\Carbon::now();

		\App\Models\Paymenttype::insert
		(
			[
				[
					'name'        => 'creditcard',
					'description' => 'Cartão de Crédito',
					'created_at'  => $now
				],
				[
					'name'        => 'boleto',
					'description' => 'Boleto',
					'created_at'  => $now
				],
				[
					'name'        => 'cash',
					'description' => 'Dinheiro',
					'created_at'  => $now
				]
			]
		);
	}
}