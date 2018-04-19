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
use App\Http\Umstudio\Cached;

class DeveloperController extends AdminController
{
	public function flushCache()
	{
		Cached::flush();
		return redirect()->route('list_cache')->with('messages', ['Cache limpo com sucesso.']);
	}

	public function listCache()
	{
		$caches = Cached::list();
		View::share(compact('caches'));
		return view('Admin.cachelist');
	}
}