<?php

namespace App\Models\Common;

use App\Http\Utilities\MasterModel;
use App\Http\Utilities\Cached;
use App\Http\Utilities\Result;
use \App\Traits\OrderTrait;

class City extends MasterModel
{
	use OrderTrait;
	protected $connection = 'common';
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function getByUf($p_uf)
	{
		$uf_id = \App\Models\Common\State::getStateIdByUf($p_uf);
		if (!$uf_id)
		{
			return Result::undefined();
		}

		return Cached::get
		(
			'App\Models\Common\City',
			['getByUf', $p_uf],
			function() use ($uf_id)
			{
				return \App\Models\Common\City::select('id','name')->where('state_id', $uf_id)->get()->pluck('name','id')->toArray();
			}
		);
	}

	public function state()
	{
		return $this->hasOne(\App\Models\Common\State::class, 'id', 'state_id');
	}

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'state_id'   => 'required',
			'name'       => 'required|max:150',
		];
		return Role::_validate($request, $rules, $id);
	}
}

