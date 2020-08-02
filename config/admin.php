<?php
return [
	'logo' => 'logo.png',
	'dashboard' =>
	[
		[
			'type'    => 'dashboard',
			'roles'   => ['Master','Admin','Developer'],
			'caption' => 'Vídeos',
			'color'   => 'bg-green',
			'ico'     => 'fab fa-youtube',
			'link'    => 'admin_video',
			'model'   => 'Examples\Video'
		]
	],
	'index' =>
	[
		'pagination' =>
		[
			'perpages' => [10,20,50,100,200,300]
		]
	]
];
