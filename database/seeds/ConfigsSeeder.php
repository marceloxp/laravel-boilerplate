<?php

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$now = \Carbon\Carbon::now();

        $data = array
        (
            array('name' => 'dbconfig.ready', 'value' => '1', 'created_at' => $now)
        );

        Config::insert($data);
    }
}
