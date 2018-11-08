<?php
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
			Route::get ('/'         , 'TagsController@index'     )->name('admin_tags'       )->group('admin_tags');
			Route::get ('edit/{id?}', 'TagsController@create'    )->name('admin_tags_edit'  )->group('admin_tags');
			Route::post('edit/{id?}', 'TagsController@store'     )->name('admin_tags_save'  )->group('admin_tags');
			Route::get ('show/{id}' , 'TagsController@show'      )->name('admin_tags_show'  )->group('admin_tags');
			Route::post('delete/'   , 'TagsController@destroy'   )->name('admin_tags_delete')->group('admin_tags');
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
			Route::get ('/'         , 'CustomersController@index'  )->name('admin_customers'       )->group('admin_customers');
			Route::get ('edit/{id?}', 'CustomersController@create' )->name('admin_customers_edit'  )->group('admin_customers');
			Route::post('edit/{id?}', 'CustomersController@store'  )->name('admin_customers_save'  )->group('admin_customers');
			Route::get ('show/{id}' , 'CustomersController@show'   )->name('admin_customers_show'  )->group('admin_customers');
			Route::post('delete/'   , 'CustomersController@destroy')->name('admin_customers_delete')->group('admin_customers');
		}
	);

	// Address Types
	Route::group
	(
		['prefix' => 'address_types'],
		function()
		{
			Route::get ('/'         , 'AddressTypesController@index'  )->name('admin_address_types'       )->group('admin_address_types');
			Route::get ('edit/{id?}', 'AddressTypesController@create' )->name('admin_address_types_edit'  )->group('admin_address_types');
			Route::post('edit/{id?}', 'AddressTypesController@store'  )->name('admin_address_types_save'  )->group('admin_address_types');
			Route::get ('show/{id}' , 'AddressTypesController@show'   )->name('admin_address_types_show'  )->group('admin_address_types');
			Route::post('delete/'   , 'AddressTypesController@destroy')->name('admin_address_types_delete')->group('admin_address_types');
		}
	);