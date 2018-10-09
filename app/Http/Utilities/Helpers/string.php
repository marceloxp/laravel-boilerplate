<?php

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
			$str_final = $this->right($p_word, strlen($pl));
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
			if (strcasecmp($this->right($p_word, 1), 's') === 0)
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