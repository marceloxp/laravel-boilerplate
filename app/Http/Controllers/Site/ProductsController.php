<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class ProductsController extends Controller
{
	public function index()
	{
		$product = \App\Models\Product::first();
		View::share(compact('product'));
		return view('site/pages/products');
	}
}