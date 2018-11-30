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
						Route::get ('/'    , 'CacheController@index' )->name('admin_cache'      )->menu('admin_cache_index');
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

				// Categorias
				Route::group
				(
					['prefix' => 'categories'],
					function()
					{
						Route::get ('/'         , 'CategoryController@index'  )->name('admin_category'       )->group('admin_category');
						Route::get ('edit/{id?}', 'CategoryController@create' )->name('admin_category_edit'  )->group('admin_category');
						Route::post('edit/{id?}', 'CategoryController@store'  )->name('admin_category_save'  )->group('admin_category');
						Route::get ('show/{id}' , 'CategoryController@show'   )->name('admin_category_show'  )->group('admin_category');
						Route::post('delete/'   , 'CategoryController@destroy')->name('admin_category_delete')->group('admin_category');
					}
				);

				// Galeria de Imagens
				Route::group
				(
					['prefix' => 'galleries'],
					function()
					{
						Route::get ('/'         , 'GalleryController@index'  )->name('admin_gallery'       )->group('admin_gallery');
						Route::get ('edit/{id?}', 'GalleryController@create' )->name('admin_gallery_edit'  )->group('admin_gallery');
						Route::post('edit/{id?}', 'GalleryController@store'  )->name('admin_gallery_save'  )->group('admin_gallery');
						Route::get ('show/{id}' , 'GalleryController@show'   )->name('admin_gallery_show'  )->group('admin_gallery');
						Route::post('delete/'   , 'GalleryController@destroy')->name('admin_gallery_delete')->group('admin_gallery');
					}
				);

				include('custom_admin.php');
			}
		);
	}
);

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index')->middleware('adminer');