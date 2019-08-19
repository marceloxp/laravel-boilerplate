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

if (!function_exists('uploaded_file_url'))
{
	function uploaded_file_url($p_file_name)
	{
		$disk_name = get_disk_name($p_file_name);
		return \Illuminate\Support\Facades\Storage::disk($disk_name)->url($p_file_name);
	}
}

if (!function_exists('img_uploaded_file'))
{
	function img_uploaded_file($p_file_name, $p_attr = '')
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
				return sprintf('<img src="%s?v=%s" %s >', uploaded_file_url($p_file_name), app_version('0.0.1'), $p_attr);
			break;
			case 'pdf':
				return sprintf('<img src="%s?v=%s" %s >', vasset('/images/admin/fileextensions/pdf.png'), app_version('0.0.1'), $p_attr);
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
					'<a href="%s" target="_blank"><img src="%s?v=%s" %s ></a>',
					uploaded_file_url($p_file_name),
					uploaded_file_url($p_file_name),
					app_version('0.0.1'),
					$p_attr
				);
			break;
			case 'pdf':
				return sprintf
				(
					'<a href="%s" target="_blank"><img src="%s" %s ></a>',
					uploaded_file_url($p_file_name),
					vasset('/images/admin/fileextensions/pdf.png'),
					$p_attr
			);
			break;
		}
	}
}

if (!function_exists('file_to_array'))
{
	function file_to_array($p_file_name)
	{
		return explode(PHP_EOL, file_get_contents($p_file_name));
	}
}

if (!function_exists('array_to_file'))
{
	function array_to_file($p_array, $p_file_name)
	{
		file_put_contents($p_file_name, implode(PHP_EOL, $p_array));
		return true;
	}
}

if (!function_exists('trim_file'))
{
	function trim_file($p_file_name)
	{
		$body = file_get_contents($p_file_name);
		$array = explode(PHP_EOL, $body);
		$k = 0;
		$cuts = true;
		$last = $array[count($array)-1];
		$last = str_replace(chr(9), '', $last);
		$cuts = (empty($last));
		while($cuts)
		{
			array_pop($array);
			$k++;
			if ($k > 1000)
			{
				throw new Exception('Stack Overflow!');
			}
			$last = $array[count($array)-1];
			$last = str_replace(chr(9), '', $last);
			$cuts = (empty($last));
		}
		$body = implode(PHP_EOL, $array);
		file_put_contents($p_file_name, $body);
	}
}

if (!function_exists('ZipFolder'))
{
	function ZipFolder($source, $destination)
	{
		if (!extension_loaded('zip') || !file_exists($source)) {
			return false;
		}

		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}

		$source = str_replace('\\', '/', realpath($source));

		if (is_dir($source) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file)
			{
				$file = str_replace('\\', '/', $file);

				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;

				$file = realpath($file);

				if (is_dir($file) === true)
				{
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true)
				{
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		else if (is_file($source) === true)
		{
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}
}