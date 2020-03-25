<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use \App\Http\Utilities\Result;
use Hook;

class MasterManyController extends AdminController
{
	public function __construct($p_caption, $p_master_model, $p_many_model)
	{
		$this->caption     = $p_caption;
		$this->MasterModel = $p_master_model;
		$this->ManyModel   = $p_many_model;
		$this->master_name = class_basename($this->MasterModel);
		$this->many_name   = class_basename($this->ManyModel);
		$this->order_name  = [$this->master_name, $this->many_name];
		$this->pivotName   = sprintf('%s%s', strtolower($this->order_name[0]), $this->order_name[1]);

		$this->master_many_method = Illuminate\Support\Str::plural(strtolower(class_basename($this->ManyModel)));
		$this->setCaption($p_caption);

		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $master_id)
	{
		if (empty($master_id))
		{
			die('Invalid parameters.');
		}

		$model = new $this->MasterModel;
		$table_name = $model->getTable();

		$this->setCaption($this->caption, db_get_name($table_name, $master_id));

		return $this->defaultIndex
		(
			[
				'pivot_scope'    =>
				[
					'name'  => $this->pivotName,
					'param' => $master_id
				],
				'request'        => $request,
				'model'          => $this->ManyModel,
				'display_fields' => ['id','name','created_at']
			]
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $master_id)
	{
		$master_many_method = $this->master_many_method;
		$ids = $request->input('ids');
		foreach ($ids as $many_id)
		{
			$master_register = $this->MasterModel::findOrFail($master_id);
			$result          = $master_register->{$master_many_method}()->attach($many_id);
		}
		return Result::success('Registros adicionados com sucesso.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($master_id, $many_id)
	{
		return $this->defaultShow
		(
			[
				'id'             => $many_id,
				'model'          => Tag::class,
				'display_fields' => ['id','name']
			]
		);
	}

	public function detach(Request $request, $master_id)
	{
		$master_many_method = $this->master_many_method;
		$ids = $request->all('ids');
		$ids = explode(',', $ids['ids']);
		foreach ($ids as $many_id)
		{
			$master_register = $this->MasterModel::findOrFail($master_id);
			$result          = $master_register->{$master_many_method}()->detach($many_id);
		}

		return Result::success('Registros excluídos com sucesso.');
	}
}