<?php

namespace App\Http\Umstudio;

class MetaSocial
{
	private static $custom_config = [];

	private static function getTemplate()
	{
		return '
			<title>{:title}</title>
			<meta name="title" content="{:title}">
			<meta name="description" content="{:description}">
			<meta name="keywords" content="{:keywords}">

			<!-- FACEBOOK -->
			<meta property="og:title" content="{:title}">
			<meta property="og:image" content="{:image}">
			<meta property="og:type" content="{:type}">
			<meta property="og:url" content="{:url}">
			<meta property="og:description" content="{:description}">

			<!-- GOOGLE PLUS -->
			<meta itemprop="name" content="{:title}">
			<meta itemprop="image" content="{:image}">
			<meta itemprop="url" content="{:url}">
			<meta itemprop="description" content="{:description}">

			<!-- TWITTER -->
			<meta name="twitter:title" content="{:title}">
			<meta name="twitter:image:src" content="{:image}">
			<meta name="twitter:site" content="{:url}">
			<meta name="twitter:description" content="{:description}">
		';
	}

	private static function get($key)
	{
		$configs = array_merge(self::$custom_config, config('metasocial'));
		return $configs[$key] ?? '';
	}

	public static function append($key, $value)
	{
		$config_value = self::get($key);
		$value = trim($config_value . $value, ' -');

		self::$custom_config[$key] = $value;
	}

	public static function set($key, $value = null)
	{
		if (is_array($key))
		{
			self::$custom_config = array_merge(self::$custom_config, $key);
			return;
		}

		self::$custom_config[$key] = $value;
	}

	public static function build()
	{
		$result = self::getTemplate();
		$configs = array_merge(config('metasocial'), self::$custom_config);

		if (!array_key_exists('type', $configs)) { $configs['type'] = 'website'; }
		if (!array_key_exists('url' , $configs)) { $configs['url']  = ''       ; }

		foreach ($configs as $key => $value)
		{
			switch ($key)
			{
				case 'url':
					if (empty($value))
					{
						$value = env('APP_URL', '');
					}
				break;
				case 'image':
					if (!empty($value))
					{
						if (strpos($value, 'http') === false)
						{
							$value = vasset($value);
						}

						$size = false;
						try
						{
							$size = getimagesize($value);
							if (!$size)
							{
								$value = '';
							}
							else
							{
								$result .= PHP_EOL;
								$result .= '			<!-- IMAGE SIZE -->' . PHP_EOL;
								$result .= sprintf('			<meta property="og:image:width"  content="%s">' . PHP_EOL, $size[0]);
								$result .= sprintf('			<meta property="og:image:height" content="%s">' . PHP_EOL, $size[1]);
							}
						}
						catch (\Exception $e)
						{
							$value = '';
						}
					}
				break;
			}

			$result = str_replace('{:' . $key . '}', $value, $result);
		}

		return $result;
	}

	public static function print()
	{
		echo self::build();
	}
}