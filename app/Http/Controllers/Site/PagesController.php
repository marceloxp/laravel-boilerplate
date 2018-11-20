<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Http\Utilities\Cart;
use App\Http\Utilities\ProductCart;
use App\Http\Utilities\Datasite;
use App\Http\Utilities\MetaSocial;
use App\Http\Utilities\Pocket;
use App\Http\Utilities\ProductValue;

class PagesController extends Controller
{
	public function index()
	{
		return view('site/pages/home');
	}

	public function faleconosco()
	{
		MetaSocial::append('title', ' - Fale Conosco');
		MetaSocial::set('description', 'Entre em contato conosco.');
		return view('site/pages/faleconosco');
	}
}