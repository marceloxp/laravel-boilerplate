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
		try
		{
			$now = \Carbon\Carbon::now();

			$categories = 
			[
				['name' => 'Space'  , 'description' => 'Space Videos'  , 'created_at' => $now],
				['name' => 'Animals', 'description' => 'Animals Videos', 'created_at' => $now],
				['name' => 'Cities' , 'description' => 'Cities Videos' , 'created_at' => $now],
			];

			$subcategories = 
			[
				'Space'   => ['Sun','Earth','Galaxy'],
				'Animals' => ['Cat','Dog','Duck','Rat','Horse'],
				'Cities'  => ['São Paulo','Rio de Janeiro','Amazonas','Ceará','Goiás'],
			];

			foreach ($categories as $category)
			{
				$result      = Category::create($category);
				$category_id = $result->id;
				$subs        = $subcategories[$result->name];

				foreach ($subs as $sub)
				{
					\App\Models\Subcategory::create(['category_id' => $category_id, 'name' => $sub, 'created_at' => $now]);
				}
			}
		}
		catch (Exception $e)
		{
			echo sprintf('Ocorreu um erro na operação: %s', $e->getMessage());
			die;
		}
	}
}
