<?php

namespace App\Http\Umstudio;

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