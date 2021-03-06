<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Common\User;
use App\Http\Utilities\Result;
use Illuminate\Support\Str;

class AdminController extends Controller
{
	public  $user_logged;
	private $schemas     = [];
	public  $caption     = '';
	public  $description = '';
	public  $parent      = [];

    public function __construct()
	{
		$this->middleware
		(
			function($request, $next)
			{
				$user = Auth::user();
				$this->user_logged = $user;
				
				$is_ajax = $request->ajax();
				if ($is_ajax)
				{
					return $next($request);
				}

				$menus      = $this->buildMenus();
				$route_name = Route::currentRouteName();
				$roles      = $this->getPagePermission($route_name, $menus);
				$user->authorizeRoles($roles);
				$this->user = $user;
				$this->route_name = $route_name;

				$route_parts = 
				[
					'name'  => Route::getCurrentRoute()->getName(),
					'menu'  => Route::getCurrentRoute()->getMenu(),
					'group' => Route::getCurrentRoute()->getGroup(),
				];
				$route_parts['verify'] = $route_parts['group'] ?? $route_parts['menu'] ?? $route_parts['name'];
				$verify = $route_parts['verify'];

				View::share(compact('user','menus','route_name','route_parts','verify'));

				return $next($request);
			}
		);
	}

	public function getUploadedFile($p_file_name, $p_height = 100, $add_link = true)
	{
		if (empty($p_file_name)) { return $p_file_name; }
		if ($add_link)
		{
			return sprintf('%s<br/>%s', link_uploaded_file($p_file_name, sprintf('class="uploaded-file" height="%s"', $p_height)), $p_file_name);
		}
		return sprintf('%s<br/>%s', img_uploaded_file($p_file_name, sprintf('class="uploaded-file" height="%s"', $p_height)), $p_file_name);
	}

	private function getPagePermission($route_name, $menus)
	{
		foreach ($menus as $key => $groups)
		{
			$node = collect($groups['items'] ?? [])->where('route', $route_name);
			if ($node->count() > 0)
			{
				$result = $node->first();
				$result = collect($result)->get('roles') ?? [];
				return $result;
			}
		}
		return [];
	}

	public function setCaption($p_caption, $p_description = '')
	{
		$this->caption     = $p_caption;
		$this->description = $p_description;
	}

	public function setCaptionByModel($p_model_1, $p_model_2 = null)
	{
		$this->caption     = $p_model_1::getTableCaption();
		$this->description = (!empty($p_model_2)) ? $p_model_2::getTableCaption() : '';
	}

	public function setPivotCaption($p_master_id)
	{
		$this->setCaption($this->master::getTableCaption() . ' - ' . db_get_name($this->master::getSchemaName(), $this->master::getTableName(), $p_master_id), $this->model::getTableCaption());
	}

	public function setModel($p_model)
	{
		$this->model = $p_model;
	}

	public function setMasterModel($p_master)
	{
		$this->master = $p_master;
	}

	public function defineCaption($p_model, $p_id)
	{
		$parent = $p_model::select('id','name')->where('id',$p_id)->first();
		$this->setCaption(sprintf('%s - %s', $parent->name, $this->caption));
	}

	public function getPivotScopeConfig($p_master_id)
	{
		return
		[
			'name'  => db_get_pivot_scope_name([$this->model, $this->master]),
			'param' => $p_master_id
		];
	}

	public function getCaption()
	{
		$result = $this->caption;
		@$description = $this->description;
		if (!empty($this->description))
		{
			$result .= ' - ' . $this->description;
		}

		return $result;
	}

	public function setParent($p_model)
	{
		$field_name = sprintf('%s_id', str_to_singular($p_model::getTableName()));
		$this->parent['model'] = $p_model;
		$this->parent['field'] = $field_name;
	}

	public function getPerPage($p_request)
	{
		$max_results = collect(config('admin.index.pagination.perpages'))->max();
        $result = intval($p_request->query('perpage', 50));
		$result = min($result, $max_results);
		return $result;
	}

	public function phpinfo()
	{
		return view('Admin.phpinfo');
	}

	public function logSqls()
	{
		\DB::listen
		(
			function($sql)
			{
				if (gettype($sql) == 'object')
				{
					if (property_exists($sql, 'sql'))
					{
						echo '<pre>';
						print_r($sql->sql);
						echo PHP_EOL;
						print_r($sql->bindings);
						echo '</pre>';
					}
				}
			}
		);
	}

	public function ajustPaginate($p_request, $p_table)
	{
    $result = $p_table->appends($p_request->query())->links()->toHtml();
		$result = str_replace('<ul class="pagination">', '<ul class="pagination pagination-sm no-margin pull-right">', $result);
		return $result;
	}

