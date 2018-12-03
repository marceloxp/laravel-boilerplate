<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CustomerController extends SiteController
{
	public function cadastro(Request $request)
	{
		$cliente = \App\Models\Customer::first();

		if ($request->isMethod('post'))
		{
			dump($request->all());
		}

		View::share(compact('cliente'));

		return view('site/pages/usuario/cadastro');
	}
}