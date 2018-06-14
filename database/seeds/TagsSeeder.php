<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$now = \Carbon\Carbon::now();

		$tags = ['Space','Planet','Nasa','4k','Universe'];

		foreach ($tags as $tag)
		{
	        $data = array('name' => $tag, 'created_at' => $now);
	        App\Models\Tag::insert($data);
		}

        $videos = \App\Models\Video::get();
        $tags   = \App\Models\Tag::get();
        foreach ($videos as $video)
        {
            foreach ($tags as $tag)
            {
                $video->tags()->attach($tag, ['created_at' => $now]);
            }
        }
    }
}