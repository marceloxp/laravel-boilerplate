<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
	public function login()
	{
		$customer = new \App\Http\Utilities\Customer();
		$customer->login('1234', ['name' => 'Marcelo de Souza Lima', 'email' => 'marceloxp@gmail.com']);
		return redirect()->route('home');
	}

	public function logout()
	{
		$customer = new \App\Http\Utilities\Customer();
		$customer->logout();
		return redirect()->route('home');
	}
}