	public function getSearchAndOrders($p_request, $fields_schema)
	{
		$search_field = $p_request->query('field');
		$search_value = $p_request->query('value');
		$search_dtini = $p_request->query('range_ini');
		$search_dtend = $p_request->query('range_end');
		$search_order = [];

		$fields = $p_request->get('fields');
		$orders = $p_request->get('orders');
		if ($fields && $orders)
		{
			$fields = explode(',', $p_request->get('fields'));
			$orders = explode(',', $p_request->get('orders'));
		}

		if ( (!empty($orders)) && (!empty($fields)) )
		{
			if (count($orders) == count($fields))
			{
				foreach ($fields as $key => $field_name)
				{
					if ($fields_schema[$field_name]['has_relation'])
					{
						$relation = $fields_schema[$field_name]['relation'];
						$search_order[] = ['field_name' => $relation['custom_field'], 'direction' => $orders[$key]];
					}
					else
					{
						$search_order[] = ['field_name' => $field_name, 'direction' => $orders[$key]];
					}
				}
			}
		}

		$search_range = null;
		$range_field = $p_request->get('range_field');
		if ($search_dtini && $search_dtend)
		{
			$search_range = [$search_dtini, $search_dtend];
		}

		return compact('search_field','search_value','search_order','search_range','range_field');
	}

	public function getTableSearch($p_model, $p_perpage, $p_request, $display_fields, $fields_schema, $p_params = [])
	{
		$search_params = $this->getSearchAndOrders($p_request, $fields_schema);
		extract($search_params, EXTR_OVERWRITE);
		extract($p_params     , EXTR_OVERWRITE);

		$table_name  = $p_model::getTableName();
		$schema_name = $p_model::getSchemaName();

		$table = $p_model::select('');

		$query_fields = [];
		foreach ($display_fields as $field_name)
		{
			if (array_key_exists($field_name, $fields_schema))
			{
				if ( ($fields_schema[$field_name]['is_appends'] == false) && ($fields_schema[$field_name]['has_pivot'] == false) )
				{
					$query_fields[] = sprintf('%s.%s', $table_name, $field_name);

					$relation = $fields_schema[$field_name];
					if ($relation['has_relation'])
					{
						$relation = $relation['relation'];
						$query_fields[] = sprintf('%s.%s AS %s', $relation['ref_table'], 'name', $relation['custom_field']);

						$table->join
						(
							$relation['ref_table'],
							sprintf('%s.%s', $relation['table_name'], $relation['field_name']),
							'=',
							sprintf('%s.%s', $relation['ref_table'], $relation['field_index'])
						);
					}
				}
			}
		}
		$table->select($query_fields);

		if ( ($search_field && $search_value) || ($search_order) || ($range_field) )
		{
			if ($search_field && $search_value)
			{
				$search_where = ($search_value) ? ['%' . str_replace(' ', '%', $search_value) . '%'] : [];

				$relation = $fields_schema[$search_field];
				if ($relation['has_relation'])
				{
					$relation = $relation['relation'];
					$table->where(sprintf('%s.name', $relation['ref_table']), 'like', $search_where);
				}
				else
				{
					$table->where(sprintf('%s.%s', $table_name, $search_field), 'like', $search_where);
				}
			}

			if ($range_field)
			{
				$date_ini = $search_range[0];
				$date_end = Carbon::createFromFormat('Y-m-d H:i:s', $search_range[1] . '00:00:00')->addDay()->format('Y-m-d');
				$table->whereBetween(sprintf('%s.%s', $table_name, $range_field), [$date_ini, $date_end]);
			}
		}

		if ($one_table->has)
		{
			$table->where(sprintf('%s.%s', $table_name, $one_table->field), $one_table->id);
		}

		if (!empty($where))
		{
			$table->where($where);
		}

		if ($search_order)
		{
			foreach ($search_order as $order)
			{
				$table->orderBy($order['field_name'], $order['direction']);
			}
		}
		else
		{
			$table->orderBy(sprintf('%s.id', $table_name), 'DESC');
		}

		if (!empty($pivot_scope))
		{
			$scope_name  = $pivot_scope['name'];
			$scope_param = $pivot_scope['param'];
			$table->{$scope_name}($scope_param);
		}

		$hook_name = hook_name(sprintf('admin_index_table_before_paginate_%s', $table_name));
		$table     = \Hook::apply_filters($hook_name, $table);

		$hook_name = hook_name(sprintf('admin_index_table_before_paginate_%s_%s', $table_name, $this->route_name));
		$table     = \Hook::apply_filters($hook_name, $table);

		$table = $table->paginate($p_perpage);

		return $table;
	}

