<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Umstudio\MasterModel;
use App\Http\Umstudio\Cached;
use App\Http\Umstudio\Result;

class State extends MasterModel
{
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
					$state = \App\Models\State::select('id')->where('uf', $p_uf)->first();
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
					return \App\Models\State::select('id','name')->get()->pluck('name','id')->toArray();
				}
			);
		}
		catch (\Exception $e)
		{
			return Result::exception($e);
		}
	}
}
