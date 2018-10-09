<?php

namespace App\Http\Utilities;

class MetaSocial
{
	private static $config_use = 'default';
	private static $custom_config = [];

	private static function getTemplate()
	{
		return '
			<title>{:title}</title>
			<meta name="title" content="{:title}">
			<meta name="description" content="{:description}">
			<meta name="keywords" content="{:keywords}">
			<meta name="theme-color" content="{:theme_color}">

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
			<meta name="twitter:card" content="{:twitter_card}">
			<meta name="twitter:title" content="{:title}">
			<meta name="twitter:creator" content="{:twitter_creator}">
			<meta name="twitter:image:src" content="{:image}">
			<meta name="twitter:site" content="{:url}">
			<meta name="twitter:description" content="{:description}">
		';
	}

	public static function use($p_config_use)
	{
		self::$config_use = $p_config_use;
	}

	public static function getFinalConfig()
	{
		$locale = config('app.locale', 'pt-br');
		$result = array_merge
		(
			config(sprintf('metasocial.%s.default', $locale)) ?? [],
			config(sprintf('metasocial.pt-br.default', $locale)) ?? [],
			config(sprintf('metasocial.%s.%s', $locale, self::$config_use)) ?? [],
			self::$custom_config
		);
		return $result;
	}

	public static function get($key)
	{
		$configs = self::getFinalConfig();
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
		$configs = self::getFinalConfig();

		if (!array_key_exists('type'            , $configs)) { $configs['type']                 = 'website'       ; }
		if (!array_key_exists('url'             , $configs)) { $configs['url']                  = url()->current(); }
		if (!array_key_exists('twitter_card'    , $configs)) { $configs['summary_large_image']  = ''              ; }
		if (!array_key_exists('twitter_creator' , $configs)) { $configs['twitter_creator']      = ''              ; }
		
		if (array_key_exists('facebook_id', $configs))
		{
			if (!empty($configs['facebook_id']))
			{
				$result .= PHP_EOL;
				$result .= '			<!-- FACEBOOK APP -->' . PHP_EOL;
				$result .= sprintf('			<meta property="fb:app_id" content="%s">' . PHP_EOL, $configs['facebook_id']);
			}
		}

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
								$result .= sprintf('			<meta property="og:image:type" content="%s">' . PHP_EOL, $size['mime']);
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