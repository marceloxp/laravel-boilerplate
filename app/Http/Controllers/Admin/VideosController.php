<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use App\Http\Umstudio\Youtube;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use Hook;

class VideosController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Vídeos';
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$panel_title    = $this->caption;
		$fields_schema  = Video::getFieldsMetaData();
		$perpage        = $this->getPerPage($request);
		$table_name     = (new Video())->getTable();
		$display_fields = ['id','name','youtube','created_at'];
		$table          = $this->getTableSearch(Video::class, $perpage, $request, $display_fields, $fields_schema);
		$paginate       = $this->ajustPaginate($request, $table);
		$has_table      = (!empty($table));
		$search_dates   = ['created_at'];

		View::share(compact('panel_title','fields_schema','table_name','display_fields','table','paginate','has_table','search_dates'));

		$this->hooks_index($table_name);

		return view('Admin.generic');
	}

	public function hooks_index($table_name)
	{
		Hook::listen
		(
			sprintf('admin_index_%s_youtube', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return Youtube::getImageUrlLink($display_value);
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
		$table_name     = (new Video())->getTable();
		$register       = ($id) ? Video::find($id) : new Video;
		$is_creating    = (empty($id));
		$panel_title    = [$this->caption, ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
		$table_name     = (new Video())->getTable();
		$display_fields = ['id', 'name', 'youtube'];
		$fields_schema  = Video::getFieldsMetaData();

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

		$valid = Video::validate($request, $id);
		if (!$valid['success'])
		{
			return back()
				->withErrors($valid['all'])
				->withInput()
			;
		}

		if (!empty($id))
		{
			$register = Video::firstOrNew(['id' => $id]);
			$register->fill($request->all());
		}
		else
		{
			$register = Video::create($request->all());
		}

		if ($register->save())
		{
			$table_name = (new Video())->getTable();
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
		$table_name     = (new Video())->getTable();
		$register       = ($id) ? Video::find($id) : new Video;
		$panel_title    = [$this->caption, 'Visualizar', 'fa-fw fa-eye'];
		$display_fields = ['id','name','youtube','created_at','updated_at','deleted_at'];
		$fields_schema  = Video::getFieldsMetaData();

		View::share(compact('register','panel_title','display_fields','fields_schema','table_name'));

		$this->hooks_show($table_name);

		return view('Admin.generic_show');
	}

	public function hooks_show($table_name)
	{
		Hook::listen
		(
			sprintf('admin_show_%s_youtube', $table_name),
			function($callback, $output, $display_value, $register)
			{
				return Youtube::getImageUrlLink($display_value);
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
		return $this->destroy_register(Video::class, $request);
	}
}