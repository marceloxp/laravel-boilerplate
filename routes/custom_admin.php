<?php
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
			Route::get ('{menu_id}'              , 'MenuRoleController@index' )->name('admin_menu_role'       )->group('admin_menu_role');
			Route::post('{menu_id}/attach'       , 'MenuRoleController@store' )->name('admin_menu_role_attach')->group('admin_menu_role');
			Route::get ('{menu_id}/show/{role_id}', 'RoleController@pivot_show')->name('admin_menus_show'     )->group('admin_role');
			Route::post('{menu_id}/detach'       , 'MenuRoleController@detach')->name('admin_menu_role_detach')->group('admin_menu_role');
		}
	);
	// End Menu - Role