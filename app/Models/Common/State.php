<?php

namespace App\Models\Common;

use App\Models\Masters\CommonModel;
use App\Http\Utilities\Cached;
use App\Http\Utilities\Result;
use App\Traits\CodeTrait;

class State extends CommonModel
{
	use CodeTrait;

	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

    public static function getStateIdByUf($p_uf)
	{
		try
		{
			$result = Cached::get
			(
				'App\Models\State',
				['getStateIdByUf', $p_uf],
				function() use ($p_uf)
				{
					$state = \App\Models\Common\State::select('id')->where('uf', $p_uf)->first();
					return $state->id ?? false;
				}
			);

			return $result['data'] ?? false;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

    public static function getAll()
	{
		try
		{
			return Cached::get
			(
				'App\Models\State',
				'getAll',
				function()
				{
					return \App\Models\Common\State::select('id','name')->get()->pluck('name','id')->toArray();
				}
			);
		}
		catch (\Exception $e)
		{
			return Result::exception($e);
		}
	}
}
