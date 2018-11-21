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
			Route::get ('/'         , 'TagsController@index'  )->name('admin_tags'       )->group('admin_tags');
			Route::get ('edit/{id?}', 'TagsController@create' )->name('admin_tags_edit'  )->group('admin_tags');
			Route::post('edit/{id?}', 'TagsController@store'  )->name('admin_tags_save'  )->group('admin_tags');
			Route::get ('show/{id}' , 'TagsController@show'   )->name('admin_tags_show'  )->group('admin_tags');
			Route::post('delete/'   , 'TagsController@destroy')->name('admin_tags_delete')->group('admin_tags');
		}
	);

	// Tags dos Vídeos
	Route::group
	(
		['prefix' => 'tag_video'],
		function()
		{
			Route::get ('{video_id}'              , 'TagVideoController@index' )->name('admin_tag_video'       )->group('admin_tag_video');
			Route::post('{video_id}/attach'       , 'TagVideoController@store' )->name('admin_tag_video_attach')->group('admin_tag_video');
			Route::get ('{video_id}/show/{tag_id}', 'TagsController@pivot_show')->name('admin_videos_show'     )->group('admin_tags');
			Route::post('{video_id}/detach'       , 'TagVideoController@detach')->name('admin_tag_video_detach')->group('admin_tags');
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

	// Seções
	Route::group
	(
		['prefix' => 'sections'],
		function()
		{
			Route::get ('/'         , 'SectionsController@index'  )->name('admin_menusections'       )->group('admin_menusections');
			Route::get ('edit/{id?}', 'SectionsController@create' )->name('admin_menusections_edit'  )->group('admin_menusections');
			Route::post('edit/{id?}', 'SectionsController@store'  )->name('admin_menusections_save'  )->group('admin_menusections');
			Route::get ('show/{id}' , 'SectionsController@show'   )->name('admin_menusections_show'  )->group('admin_menusections');
			Route::post('delete/'   , 'SectionsController@destroy')->name('admin_menusections_delete')->group('admin_menusections');
		}
	);

	// Menu Links
	Route::group
	(
		['prefix' => 'menusections/{section_id}/menulinks'],
		function()
		{
			Route::get ('/'         , 'MenulinkController@index'  )->name('admin_menulinks'       )->group('admin_menulinks');
			Route::get ('edit/{id?}', 'MenulinkController@create' )->name('admin_menulinks_edit'  )->group('admin_menulinks');
			Route::post('edit/{id?}', 'MenulinkController@store'  )->name('admin_menulinks_save'  )->group('admin_menulinks');
			Route::get ('show/{id}' , 'MenulinkController@show'   )->name('admin_menulinks_show'  )->group('admin_menulinks');
			Route::post('delete/'   , 'MenulinkController@destroy')->name('admin_menulinks_delete')->group('admin_menulinks');
		}
	);

	// Permissões das Seções
	Route::group
	(
		['prefix' => 'menusection_role'],
		function()
		{
			Route::get ('{menusection_id}'               , 'MenusectionRoleController@index' )->name('admin_menusection_role'       )->group('admin_menusection_role');
			Route::post('{menusection_id}/attach'        , 'MenusectionRoleController@store' )->name('admin_menusection_role_attach')->group('admin_menusection_role');
			Route::get ('{menusection_id}/show/{role_id}', 'SectionsController@pivot_show'   )->name('admin_sections_show'          )->group('admin_roles');
			Route::post('{menusection_id}/detach'        , 'MenusectionRoleController@detach')->name('admin_menusection_role_detach')->group('admin_roles');
		}
	);

	// Sub Categorias
	Route::group
	(
		['prefix' => 'categories/{category_id}/subcategory'],
		function()
		{
			Route::get ('/'         , 'SubcategoryController@index'  )->name('admin_subcategories'       )->group('admin_subcategories');
			Route::get ('edit/{id?}', 'SubcategoryController@create' )->name('admin_subcategories_edit'  )->group('admin_subcategories');
			Route::post('edit/{id?}', 'SubcategoryController@store'  )->name('admin_subcategories_save'  )->group('admin_subcategories');
			Route::get ('show/{id}' , 'SubcategoryController@show'   )->name('admin_subcategories_show'  )->group('admin_subcategories');
			Route::post('delete/'   , 'SubcategoryController@destroy')->name('admin_subcategories_delete')->group('admin_subcategories');
		}
	);

	// Produtos
	Route::group
	(
		['prefix' => 'products'],
		function()
		{
			Route::get ('/'         , 'ProductController@index'  )->name('admin_product'       )->group('admin_product');
			Route::get ('edit/{id?}', 'ProductController@create' )->name('admin_product_edit'  )->group('admin_product');
			Route::post('edit/{id?}', 'ProductController@store'  )->name('admin_product_save'  )->group('admin_product');
			Route::get ('show/{id}' , 'ProductController@show'   )->name('admin_product_show'  )->group('admin_product');
			Route::post('delete/'   , 'ProductController@destroy')->name('admin_product_delete')->group('admin_product');
		}
	);