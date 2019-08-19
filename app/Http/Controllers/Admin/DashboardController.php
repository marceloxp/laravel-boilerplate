<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends AdminController
{
    public function index(Request $request)
    {
		$counts = collect(config('admin.dashboard'));
		$counts->transform
		(
			function($item, $key)
			{
				$model           = sprintf('\App\Models\%s', $item['model']);
				$item['link']    = route($item['link']);
				$item['visible'] = true;

				if (array_key_exists('where', $item))
				{
					$item['quant'] = $model::where($item['where'])->count();
				}
				else
				{
					$item['quant'] = $model::count();
				}

				if (array_key_exists('roles', $item))
				{
					$item['visible'] = $this->user->hasAnyRole($item['roles']);
				}

				return $item;
			}
		);
	
		View::share(compact('counts'));

        return view('Admin.dashboard');
    }
}