<?php
if (!function_exists('ddd'))
{
	function ddd($object)
	{
		dump($object);
		die;
	}
}