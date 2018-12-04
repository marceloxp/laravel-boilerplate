<?php

use App\Http\Utilities\RouteLang;
use App\Http\Middleware\Shopping;

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
		'middleware' => ['frontend','shopping'],
		'namespace'  => 'Site'
	],
	function()
	{
		Route::group
		(
			[],
			function()
			{
				Route::get('/'           , 'PageController@index'      )->name('home');
				Route::get('/faleconosco', 'PageController@faleconosco')->name('faleconosco');
				Route::get('/products'   , 'ProductController@index'   )->name('produtos');
			}
		);

		Route::group
		(
			['prefix' => '/sobre'],
			function()
			{
				Route::get('/'        , 'SobreController@index'   )->name('sobre');
				Route::get('/empresa' , 'SobreController@empresa' )->name('sobre_empresa');
				Route::get('/tradicao', 'SobreController@tradicao')->name('sobre_tradicao');
			}
		);

		Route::get ('/usuario/cadastro', 'CustomerController@cadastro')->name('usuario_cadastro');
		Route::post('/usuario/cadastro', 'CustomerController@cadastro')->name('usuario_cadastro');
		Route::get ('/usuario/login'   , 'CustomerController@login')->name('usuario_login');
		Route::post('/usuario/login'   , 'CustomerController@login')->name('usuario_login');
		Route::get ('/usuario/logout'  , 'CustomerController@logout')->name('usuario_logout');
	}
);