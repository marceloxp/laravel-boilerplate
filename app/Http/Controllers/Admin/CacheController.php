<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Utilities\Cached;
use App\Http\Utilities\Result;
use App\Http\Utilities\DBConfig;

class CacheController extends AdminController
{
	public function index()
	{
		$use_cache = config('cache.use', 's');
		$cache_count = Cached::count();
		View::share(compact('cache_count','use_cache'));
		return view('Admin.cache_index');
	}

	public function list()
	{
		$caches = Cached::list();
		View::share(compact('caches'));
		return view('Admin.cachelist');
	}

	public function clear()
	{
		Cached::flush();
		return redirect()->route('admin_cache_index')->with('messages', ['Cache limpo com sucesso.']);
	}

	public function setuse(Request $request)
	{
		$use_cache = $request->input('use');
		if (!in_array($use_cache, ['n','s']))
		{
			return Result::undefined();
		}

		$result = DBConfig::set('cache.use', $use_cache);
		
		return Result::ifthen($result);
	}
}