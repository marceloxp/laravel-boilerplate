<?php

namespace App\Http\Umstudio;

use App\Http\Umstudio\Result;

class Brasil
{
	private static $datasite = [];

	public static function getStates()
	{
		return \App\Models\State::getAll();
    }

	public static function getCitiesByUf($p_uf)
	{
		return \App\Models\City::getByUf($p_uf);
    }
}