	public function processUploads($request, $form)
	{
		try
		{
			foreach ($request->files as $field_name => $file)
			{
				$file = $form[$field_name];

				if (!$file->isValid())
				{
					$messages = ['Ocorreu um erro no envio da imagem.'];

					switch ($file->getError())
					{
						case UPLOAD_ERR_INI_SIZE:
							$messages[] = sprintf('O tamanho do arquivo excede o limite permitido pelo servidor (%s).', ini_get('upload_max_filesize'));
						break;
						case UPLOAD_ERR_FORM_SIZE:
							$messages[] = 'O tamanho do arquivo excede o limite permitido pelo formulário.';
						break;
						case UPLOAD_ERR_PARTIAL:
							$messages[] = 'O upload do arquivo foi feito parcialmente.';
						break;
						case UPLOAD_ERR_NO_FILE:
							$messages[] = 'Nenhum arquivo foi enviado.';
						break;
						case UPLOAD_ERR_NO_TMP_DIR:
							$messages[] = 'Não há pasta temporária definida.';
						break;
						case UPLOAD_ERR_CANT_WRITE:
							$messages[] = 'Falha em escrever o arquivo em disco.';
						break;
						case UPLOAD_ERR_EXTENSION:
							$messages[] = 'Uma extensão do PHP interrompeu o upload do arquivo.';
						break;
					}

					return back()
						->withErrors($messages)
						->withInput()
					;
				}

				$extension = $file->getClientOriginalExtension();

				switch ($extension)
				{
					case 'pdf':
						$disk_name  = 'upload_pdfs';
					break;
					default:
						$disk_name  = 'upload_images';
					break;
				}

				$file_name  = $file->getClientOriginalName();
				$check_file = disk_new_file_name($disk_name, $file->getClientOriginalName());
				$saved_file = $request->file($field_name)->storeAs('', $check_file, ['disk' => $disk_name]);

				if (!$saved_file)
				{
					return back()
						->withErrors('Ocorreu um erro na gravação do arquivo.')
						->withInput()
					;
				}

				$form[$field_name] = $saved_file;
			}

			return $form;
		}
		catch (\Exception $e)
		{
			report($e);
			return back()
				->withErrors('Ocorreu um erro não esperado no processamento do arquivo.')
				->withInput()
			;
		}
	}

	public function destroy_register($p_model, $p_request)
	{
		try
		{
			$ids = $p_request->get('ids');
			$ids = explode(',', $ids);
			if (empty($ids))
			{
				return Result::invalid();
			}

			if ($p_model::destroy($ids))
			{
				$message = (count($ids) > 1) ? 'Registros removidos com sucesso.' : 'Registro removido com sucesso.';
				return Result::success($message);
			}
			else
			{
				return Result::error('Ocorreu um erro na remoção dos dados.');
			}
		}
		catch (\Exception $e)
		{
			return Result::exception($e);
		}

		return Result::undefined();
	}

	public function getOneTable()
	{
		$table_id = Route::getCurrentRoute()->parameter('id');
		$result = (object)
		[
			'has'      => false,
			'table_id' => $table_id,
			'id'       => null,
			'name'     => null,
			'field'    => null,
		];

		$one_table_id = Route::getCurrentRoute()->parameter('one_table_id');
		if (!empty($one_table_id))
		{
			$result = (object)
			[
				'has'      => true,
				'table_id' => $table_id,
				'id'       => $one_table_id,
				'schema'   => request()->segment(2),
				'name'     => request()->segment(3),
				'field'    => db_table_name_to_field_id(request()->segment(3)),
			];
		}

		return $result;
	}

