<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = \Carbon\Carbon::now();

		$public_role = 
		[
			'name'        => 'Public',
			'description' => 'Public User',
			'color'       => 'Cinza',
			'created_at'  => $now
		];

		$register = App\Models\Common\Role::create($public_role);
		$register->save();
		
		$admin_role = 
		[
			'name'        => 'Admin',
			'description' => 'Generic Admin User',
			'color'       => 'Azul',
			'created_at'  => $now
		];

		$register = App\Models\Common\Role::create($admin_role);
		$register->save();

		$master_role = 
		[
			'name'        => 'Master',
			'description' => 'Master Admin User',
			'color'       => 'Verde',
			'created_at'  => $now
		];

		$register = App\Models\Common\Role::create($master_role);
		$register->save();

		$developer_role = 
		[
			'name'        => 'Developer',
			'description' => 'Developer Admin User',
			'color'       => 'Laranja',
			'created_at'  => $now
		];

		$register = App\Models\Common\Role::create($developer_role);
		$register->save();
	}
}
