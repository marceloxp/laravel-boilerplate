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
		$this->call(ConfigsTableSeeder::class);
		$this->call(RolesTableSeeder::class);
		$this->call(UsersTableSeeder::class);
		$this->call(CategoriesTableSeeder::class);
		$this->call(VideosTableSeeder::class);
        $this->call(StatesTableSeeder::class);
		$this->call(CitiesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PaymenttypesTableSeeder::class);
        $this->call(PaymentsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
    }
}