<?php

namespace App\Http\Utilities;

use App\Http\Utilities\Result;

class Brasil
{
	private static $datasite = [];

	public static function getStates()
	{
		return \App\Models\Common\State::getAll();
    }

	public static function getCitiesByUf($p_uf)
	{
		return \App\Models\Common\City::getByUf($p_uf);
    }
}