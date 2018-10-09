<?php

if (!function_exists('dic'))
{
    function dic($p_string)
	{
		$slug           = 'dic.' . $p_string;
		$current_locale = config('app.current_locale', 'pt-br');
		$has            = Lang::has($slug, $current_locale);
		$result         = ($has) ? __($slug) : $p_string;
		return $result;
    }
}

if (!function_exists('lang_home_link'))
{
    function lang_home_link($p_lang = null)
	{
		return url(\App\Http\Utilities\RouteLang::rootUrl($p_lang));
    }
}