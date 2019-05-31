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

\Illuminate\Routing\Route::macro('menu'   , function($value)  { $this->menu = $value; return $this;  } );
\Illuminate\Routing\Route::macro('getMenu', function()        { return $this->menu ?? null;            } );

\Illuminate\Routing\Route::macro('group'   , function($value) { $this->group = $value; return $this; } );
\Illuminate\Routing\Route::macro('getGroup', function()       { return $this->group ?? null;           } );

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
			['middleware' => ['auth','admin'], 'namespace' => 'Admin'],
			function()
			{
				Route::get('/'      , 'DashboardController@index'  )->name('admin_dashboard');
				Route::get('phpinfo', 'DeveloperController@phpinfo')->name('admin_phpinfo');
				
				// Admin table reorder
				Route::post('{table}/reorder', 'AdminController@reorder');

				// Search Modal
				Route::get('modal-search', 'SearchmodalController@index');

				// Configs
				Route::group
				(
					['prefix' => 'configs'],
					function()
					{
						Route::get ('/'         , 'ConfigController@index'  )->name('admin_config'       )->group('admin_config');
						Route::get ('edit/{id?}', 'ConfigController@create' )->name('admin_config_edit'  )->group('admin_config');
						Route::post('edit/{id?}', 'ConfigController@store'  )->name('admin_config_save'  )->group('admin_config');
						Route::get ('show/{id}' , 'ConfigController@show'   )->name('admin_config_show'  )->group('admin_config');
						Route::post('delete/'   , 'ConfigController@destroy')->name('admin_config_delete')->group('admin_config');
					}
				);

				// Cache
				Route::group
				(
					['prefix' => 'cache'],
					function()
					{
						Route::get ('/'    , 'CacheController@index' )->name('admin_cache_index')->menu('admin_cache_index');
						Route::get ('list' , 'CacheController@list'  )->name('admin_cache_list' )->menu('admin_cache_list');
						Route::get ('clear', 'CacheController@clear' )->name('admin_cache_clear');
						Route::post('use'  , 'CacheController@setuse')->name('admin_cache_use'  );
					}
				);

				// Users
				Route::group
				(
					['prefix' => 'users'],
					function()
					{
						Route::get ('/'         , 'UserController@index'  )->name('admin_user'       )->group('admin_user');
						Route::get ('edit/{id?}', 'UserController@create' )->name('admin_user_edit'  )->group('admin_user');
						Route::post('edit/{id?}', 'UserController@store'  )->name('admin_user_save'  )->group('admin_user');
						Route::get ('show/{id}' , 'UserController@show'   )->name('admin_user_show'  )->group('admin_user');
						Route::post('delete/'   , 'UserController@destroy')->name('admin_user_delete')->group('admin_user');
					}
				);

				// Roles
				Route::group
				(
					['prefix' => 'roles'],
					function()
					{
						Route::get ('/'         , 'RoleController@index'  )->name('admin_role'       )->group('admin_role');
						Route::get ('edit/{id?}', 'RoleController@create' )->name('admin_role_edit'  )->group('admin_role');
						Route::post('edit/{id?}', 'RoleController@store'  )->name('admin_role_save'  )->group('admin_role');
						Route::get ('show/{id}' , 'RoleController@show'   )->name('admin_role_show'  )->group('admin_role');
						Route::post('delete/'   , 'RoleController@destroy')->name('admin_role_delete')->group('admin_role');
					}
				);

				// Begin Auditoria
				Route::group
				(
					['prefix' => 'audits'],
					function()
					{
						Route::get ('/'         , 'AuditController@index'  )->name('admin_audits'       )->group('admin_audits');
						Route::get ('show/{id}' , 'AuditController@show'   )->name('admin_audits_show'  )->group('admin_audits');
					}
				);
				// End Auditoria

				// Admin Ajax
				// http://www.local.laravel-boilerplate.com.br/admin/users/admin/ajax/users/btn-send-mail
				Route::post('ajax/{table}/{action}', 'AjaxController@index');

				include('custom_admin.php');
			}
		);
	}
);

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index')->middleware('adminer')->name('adminer');