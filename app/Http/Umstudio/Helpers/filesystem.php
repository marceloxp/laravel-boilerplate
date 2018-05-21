<?php
if (!function_exists('disk_file_exists'))
{
	function disk_file_exists($p_disk_name, $p_file_name)
	{
		return \Illuminate\Support\Facades\Storage::disk($p_disk_name)->exists($p_file_name);
	}
}

if (!function_exists('uploaded_image'))
{
	function uploaded_image($p_file_name)
	{
		return \Illuminate\Support\Facades\Storage::disk('upload_images')->url($p_file_name);
	}
}

if (!function_exists('disk_new_file_name'))
{
	function disk_new_file_name($p_disk_name, $p_file_name)
	{
		if (!disk_file_exists($p_disk_name, $p_file_name))
		{
			return $p_file_name;
		}

		$re = '/(?<=_)\d+(?=\.)/m';
		$str = $p_file_name;
		preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

		$name = \File::name($p_file_name);
		$ext  = \File::extension($p_file_name);

		if (empty($matches))
		{
			return sprintf('%s_1.%s', $name, $ext);
		}
		else
		{
			$number = intval($matches[0][0]);
			$rawname = str_replace('_' . $number, '', $name);
			$number++;
			return sprintf('%s_%s.%s', $rawname, $number, $ext);
		}
	}
}

if (!function_exists('uploaded_img'))
{
	function uploaded_img($p_file_name, $p_attr = '')
	{
		if (empty($p_file_name))
		{
			return '';
		}

		return sprintf('<img src="%s" %s >', uploaded_image($p_file_name), $p_attr);
	}
}