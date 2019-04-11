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
		$this->appends = ['roles' => 'Permissões'];
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
				'appends'        => $this->appends,
				'slug'           => 'menu',
				'request'        => $request,
				'model'          => $this->model,
				'editable'       => true,
				'display_fields' => ['id','order','name','ico','roles','link','created_at']
			]
		);
	}

	public function hooks_index($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_index_%s_%s', $table_name, 'name'),
			function($display_value, $register)
			{
				return fa_ico($register['ico'], $display_value);
			},
			10, 2
		);

		Hook::add_filter
		(
			sprintf('admin_index_%s_%s', $table_name, 'roles'),
			function($display_value, $register)
			{
				$roles = $this->model::getRoles($register['id']);
				if ($roles->isEmpty()) { return bs_label(0, 'Public'); }
				\App\Models\Role::ajustCollectionRolesColor($roles);
				$display_value = $roles->toBootstrapLabel()->toText('&nbsp;');
				return $display_value;
			},
			10, 2
		);

		Hook::add_filter
		(
			sprintf('admin_index_display_fields_%s', $table_name),
			function($display_value)
			{
				$hide_fields = ['ico','link'];
				return collect($display_value)->reject(function ($value, $key) use ($hide_fields) { return (in_array($value, $hide_fields)); })->toArray();
			},
			10, 1
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
				'disabled'       => ['created_at','updated_at'],
				'display_fields' => 
				[
					'id'         => 0,
					'name'       => 3,
					'slug'       => 3,
					'parent_id'  => 3,
					'type'       => 3,
					'order'      => 3,
					'color'      => 3,
					'ico'        => 3,
					'link'       => 3,
					'model'      => 3,
					'route'      => 3,
					'created_at' => 3,
					'updated_at' => 3,
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
				'appends'        => $this->appends,
				'model'          => $this->model,
				'display_fields' => ['id','parent_id','order','type','name','roles','slug','color','ico','link','target','model','route','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_show_%s_%s', $table_name, 'roles'),
			function($display_value, $register)
			{
				$display_value = collect($display_value->toArray());
				if ($display_value->isEmpty()) { return bs_label(0, 'Public'); }
				\App\Models\Role::ajustCollectionRolesColor($display_value);
				return $display_value->toBootstrapLabel()->toText('&nbsp;');
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
