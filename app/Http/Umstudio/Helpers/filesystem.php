<?php
if (!function_exists('disk_file_exists'))
{
	function disk_file_exists($p_disk_name, $p_file_name)
	{
		return \Illuminate\Support\Facades\Storage::disk($p_disk_name)->exists($p_file_name);
	}
}

if (!function_exists('is_image'))
{
	function is_image($p_file_name)
	{
		$extension = \File::extension($p_file_name);

		switch ($extension)
		{
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
				return true;
			break;
		}

		return false;
	}
}

if (!function_exists('get_disk_name'))
{
	function get_disk_name($p_file_name)
	{
		$extension = \File::extension($p_file_name);

		switch ($extension)
		{
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
				return 'upload_images';
			break;
			case 'pdf':
				return 'upload_pdfs';
			break;
		}

		return 'others';
	}
}

if (!function_exists('uploaded_file'))
{
	function uploaded_file($p_file_name)
	{
		$disk_name = get_disk_name($p_file_name);
		return \Illuminate\Support\Facades\Storage::disk($disk_name)->url($p_file_name);
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

if (!function_exists('uploaded_file'))
{
	function uploaded_file($p_file_name, $p_attr = '')
	{
		if (empty($p_file_name))
		{
			return '';
		}

		$info = pathinfo($p_file_name);
		$extension = strtolower($info['extension']);

		switch ($extension)
		{
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
				return sprintf('<img src="%s" %s >', uploaded_file($p_file_name), $p_attr);
			break;
			case 'pdf':
				return sprintf('<img src="%s" %s >', vasset('/images/admin/fileextensions/pdf.png'), $p_attr);
			break;
		}
	}
}

if (!function_exists('link_uploaded_img'))
{
	function link_uploaded_file($p_file_name, $p_attr = '')
	{
		if (empty($p_file_name))
		{
			return '';
		}

		$extension = \File::extension($p_file_name);

		switch ($extension)
		{
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
				return sprintf
				(
					'<a href="%s" target="_blank"><img src="%s" %s ></a>',
					uploaded_file($p_file_name),
					uploaded_file($p_file_name),
					$p_attr
				);
			break;
			case 'pdf':
				return sprintf
				(
					'<a href="%s" target="_blank"><img src="%s" %s ></a>',
					uploaded_file($p_file_name),
					vasset('/images/admin/fileextensions/pdf.png'),
					$p_attr
			);
			break;
		}
	}
}