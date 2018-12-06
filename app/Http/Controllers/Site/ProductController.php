<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class ProductController extends SiteController
{
	public function index(Request $request)
	{
		$product = \App\Models\Product::first();
		View::share(compact('product'));
		return view('site/pages/products');
	}
}