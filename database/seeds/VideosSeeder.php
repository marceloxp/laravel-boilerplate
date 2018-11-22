<?php

use Illuminate\Database\Seeder;

class VideosSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = \Carbon\Carbon::now();
		$category_id = \App\Models\Category::select('id')->where(['name' => 'Space'])->first()->toArray()['id'];

		\App\Models\Video::insert
		(
			[
				[
					'name'        => 'NASA | 4K Video: Thermonuclear Art – The Sun In Ultra HD 4K',
					'category_id' => $category_id,
					'youtube'     => 'https://www.youtube.com/watch?v=omlXSRvb1Wo',
					'created_at'  => $now
				],
				[
					'name'        => 'NASA UHD Video: Stunning Aurora Borealis from Space in Ultra-High Definition (4K)',
					'category_id' => $category_id,
					'youtube'     => 'https://www.youtube.com/watch?v=fVMgnmi2D1w',
					'created_at'  => $now
				],
				[
					'name'        => 'A Comparação do Tamanho do Universo - O Vídeo Mais Completo de Todos',
					'category_id' => $category_id,
					'youtube'     => 'https://www.youtube.com/watch?v=BueCYLvTBso',
					'created_at'  => $now
				]
			]
		);
	}
}