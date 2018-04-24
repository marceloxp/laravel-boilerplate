<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use App\Http\Umstudio\Datasite;
use Closure;
use Auth;

class AdminMiddleware
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
        if (!Auth::check())
        {
            return redirect()->route('login');
        }

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
