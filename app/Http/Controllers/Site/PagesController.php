<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
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

		$product = (Object)
		[
			'id'       => 152,
			'name'     => 'Product 1',
			'price'    => 10,
			'discount' => 50
		];

		$price = new ProductValue(10, 1, 50);
		$price->setPayment('visa', 0, 3);
		$price->setPayment('incash', 7, 1);

		$product->value = $price;

		r($product);

		~r('Done');

		// $pv = new ProductValue(10, 1, 50);
		// $pv->setPayment('visa', 0, 12);
		// $pv->setPayment('incash', 7, 1);
		// ~r($pv);

		// $payments = $pv->getPayment('visa');
		// ~r($payments);

		// Pocket::reset();

		// Pocket::del('product_1');
		// Pocket::del('product_2');

		// $product_1 = Pocket::set('Livro 1', 10, 1);
		// r($product_1);

		// $product_2 = Pocket::set('Livro 2', 10, 1);
		// r($product_2);

		// $all = Pocket::all();
		// r($all);
		
		return view('site/pages/faleconosco');
	}
}