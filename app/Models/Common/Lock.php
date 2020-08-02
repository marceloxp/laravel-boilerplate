<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\CommonModel;
use App\Http\Utilities\Carbex;

class Lock extends CommonModel
{
	protected $dates   = ['created_at','updated_at'];
	protected $guarded = ['created_at','updated_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name' => 'required|max:124',
		];
		return self::_validate($request, $rules, $id);
	}

	private static function __try_lock($name)
	{
		try
		{
			self::create(['name' => $name]);
			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public static function lock($name, $timeout_seconds = 15)
	{
		$last_elapsed = -1;
		$elapsed = 0;
		$now = Carbex::now();
		do
		{
			$locked = self::__try_lock($name);
			if ($locked)
			{
				return true;
			}
			$clock = Carbex::now();
			$elapsed = $clock->diffInSeconds($now);
			usleep(250);
			if (env('APP_DEBUG'))
			{
				if ($last_elapsed != $elapsed)
				{
					$last_elapsed = $elapsed;
				}
			}
		} while ($elapsed < $timeout_seconds);

		return false;
	}

	public static function unlock($name)
	{
		$lock = self::where('name', $name);
		if ($lock)
		{
			$lock->delete();
		}
		return true;
	}

	public static function unlockall()
	{
		try
		{
			self::where('id', '>=', '0')->delete();
			return true;
		}
		catch (Exception $e)
		{
			logger($e->getMessage());
			return false;
		}
	}
}