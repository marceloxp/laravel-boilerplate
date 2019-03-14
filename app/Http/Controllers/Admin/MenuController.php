<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Hook;

class MenuController extends AdminController
{
	public function __construct()
	{
		$this->setModel(\App\Models\Menu::class);
		$this->setCaptionByModel($this->model);
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return $this->defaultTreeIndex
		(
			[
				'pivot'          => $this->model::getPivotConfig(['roles' => 'fa-key']),
				'slug'           => 'menu',
				'request'        => $request,
				'model'          => $this->model,
				'editable'       => true,
				'display_fields' => ['id','name','type','slug','ico','link','model','route','created_at','updated_at','deleted_at']
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
	public function create(Request $request, $id = null)
	{
		return $this->defaultCreate
		(
			[
				'id'             => $id,
				'request'        => $request,
				'model'          => $this->model,
				'disabled'       => ['created_at','updated_at'],
				'display_fields' => 
				[
					'id'         => 0,
					'parent_id'  => 6,
					'type'       => 6,
					'name'       => 6,
					'slug'       => 6,
					'color'      => 6,
					'ico'        => 6,
					'link'       => 6,
					'model'      => 6,
					'route'      => 6,
					'created_at' => 6,
					'updated_at' => 6,
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
				'display_fields' => ['id','name','slug','created_at','updated_at']
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
