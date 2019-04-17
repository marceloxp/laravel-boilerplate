<?php

namespace App\Http\Utilities;

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

	public static function cached($p_prefix, $p_cache_name, $message = '', $data = [], $cached, $message_log = '')
	{
		$cache_name = sprintf('%s-%s', $p_prefix, $p_cache_name);
		$result     = self::get(true, $message, $data, $cached, $message_log);
		$result     = array_merge(compact('cache_name'), $result);
		return $result;
	}

	public static function error($message = '', $data = [], $message_log = '')
	{
		return self::get(false, $message, $data, false, $message_log);
	}

	public static function ifthen($boolean_value, $success_message = 'Solicitação realizada com sucesso.', $error_message = 'Ocorreu um erro na solicitação.')
	{
		return ($boolean_value) ? self::success($success_message) : self::error($error_message);
	}

	public static function invalid($data = [])
	{
		return self::error('Entrada de dados inválida.', $data);
	}

	public static function undefined($message = '', $data = [], $message_log = '')
	{
		$use_message = $message;
		if (empty($message))
		{
			$use_message = 'Ocorreu um erro na solicitação.';
		}
		return self::get(false, $use_message, $data, false, $message_log);
	}

	public static function exception($exception)
	{
		return self::get(false, 'Ocorreu um erro na solicitação.', [], false, $exception->getMessage());
	}
}