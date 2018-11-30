<?php

namespace App\Http\Utilities;

use Session;

class Customer
{
	const name = 'customer';
	const attr = 'customer.attr';
	const data = ['id' => null, 'logged' => false];

	protected $data;

	public function __construct()
	{
		$this->data = $this->get();
	}

	public function logged()
	{
		return $this->get('logged', false);
	}

	public function get($p_attr = null, $p_default_value = null)
	{
		if (empty($p_attr))
		{
			return \Session::get(self::name) ?? collect(self::data);
		}
		else
		{
			return self::get()->get($p_attr, $p_default_value);
		}
	}

	public function login($p_id, $p_data)
	{
		$data = collect(['id' => $p_id, 'logged' => true]);
		$data = $data->merge($p_data);
		\Session::put(self::name, $data);
		$this->data = $data;
		return self::get();
	}

	public function logout()
	{
		\Session::forget(self::name);
	}
}