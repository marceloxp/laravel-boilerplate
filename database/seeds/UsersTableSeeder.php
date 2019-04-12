<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now    = Carbon::now();
        $prefix = trim(DB::getTablePrefix(), '_');
        $year   = Carbon::now()->year;

        $admin_user = 
        [
            'name'       => 'Admin',
            'email'      => 'change@host.com.br',
            'password'   => Hash::make( sprintf('%s%s%s', $prefix, $year, 'admin') ),
            'created_at' => $now
        ];

        $master_user = 
        [
            'name'       => 'Master',
            'email'      => 'projetos@host.com',
            'password'   => Hash::make( sprintf('%s%s%s', $prefix, $year, 'master') ),
            'created_at' => $now
        ];

        $developer_user = 
        [
            'name'       => 'Developer',
            'email'      => 'projetos.developer@host.com',
            'password'   => Hash::make( sprintf('%s%s%s', $prefix, $year, 'developer') ),
            'created_at' => $now
        ];

		DB::transaction
		(
			function() use($admin_user, $master_user, $developer_user)
			{
				$role_public = \App\Models\Role::where('name', 'Public')->first();
                $role_admin  = \App\Models\Role::where('name', 'Admin')->first();
				$role_master = \App\Models\Role::where('name', 'Master')->first();
				$role_dev    = \App\Models\Role::where('name', 'Developer')->first();

				$register = App\Models\User::create($admin_user);
				$register->save();
				$register->roles()->attach($role_admin);
                $register->roles()->attach($role_public);

				$register = App\Models\User::create($master_user);
				$register->save();
				$register->roles()->attach($role_admin);
				$register->roles()->attach($role_master);
                $register->roles()->attach($role_public);

				$register = App\Models\User::create($developer_user);
				$register->save();
				$register->roles()->attach($role_admin);
				$register->roles()->attach($role_master);
				$register->roles()->attach($role_dev);
                $register->roles()->attach($role_public);
			},
			5
		);
    }
}
