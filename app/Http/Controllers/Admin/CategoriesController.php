<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Hook;

class CategoriesController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Categorias';
		$this->model   = Category::class;
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
				'display_fields' => ['id','name','image','description','created_at']
			]
		);
	}

	public function getUploadedFile($p_file_name, $p_height = 100)
	{
		if (empty($p_file_name)) { return $p_file_name; }
		return sprintf('%s<br/>%s', link_uploaded_file($p_file_name, sprintf('height="%s"', $p_height)), $p_file_name);
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
				'display_fields' => ['id','name','image','description']
			]
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
				'display_fields' => ['id','name','image','description','created_at','updated_at','deleted_at']
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