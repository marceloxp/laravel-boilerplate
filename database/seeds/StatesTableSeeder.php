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
    	$console = $this->command->getOutput();

        $console->writeln('Removing old registers...');
        \DB::select(sprintf('TRUNCATE TABLE %s;', 'common.cities'));
        \DB::select(sprintf('TRUNCATE TABLE %s;', 'common.states'));

		$data = config('brasil.estados');
		$states = [];

		foreach ($data as $uf => $state)
		{
			$states[] = ['name' => $state, 'uf' => $uf, 'created_at' => $now];
		}

		$console->writeln('Seeding table...');
		$console->progressStart(count($states));
		foreach ($states as $state)
		{
			$result = App\Models\Common\State::create($state)->save();
			$console->progressAdvance();
		}
		$console->progressFinish();

		$console->writeln('Done');
		$console->newLine();
    }
}