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
			'link'    => 'admin_video',
			'model'   => 'Video'
		],
		[
			'roles'   => ['Master'],
			'caption' => 'Clientes',
			'color'   => 'bg-purple',
			'ico'     => 'fa-users',
			'link'    => 'admin_customer',
			'model'   => 'Customer'
		],
		[
			'roles'   => ['Master'],
			'caption' => 'Contatos',
			'color'   => 'bg-blue',
			'ico'     => 'fa-envelope',
			'link'    => 'admin_contact',
			'model'   => 'Contact'
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
					'group'   => 'admin_category',
					'route'   => 'admin_category'
				],
				[
					'type'    => 'link',
					'caption' => 'Vídeos',
					'ico'     => 'fa-youtube',
					'group'   => 'admin_video',
					'route'   => 'admin_video'
				],
				[
					'type'    => 'link',
					'caption' => 'Tags',
					'ico'     => 'fa-tags',
					'group'   => 'admin_tag',
					'route'   => 'admin_tag'
				],
				[
					'type'    => 'link',
					'caption' => 'Contatos',
					'ico'     => 'fa-envelope',
					'group'   => 'admin_contact',
					'route'   => 'admin_contact'
				],
				[
					'type'    => 'link',
					'caption' => 'Tipos de Endereços',
					'ico'     => 'fa-users',
					'group'   => 'admin_address_type',
					'route'   => 'admin_address_type'
				],
				[
					'type'    => 'link',
					'caption' => 'Clientes',
					'ico'     => 'fa-users',
					'group'   => 'admin_customer',
					'route'   => 'admin_customer'
				],
				[
					'type'    => 'link',
					'caption' => 'Tipos de Pagamentos',
					'ico'     => 'fa-credit-card',
					'group'   => 'admin_paymenttype',
					'route'   => 'admin_paymenttype'
				],
				[
					'type'    => 'link',
					'caption' => 'Pagamentos',
					'ico'     => 'fa-money',
					'group'   => 'admin_payment',
					'route'   => 'admin_payment'
				],
				[
					'type'    => 'link',
					'caption' => 'Produtos',
					'ico'     => 'fa-cube',
					'group'   => 'admin_product',
					'route'   => 'admin_product'
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
		'developer' =>
		[
			'roles'   => ['Developer'],
			'type'    => 'header',
			'caption' => 'Developer',
			'ico'     => 'fa-terminal',
			'items'   => 
			[
				[
					'roles'   => ['Developer'],
					'type'    => 'link',
					'caption' => 'phpinfo',
					'ico'     => 'fa-info-circle',
					'route'   => 'admin_phpinfo'
				],
				[
					'roles'   => ['Developer'],
					'type'    => 'internal-link',
					'caption' => 'Adminer',
					'ico'     => 'fa-database',
					'link'    => 'adminer',
					'target'  => '_blank'
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
					'caption' => 'Galeria',
					'ico'     => 'fa-picture-o',
					'group'   => 'admin_gallery',
					'route'   => 'admin_gallery'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Configurações',
					'ico'     => 'fa-gear',
					'group'   => 'admin_config',
					'route'   => 'admin_config'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Permissões',
					'ico'     => 'fa-unlock-alt',
					'group'   => 'admin_role',
					'route'   => 'admin_role'
				],
				[
					'roles'   => ['Master','Developer'],
					'type'    => 'link',
					'caption' => 'Usuários',
					'ico'     => 'fa-user',
					'group'   => 'admin_user',
					'route'   => 'admin_user'
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
