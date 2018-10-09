<?php

namespace App\Http\Utilities;
use App\Http\Utilities\HttpCurl;
use App\Http\Utilities\Result;

class Cep
{
	public static function valid($p_cep)
	{
		$cep = self::toNumeric($p_cep);
		$faixas = config('cep.faixas');

		foreach ($faixas as $uf => $uf_faixas)
		{
			if (is_array($uf_faixas[0]))
			{
				foreach ($uf_faixas as $faixa)
				{
					if ( ($cep >= $faixa[0]) && ($cep <= $faixa[1]) )
					{
						return true;
					}
				}
			}
			else
			{
				if ( ($cep >= $uf_faixas[0]) && ($cep <= $uf_faixas[1]) )
				{
					return true;
				}
			}
		}

		return false;
	}

	public static function mask($p_cep, $use_dot_separator = false)
	{
		$cep = self::toNumeric($p_cep);
		$cep = sprintf('%08s', $cep);
		$use_mask = ($use_dot_separator) ? '##.###-###' : '#####-###';
		return str_mask($cep, $use_mask);
	}

	public static function toNumeric($p_cep)
	{
		$cep = preg_replace( '/[^0-9]/', '', $p_cep);
		$cep = sprintf('%08s', $cep);
		$cep = intval($cep);
		return $cep;
	}

	public static function get($p_cep)
	{
		try
		{
			if (!self::valid($p_cep))
			{
				$result = 
				[
					'service'     => 'unknow',
					'located'     => false,
					'message'     => 'Este CEP não pertence as faixas de CEP do Brasil.'
				];

				return $result;
			}

			$cep = self::mask($p_cep);
			return Cached::get
			(
				'App\Http\Utilities\Cep',
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
			report($e);
			return Result::exception($e);
		}
    }

	private static function getcep($cep)
	{
		$method = collect(['viacep','postmon'])->random();
		return self::$method($cep);
	}

	public static function viacep($p_cep)
	{
		$data = HttpCurl::json(sprintf('https://viacep.com.br/ws/%s/json/', $p_cep));
		if (!$data) { return false; }
		$json_cep = json_decode($data, true);

		$result = 
		[
			'service'     => 'viacep',
			'located'     => true,
			'message'     => '',
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
			'located'     => true,
			'message'     => '',
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