<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Hook;

class UsersController extends AdminController
{
    public function __construct()
	{
		$this->caption = 'Usuários';
		$this->appends = ['roles' => 'Permissões'];
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
				'appends'        => $this->appends,
				'request'        => $request,
				'model'          => User::class,
				'display_fields' => ['id','name','email','roles','created_at']
			]
		);
	}

	private function getUsersRolesLabel($display_value)
	{
		$result = [];
		$display_value = $display_value->pluck('name', 'id');
		foreach ($display_value as $role_id => $role_name)
		{
			$bg_color = \App\Models\Role::getBgColorByRole($role_name);
			$result[] = sprintf('<small class="label pull-center %s">%s</small>', $bg_color, $role_name);
		}
		$result = implode('&nbsp;', $result);
		return $result;
	}

	public function hooks_index($table_name)
	{
		Hook::listen
		(
			sprintf('admin_index_%s_roles', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return $this->getUsersRolesLabel($display_value);
			}
		);

		Hook::listen
		(
			sprintf('admin_index_search_fields_%s', $table_name),
			function($callback, $output, $search_fields)
			{
				return collect($search_fields)->reject(function($value, $key) { return $value == 'roles'; })->all();
			}
		);

		Hook::listen
		(
			sprintf('admin_index_sort_fields_%s', $table_name),
			function($callback, $output, $sort_fields)
			{
				return collect($sort_fields)->reject(function($value, $key) { return $value == 'roles'; })->all();
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
		$table_name     = (new User())->getTable();
		$register       = ($id) ? User::find($id) : new User;
		$is_creating    = (empty($id));
		$panel_title    = [$this->caption, ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
		$table_name     = (new User())->getTable();
		$display_fields = ['id','name','email','password','roles'];
		$fields_schema  = User::getFieldsMetaData($this->appends);

		View::share(compact('register','is_creating','panel_title','display_fields','fields_schema','table_name'));

		$this->hooks_edit($table_name);

		return view('Admin.generic_add');
	}

	public function hooks_edit($table_name)
	{
		Hook::listen
		(
			sprintf('admin_edit_%s_roles', $table_name),
			function($callback, $output, $input, $field_schema, $register)
			{
				$field_name = $field_schema['name'];
				$required   = (!$field_schema['nullable']) ? 'required' : '';
				$options    = \App\Models\Role::select('id', 'name')->get()->pluck('name', 'id')->all();
				$register   = (!is_array($register)) ? $register->pluck('id') : collect($register);

				$input = '';
				foreach($options as $role_id => $role_name)
				{
					$node    = $register->search($role_id);
					$checked = ($node !== false) ? 'checked' : '';

					$input .= '<div class="checkbox"><label>';
					$input .= sprintf('<input type="checkbox" name="%s[]" value="%s" %s>%s', $field_name, $role_id, $checked, $role_name);
					$input .= '</label></div>';
				}

				return $input;
			}
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
		$id = $request->get('id');
		$roles = $request->get('roles') ?: [];

		if (empty($roles))
		{
			return back()
				->withErrors(['Por favor, selecione ao menos uma permissão.'])
				->withInput()
			;			
		}

		$valid = User::validate($request, $id);
		if (!$valid['success'])
		{
			return back()
				->withErrors($valid['all'])
				->withInput()
			;
		}

		$form = $request->all();
		unset($form['roles']);
		if (empty($form['password']))
		{
			unset($form['password']);
		}
		else
		{
			$form['password'] = Hash::make($form['password']);
		}

		$form = $this->processUploadImages($request, $form);

		if (!empty($id))
		{
			$register = User::firstOrNew(['id' => $id]);
			$register->fill($form);
		}
		else
		{
			$register = User::create($form);
		}

		if ($register->save())
		{
			$register->roles()->detach();

			foreach ($roles as $role_id)
			{
				$role = \App\Models\Role::where('id', $role_id)->first();
				$register->roles()->attach($role);
			}

			$table_name = (new User())->getTable();
			$message = ($id) ? 'Registro atualizado com sucesso.' : 'Registro criado com sucesso.';
			return redirect(Route('admin_' . $table_name))->with('messages', [$message]);
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
		$table_name     = (new User())->getTable();
		$register       = ($id) ? User::find($id) : new User;
		$panel_title    = [$this->caption, 'Visualizar', 'fa-fw fa-eye'];
		$display_fields = ['id','name','email','roles','created_at','updated_at','deleted_at'];
		$fields_schema  = User::getFieldsMetaData($this->appends);

		View::share(compact('register','panel_title','display_fields','fields_schema','table_name','image_fields'));

		$this->hooks_show($table_name);

		return view('Admin.generic_show');
	}

	public function hooks_show($table_name)
	{
		Hook::listen
		(
			sprintf('admin_show_%s_roles', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return $this->getUsersRolesLabel($display_value);
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
		return $this->destroy_register(User::class, $request);
	}
}