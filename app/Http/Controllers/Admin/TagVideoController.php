<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use App\Http\Utilities\Youtube;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use \App\Http\Utilities\Result;
use App\Models\Video;
use App\Models\Tag;
use Hook;

class TagVideoController extends AdminController
{
	public function __construct()
	{
		$this->setCaption('Vídeos (Tags)');
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $video_id)
	{
		if (empty($video_id))
		{
			die('Invalid parameters.');
		}

		$this->setCaption('Tags', db_get_name('videos', $video_id));

		return $this->defaultIndex
		(
			[
				'pivot_scope'    =>
				[
					'name'  => 'tagVideo',
					'param' => $video_id
				],
				'request'        => $request,
				'model'          => Tag::class,
				'display_fields' => ['id','name','created_at']
			]
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $id = null)
	{
		return $this->defaultCreate
		(
			[
				'id'             => $id,
				'request'        => $request,
				'model'          => Video::class,
				'display_fields' => ['id','category_id','name','youtube']
			]
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $video_id)
	{
		$ids = $request->input('ids');
		foreach ($ids as $tag_id)
		{
			$video = Video::findOrFail($video_id);
			$result = $video->tags()->attach($tag_id);
		}

		return Result::success('Registros adicionados com sucesso.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($video_id, $tag_id)
	{
		return $this->defaultShow
		(
			[
				'id'             => $tag_id,
				'model'          => Tag::class,
				'display_fields' => ['id','name']
			]
		);
	}

	public function detach(Request $request, $video_id)
	{
		$ids = $request->input('ids');
		$ids = explode(',', $ids['ids']);
		foreach ($ids as $tag_id)
		{
			$video = Video::findOrFail($video_id);
			$result = $video->tags()->detach($tag_id);
		}

		return Result::success('Registros excluídos com sucesso.');
	}
}