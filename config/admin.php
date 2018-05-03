<?php
return [
	'logo' => 'logo.png',
	'dashboard' =>
	[
		[
			'roles'   => ['Master'],
			'caption' => 'Vídeos',
			'color'   => 'bg-green',
			'ico'     => 'fa-video-camera',
			'link'    => 'admin_videos',
			'model'   => 'Video'
		]
	],
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
					'route'   => 'admin_dashboard'
				]
			]
		],
		'tables' =>
		[
			'type'    => 'header',
			'caption' => 'Tabelas',
			'ico'     => 'fa-table',
			'items'   => 
			[
				[
					'type'    => 'link',
					'caption' => 'Categorias',
					'ico'     => 'fa-folder',
					'group'   => 'admin_categories',
					'route'   => 'admin_categories'
				],
				[
					'type'    => 'link',
					'caption' => 'Vídeos',
					'ico'     => 'fa-youtube',
					'group'   => 'admin_videos',
					'route'   => 'admin_videos'
				]
			]
		],
		'cache' =>
		[
			'roles'   => ['Master','Developer'],
			'type'    => 'header',
			'caption' => 'Cache',
			'ico'     => 'fa-rocket',
			'items'   => 
			[
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Listar',
					'ico'     => 'fa-list',
					'route'   => 'admin_cache_list',
					'menu'    => 'admin_cache_list'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Configurar',
					'ico'     => 'fa-gears',
					'route'   => 'admin_cache',
					'menu'    => 'admin_cache_index'
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
					'group'   => 'admin_configs',
					'route'   => 'admin_configs'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Permissões',
					'ico'     => 'fa-unlock-alt',
					'group'   => 'admin_roles',
					'route'   => 'admin_roles'
				],
				[
					'roles'   => ['Developer'],
					'type'    => 'link',
					'caption' => 'phpinfo',
					'ico'     => 'fa-info-circle',
					'route'   => 'admin_phpinfo'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Usuários',
					'ico'     => 'fa-user',
					'group'   => 'admin_users',
					'route'   => 'admin_users'
				],
				[
					'type'    => 'link',
					'target'  => '_blank',
					'caption' => 'Ir ao Site',
					'ico'     => 'fa-home',
					'route'   => 'home'
				],
				[
					'type'    => 'link',
					'caption' => 'Sair',
					'ico'     => 'fa-close',
					'route'   => 'logout'
				]
			]
		]
	]
];
