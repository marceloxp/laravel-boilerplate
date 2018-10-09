<?php
return [
	'pt-br' =>
	[
		'default' =>
		[
			'theme_color'     => 'Gray',
			'title'           => env('APP_NAME'),
			'description'     => env('APP_DESCRIPTION'),
			'keywords'        => 'keywords, site',
			'type'            => 'website',
			'image'           => '/images/laravel-logo.png',
			'facebook_id'     => '',
			'twitter_card'    => 'summary_large_image',
			'twitter_creator' => ''
		],
		'sobre' =>
		[
			'theme_color' => 'Orange',
			'title'       => 'Novo Site - Sobre'
		]
	],
	'en' =>
	[
		'default' =>
		[
			'theme_color'     => 'Orange',
			'title'           => 'New Site',
			'description'     => 'Site Description',
		],
		'sobre' =>
		[
			'title' => 'New Site - About'
		]
	],
	'es' =>
	[
		'default' =>
		[
			'theme_color'     => 'Gold',
			'title'           => 'Nuevo sitio',
			'description'     => 'Descripción del sitio',
		],
		'sobre' =>
		[
			'title' => 'Nuevo sitio - En'
		]
	]
];