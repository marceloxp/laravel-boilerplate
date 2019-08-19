<?php

namespace App\Http\Utilities;

use Illuminate\Support\Facades\Route;

class AutoAssets
{
	public static function print($p_type)
	{
		$auto_assets = [];
		$available_assets = [];
		$routename = Route::currentRouteName();
		if (!empty($routename))
		{
			if (strpos($routename, 'admin_') === false)
			{
				$asset_file     = sprintf('%s/%s.%s', $p_type, $routename, $p_type);
				$asset_file_min = sprintf('%s/%s.min.%s', $p_type, $routename, $p_type);
			}
			else
			{
				$asset_file     = sprintf('%s/admin/%s.%s'    , $p_type, $routename, $p_type);
				$asset_file_min = $asset_file;
			}

			if ((!env_is_local()) && file_exists(public_path($asset_file_min)))
			{
				$auto_assets[] = $asset_file_min;
			}
			elseif (file_exists(public_path($asset_file)))
			{
				$auto_assets[] = $asset_file;
			}
			else
			{
				$available_assets[] = $asset_file;
			}
		}

		foreach ($auto_assets as $asset)
		{
			echo ($p_type == 'js') ? javascript($asset) : css($asset) . PHP_EOL;
		}

		foreach ($available_assets as $asset)
		{
			echo sprintf('<!-- %s -->', $asset) . PHP_EOL;
		}
    }
}