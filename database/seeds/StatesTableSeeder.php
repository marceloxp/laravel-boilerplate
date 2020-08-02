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
			var_dump($state);
			$result = App\Models\Common\State::create($state)->save();
			var_dump($result);
			die;
		}
    }
}