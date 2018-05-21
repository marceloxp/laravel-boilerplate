<?php
if (!function_exists('ddd'))
{
	function ddd($object)
	{
		dump($object);
		die;
	}
}

if (!function_exists('isRedirect'))
{
	function isRedirect($object)
	{
		if (gettype($object) != 'object')
		{
			return false;
		}

		$class_name = (new \ReflectionClass($object))->getShortName();

		return ($class_name == 'RedirectResponse');
	}
}