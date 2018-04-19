<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $admin_role = 
        [
            'name'        => 'Admin',
            'description' => 'Generic Admin User',
			'color'       => 'Azul',
            'created_at'  => $now
        ];

        $register = App\Models\Role::create($admin_role);
        $register->save();

        $master_role = 
        [
            'name'        => 'Master',
            'description' => 'Master Admin User',
			'color'       => 'Verde',
            'created_at'  => $now
        ];

        $register = App\Models\Role::create($master_role);
        $register->save();

        $developer_role = 
        [
            'name'        => 'Developer',
            'description' => 'Developer Admin User',
			'color'       => 'Laranja',
            'created_at'  => $now
        ];

        $register = App\Models\Role::create($developer_role);
        $register->save();
    }
}
