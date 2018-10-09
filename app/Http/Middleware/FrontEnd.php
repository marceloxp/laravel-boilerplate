<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Utilities\Datasite;
use Closure;

class FrontEnd
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
		if (!$request->ajax())
		{
			$routename = Route::currentRouteName();
			$url = 
			[
				'name'    => $routename,
				'base'    => env('APP_URL'),
				'admin'   => sprintf('%s/admin', env('APP_URL')),
				'current' => url()->current()
			];

			Datasite::add('csrf_token', csrf_token());
			Datasite::add(compact('url'));
		}
		
		return $next($request);
    }
}