<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Common\Role;
use App\Exports\RolesExport;
use Hook;

class RoleController extends AdminController
{
    public function __construct()
	{
		$this->caption = 'Permissões';
		$this->model   = Role::class;
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
				'display_fields' => ['id','name','description','color','created_at']
			]
		);
	}

	private function formatRoleColor($p_display_value)
	{
		$color = Role::getColorBg($p_display_value);
		return sprintf('<small class="label pull-center %s">%s</small>', $color, $p_display_value);
	}

	public function hooks_index($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_index_%s_color', $table_name),
			function($display_value, $register)
			{
				return $this->formatRoleColor($display_value);
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
				'display_fields' => ['id', 'name', 'description','color']
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
				'display_fields' => ['id','name','description','color','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_show_%s_color', $table_name),
			function($display_value, $register)
			{
				return $this->formatRoleColor($display_value);
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