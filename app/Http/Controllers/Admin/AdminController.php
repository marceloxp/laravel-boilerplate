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
use App\Models\User;
use App\Http\Utilities\Result;
use Stringy as S;

class AdminController extends Controller
{
	public $user_logged;
	private $schemas = [];
	public $caption = '';
	public $description = '';

    public function __construct()
	{
		$this->middleware
		(
			function($request, $next)
			{
				$user = Auth::user();
				$this->user_logged = $user;
				
				$menus      = $this->buildMenus();
				$route_name = Route::currentRouteName();
				$roles      = $this->getPagePermission($route_name, $menus);
				$user->authorizeRoles($roles);
				$this->user = $user;

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

	public function getPerPage($p_request)
	{
        $result = intval($p_request->query('perpage', 15));
		$result = min($result, 50);
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

		$table_name = $p_model::getTableName();

		$table = $p_model::select('');

		$query_fields = [];
		foreach ($display_fields as $field_name)
		{
			if (array_key_exists($field_name, $fields_schema))
			{
				if ($fields_schema[$field_name]['type'] !== 'appends')
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
				$where = ($search_value) ? ['%' . str_replace(' ', '%', $search_value) . '%'] : [];

				$relation = $fields_schema[$search_field];
				if ($relation['has_relation'])
				{
					$relation = $relation['relation'];
					$table->where(sprintf('%s.name', $relation['ref_table']), 'like', $where);
				}
				else
				{
					$table->where(sprintf('%s.%s', $table_name, $search_field), 'like', $where);
				}
			}

			if ($range_field)
			{
				$date_ini = $search_range[0];
				$date_end = Carbon::createFromFormat('Y-m-d H:i:s', $search_range[1] . '00:00:00')->addDay()->format('Y-m-d');
				$table->whereBetween(sprintf('%s.%s', $table_name, $range_field), [$date_ini, $date_end]);
			}
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
			$table->orderBy('id', 'DESC');
		}

		if (!empty($pivot_scope))
		{
			$scope_name  = $pivot_scope['name'];
			$scope_param = $pivot_scope['param'];
			$table->{$scope_name}($scope_param);
		}

		if (!empty($p_where))
		{
			$table->where($p_where);
		}

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

	public function defaultIndex($p_args)
	{
		$default_params = 
		[
			'pívot'        => [],
			'pivot_scope'  => [],
			'where'        => [],
			'appends'      => [],
			'exportable'   => false
		];

		$params = array_merge($default_params, $p_args);

		if (!empty($params['pivot_scope']))
		{
			$params['pivot_scope']['model'] = (string)S::create($params['pivot_scope']['name'])->underscored();
		}

		extract($params, EXTR_OVERWRITE);

		$is_pivot          = (!empty($pivot_scope));
		$class_pivot       = ($is_pivot) ? 'pivot' : '';
		$panel_title       = $this->caption;
		$panel_description = $this->description;
		$fields_schema     = $model::getFieldsMetaData($appends);
		$perpage           = $this->getPerPage($request);
		$table_name        = $model::getTableName();
		$model_name        = $model::getModelName();
		$table             = $this->getTableSearch($model, $perpage, $request, $display_fields, $fields_schema, $params);
		$paginate          = $this->ajustPaginate($request, $table);
		$ids               = $table->pluck('id')->toJson();
		$has_table         = (!empty($table));
		$search_dates      = ['created_at'];

		$share_params = compact('panel_title','panel_description','fields_schema','table_name','model_name','display_fields','table','ids','paginate','has_table','search_dates','pivot','pivot_scope','is_pivot','class_pivot','exportable');
		
		View::share($share_params);

		if (method_exists($this, 'hooks_index'))
		{
			$this->hooks_index($table_name);
		}

		$excepts = ['fields_schema','search_dates','table_name','exportable'];
		$jsvars = collect($share_params)->except($excepts)->toArray();
		
		datasite_add(['params' => $jsvars]);

		return view('Admin.generic');
	}

	public function defaultShow($p_args)
	{
		$default_params = [];
		$params = array_merge($default_params, $p_args);
		extract($params, EXTR_OVERWRITE);

		$table_name     = $model::getTableName();
		$register       = ($id) ? $model::find($id) : new $model;
		$panel_title    = [$this->caption, 'Visualizar', 'fa-fw fa-eye'];
		$fields_schema  = $model::getFieldsMetaData();

		View::share(compact('register','panel_title','display_fields','fields_schema','table_name'));

		if (method_exists($this, 'hooks_show'))
		{
			$this->hooks_show($table_name);
		}

		return view('Admin.generic_show');
	}

	public function defaultCreate($p_args)
	{
		$default_params = 
		[
			'image_fields' => []
		];
		$params = array_merge($default_params, $p_args);
		extract($params, EXTR_OVERWRITE);

		$table_name     = $model::getTableName();
		$register       = ($id) ? $model::find($id) : new $model;
		$is_creating    = (empty($id));
		$panel_title    = [$this->caption, ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
		$fields_schema  = $model::getFieldsMetaData();

		if (method_exists($this, 'hooks_edit'))
		{
			$this->hooks_edit($table_name);
		}

		View::share(compact('register','is_creating','panel_title','display_fields','fields_schema','image_fields','table_name'));

		return view('Admin.generic_add');
	}

	public function defaultStore($request, $model)
	{
		$id = $request->get('id');

		$valid = $model::validate($request, $id);
		if (!$valid['success'])
		{
			return back()
				->withErrors($valid['all'])
				->withInput()
			;
		}

		$form = $request->all();

		$form = $this->processUploads($request, $form);

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
			$register = $model::create($form);
		}

		$saved = (empty($id)) ? ($register->save()) : ($register->update()) ;

		if ($saved)
		{
			$table_name = $model::getTableName();
			$message = ($id) ? 'Registro atualizado com sucesso.' : 'Registro criado com sucesso.';
			$request->session()->flash('messages', [$message]);
			return redirect(Route('admin_' . $table_name));
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

	private function buildMenus()
	{
		return config('admin.menu');
	}
}