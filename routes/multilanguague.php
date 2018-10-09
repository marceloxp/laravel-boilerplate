<?php

use App\Http\Utilities\RouteLang;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group
(
	[
		'middleware' => 'frontend',
		'namespace'  => 'Site'
	],
	function()
	{
		Route::group
		(
			['prefix' => RouteLang::lang()],
			function($route)
			{
				Route::group
				(
					[],
					function($route)
					{
						Route::get(RouteLang::root()                       , 'PagesController@index'      )->name('home');
						Route::get(RouteLang::route($route, '/faleconosco'), 'PagesController@faleconosco')->name('faleconosco');
					}
				);
			}
		);

		Route::group
		(
			['prefix' => RouteLang::lang()],
			function($route)
			{
				Route::group
				(
					['prefix' => RouteLang::prefix('/sobre')],
					function($route)
					{
						Route::get(RouteLang::route($route, '/')        , 'SobreController@index'   )->name('sobre');
						Route::get(RouteLang::route($route, '/empresa') , 'SobreController@empresa' )->name('sobre_empresa');
						Route::get(RouteLang::route($route, '/tradicao'), 'SobreController@tradicao')->name('sobre_tradicao');
					}
				);
			}
		);
	}
);