	public function defaultIndex($p_args)
	{
		$default_params = 
		[
			'pivot'        => [],
			'pivot_scope'  => [],
			'table_many'   => [],
			'where'        => [],
			'appends'      => [],
			'render'       => 'Admin.generic',
			'editable'     => true,
			'sortable'     => false,
			'exportable'   => false
		];

		$params = array_merge($default_params, $p_args);

		$one_table = $this->getOneTable();
		if (!$one_table->has)
		{
			$id            = $one_table->table_id;
			$array_caption = ['Home', $this->caption];
		}
		else
		{
			$pre_caption   = db_get_name($one_table->schema, $one_table->name, $one_table->id);
			$array_caption = ['Home', $pre_caption, $this->caption];
		}
		$params['one_table'] = $one_table;

		if (!empty($params['pivot_scope']))
		{
			$params['pivot_scope']['model'] = Str::snake($params['pivot_scope']['name']);
		}

		extract($params, EXTR_OVERWRITE);

		if ($sortable)
		{
			$sort_fields = $request->get('fields', '');
			if (!empty($sort_fields))
			{
				$fields = explode(',', $sort_fields);
				if (count($fields) > 1)
				{
					$sortable = false;
				}
				else
				{
					if (!in_array('position', $fields))
					{
						$sortable = false;
					}
				}
			}
		}

		$page              = $request->get('page', 1);
		$is_pivot          = (!empty($pivot_scope));
		$class_pivot       = ($is_pivot) ? 'pivot' : '';
		$panel_title       = admin_breadcrumb($array_caption, 'fas fa-folder');
		$panel_description = $this->description;
		$table_name        = $model::getTableName();
		$model_name        = $model::getModelName();

		if (method_exists($this, 'hooks_index'))
		{
			$this->hooks_index($table_name);
		}

		$table_schema  = $model::getSchemaName();
		$fields_schema = $model::getFieldsMetaData($appends);
		$field_names   = array_keys($fields_schema);
		$perpage       = $this->getPerPage($request);
		$table         = $this->getTableSearch($model, $perpage, $request, $display_fields, $fields_schema, $params);
		$paginate      = $this->ajustPaginate($request, $table);
		$hook_name     = hook_name(sprintf('admin_index_table_%s', $table_name));
		$table         = \Hook::apply_filters($hook_name, $table);
		$ids           = $table->pluck('id')->toJson();
		$has_table     = ($table->total() > 0);
		$search_dates  = ['created_at'];

		$share_params = compact('panel_title','panel_description','fields_schema','field_names','table_schema','table_name','model_name','display_fields','table','ids','paginate','page','has_table','search_dates','pivot','pivot_scope','is_pivot','class_pivot','exportable','editable','table_many','perpage','sortable');
		
		View::share($share_params);

		$request->session()->put('url_back', url()->current());

		$excepts = ['fields_schema','field_names','search_dates','exportable'];
		$jsvars = collect($share_params)->except($excepts)->toArray();
		
		datasite_add(['params' => $jsvars]);

		return view($render);
	}

	public function defaultTreeIndex($p_args)
	{
		$default_params = 
		[
			'pivot'       => [],
			'pivot_scope' => [],
			'table_many'  => [],
			'where'       => [],
			'appends'     => [],
			'editable'    => true,
			'sortable'    => false,
			'exportable'  => false
		];

		$params = array_merge($default_params, $p_args);

		if (!empty($params['pivot_scope']))
		{
			$params['pivot_scope']['model'] = (string)S::create($params['pivot_scope']['name'])->underscored();
		}

		extract($params, EXTR_OVERWRITE);

		$array_caption = ['Home', $this->caption];

		$sortable          = false;
		$is_pivot          = (!empty($pivot_scope));
		$class_pivot       = ($is_pivot) ? 'pivot' : '';
		$panel_title       = admin_breadcrumb($array_caption, 'fas fa-folder');
		$panel_description = $this->description;
		$table_name        = $model::getTableName();
		$model_name        = $model::getModelName();

		if (method_exists($this, 'hooks_index'))
		{
			$this->hooks_index($table_name);
		}

		$table_schema  = $model::getSchemaName();
		$fields_schema = $model::getFieldsMetaData($appends);
		$field_names   = array_keys($fields_schema);
		$table         = $model::getTreeAligned($display_fields, $fields_schema);
		$has_table     = ($table->count() > 0);

		$share_params = compact('model','panel_title','panel_description','fields_schema','field_names','table_name','table_schema','model_name','display_fields','table','has_table','pivot','pivot_scope','is_pivot','class_pivot','exportable','editable');
		
		View::share($share_params);

		$request->session()->put('url_back', url()->current());

		$excepts = ['fields_schema','field_names','search_dates','exportable'];
		$jsvars = collect($share_params)->except($excepts)->toArray();
		
		datasite_add(['params' => $jsvars]);

		return view('Admin.tree');
	}

	public function defaultShow($p_args)
	{
		$default_params =
		[
			'appends' => []
		];
		$params = array_merge($default_params, $p_args);
		extract($params, EXTR_OVERWRITE);

		$table_name = $model::getTableName();

		if (method_exists($this, 'hooks_show'))
		{
			$this->hooks_show($table_name);
		}

		$register      = ($id) ? $model::find($id) : new $model;
		$panel_title   = admin_breadcrumb([$this->caption, 'Visualizar'], 'fas fa-eye');
		$fields_schema = $model::getFieldsMetaData($appends);

		View::share(compact('register','panel_title','display_fields','fields_schema','table_name'));

		return view('Admin.generic_show');
	}

