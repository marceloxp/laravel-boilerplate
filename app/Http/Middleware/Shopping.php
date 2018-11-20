<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\View;
use App\Http\Utilities\Datasite;
use App\Http\Utilities\Cart;
use Closure;

class Shopping
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$cart = Cart::all();
		View::share(compact('cart'));
		return $next($request);
	}
}