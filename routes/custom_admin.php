<?php
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

	// Cities
	Route::group
	(
		['prefix' => 'cities'],
		function()
		{
			Route::get ('/'         , 'CityController@index'  )->name('admin_city'       )->group('admin_city');
			Route::get ('edit/{id?}', 'CityController@create' )->name('admin_city_edit'  )->group('admin_city');
			Route::post('edit/{id?}', 'CityController@store'  )->name('admin_city_save'  )->group('admin_city');
			Route::get ('show/{id}' , 'CityController@show'   )->name('admin_city_show'  )->group('admin_city');
			Route::post('delete/'   , 'CityController@destroy')->name('admin_city_delete')->group('admin_city');
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

	// Begin Vídeos
	Route::group
	(
		['prefix' => 'videos'],
		function()
		{
			Route::get ('/'          , 'VideoController@index'  )->name('admin_video'       )->group('admin_video');
			Route::get ('/pivot/{id}', 'VideoController@pivot'  )->name('admin_video_pivot' )->group('admin_video');
			Route::get ('edit/{id?}' , 'VideoController@create' )->name('admin_video_edit'  )->group('admin_video');
			Route::post('edit/{id?}' , 'VideoController@store'  )->name('admin_video_save'  )->group('admin_video');
			Route::get ('show/{id}'  , 'VideoController@show'   )->name('admin_video_show'  )->group('admin_video');
			Route::post('delete/'    , 'VideoController@destroy')->name('admin_video_delete')->group('admin_video');
		}
	);
	// End Vídeos

	// Begin Tags
	Route::group
	(
		['prefix' => 'tags'],
		function()
		{
			Route::get ('/'         , 'TagController@index'  )->name('admin_tag'       )->group('admin_tag');
			Route::get ('edit/{id?}', 'TagController@create' )->name('admin_tag_edit'  )->group('admin_tag');
			Route::post('edit/{id?}', 'TagController@store'  )->name('admin_tag_save'  )->group('admin_tag');
			Route::get ('show/{id}' , 'TagController@show'   )->name('admin_tag_show'  )->group('admin_tag');
			Route::post('delete/'   , 'TagController@destroy')->name('admin_tag_delete')->group('admin_tag');
		}
	);
	// End Tags

	// Begin Tags dos Vídeos
	Route::group
	(
		['prefix' => 'tag_video'],
		function()
		{
			Route::get ('{video_id}'              , 'TagVideoController@index' )->name('admin_tag_video'       )->group('admin_tag_video');
			Route::post('{video_id}/attach'       , 'TagVideoController@store' )->name('admin_tag_video_attach')->group('admin_tag_video');
			Route::get ('{video_id}/show/{tag_id}', 'TagController@pivot_show')->name('admin_videos_show'     )->group('admin_tag');
			Route::post('{video_id}/detach'       , 'TagVideoController@detach')->name('admin_tag_video_detach')->group('admin_tag');
		}
	);
	// End Tags dos Vídeos

	// Begin Admin Menu
	Route::group
	(
		['prefix' => 'menu'],
		function()
		{
			Route::get ('/'         , 'MenuController@index'  )->name('admin_menu'       )->group('admin_menu');
			Route::get ('edit/{id?}', 'MenuController@create' )->name('admin_menu_edit'  )->group('admin_menu');
			Route::post('edit/{id?}', 'MenuController@store'  )->name('admin_menu_save'  )->group('admin_menu');
			Route::get ('show/{id}' , 'MenuController@show'   )->name('admin_menu_show'  )->group('admin_menu');
			Route::post('delete/'   , 'MenuController@destroy')->name('admin_menu_delete')->group('admin_menu');
		}
	);
	// End Admin Menu

	// Begin Menu - Role
	Route::group
	(
		['prefix' => 'menu_role'],
		function()
		{
			Route::get ('{menu_id}'               , 'MenuRoleController@index' )->name('admin_menu_role'       )->group('admin_menu_role');
			Route::post('{menu_id}/attach'        , 'MenuRoleController@store' )->name('admin_menu_role_attach')->group('admin_menu_role');
			Route::get ('{menu_id}/show/{role_id}', 'RoleController@pivot_show')->name('admin_menus_show'     )->group('admin_role');
			Route::post('{menu_id}/detach'        , 'MenuRoleController@detach')->name('admin_menu_role_detach')->group('admin_menu_role');
		}
	);
	// End Menu - Role

	include('payment_admin.php');

	// Begin Common Listas Auxiliares
	Route::group
	(
		['prefix' => 'common/genericlists'],
		function()
		{
			Route::get ('/'         , 'Common\GenericlistController@index'  )->name('admin_common_genericlists'       )->group('admin_common_genericlists');
			Route::get ('edit/{id?}', 'Common\GenericlistController@create' )->name('admin_common_genericlists_edit'  )->group('admin_common_genericlists');
			Route::post('edit/{id?}', 'Common\GenericlistController@store'  )->name('admin_common_genericlists_save'  )->group('admin_common_genericlists');
			Route::get ('show/{id}' , 'Common\GenericlistController@show'   )->name('admin_common_genericlists_show'  )->group('admin_common_genericlists');
			Route::post('delete/'   , 'Common\GenericlistController@destroy')->name('admin_common_genericlists_delete')->group('admin_common_genericlists');
		}
	);
	// End Common Listas Auxiliares