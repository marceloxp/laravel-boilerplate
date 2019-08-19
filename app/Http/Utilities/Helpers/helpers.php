<?php
if (!function_exists('app_version'))
{
	function app_version($default = '0.0.1')
	{
		return config('app.version', $default);
	}
}

if (!function_exists('get_user_agent'))
{
	function get_user_agent()
	{
		return request()->server('HTTP_USER_AGENT');
	}

	function user_agent_is_whatsapp()
	{
		return (strpos(request()->server('HTTP_USER_AGENT'), 'WhatsApp') !== false);
	}
}

if (!function_exists('get_env'))
{
	function get_env()
	{
		return env('APP_ENV', 'local');
	}

	function env_is_local()
	{
		return (get_env() == 'local');
	}

	function env_is_stage()
	{
		return (get_env() == 'stage');
	}

	function env_is_production()
	{
		return (get_env() == 'production');
	}

	function env_is_stage_or_production()
	{
		return (env_is_stage() or env_is_production());
	}
}

if (!function_exists('vasset'))
{
	function vasset($p_asset)
	{
		$glue = (strpos($p_asset, '?') === false) ? '?' : '&';
		return sprintf('%s%sv=%s', asset($p_asset), $glue, app_version());
	}
}

if (!function_exists('javascript'))
{
	function javascript($p_source)
	{
		if (file_exists(public_path($p_source)))
		{
			return new \Illuminate\Support\HtmlString( sprintf('<script type="text/javascript" src="%s"></script>', vasset($p_source)) );
		}
		
		return new \Illuminate\Support\HtmlString(sprintf('<!-- %s -->', $p_source));
	}
}

if (!function_exists('javascript_routine'))
{
	function javascript_routine($p_routine)
	{
		return '<script>' . $p_routine . '</script>';
	}
}

if (!function_exists('javascript_var'))
{
	function javascript_var($p_var_name, $p_var_value, $p_quote_var = true)
	{
		$result = '<script>';
		$result .= 'var ' . $p_var_name . ' = ';
		if ($p_quote_var)
		{
			$result .= '"';
		}
		$result .= $p_var_value;
		if ($p_quote_var)
		{
			$result .= '"';
		}
		$result .= ';';
		$result .= '</script>';
		return $result;
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

if (!function_exists('img_background'))
{
	function img_background($p_source, $p_background, $p_properties = '', $p_style_properties = '')
	{
		$result = sprintf
		(
			'<img src="%s" style="background-image: url(%s); %s" %s>',
			vasset($p_source),
			vasset($p_background),
			$p_style_properties,
			$p_properties
		);
		return new \Illuminate\Support\HtmlString($result);
	}
}