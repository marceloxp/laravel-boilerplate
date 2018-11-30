<?php

use Illuminate\Database\Seeder;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$now = \Carbon\Carbon::now();

        $data = 
        [
            ['name' => 'dbconfig.ready', 'value' => '1', 'created_at' => $now],
            ['name' => 'cache.use'     , 'value' => 's', 'created_at' => $now]
        ];

        \App\Models\Config::insert($data);
    }
}