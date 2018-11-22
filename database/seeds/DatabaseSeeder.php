<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call(ConfigsSeeder::class);
		$this->call(RolesSeeder::class);
		$this->call(UsersSeeder::class);
		$this->call(CategoriesSeeder::class);
		$this->call(VideosSeeder::class);
        $this->call(StatesSeeder::class);
		$this->call(CitiesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(CustomersSeeder::class);
    }
}