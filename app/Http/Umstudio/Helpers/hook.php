<?php
if (!function_exists('hook_name'))
{
	function hook_name($name)
	{
		if (config('debugbar.enabled'))
		{
			if (config('debugbar.collectors.hooks'))
			{
				if (\Debugbar::hasCollector('Hooks') === false)
				{
					\Debugbar::addCollector(new \DebugBar\DataCollector\MessagesCollector('Hooks'));
				}
				Debugbar::getCollector('Hooks')->addMessage('', $name);
			}
		}

		return $name;
	}
}