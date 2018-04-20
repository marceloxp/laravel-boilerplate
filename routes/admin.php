<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return redirect('admin'); });

//Admin routes
Route::group
(
	['prefix' => 'admin'],
	function()
	{
		// Authentication Routes...
		$this->get('login' , 'Auth\LoginController@showLoginForm')->name('login');
		$this->post('login', 'Auth\LoginController@login');
		$this->get('logout', 'Auth\LoginController@logout')->name('logout');

		// Password Reset Routes...
		// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
		// $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
		// $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
		// $this->post('password/reset', 'Auth\ResetPasswordController@reset');

		Route::group
		(
			['middleware' => 'auth', 'namespace' => 'Admin'],
			function()
			{
				Route::get('/'          , 'DashboardController@index'     )->name('dashboard');
				Route::get('clear_cache', 'DeveloperController@flushCache')->name('clear_cache');
				Route::get('phpinfo'    , 'DeveloperController@phpinfo'   )->name('phpinfo');
				Route::get('cache'      , 'DeveloperController@listCache' )->name('list_cache');

				//Configs
				Route::group
				(
					['prefix' => 'configs'],
					function()
					{
						Route::get ('/'         , 'ConfigsController@index'  )->name('configs');
						Route::get ('edit/{id?}', 'ConfigsController@create' )->name('configs_edit');
						Route::post('edit/{id?}', 'ConfigsController@store'  )->name('configs_save');
						Route::get ('show/{id}' , 'ConfigsController@show'   )->name('configs_show');
						Route::post('delete/'   , 'ConfigsController@destroy')->name('configs_delete');
					}
				);

				//Users
				Route::group
				(
					['prefix' => 'users'],
					function()
					{
						Route::get ('/'         , 'UsersController@index'  )->name('users');
						Route::get ('edit/{id?}', 'UsersController@create' )->name('users_edit');
						Route::post('edit/{id?}', 'UsersController@store'  )->name('users_save');
						Route::get ('show/{id}' , 'UsersController@show'   )->name('users_show');
						Route::post('delete/'   , 'UsersController@destroy')->name('users_delete');
					}
				);

				//Roles
				Route::group
				(
					['prefix' => 'roles'],
					function()
					{
						Route::get ('/'         , 'RolesController@index'  )->name('roles');
						Route::get ('edit/{id?}', 'RolesController@create' )->name('roles_edit');
						Route::post('edit/{id?}', 'RolesController@store'  )->name('roles_save');
						Route::get ('show/{id}' , 'RolesController@show'   )->name('roles_show');
						Route::post('delete/'   , 'RolesController@destroy')->name('roles_delete');
					}
				);

				//Vídeos
				Route::group
				(
					['prefix' => 'videos'],
					function()
					{
						Route::get ('/'         , 'VideosController@index'  )->name('videos');
						Route::get ('edit/{id?}', 'VideosController@create' )->name('videos_edit');
						Route::post('edit/{id?}', 'VideosController@store'  )->name('videos_save');
						Route::get ('show/{id}' , 'VideosController@show'   )->name('videos_show');
						Route::post('delete/'   , 'VideosController@destroy')->name('videos_delete');
					}
				);
			}
		);
	}
);