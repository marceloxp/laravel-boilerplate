<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use \App\Http\Utilities\Result;
use Hook;

class TagVideoController extends AdminController
{
	public function __construct()
	{
		$this->setMasterModel(\App\Models\Video::class);
		$this->setModel(\App\Models\Tag::class);
		$this->setCaptionByModel($this->master, $this->model);
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $target_id)
	{
		if (empty($target_id))
		{
			die('Invalid parameters.');
		}

		$this->setPivotCaption($target_id);

		return $this->defaultIndex
		(
			[
				'pivot_scope'    => $this->getPivotScopeConfig($target_id),
				'request'        => $request,
				'model'          => $this->model,
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
	public function store(Request $request, $target_id)
	{
		$model_name = $this->model::getTableName();
		$ids = $request->input('ids');
		foreach ($ids as $model_id)
		{
			$master = $this->master::findOrFail($target_id);
			$result = $master->$model_name()->attach($model_id);
		}

		return Result::success('Registros adicionados com sucesso.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($target_id, $model_id)
	{
		return $this->defaultShow
		(
			[
				'id'             => $model_id,
				'model'          => $this->model,
				'display_fields' => ['id','name']
			]
		);
	}

	public function detach(Request $request, $target_id)
	{
		$model_name = $this->model::getTableName();
		$ids = $request->input('ids');
		$ids = explode(',', $ids);
		foreach ($ids as $model_id)
		{
			$master = $this->master::findOrFail($target_id);
			$result = $master->$model_name()->detach($model_id);
		}

		return Result::success('Registros excluídos com sucesso.');
	}
}