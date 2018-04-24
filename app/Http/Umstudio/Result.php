<?php

namespace App\Http\Umstudio;

use Illuminate\Support\Facades\Log;

class Result
{
	public static function get($success, $message = '', $data = [], $cached = false, $message_log = '')
	{
		$result =
		[
			'result'  => true,
			'success' => (!empty($success)),
			'cached'  => $cached,
			'message' => $message,
			'data'    => $data
		];

		if (!empty($message_log))
		{
			if ($success)
			{
				Log::info($message_log);
			}
			else
			{
				Log::error($message_log);
			}
		}

		return $result;
    }

	public static function success($message = '', $data = [], $message_log = '')
	{
		return self::get(true, $message, $data, false, $message_log);
	}

	public static function data($data = [], $message_log = '')
	{
		return self::get(true, '', $data, false, $message_log);
	}

	public static function cached($message = '', $data = [], $cached, $message_log = '')
	{
		return self::get(true, $message, $data, $cached, $message_log);
	}

	public static function error($message = '', $data = [], $message_log = '')
	{
		return self::get(false, $message, $data, false, $message_log);
	}

	public static function undefined($message = '', $data = [], $message_log = '')
	{
		$use_message = $message ?? 'Ocorreu um erro na solcitação.';
		return self::get(false, $use_message, $data, false, $message_log);
	}

	public static function exception($exception)
	{
		return self::get(false, 'Ocorreu um erro na solcitação.', [], false, $exception->getMessage());
	}
}