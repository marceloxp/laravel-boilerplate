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
						Route::get ('/'         , 'ConfigsController@index'  )->name('admin_configs'       )->group('admin_configs');
						Route::get ('edit/{id?}', 'ConfigsController@create' )->name('admin_configs_edit'  )->group('admin_configs');
						Route::post('edit/{id?}', 'ConfigsController@store'  )->name('admin_configs_save'  )->group('admin_configs');
						Route::get ('show/{id}' , 'ConfigsController@show'   )->name('admin_configs_show'  )->group('admin_configs');
						Route::post('delete/'   , 'ConfigsController@destroy')->name('admin_configs_delete')->group('admin_configs');
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
						Route::get ('/'         , 'UsersController@index'  )->name('admin_users'       )->group('admin_users');
						Route::get ('edit/{id?}', 'UsersController@create' )->name('admin_users_edit'  )->group('admin_users');
						Route::post('edit/{id?}', 'UsersController@store'  )->name('admin_users_save'  )->group('admin_users');
						Route::get ('show/{id}' , 'UsersController@show'   )->name('admin_users_show'  )->group('admin_users');
						Route::post('delete/'   , 'UsersController@destroy')->name('admin_users_delete')->group('admin_users');
					}
				);

				// Roles
				Route::group
				(
					['prefix' => 'roles'],
					function()
					{
						Route::get ('/'         , 'RolesController@index'  )->name('admin_roles'       )->group('admin_roles');
						Route::get ('edit/{id?}', 'RolesController@create' )->name('admin_roles_edit'  )->group('admin_roles');
						Route::post('edit/{id?}', 'RolesController@store'  )->name('admin_roles_save'  )->group('admin_roles');
						Route::get ('show/{id}' , 'RolesController@show'   )->name('admin_roles_show'  )->group('admin_roles');
						Route::post('delete/'   , 'RolesController@destroy')->name('admin_roles_delete')->group('admin_roles');
					}
				);

				// Categorias
				Route::group
				(
					['prefix' => 'categories'],
					function()
					{
						Route::get ('/'         , 'CategoriesController@index'  )->name('admin_categories'       )->group('admin_categories');
						Route::get ('edit/{id?}', 'CategoriesController@create' )->name('admin_categories_edit'  )->group('admin_categories');
						Route::post('edit/{id?}', 'CategoriesController@store'  )->name('admin_categories_save'  )->group('admin_categories');
						Route::get ('show/{id}' , 'CategoriesController@show'   )->name('admin_categories_show'  )->group('admin_categories');
						Route::post('delete/'   , 'CategoriesController@destroy')->name('admin_categories_delete')->group('admin_categories');

						// Route::group
						// (
						// 	['prefix' => '{category_id}/subcategory'],
						// 	function()
						// 	{
						// 		Route::get ('/'         , 'SubcategoriesController@index'  )->name('admin_subcategories'       )->group('admin_subcategories');
						// 		Route::get ('edit/{id?}', 'SubcategoriesController@create' )->name('admin_subcategories_edit'  )->group('admin_subcategories');
						// 		Route::post('edit/{id?}', 'SubcategoriesController@store'  )->name('admin_subcategories_save'  )->group('admin_subcategories');
						// 		Route::get ('show/{id}' , 'SubcategoriesController@show'   )->name('admin_subcategories_show'  )->group('admin_subcategories');
						// 		Route::post('delete/'   , 'SubcategoriesController@destroy')->name('admin_subcategories_delete')->group('admin_subcategories');
						// 	}
						// );
					}
				);

				// Galeria de Imagens
				Route::group
				(
					['prefix' => 'galleries'],
					function()
					{
						Route::get ('/'         , 'GalleriesController@index'  )->name('admin_galleries'       )->group('admin_galleries');
						Route::get ('edit/{id?}', 'GalleriesController@create' )->name('admin_galleries_edit'  )->group('admin_galleries');
						Route::post('edit/{id?}', 'GalleriesController@store'  )->name('admin_galleries_save'  )->group('admin_galleries');
						Route::get ('show/{id}' , 'GalleriesController@show'   )->name('admin_galleries_show'  )->group('admin_galleries');
						Route::post('delete/'   , 'GalleriesController@destroy')->name('admin_galleries_delete')->group('admin_galleries');
					}
				);

				include('custom_admin.php');
			}
		);
	}
);

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index')->middleware('adminer');