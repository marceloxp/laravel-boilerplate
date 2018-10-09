<?php

namespace App\Http\Utilities;

class HttpCurl
{
	public static function json($p_url)
	{
		try
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $p_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);

			$http_code = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
			if ($http_code !== 200)
			{
				curl_close($ch);
				return false;
			}

			curl_close($ch);
			return $result;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}