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

class UserController extends AdminController
{
    public function __construct()
	{
		$this->caption = 'Usuários';
		$this->model   = User::class;
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
				'model'          => $this->model,
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
		Hook::add_filter
		(
			sprintf('admin_index_%s_roles', $table_name),
			function($display_value, $register)
			{
				return $this->getUsersRolesLabel($display_value);
			},
			10, 2
		);

		Hook::add_filter
		(
			sprintf('admin_index_search_fields_%s', $table_name),
			function($search_fields)
			{
				return collect($search_fields)->reject(function($value, $key) { return $value == 'roles'; })->all();
			},
			10, 1
		);

		Hook::add_filter
		(
			sprintf('admin_index_sort_fields_%s', $table_name),
			function($sort_fields)
			{
				return collect($sort_fields)->reject(function($value, $key) { return $value == 'roles'; })->all();
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
				'appends'        => $this->appends,
				'model'          => $this->model,
				'display_fields' => ['id','name','email','password','roles']
			]
		);
	}

	public function hooks_edit($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_edit_%s_roles', $table_name),
			function($input, $field_value, $register, $field_schema)
			{
				$field_name = $field_schema['name'];
				$required   = (!$field_schema['nullable']) ? 'required' : '';
				$options    = \App\Models\Role::select('id', 'name')->get()->pluck('name', 'id')->all();
				$register   = (!is_array($register)) ? $register->roles->pluck('id') : collect($register);

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
			return redirect(Route('admin_user'))->with('messages', [$message]);
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
		return $this->defaultShow
		(
			[
				'id'             => $id,
				'appends'        => $this->appends,
				'model'          => $this->model,
				'display_fields' => ['id','name','email','roles','created_at','updated_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_show_%s_roles', $table_name),
			function($display_value, $register)
			{
				return $this->getUsersRolesLabel($display_value);
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