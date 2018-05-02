<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Umstudio\MasterModel;
use App\Http\Umstudio\Cached;
use App\Http\Umstudio\Result;

class City extends MasterModel
{
    public static function getByUf($p_uf)
	{
		$uf_id = \App\Models\State::getStateIdByUf($p_uf);
		if (!$uf_id)
		{
			return Result::undefined();
		}

		return Cached::get
		(
			'App\Models\City',
			['getByUf', $p_uf],
			function() use ($uf_id)
			{
				return \App\Models\City::select('id','name')->where('state_id', $uf_id)->get()->pluck('name','id')->toArray();
			}
		);
	}
}