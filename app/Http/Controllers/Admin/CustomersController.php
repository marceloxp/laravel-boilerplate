<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Hook;

class CustomersController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Clientes';
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
				'model'          => Customer::class,
				'editable'       => false,
				'display_fields' => ['id','name','email','state','status']
			]
		);
	}

	// public function hooks_index($table_name)
	// {
	// 	Hook::add_filter
	// 	(
	// 		sprintf('admin_index_%s_name', $table_name),
	// 		function($display_value, $register)
	// 		{
	// 			return sprintf('<i>%s</i>', $display_value);
	// 		},
	// 		10, 2
	// 	);
	// }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		return $this->defaultStore($request, Customer::class);
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
				'model'          => Customer::class,
				'display_fields' => ['id','name','username','born','cpf','email','phone_prefix','phone','cep','state','city','address','address_number','complement','neighborhood','newsletter','rules','status','ip','flags','deleted_at','created_at','updated_at']
			]
		);
	}

	// public function hooks_show($table_name)
	// {
	// 	Hook::add_filter
	// 	(
	// 		sprintf('admin_show_%s_name', $table_name),
	// 		function($display_value, $register)
	// 		{
	// 			return sprintf('<i>%s</i>', $display_value);
	// 		},
	// 		10, 2
	// 	);
	// }

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
		return $this->defaultDestroy
		(
			[
				'request' => $request,
				'model'   => Customer::class
			]
		);
	}
}
