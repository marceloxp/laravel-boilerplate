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
		$this->model   = Customer::class;
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
				'model'          => $this->model,
				'editable'       => true,
				'display_fields' => ['id','name','address_type_id','email','state','status']
			]
		);
	}

	public function hooks_index($table_name)
	{
		Hook::add_filter(sprintf('admin_index_field_align_%s_%s', $table_name, 'status'), function($display_value, $register) { return 'center'; }, 10, 2 );
		Hook::add_filter(sprintf('admin_index_title_align_%s_%s', $table_name, 'status'), function($display_value           ) { return 'center'; }, 10, 2 );
		Hook::add_filter(sprintf('admin_index_field_align_%s_%s', $table_name, 'state' ), function($display_value, $register) { return 'center'; }, 10, 2 );
		Hook::add_filter(sprintf('admin_index_title_align_%s_%s', $table_name, 'state' ), function($display_value           ) { return 'center'; }, 10, 2 );
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
				'model'          => $this->model,
				'disabled'       => ['created_at'],
				'display_fields' => 
				[
					'id'              => 0,
					
					'name'            => 6,
					'username'        => 6,
					
					'born'            => 4,
					'cpf'             => 4,
					'email'           => 4,
					
					'phone_prefix'    => 4,
					'phone'           => 4,
					'address_type_id' => 4,
					
					'address'         => 8,
					'address_number'  => 2,
					'complement'      => 2,
					
					'neighborhood'    => 3,
					'cep'             => 3,
					'state'           => 3,
					'city'            => 3,
					
					'password'        => 3,
					'newsletter'      => 3,
					'rules'           => 3,
					'status'          => 3,

					'ip'              => 3,
					'created_at'      => 3,
				]
			]
		);
	}

	public function hooks_edit($table_name)
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		return $this->defaultStore($request, $this->model);
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
				'model'          => $this->model,
				'display_fields' => ['id','name','address_type_id','username','born','cpf','email','phone_prefix','phone','cep','state','city','address','address_number','complement','neighborhood','newsletter','rules','status','ip','created_at','updated_at']
			]
		);
	}

	public function hooks_show($table_name)
	{

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
