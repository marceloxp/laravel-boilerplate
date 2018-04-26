<?php
if (!function_exists('app_version'))
{
    function app_version($default = '0.0.1')
	{
		return config('app.version', $default);
    }
}

if (!function_exists('vasset'))
{
    function vasset($p_asset)
	{
		return sprintf('%s?v=%s', asset($p_asset), app_version());
    }
}

if (!function_exists('script'))
{
    function script($p_source)
	{
		return new \Illuminate\Support\HtmlString( sprintf('<script type="text/javascript" src="%s"></script>', vasset($p_source)) );
    }
}

if (!function_exists('css'))
{
    function css($p_source)
	{
		return new \Illuminate\Support\HtmlString( sprintf('<link rel="stylesheet" type="text/css" href="%s">', vasset($p_source)) );
    }
}

if (!function_exists('img'))
{
    function img($p_source, $p_properties = '')
	{
		return new \Illuminate\Support\HtmlString( sprintf('<img src="%s" %s>', vasset($p_source), $p_properties) );
    }
}