<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckReferer;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group
(
	['middleware' => 'referer'],
	function()
	{
		Route::post('addconfig', 'ConfigsController@store');
		Route::post('configs'  , 'ConfigsController@index');
	}
);

Route::prefix('brasil')->group
(
	function()
	{
		Route::get
		(
			'states',
			function()
			{
				$result = \App\Http\Umstudio\Brasil::getStates();
				return response($result)->withHeaders(cached_headers($result));
			}
		);

		Route::get
		(
			'cities/{uf}',
			function($uf)
			{
				$result = \App\Http\Umstudio\Brasil::getCitiesByUf($uf);
				return response($result)->withHeaders(cached_headers($result));
			}
		);

		Route::get
		(
			'cep/{cep}',
			function($cep)
			{
				$result = \App\Http\Umstudio\Cep::get($cep);
				return response($result)->withHeaders(cached_headers($result));
			}
		);
	}
);