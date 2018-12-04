<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class SiteController extends Controller
{
	public function __construct()
	{
		$customer = new \App\Http\Utilities\Customer();
		$this->customer = $customer;
		datasite_add(['customer' => $customer->get()]);
		View::share(compact('customer'));
	}
}