<?php
if (!function_exists('html_purifier'))
{
	function html_purifier($p_html)
	{
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8');
		$purifier = new HTMLPurifier($config);
		$result = $purifier->purify($p_html);
		return $result;
	}

	function html_data($p_array)
	{
		$result = [];
		foreach ($p_array as $name => $value)
		{
			$result[] = sprintf(' data-%s="%s" ', $name, $value);
		}
		return implode(' ', $result);
	}
}