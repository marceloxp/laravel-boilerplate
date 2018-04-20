<?php

use Illuminate\Database\Seeder;
use App\Models\Video;

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

        Video::insert
		(
			[
				[
					'name'       => 'NASA | 4K Video: Thermonuclear Art – The Sun In Ultra HD 4K',
					'youtube'    => 'https://www.youtube.com/watch?v=omlXSRvb1Wo',
					'created_at' => $now
				],
				[
					'name'       => 'NASA UHD Video: Stunning Aurora Borealis from Space in Ultra-High Definition (4K)',
					'youtube'    => 'https://www.youtube.com/watch?v=fVMgnmi2D1w',
					'created_at' => $now
				],
				[
					'name'       => 'A Comparação do Tamanho do Universo - O Vídeo Mais Completo de Todos',
					'youtube'    => 'https://www.youtube.com/watch?v=BueCYLvTBso',
					'created_at' => $now
				]
			]
		);
    }
}
