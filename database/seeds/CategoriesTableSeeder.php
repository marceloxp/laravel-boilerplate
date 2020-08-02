<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
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
			\DB::select(sprintf('DELETE FROM examples.%s WHERE id >= 0', 'tag_video'));
			\DB::select(sprintf('DELETE FROM examples.%s WHERE id >= 0', 'videos'));
			\DB::select(sprintf('DELETE FROM examples.%s WHERE id >= 0', 'categories'));
			
			$categories = 
			[
				['name' => 'Space'  , 'description' => 'Space Videos'  , 'childs' => ['Sun','Earth','Galaxy'] ],
				['name' => 'Animals', 'description' => 'Animals Videos', 'childs' => ['Cat','Dog','Duck','Rat','Horse'] ],
				['name' => 'Cities' , 'description' => 'Cities Videos' , 'childs' => ['São Paulo','Rio de Janeiro','Amazonas','Ceará','Goiás'] ],
			];

			foreach ($categories as $category)
			{
				$register = App\Models\Examples\Category::addRoot($category['name'], $category['description']);
				foreach ($category['childs'] as $child)
				{
					App\Models\Examples\Category::addSubCategory($register->id, $child, '');
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