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

				// Vídeos
				Route::group
				(
					['prefix' => 'videos'],
					function()
					{
						Route::get ('/'          , 'VideosController@index'  )->name('admin_videos'       )->group('admin_videos');
						Route::get ('/pivot/{id}', 'VideosController@pivot'  )->name('admin_videos_pivot' )->group('admin_videos');
						Route::get ('edit/{id?}' , 'VideosController@create' )->name('admin_videos_edit'  )->group('admin_videos');
						Route::post('edit/{id?}' , 'VideosController@store'  )->name('admin_videos_save'  )->group('admin_videos');
						Route::get ('show/{id}'  , 'VideosController@show'   )->name('admin_videos_show'  )->group('admin_videos');
						Route::post('delete/'    , 'VideosController@destroy')->name('admin_videos_delete')->group('admin_videos');
					}
				);

				// Tags
				Route::group
				(
					['prefix' => 'tags'],
					function()
					{
						Route::get ('/'                       , 'TagsController@index'     )->name('admin_tags'       )->group('admin_tags');
						Route::get ('edit/{id?}'              , 'TagsController@create'    )->name('admin_tags_edit'  )->group('admin_tags');
						Route::post('edit/{id?}'              , 'TagsController@store'     )->name('admin_tags_save'  )->group('admin_tags');
						Route::get ('show/{id}'               , 'TagsController@show'      )->name('admin_tags_show'  )->group('admin_tags');
						Route::post('delete/'                 , 'TagsController@destroy'   )->name('admin_tags_delete')->group('admin_tags');
					}
				);

				// Tags dos Vídeos
				Route::group
				(
					['prefix' => 'tag_video'],
					function()
					{
						Route::get ('{video_id}'              , 'TagVideoController@index'  )->name('admin_tag_video'       )->group('admin_tag_video');
						Route::post('{video_id}/attach'       , 'TagVideoController@store'  )->name('admin_tag_video_attach')->group('admin_tag_video');
						Route::get ('{video_id}/show/{tag_id}', 'TagsController@pivot_show' )->name('admin_videos_show'     )->group('admin_tags');
						Route::post('{video_id}/detach'       , 'TagVideoController@detach' )->name('admin_tag_video_detach')->group('admin_tags');
					}
				);

				// Customers
				Route::group
				(
					['prefix' => 'customers'],
					function()
					{
						Route::get ('/'                       , 'CustomersController@index'  )->name('admin_customers'       )->group('admin_customers');
						Route::get ('edit/{id?}'              , 'CustomersController@create' )->name('admin_customers_edit'  )->group('admin_customers');
						Route::post('edit/{id?}'              , 'CustomersController@store'  )->name('admin_customers_save'  )->group('admin_customers');
						Route::get ('show/{id}'               , 'CustomersController@show'   )->name('admin_customers_show'  )->group('admin_customers');
						Route::post('delete/'                 , 'CustomersController@destroy')->name('admin_customers_delete')->group('admin_customers');
					}
				);
			}
		);
	}
);

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index')->middleware('adminer');