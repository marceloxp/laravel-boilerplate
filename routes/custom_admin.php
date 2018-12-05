<?php
	// Vídeos
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

	// Tags
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

	// Tags dos Vídeos
	Route::group
	(
		['prefix' => 'tag_video'],
		function()
		{
			Route::get ('{video_id}'              , 'TagVideoController@index' )->name('admin_tag_video'       )->group('admin_tag_video');
			Route::post('{video_id}/attach'       , 'TagVideoController@store' )->name('admin_tag_video_attach')->group('admin_tag_video');
			Route::get ('{video_id}/show/{tag_id}', 'TagsController@pivot_show')->name('admin_videos_show'     )->group('admin_tag');
			Route::post('{video_id}/detach'       , 'TagVideoController@detach')->name('admin_tag_video_detach')->group('admin_tag');
		}
	);

	// Customers
	Route::group
	(
		['prefix' => 'customers'],
		function()
		{
			Route::get ('/'         , 'CustomerController@index'  )->name('admin_customer'       )->group('admin_customer');
			Route::get ('edit/{id?}', 'CustomerController@create' )->name('admin_customer_edit'  )->group('admin_customer');
			Route::post('edit/{id?}', 'CustomerController@store'  )->name('admin_customer_save'  )->group('admin_customer');
			Route::get ('show/{id}' , 'CustomerController@show'   )->name('admin_customer_show'  )->group('admin_customer');
			Route::post('delete/'   , 'CustomerController@destroy')->name('admin_customer_delete')->group('admin_customer');
		}
	);

	// Address Types
	Route::group
	(
		['prefix' => 'address_types'],
		function()
		{
			Route::get ('/'         , 'AddressTypeController@index'  )->name('admin_address_type'       )->group('admin_address_type');
			Route::get ('edit/{id?}', 'AddressTypeController@create' )->name('admin_address_type_edit'  )->group('admin_address_type');
			Route::post('edit/{id?}', 'AddressTypeController@store'  )->name('admin_address_type_save'  )->group('admin_address_type');
			Route::get ('show/{id}' , 'AddressTypeController@show'   )->name('admin_address_type_show'  )->group('admin_address_type');
			Route::post('delete/'   , 'AddressTypeController@destroy')->name('admin_address_type_delete')->group('admin_address_type');
		}
	);

	// Seções
	Route::group
	(
		['prefix' => 'sections'],
		function()
		{
			Route::get ('/'         , 'SectionController@index'  )->name('admin_menusection'       )->group('admin_menusection');
			Route::get ('edit/{id?}', 'SectionController@create' )->name('admin_menusection_edit'  )->group('admin_menusection');
			Route::post('edit/{id?}', 'SectionController@store'  )->name('admin_menusection_save'  )->group('admin_menusection');
			Route::get ('show/{id}' , 'SectionController@show'   )->name('admin_menusection_show'  )->group('admin_menusection');
			Route::post('delete/'   , 'SectionController@destroy')->name('admin_menusection_delete')->group('admin_menusection');
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
			Route::get ('{menusection_id}/show/{role_id}', 'SectionController@pivot_show'    )->name('admin_sections_show'          )->group('admin_role');
			Route::post('{menusection_id}/detach'        , 'MenusectionRoleController@detach')->name('admin_menusection_role_detach')->group('admin_role');
		}
	);

	// Sub Categorias
	Route::group
	(
		['prefix' => 'categories/{category_id}/subcategory'],
		function()
		{
			Route::get ('/'         , 'SubcategoryController@index'  )->name('admin_subcategoy'       )->group('admin_subcategory');
			Route::get ('edit/{id?}', 'SubcategoryController@create' )->name('admin_subcategoy_edit'  )->group('admin_subcategory');
			Route::post('edit/{id?}', 'SubcategoryController@store'  )->name('admin_subcategoy_save'  )->group('admin_subcategory');
			Route::get ('show/{id}' , 'SubcategoryController@show'   )->name('admin_subcategoy_show'  )->group('admin_subcategory');
			Route::post('delete/'   , 'SubcategoryController@destroy')->name('admin_subcategoy_delete')->group('admin_subcategory');
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

	// Pagamentos
	Route::group
	(
		['prefix' => 'payments'],
		function()
		{
			Route::get ('/'         , 'PaymentController@index'  )->name('admin_payment'       )->group('admin_payment');
			Route::get ('edit/{id?}', 'PaymentController@create' )->name('admin_payment_edit'  )->group('admin_payment');
			Route::post('edit/{id?}', 'PaymentController@store'  )->name('admin_payment_save'  )->group('admin_payment');
			Route::get ('show/{id}' , 'PaymentController@show'   )->name('admin_payment_show'  )->group('admin_payment');
			Route::post('delete/'   , 'PaymentController@destroy')->name('admin_payment_delete')->group('admin_payment');
		}
	);

	// Tipos de Pagamentos
	Route::group
	(
		['prefix' => 'paymenttype'],
		function()
		{
			Route::get ('/'         , 'PaymenttypeController@index'  )->name('admin_paymenttype'       )->group('admin_paymenttype');
			Route::get ('edit/{id?}', 'PaymenttypeController@create' )->name('admin_paymenttype_edit'  )->group('admin_paymenttype');
			Route::post('edit/{id?}', 'PaymenttypeController@store'  )->name('admin_paymenttype_save'  )->group('admin_paymenttype');
			Route::get ('show/{id}' , 'PaymenttypeController@show'   )->name('admin_paymenttype_show'  )->group('admin_paymenttype');
			Route::post('delete/'   , 'PaymenttypeController@destroy')->name('admin_paymenttype_delete')->group('admin_paymenttype');
		}
	);

	// Contatos
	Route::group
	(
		['prefix' => 'contact'],
		function()
		{
			Route::get ('/'         , 'ContactController@index'  )->name('admin_contact'       )->group('admin_contact');
			Route::get ('edit/{id?}', 'ContactController@create' )->name('admin_contact_edit'  )->group('admin_contact');
			Route::post('edit/{id?}', 'ContactController@store'  )->name('admin_contact_save'  )->group('admin_contact');
			Route::get ('show/{id}' , 'ContactController@show'   )->name('admin_contact_show'  )->group('admin_contact');
			Route::post('delete/'   , 'ContactController@destroy')->name('admin_contact_delete')->group('admin_contact');
		}
	);