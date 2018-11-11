<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Subcategory;
use Hook;

class SubcategoryController extends AdminController
{
	public function __construct(Request $request)
	{
		$this->setCaption('Sub Categorias');
		$this->setModel(Subcategory::class);
		parent::__construct();
	}

	private function defineCaption($category_id)
	{
		$parent = \App\Models\Category::select('id','name')->where('id',$category_id)->first();
		$this->setCaption(sprintf('%s - %s', $parent->name, $this->caption));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $category_id)
	{
		$this->defineCaption($category_id);
		return $this->defaultIndex
		(
			[
				'where'          => ['category_id' => $category_id],
				'request'        => $request,
				'model'          => $this->model,
				'editable'       => true,
				'display_fields' => ['id','name','status','created_at']
			]
		);
	}

	public function hooks_index($table_name)
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $category_id = null, $id = null)
	{
		$this->defineCaption($category_id);
		View::share(compact('category_id'));
		return $this->defaultCreate
		(
			[
				'id'             => $id,
				'request'        => $request,
				'model'          => $this->model,
				'disabled'       => ['created_at'],
				'display_fields' => 
				[
					'id'          => 0,
					'category_id' => 0,
					'name'        => 4,
					'status'      => 4,
					'created_at'  => 4,
				]
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
				'display_fields' => ['id','category_id','name','status','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
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
		return $this->defaultDestroy
		(
			[
				'request' => $request,
				'model'   => $this->model
			]
		);
	}
}