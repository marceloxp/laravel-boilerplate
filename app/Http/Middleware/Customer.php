<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\View;
use Closure;

class Customer
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
		$customer = new \App\Http\Utilities\Customer();
		$request->merge(compact('customer'));
		View::share(compact('customer'));
		return $next($request);
	}
}