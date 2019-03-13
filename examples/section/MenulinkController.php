<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Hook;

class MenulinkController extends AdminController
{
	public function __construct()
	{
		$this->setCaption('Links');
		$this->setModel(\App\Models\Menulink::class);
		$this->setParent(\App\Models\Menusection::class);
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $parent_id)
	{
		$this->defineCaption($this->parent['model'], $parent_id);
		return $this->defaultIndex
		(
			[
				'where'          => [$this->parent['field'] => $parent_id],
				'request'        => $request,
				'model'          => $this->model,
				'editable'       => true,
				'display_fields' => ['id','menusection_id','name','icon','group','route','status','created_at']
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
	public function create(Request $request, $parent_id = null, $id = null)
	{
		$this->defineCaption($this->parent['model'], $parent_id);
		View::share([$this->parent['field'] => $parent_id]);
		return $this->defaultCreate
		(
			[
				'id'             => $id,
				'request'        => $request,
				'model'          => $this->model,
				'disabled'       => ['created_at'],
				'display_fields' => 
				[
					'id'             => 0,
					'menusection_id' => 0,
					'name'           => 6,
					'icon'           => 6,
					'group'          => 6,
					'route'          => 6,
					'status'         => 6,
					'created_at'     => 6,
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
				'display_fields' => ['id','menusection_id','name','icon','group','route','order','status','created_at','updated_at','deleted_at']
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
