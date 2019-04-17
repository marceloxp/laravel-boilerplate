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
			'ico'     => 'fa-video-camera',
			'link'    => 'admin_video',
			'model'   => 'Video'
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
