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
				$data = \App\Http\Umstudio\Brasil::getStates();
				return response($data['value'])->withHeaders($data['header']);
			}
		);

		Route::get
		(
			'cities/{uf}',
			function($uf)
			{
				$data = \App\Http\Umstudio\Brasil::getCitiesByUf($uf);
				return response($data['value'])->withHeaders($data['header']);
			}
		);
	}
);