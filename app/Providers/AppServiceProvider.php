<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// SET LOCALE FROM URL
		$segment1 = Request::segment(1);
		if (in_array($segment1, config('app.all_langs')))
		{
			App::setLocale($segment1);
			config(['app.current_locale' => $segment1]);
		}
		else
		{
			config(['app.current_locale' => config('app.locale')]);
		}

		if (!is_dir(public_path('storage')))
		{
			App::make('files')->link(storage_path('app/public'), public_path('storage'));
		}
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// MERGE DB_CONFIG WITH CONFIG FILES
		try
		{
			$custom_configs = \DB::table('configs')->select('name','value')->where('status', 'Ativo')->get();
			collect($custom_configs)->each
			(
				function($item, $key)
				{
					config([$item->name => $item->value]);
				}
			);
		}
		catch (\Exception $e)
		{
			report($e);
		}
	}
}