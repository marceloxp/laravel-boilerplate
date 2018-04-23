<?php

namespace App\Http\Umstudio;

use Illuminate\Support\Facades\Cache;

class Cached
{
	public static function get($p_prefix, $p_key, $value, $minutes = 10)
	{
		$prefix = mb_strtoupper($p_prefix);
		$key    = (is_array($p_key)) ? implode('-', $p_key) : $p_key;
		$key    = mb_strtoupper($key);

		$cache_name = sprintf('%s-%s', $prefix, $key);
		if (Cache::has($cache_name))
		{
			return [
				'header' => ['cached' => 'true'],
				'cached' => true,
				'value'  => Cache::get($cache_name)
			];
		}
		else
		{
			$data = (is_callable($value)) ? $value() : $value;
			Cache::put($cache_name, $data, $minutes);

			$caches = Cache::get('gcache-prefixes') ?? collect([]);

			if (!$caches->has($prefix))
			{
				$caches->put($prefix, collect([]));
			}

			$caches[$prefix]->put($key, $data);

			Cache::forever('gcache-prefixes', $caches);

			return [
				'header' => ['cached' => 'false'],
				'cached' => false,
				'value'  => $data
			];
		}
    }

	public static function list()
	{
		$caches = Cache::get('gcache-prefixes') ?? collect([]);
		return $caches->toArray();
	}

    public static function forget($p_prefix, $p_key = null)
	{
		$prefix = mb_strtoupper($p_prefix);
		$caches = Cache::get('gcache-prefixes') ?? collect([]);

		if ($p_key !== null)
		{
			$key = (is_array($p_key)) ? implode('-', $p_key) : $p_key;
			$key    = mb_strtoupper($key);
			$cache_name = sprintf('%s-%s', $prefix, $key);
			Cache::forget($cache_name);

			if ($caches->has($prefix))
			{
				if ($caches->get($prefix)->has($key))
				{
					$caches[$prefix] = $caches[$prefix]->except([$key]);
				}
			}

			return true;
		}

		if ($caches->has($prefix))
		{
			$caches->get($prefix)->each
			(
				function($item, $key) use ($prefix)
				{
					$cache_name = sprintf('%s-%s', $prefix, $key);
					Cache::forget($cache_name);
				}
			);

			unset($caches[$prefix]);
		}

		return true;
    }

	public static function flush()
	{
		return Cache::flush();
	}
}