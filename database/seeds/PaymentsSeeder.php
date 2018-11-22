<?php

use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$now = \Carbon\Carbon::now();

		\App\Models\Payment::insert
		(
			[
				[
					'name'       => 'visa',
					'title'      => 'Visa',
					'type'       => 'creditcard',
					'discount'   => 0,
					'parcs'      => 12,
					'created_at' => $now
				],
				[
					'name'       => 'boleto',
					'title'      => 'Boleto',
					'type'       => 'boleto',
					'discount'   => 10,
					'parcs'      => 1,
					'created_at' => $now
				],
				[
					'name'       => 'cash',
					'title'      => 'Dinheiro',
					'type'       => 'avista',
					'discount'   => 15,
					'parcs'      => 1,
					'created_at' => $now
				],
			]
		);
    }
}