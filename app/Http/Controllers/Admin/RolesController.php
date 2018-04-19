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
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$panel_title    = 'Permissões';
		$fields_schema  = Role::getFieldsMetaData();
		$perpage        = $this->getPerPage($request);
		$table_name     = (new Role())->getTable();
		$display_fields = ['id','name','description','color','created_at'];
		$table          = $this->getTableSearch(Role::class, $perpage, $request, $display_fields, $fields_schema);
		$paginate       = $this->ajustPaginate($request, $table);
		$has_table      = (!empty($table));
		$search_dates   = ['created_at'];

		View::share(compact('panel_title','fields_schema','table_name','display_fields','table','paginate','has_table','search_dates'));

		$this->hooks_index($table_name);

		return view('Admin.generic');
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
		$panel_title    = ['Permissões', ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
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
		$id = $request->get('id');

		$valid = Role::validate($request, $id);
		if (!$valid['success'])
		{
			return back()
				->withErrors($valid['all'])
				->withInput()
			;
		}

		if (!empty($id))
		{
			$register = Role::firstOrNew(['id' => $id]);
			$register->fill($request->all());
		}
		else
		{
			$register = Role::create($request->all());
		}

		if ($register->save())
		{
			$table_name = (new Role())->getTable();
			$message = ($id) ? 'Registro atualizado com sucesso.' : 'Registro criado com sucesso.';
			return redirect(Route($table_name))->with('messages', [$message]);
		}
		else
		{
			return back()
				->withErrors('Ocorreu um erro na gravação do registro.')
				->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$table_name     = (new Role())->getTable();
		$register       = ($id) ? Role::find($id) : new Role;
		$panel_title    = ['Permissões', 'Visualizar', 'fa-fw fa-eye'];
		$display_fields = ['id','name','description','color','created_at','updated_at','deleted_at'];
		$fields_schema  = Role::getFieldsMetaData();

		View::share(compact('register','panel_title','display_fields','fields_schema','table_name'));

		$this->hooks_show($table_name);

		return view('Admin.generic_show');
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