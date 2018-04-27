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
			'table'   => 'videos'
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
					'route'   => 'admin_dashboard',
					'link'    => 'admin_dashboard'
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
					'caption' => 'Vídeos',
					'ico'     => 'fa-youtube',
					'route'   => 'admin_videos',
					'link'    => 'admin_videos'
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
					'link'    => 'admin_cache_list'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Configurar',
					'ico'     => 'fa-gears',
					'route'   => 'admin_cache_index',
					'link'    => 'admin_cache_index'
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
					'route'   => 'admin_configs',
					'link'    => 'admin_configs'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Permissões',
					'ico'     => 'fa-unlock-alt',
					'route'   => 'admin_roles',
					'link'    => 'admin_roles'
				],
				[
					'roles'   => ['Developer'],
					'type'    => 'link',
					'caption' => 'phpinfo',
					'ico'     => 'fa-info-circle',
					'route'   => 'admin_phpinfo',
					'link'    => 'admin_phpinfo'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Usuários',
					'ico'     => 'fa-user',
					'route'   => 'admin_users',
					'link'    => 'admin_users'
				],
				[
					'type'    => 'link',
					'target'  => '_blank',
					'caption' => 'Ir ao Site',
					'ico'     => 'fa-home',
					'route'   => 'home',
					'link'    => 'home'
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
