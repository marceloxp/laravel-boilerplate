<?php

namespace App\Http\Utilities;

class RouteLang
{
	public static function getDefaultLocale()
	{
		return config('app.default_locale', 'pt-br');
	}

	public static function getCurrentLocale()
	{
		return config('app.current_locale', 'pt-br');
	}

	public static function lang($p_lang = null)
	{
		$result = $p_lang ?? self::getCurrentLocale();
		if ($result == self::getDefaultLocale())
		{
			$result = '';
		}

		return $result;
    }

	public static function rootUrl($p_lang = null)
	{
		return '/' . self::lang($p_lang);
	}

    public static function prefix($p_url)
	{
		$result = __('routes.' . $p_url);
		$result = str_replace('routes.', '', $result);
		return $result;
    }

    public static function root()
	{
		return __('/');
    }

    public static function route($route, $p_route)
	{
		$prefix = trim($route->getLastGroupPrefix(), '/');

		if ( (!empty($prefix)) && ($p_route == '/') )
		{
			return '/';
		}

		$result = __('routes.' . $p_route);
		$result = str_replace('routes.', '', $result);
		return $result;
    }
}