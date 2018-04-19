<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $admin_user = 
        [
            'name'       => 'Admin',
            'email'      => 'change@host.com.br',
            'password'   => Hash::make('mbz2018admin'),
            'created_at' => $now
        ];

        $master_user = 
        [
            'name'       => 'Master',
            'email'      => 'projetos@umstudio.com',
            'password'   => Hash::make('mbz2018studio0001'),
            'created_at' => $now
        ];

        $developer_user = 
        [
            'name'       => 'Developer',
            'email'      => 'projetos.developer@umstudio.com',
            'password'   => Hash::make('mbz2018studio0001'),
            'created_at' => $now
        ];

		DB::transaction
		(
			function() use($admin_user, $master_user, $developer_user)
			{
				$role_admin  = \App\Models\Role::where('name', 'Admin')->first();
				$role_master = \App\Models\Role::where('name', 'Master')->first();
				$role_dev    = \App\Models\Role::where('name', 'Developer')->first();

				$register = App\Models\User::create($admin_user);
				$register->save();
				$register->roles()->attach($role_admin);

				$register = App\Models\User::create($master_user);
				$register->save();
				$register->roles()->attach($role_admin);
				$register->roles()->attach($role_master);

				$register = App\Models\User::create($developer_user);
				$register->save();
				$register->roles()->attach($role_admin);
				$register->roles()->attach($role_master);
				$register->roles()->attach($role_dev);
			},
			5
		);
    }
}
