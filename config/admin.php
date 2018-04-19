<?php
return [
	'logo' => 'logo.png',
	// 'dashboard' =>
	// [
	// 	[
	// 		'roles'   => ['Master'],
	// 		'caption' => 'Concessionárias',
	// 		'color'   => 'bg-green',
	// 		'ico'     => 'fa-car',
	// 		'link'    => 'dealers',
	// 		'table'   => 'dealers'
	// 	],
	// 	[
	// 		'caption' => 'Cadastros',
	// 		'color'   => 'bg-red',
	// 		'ico'     => 'fa-user',
	// 		'link'    => 'leads',
	// 		'table'   => 'leads'
	// 	],
	// 	[
	// 		'caption' => 'Sites',
	// 		'color'   => 'bg-orange',
	// 		'ico'     => 'fa-globe',
	// 		'link'    => 'sites',
	// 		'table'   => 'sites'
	// 	]
	// ],
	'menu' =>
	[
		'paginas' =>
		[
			'type'    => 'header',
			'caption' => 'Páginas',
			'ico'     => 'fa-book',
			'items'   => 
			[
				[
					'type'    => 'link',
					'caption' => 'Dashboard',
					'ico'     => 'fa-dashboard',
					'route'   => 'dashboard',
					'link'    => 'dashboard'
				]
			]
		],
		// 'tables' =>
		// [
		// 	'type'    => 'header',
		// 	'caption' => 'Tabelas',
		// 	'ico'     => 'fa-table',
		// 	'items'   => 
		// 	[
		// 		[
		// 			'type'    => 'link',
		// 			'caption' => 'Cadastros',
		// 			'ico'     => 'fa-user',
		// 			'route'   => 'leads',
		// 			'link'    => 'leads'
		// 		],
		// 		[
		// 			'type'    => 'link',
		// 			'caption' => 'Sites',
		// 			'ico'     => 'fa-globe',
		// 			'route'   => 'sites',
		// 			'link'    => 'sites'
		// 		],
		// 		[
		// 			'roles'   => ['Master'],
		// 			'type'    => 'link',
		// 			'caption' => 'Concessionárias',
		// 			'ico'     => 'fa-car',
		// 			'route'   => 'dealers',
		// 			'link'    => 'dealers'
		// 		]
		// 	]
		// ],
		'cache' =>
		[
			'roles'   => ['Master','Developer'],
			'type'    => 'header',
			'caption' => 'Cache',
			'ico'     => 'fa-rocket',
			'items'   => 
			[
				[
					'roles'   => ['Developer'],
					'type'    => 'link',
					'caption' => 'Listar',
					'ico'     => 'fa-list',
					'route'   => 'list_cache',
					'link'    => 'list_cache'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Limpar',
					'ico'     => 'fa-trash',
					'route'   => 'clear_cache',
					'link'    => 'clear_cache'
				]
			]
		],
		'sistema' =>
		[
			'type'    => 'header',
			'caption' => 'Sistema',
			'ico'     => 'fa-gears',
			'items'   => 
			[
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Configurações',
					'ico'     => 'fa-gear',
					'route'   => 'configs',
					'link'    => 'configs'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Permissões',
					'ico'     => 'fa-gear',
					'route'   => 'roles',
					'link'    => 'roles'
				],
				[
					'roles'   => ['Developer'],
					'type'    => 'link',
					'caption' => 'phpinfo',
					'ico'     => 'fa-info-circle',
					'route'   => 'phpinfo',
					'link'    => 'phpinfo'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Usuários',
					'ico'     => 'fa-user',
					'route'   => 'users',
					'link'    => 'users'
				],
				[
					'type'    => 'link',
					'caption' => 'Sair',
					'ico'     => 'fa-close',
					'route'   => 'logout',
					'link'    => 'logout'
				]
			]
		]
	]
];
