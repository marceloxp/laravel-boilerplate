<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Audit;
use Jenssegers\Agent\Agent;
use Hook;

class AuditController extends AdminController
{
	public function __construct()
	{
		$this->caption = 'Auditoria';
		$this->model   = Audit::class;
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
				'editable'       => false,
				'table_many'     => null,
				'display_fields' => ['id','user_id','table','username','name','url','ip','useragent','created_at','updated_at']
			]
		);
	}

	public function hooks_index($table_name)
	{

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
				'display_fields' => ['id','user_id','table','username','name','url','ip','useragent','oldvalue','newvalue','flags','created_at','updated_at','deleted_at']
			]
		);
	}

	public function hooks_show($table_name)
	{
		Hook::add_filter
		(
			sprintf('admin_show_%s_%s', $table_name, 'oldvalue'),
			function($display_value, $register)
			{
				$collection = collect(json_decode($display_value, true));
				return $collection->toHtmlTable('class="table table-bordered table-condensed table-hover" style="margin-bottom: 0px"');
			},
			10, 2
		);

		Hook::add_filter
		(
			sprintf('admin_show_%s_%s', $table_name, 'newvalue'),
			function($display_value, $register)
			{
				$collection = collect(json_decode($display_value, true));
				return $collection->toHtmlTable('class="table table-bordered table-condensed table-hover" style="margin-bottom: 0px"');
			},
			10, 2
		);

		Hook::add_filter
		(
			sprintf('admin_show_%s_%s', $table_name, 'useragent'),
			function($display_value, $register)
			{
				return $display_value;
				// $agent = new Agent();
				// $agent->setUserAgent($display_value);
				// r($agent);
			},
			10, 2
		);
	}
}