	public function defaultCreate($p_args)
	{
		$default_params = 
		[
			'appends'      => [],
			'image_fields' => [],
			'disabled'     => [],
		];
		$params = array_merge($default_params, $p_args);
		extract($params, EXTR_OVERWRITE);

		$one_table = $this->getOneTable();
		if (!$one_table->has)
		{
			$id = $one_table->table_id;
			$array_caption = ['Home', $this->caption];
			$is_creating = (empty($id));
		}
		else
		{
			$is_creating = (empty($id));
			$pre_caption = db_get_name($one_table->schema, $one_table->name, $one_table->id);
			$array_caption = ['Home', $pre_caption, $this->caption];
			$array_caption[] = ($is_creating) ? 'Adicionar' : 'Editar';
		}

		$table_schema  = $model::getSchemaName();
		$table_name    = $model::getTableName();
		$register      = ($id) ? $model::find($id) : new $model;
		$panel_title   = admin_breadcrumb($array_caption, 'fas fa-plus-square');
		$fields_schema = $model::getFieldsMetaData($appends);
		$field_names   = array_keys($fields_schema);

		if (method_exists($this, 'hooks_edit'))
		{
			$this->hooks_edit($table_name);
		}

		View::share(compact('request','model','register','is_creating','panel_title','display_fields','fields_schema','table_schema','field_names','image_fields','table_name','disabled','one_table'));

		return view('Admin.generic_add');
	}

	public function defaultStore($request, $model)
	{
		$id = $request->get('id');

		$model::ajustFormValues($request);

		$valid = $model::validate($request, $id);
		if (!$valid['success'])
		{
			return back()
				->withErrors($valid['all'])
				->withInput()
			;
		}

		$form = $request->all();

		$syncs = [];
		$pivot_fields = $model::getPivotFields();
		foreach ($pivot_fields as $pivot_table)
		{
			if (!empty($request->$pivot_table))
			{
				$syncs[$pivot_table] = $request->$pivot_table;
			}
			unset($form[$pivot_table]);
		}

		$form = $this->processUploads($request, $form);

		if (array_key_exists('password', $form))
		{
			if (empty($form['password']))
			{
				unset($form['password']);
			}
			else
			{
				$form['password'] = \Illuminate\Support\Facades\Hash::make($form['password']);
			}
		}

		if (isRedirect($form))
		{
			return $form;
		}

		if (!empty($id))
		{
			$register = $model::firstOrNew(['id' => $id]);
			$register->fill($form);
		}
		else
		{
			if (array_key_exists('id', $form))
			{
				unset($form['id']);
			}
			$register = $model::create($form);
		}

		$register->makeHidden($register->appends);
		$saved = (empty($id)) ? ($register->save()) : ($register->update()) ;

		if ($saved)
		{
			foreach ($syncs as $pivot_table => $pivot_values)
			{
				$register->$pivot_table()->sync($pivot_values);
			}
			$table_name = $model::getTableName();
			$message = ($id) ? 'Registro atualizado com sucesso.' : 'Registro criado com sucesso.';
			$request->session()->flash('messages', [$message]);

			$url_back = $request->session()->pull('url_back');
			if (!empty($url_back))
			{
				return redirect($url_back);
			}
			return redirect(request()->headers->get('referer'));
		}
		else
		{
			return back()
				->withErrors('Ocorreu um erro na gravação do registro.')
				->withInput();
		}
	}

	public function defaultDestroy($p_args)
	{
		$default_params = [];
		$params = array_merge($default_params, $p_args);
		extract($params, EXTR_OVERWRITE);

		return $this->destroy_register($model, $request);
	}

	public function reorder(Request $request, $table)
	{
		try
		{
			$model = db_table_name_to_model_path($table);
			return $model::reorder($request->pos_ini, $request->ids_ini, $request->ids_end, $request->order);
		}
		catch (Exception $e)
		{
			return Result::exception($e);
		}
	}

	private function buildMenus()
	{
		$result = \Cache::remember
		(
			'blp_admin_menu',
			now()->addSeconds(60),
			function()
			{
				$appends = ['roles' => 'Permissões'];
				$fields_schema = \App\Models\Common\Menu::getFieldsMetaData($appends);
				$table = \App\Models\Common\Menu::getTree(['id','type','order','name','ico','roles','link','target','route','created_at'], $fields_schema, $appends);
				return $table;
			}
		);
		return $result;
	}
}