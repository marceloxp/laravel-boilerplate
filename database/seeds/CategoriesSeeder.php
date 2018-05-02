<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
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
            array('name' => 'Space'  , 'description' => 'Space Videos'  , 'created_at' => $now),
            array('name' => 'Animals', 'description' => 'Animals Videos', 'created_at' => $now),
            array('name' => 'Cities' , 'description' => 'Cities Videos' , 'created_at' => $now)
        );

        Category::insert($data);
    }
}
