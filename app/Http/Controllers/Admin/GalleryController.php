<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Examples\Gallery;
use Hook;

class GalleryController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Galeria';
		$this->model   = Gallery::class;
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
				'model'          => $this->model,
				'display_fields' => ['id','name','category','description','image','status','created_at']
			]
		);
	}

	public function hooks_index($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_index_%s_image', $table_name),
			function($display_value, $register)
			{
				return $this->getUploadedFile($display_value, 100);
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
				'image_fields'   => ['image'],
				'display_fields' => ['id','name','category','description','image','status']
			]
		);
	}

	public function hooks_edit($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_edit_%s_category', $table_name),
			function($input, $field_value, $register, $field_schema)
			{
				$categories = Gallery::select('category')->distinct()->get()->toArray();
				$categories = collect($categories)->pluck('category');
				$categories = $categories->all();

				$result = admin_select_simple_with_add_button('category', $categories, $field_value, true, true);
				
				return $result;
			},
			10, 4
		);
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
				'display_fields' => ['id','name','category','description','image','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_show_%s_image', $table_name),
			function($display_value, $register)
			{
				return $this->getUploadedFile($display_value, 100);
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