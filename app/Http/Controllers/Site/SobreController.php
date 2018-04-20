<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Umstudio\MetaSocial;

class SobreController extends Controller
{
	public function index()
	{
		MetaSocial::use('sobre');
		return view('site/pages/sobre');
	}

	public function empresa()
	{
		return view('site/pages/sobre_empresa');
	}

	public function tradicao()
	{
		return view('site/pages/sobre_tradicao');
	}
}