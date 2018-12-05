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

	public function faleconosco(Request $request)
	{
		if ($request->isMethod('post'))
		{
			$valid = \App\Models\Contact::validate($request->except(['_token']));
			if (!$valid['success'])
			{
				return back()->withErrors($valid['fields'])->withInput();
			}

			$register = \App\Models\Contact::create($request->except(['_token']));

			return back()->with('message', 'Mensagem enviada com sucesso.');
		}

		MetaSocial::append('title', ' - Fale Conosco');
		MetaSocial::set('description', 'Entre em contato conosco.');
		$contact = \App\Models\Contact::firstOrNew(['id' => null]);
		View::share(compact('contact'));
		return view('site/pages/faleconosco');
	}
}