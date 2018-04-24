<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
}