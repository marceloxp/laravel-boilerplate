<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoriesController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Categorias';
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
				'model'          => Category::class,
				'display_fields' => ['id','name','description','created_at']
			]
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $id = null)
	{
		$table_name     = (new Category())->getTable();
		$register       = ($id) ? Category::find($id) : new Category;
		$is_creating    = (empty($id));
		$panel_title    = [$this->caption, ($is_creating ? 'Adicionar' : 'Editar'), 'fa-fw fa-plus'];
		$table_name     = (new Category())->getTable();
		$display_fields = ['id','name','description'];
		$fields_schema  = Category::getFieldsMetaData();

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
		return $this->defaultStore($request, Category::class);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$table_name     = (new Category())->getTable();
		$register       = ($id) ? Category::find($id) : new Category;
		$panel_title    = [$this->caption, 'Visualizar', 'fa-fw fa-eye'];
		$display_fields = ['id','name','description','created_at','updated_at','deleted_at'];
		$fields_schema  = Category::getFieldsMetaData();

		View::share(compact('register','panel_title','display_fields','fields_schema','table_name'));

		return view('Admin.generic_show');
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
		return $this->destroy_register(Category::class, $request);
	}
}