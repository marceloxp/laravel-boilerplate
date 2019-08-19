<?php

if (!function_exists('str_random'))
{
	function str_random($quant = 10)
	{
		return Illuminate\Support\Str::random($quant);
	}
}

if (!function_exists('delete_all_between'))
{
	function delete_all_between($beginning, $end, $string)
	{
		$beginningPos = strpos($string, $beginning);
		$endPos = strpos($string, $end, $beginningPos);
		if ($beginningPos === false || $endPos === false) { return $string; }
		$textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
		return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string));
	}
}

if (!function_exists('str_right'))
{
	function str_right($p_string, $p_count)
	{
		return substr($p_string, ($p_count * -1));
	}
}

if (!function_exists('str_slugify'))
{
	function str_slugify($p_string, $p_separator = '-')
	{
		return Illuminate\Support\Str::slug($p_string, $p_separator);
	}
}

if (!function_exists('str_camel'))
{
	function str_camel($p_string, $p_separator = '-')
	{
		return \Illuminate\Support\Str::camel($p_string);
	}
}

if (!function_exists('array_sort_ex'))
{
	function array_sort_ex($p_array, $p_reindex_keys = false)
	{
		if (!$p_reindex_keys)
		{
			return Illuminate\Support\Arr::sort($p_array);
		}

		return array_merge(Illuminate\Support\Arr::sort($p_array));
	}
}

if (!function_exists('str_to_singular'))
{
	function str_to_singular($p_arg)
	{
		if (is_string($p_arg))
		{
			return \Illuminate\Support\Str::singular($p_arg);
		}

		if (is_array($p_arg))
		{
			return collect($p_arg)->transform(function ($item, $key) { return \Illuminate\Support\Str::singular($item); })->toArray();
		}
	}
}

if (!function_exists('str_to_lower'))
{
	function str_to_lower($p_arg)
	{
		if (is_string($p_arg))
		{
			return mb_strtolower($p_arg);
		}

		if (is_array($p_arg))
		{
			return collect($p_arg)->transform(function ($item, $key) { return mb_strtolower($item); })->toArray();
		}
	}
}

if (!function_exists('str_mask'))
{
	function str_mask($text, $mask)
	{
		$xtext = preg_replace("/[^0-9]/","", $text);
		$maskared = '';
		$k = 0;
		for ($i = 0; $i <= strlen($mask)-1; $i++)
		{
			if ($mask[$i] == '#')
			{
				if(isset($xtext[$k]))
				{
					$maskared .= $xtext[$k++];
				}
			}
			else
			{
				if (isset($mask[$i]))
				{
					$maskared .= $mask[$i];
				}
			}
		}
		return $maskared;
	}
}

if (!function_exists('str_plural_2_singular'))
{
	function str_plural_2_singular($p_word)
	{
		$located = false;
		$regras = array
		(
			'eses' => 'es', 
			'éres' => 'er', 
			'ôres' => 'or', 
			'ões'  => 'ão', 
			'ãos'  => 'ão', 
			'res'  => 'r', 
			'zes'  => 'z', 
			'ais'  => 'al', 
			'ens'  => 'em', 
			'is'   => 'il', 
			's'    => '',
		);

		$result = $p_word;
		foreach ($regras as $pl => $si)
		{
			$str_final = str_right($p_word, strlen($pl));
			if (mb_strtolower($str_final) == mb_strtolower($pl))
			{
				$suffix = ($str_final == mb_strtoupper($pl)) ? mb_strtoupper($si) : $si;
				$result = substr($p_word, 0, (strlen($p_word) - strlen($pl))) . $suffix;
				$located = true;
				break;
			}
		}
		if (!$located)
		{
			if (strcasecmp(str_right($p_word, 1), 's') === 0)
			{
				$result = rtrim($p_word, 's', 'S');
			}
		}
		return $result;
	}
}

if (!function_exists('str2bool'))
{
	function str2bool($text)
	{
		return (strtolower($text) == 'true');
	}
}

if (!function_exists('str_only_numbers'))
{
	function str_only_numbers($text)
	{
		return preg_replace('/[^0-9]/','',$text);
	}
}

if (!function_exists('str_to_formatted_cep'))
{
	function str_to_formatted_cep($cep)
	{
		$result = str_only_numbers($cep);
		$result = str_pad($result, 8, '0', STR_PAD_LEFT);
		$result = sprintf('%s-%s', substr($result, 0, 5), substr($result, -3));
		return $result;
	}
}