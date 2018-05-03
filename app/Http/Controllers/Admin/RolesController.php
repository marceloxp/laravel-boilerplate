<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Exports\RolesExport;
use Hook;

class RolesController extends AdminController
{
    public function __construct()
	{
		$this->caption = 'Permissões';
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
				'model'          => Role::class,
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
		Hook::listen
		(
			sprintf('admin_index_%s_color', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return $this->formatRoleColor($display_value);
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
		$table_name     = (new Role())->getTable();
		$register       = ($id) ? Role::find($id) : new Role;
		$is_creating    = (empty($id));
		$panel_title    = [$this->caption, ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
		$table_name     = (new Role())->getTable();
		$display_fields = ['id', 'name', 'description','color'];
		$fields_schema  = Role::getFieldsMetaData();

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
		return $this->defaultStore($request, Role::class);
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
				'model'          => Role::class,
				'display_fields' => ['id','name','description','color','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::listen
		(
			sprintf('admin_show_%s_color', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return $this->formatRoleColor($display_value);
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
		return $this->destroy_register(Role::class, $request);
	}
}