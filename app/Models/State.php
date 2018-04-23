<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Umstudio\MasterModel;
use App\Http\Umstudio\Cached;

class State extends MasterModel
{
    public static function getStateIdByUf($p_uf)
	{
		return Cached::get
		(
			'App\Models\State',
			['getStateIdByUf', $p_uf],
			function() use ($p_uf)
			{
				$state = \App\Models\State::select('id')->where('uf', $p_uf)->first();
				return $state->id;
			}
		);
	}

    public static function getAll()
	{
		return Cached::get
		(
			'App\Models\State',
			'getAll',
			function()
			{
				$result = \App\Models\State::select('id','name')->get()->pluck('name','id')->toArray();
				return $result;
			}
		);
	}
}
