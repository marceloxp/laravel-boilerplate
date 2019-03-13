<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Utilities\Datasite;
use App\Http\Utilities\MetaSocial;

class PageController extends SiteController
{
	public function index()
	{
		return view('site/pages/home');
	}
}