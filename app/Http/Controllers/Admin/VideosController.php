<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use App\Http\Umstudio\Youtube;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use Hook;

class VideosController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Vídeos';
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
				'request'        => $request,
				'model'          => Video::class,
				'display_fields' => ['id','category_id','name','youtube','created_at']
			]
		);
	}

	public function hooks_index($table_name)
	{
		Hook::listen
		(
			sprintf('admin_index_%s_youtube', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return Youtube::getImageUrlLink($display_value);
			}
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $id = null)
	{
		$table_name     = (new Video())->getTable();
		$register       = ($id) ? Video::find($id) : new Video;
		$is_creating    = (empty($id));
		$panel_title    = [$this->caption, ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
		$table_name     = (new Video())->getTable();
		$display_fields = ['id','category_id','name','youtube'];
		$fields_schema  = Video::getFieldsMetaData();

		View::share(compact('register','is_creating','panel_title','display_fields','fields_schema','table_name'));

		return view('Admin.generic_add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		return $this->defaultStore($request, Video::class);
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
				'model'          => Video::class,
				'display_fields' => ['id','category_id','name','youtube','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::listen
		(
			sprintf('admin_show_%s_youtube', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return Youtube::getImageUrlLink($display_value);
			}
		);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		return $this->destroy_register(Video::class, $request);
	}
}