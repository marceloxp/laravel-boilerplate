<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CustomerController extends SiteController
{
	public function login(Request $request)
	{
		if ($request->isMethod('post'))
		{
			$data = ['email' => $request->get('email')];

			$user = \App\Models\Customer::where($data)->first();
			$user->makeHidden('password');

			if (!$user) { return \Redirect::back()->withErrors(['Usuário não localizado.']); }

			if (\Hash::check($request->get('password'), \Hash::make($request->get('password'))) == false)
			{
				ddd('Usuário e/ou senha incorretos.');
				return \Redirect::back()->withErrors(['Usuário e/ou senha incorretos.']);
			}

			$customer = new \App\Http\Utilities\Customer();
			$customer->login($user->id, $user);

			return redirect()->route('home');
		}

		return view('site/pages/usuario/login');
	}

	public function logout(Request $request)
	{
		$customer = new \App\Http\Utilities\Customer();
		$customer->logout();
		return redirect()->route('home');
	}

	public function cadastro(Request $request)
	{
		$cliente = \App\Models\Customer::firstOrNew(['id' => $this->customer->get('id')]);

		if ($request->isMethod('post'))
		{
			$valid = \App\Models\Customer::validate($request->except(['_token']), $request->get('id'));
			if (!$valid['success'])
			{
				return back()->withErrors($valid['fields'])->withInput();
			}
			$register = \App\Models\Customer::create($request->except(['_token']));
			return back()->with('message', 'Cadastro atualizado com sucesso.')->withInput();
		}

		View::share(compact('cliente'));

		return view('site/pages/usuario/cadastro');
	}
}