<?php
if (!function_exists('hook_name'))
{
	function hook_name($name)
	{
		$name = str_replace('<br>', '_', $name);
		$name = str_replace('<br/>', '_', $name);
		$name = strtolower(strip_tags($name));
		$name = str_replace(' ', '_', $name);
		$name = str_slugify($name, '_');

		if (config('debugbar.enabled'))
		{
			if (config('debugbar.collectors.hooks'))
			{
				if (\Debugbar::hasCollector('Hooks') === false)
				{
					\Debugbar::addCollector(new \DebugBar\DataCollector\MessagesCollector('Hooks'));
				}
				Debugbar::getCollector('Hooks')->info($name);
			}
		}

		if (config('hook.print', false))
		{
			App\Http\Utilities\HookPrint::add($name);
		}

		return $name;
	}
}