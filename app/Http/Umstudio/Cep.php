<?php

namespace App\Http\Umstudio;
use App\Http\Umstudio\HttpCurl;
use App\Http\Umstudio\Result;

class Cep
{
	public static function get($p_cep)
	{
		try
		{
			$cep = str_replace('-', '', $p_cep);
			$cep = str_pad($cep, 8, '0', STR_PAD_LEFT);

			return Cached::get
			(
				'App\Http\Umstudio\Cep',
				['get', $p_cep],
				function() use ($cep)
				{
					$result = self::getcep($cep);
					return $result;
				}
			);
		}
		catch (\Exception $e)
		{
			return Result::exception($e);
		}
    }

	private static function getcep($cep)
	{
		$value = rand();
		if ($value % 2 === 0)
		{
			$result = self::viacep($cep);
			if (!$result)
			{
				$result = self::postmon($cep);
			}
		}
		else
		{
			$result = self::postmon($cep);
			if (!$result)
			{
				$result = self::viacep($cep);
			}
		}

		return $result;
	}

	public static function viacep($p_cep)
	{
		$data = HttpCurl::json(sprintf('https://viacep.com.br/ws/%s/json/', $p_cep));
		if (!$data) { return false; }
		$json_cep = json_decode($data, true);

		$result = 
		[
			'service'     => 'viacep',
			'cep'         => $json_cep['cep'],
			'logradouro'  => $json_cep['logradouro'],
			'complemento' => $json_cep['complemento'],
			'bairro'      => $json_cep['bairro'],
			'localidade'  => $json_cep['localidade'],
			'uf'          => $json_cep['uf'],
			'ibge'        => $json_cep['ibge']
		];

		return $result;
	}

	public static function postmon($p_cep)
	{
		$data = HttpCurl::json(sprintf('http://api.postmon.com.br/v1/cep/%s', $p_cep));
		if (!$data) { return false; }
		$json_cep = json_decode($data, true);

		$result = 
		[
			'service'     => 'postmon',
			'cep'         => $json_cep['cep'],
			'logradouro'  => $json_cep['logradouro'],
			'complemento' => '',
			'bairro'      => $json_cep['bairro'],
			'localidade'  => $json_cep['cidade'],
			'uf'          => $json_cep['estado'],
			'ibge'        => $json_cep['cidade_info']['codigo_ibge']
		];

		return $result;
	}
}