<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = \Carbon\Carbon::now();

		\App\Models\Product::insert
		(
			[
				[
					'name'       => 'Produto número 1',
					'price'      => 10.00,
					'discount'   => 0,
					'created_at' => $now
				],
				[
					'name'       => 'Produto número 2',
					'price'      => 10.00,
					'discount'   => 10,
					'created_at' => $now
				],
				[
					'name'       => 'Produto número 3',
					'price'      => 10.00,
					'discount'   => 20,
					'created_at' => $now
				],
			]
		);
	}
}