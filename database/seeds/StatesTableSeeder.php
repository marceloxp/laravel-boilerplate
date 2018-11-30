<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

		$data = config('brasil.estados');
		$states = [];

		foreach ($data as $uf => $state)
		{
			$states[] = ['name' => $state, 'uf' => $uf, 'created_at' => $now];
		}

		foreach ($states as $state)
		{
			App\Models\State::create($state)->save();
		}
    }
}