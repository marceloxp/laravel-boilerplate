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
		$this->get('login' , 'Auth\LoginController@showLoginForm')->name('admin_login');
		$this->post('login', 'Auth\LoginController@login');
		$this->get('logout', 'Auth\LoginController@logout')->name('admin_logout');

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
				Route::get('/'      , 'DashboardController@index'  )->name('admin_dashboard');
				Route::get('phpinfo', 'DeveloperController@phpinfo')->name('admin_phpinfo');

				// Configs
				Route::group
				(
					['prefix' => 'configs'],
					function()
					{
						Route::get ('/'         , 'ConfigsController@index'  )->name('admin_configs');
						Route::get ('edit/{id?}', 'ConfigsController@create' )->name('admin_configs_edit');
						Route::post('edit/{id?}', 'ConfigsController@store'  )->name('admin_configs_save');
						Route::get ('show/{id}' , 'ConfigsController@show'   )->name('admin_configs_show');
						Route::post('delete/'   , 'ConfigsController@destroy')->name('admin_configs_delete');
					}
				);

				// Cache
				Route::group
				(
					['prefix' => 'cache'],
					function()
					{
						Route::get ('/'    , 'CacheController@index' )->name('admin_cache_index');
						Route::get ('list' , 'CacheController@list'  )->name('admin_cache_list');
						Route::get ('clear', 'CacheController@clear' )->name('admin_cache_clear');
						Route::post('use'  , 'CacheController@setuse')->name('admin_cache_use');
					}
				);

				// Users
				Route::group
				(
					['prefix' => 'users'],
					function()
					{
						Route::get ('/'         , 'UsersController@index'  )->name('admin_users');
						Route::get ('edit/{id?}', 'UsersController@create' )->name('admin_users_edit');
						Route::post('edit/{id?}', 'UsersController@store'  )->name('admin_users_save');
						Route::get ('show/{id}' , 'UsersController@show'   )->name('admin_users_show');
						Route::post('delete/'   , 'UsersController@destroy')->name('admin_users_delete');
					}
				);

				// Roles
				Route::group
				(
					['prefix' => 'roles'],
					function()
					{
						Route::get ('/'         , 'RolesController@index'  )->name('admin_roles');
						Route::get ('edit/{id?}', 'RolesController@create' )->name('admin_roles_edit');
						Route::post('edit/{id?}', 'RolesController@store'  )->name('admin_roles_save');
						Route::get ('show/{id}' , 'RolesController@show'   )->name('admin_roles_show');
						Route::post('delete/'   , 'RolesController@destroy')->name('admin_roles_delete');
					}
				);

				// Vídeos
				Route::group
				(
					['prefix' => 'videos'],
					function()
					{
						Route::get ('/'         , 'VideosController@index'  )->name('admin_videos');
						Route::get ('edit/{id?}', 'VideosController@create' )->name('admin_videos_edit');
						Route::post('edit/{id?}', 'VideosController@store'  )->name('admin_videos_save');
						Route::get ('show/{id}' , 'VideosController@show'   )->name('admin_videos_show');
						Route::post('delete/'   , 'VideosController@destroy')->name('admin_videos_delete');
					}
				);
			}
		);
	}
);