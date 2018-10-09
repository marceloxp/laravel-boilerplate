<?php
if (!function_exists('cached_headers'))
{
	function cached_headers($result)
	{
		return ['cached' => ($result['cached'] ?? false) ? 'true' : 'false'];
	}
}