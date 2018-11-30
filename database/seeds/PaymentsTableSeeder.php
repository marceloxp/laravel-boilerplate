<?php

use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
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
					'name'           => 'visa',
					'description'    => 'Visa',
					'paymenttype_id' => \App\Models\Paymenttype::select('id')->whereName('creditcard')->first()->id,
					'discount'       => 0,
					'parcs'          => 12,
					'created_at'     => $now
				],
				[
					'name'           => 'boleto',
					'description'    => 'Boleto',
					'paymenttype_id' => \App\Models\Paymenttype::select('id')->whereName('boleto')->first()->id,
					'discount'       => 10,
					'parcs'          => 1,
					'created_at'     => $now
				],
				[
					'name'           => 'cash',
					'description'    => 'Dinheiro',
					'paymenttype_id' => \App\Models\Paymenttype::select('id')->whereName('cash')->first()->id,
					'discount'       => 15,
					'parcs'          => 1,
					'created_at'     => $now
				],
			]
		);
    }
}