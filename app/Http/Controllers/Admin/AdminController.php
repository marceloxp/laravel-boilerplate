<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\User;

class AdminController extends Controller
{
	public $user_logged;
	private $schemas = [];

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

				$route_array = explode('_', Route::getCurrentRoute()->getName());
				$route_section = 
				[
					'name'   => $route_array[0],
					'action' => $route_array[1] ?? ''
				];
				
				View::share(compact('user','menus','route_name','route_section'));

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

	public function getTableSearch($p_model, $p_perpage, $p_request, $display_fields, $fields_schema)
	{
		$search_params = $this->getSearchAndOrders($p_request, $fields_schema);
		extract($search_params, EXTR_OVERWRITE);

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

			if ($search_order)
			{
				foreach ($search_order as $order)
				{
					$table->orderBy($order['field_name'], $order['direction']);
				}
			}

			if ($range_field)
			{
				$date_ini = $search_range[0];
				$date_end = Carbon::createFromFormat('Y-m-d H:i:s', $search_range[1] . '00:00:00')->addDay()->format('Y-m-d');
				$table->whereBetween(sprintf('%s.%s', $table_name, $range_field), [$date_ini, $date_end]);
			}
		}

		$table = $table->paginate($p_perpage);

		return $table;
	}

	public function processUploadImages($request, $form)
	{
		try
		{
			foreach ($request->files as $field_name => $file)
			{
				$file = $form[$field_name];

				if (!$file->isValid())
				{
					return back()
						->withErrors('Ocorreu um erro no envio da imagem.')
						->withInput()
					;
				}

				$original_name = $file->getClientOriginalName();
				$original_ext  = $file->getClientOriginalExtension();
				$content_name  = str_replace('.' . $original_ext, '', $original_name);

				$move_file = 
				[
					'name'      => str_slug($content_name),
					'extension' => $original_ext,
					'full_name' => sprintf('%s.%s', str_slug($content_name), $original_ext),
				];

				$form[$field_name] = $move_file['full_name'];
				$upload = $file->storeAs('images', $move_file['full_name'], 'uploads');

				if (!$upload)
				{
					return back()
						->withErrors('Ocorreu um erro na gravação da imagem.')
						->withInput()
					;
				}
			}

			return $form;
		}
		catch (\Exception $e)
		{
			return back()
				->withErrors('Ocorreu um erro não esperado no processamento da imagem.')
				->withInput()
			;
		}
	}

	public function destroy_register($p_model, $p_request)
	{
		try
		{
            $result =
			[
                'result'  => true,
                'success' => false,
                'tag'     => 0,
                'message' => '',
                'error'   => ''
            ];

			$ids = $p_request->get('ids');
			$ids = explode(',', $ids);
			if (empty($ids))
			{
				$result['success'] = false;
				$result['message'] = 'Entrada de dados inválida.';
				return $result;
			}

			if ($p_model::destroy($ids))
			{
				$message = (count($ids) > 1) ? 'Registros removidos com sucesso.' : 'Registro removido com sucesso.';
				$result['success'] = true;
				$result['message'] = $message;
			}
			else
			{
				$result['message'] = 'Ocorreu um erro na remoção dos dados.';
			}
		}
		catch (\Exception $e)
		{
            $result['success'] = false;
            $result['message'] = 'Ocorreu um erro na remoção dos dados.';
            $result['error'] = $e->getMessage();
		}

		return $result;
	}

	private function buildMenus()
	{
		return config('admin.menu');
	}
}