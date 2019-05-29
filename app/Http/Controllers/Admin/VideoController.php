<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use App\Http\Utilities\Youtube;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use Hook;

class VideoController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Vídeos';
		$this->model   = Video::class;
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return $this->defaultIndex
		(
			[
				'pivot'          => $this->model::getPivotConfig(['tags' => 'fas fa-tags']),
				'request'        => $request,
				'model'          => $this->model,
				'display_fields' => ['id','category_id','tags','name','youtube','created_at']
			]
		);
	}

	public function hooks_index($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_index_%s_youtube', $table_name),
			function($display_value, $register)
			{
				return Youtube::getImageUrlLink($display_value);
			},
			10, 2
		);

		Hook::add_filter
		(
			sprintf('admin_index_%s_category_id', $table_name),
			function($display_value, $register)
			{
				return \App\Models\Category::getStrPath($register['category_id']);
			},
			10, 2
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
				'model'          => $this->model,
				'display_fields' => ['id','category_id','tags','name','youtube']
			]
		);
	}

	public function hooks_edit($table_name)
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		return $this->defaultStore($request, $this->model);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return $this->defaultShow
		(
			[
				'id'             => $id,
				'model'          => $this->model,
				'display_fields' => ['id','category_id','name','tags','youtube','created_at','updated_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_show_%s_youtube', $table_name),
			function($display_value, $register)
			{
				return sprintf
				(
					'%s <br/> %s',
					Youtube::getEmbeddedPlayer($display_value),
					Youtube::getUrlLink($display_value)
				);
			},
			10, 2
		);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		return $this->defaultDestroy
		(
			[
				'request' => $request,
				'model'   => $this->model
			]
		);
	}
}