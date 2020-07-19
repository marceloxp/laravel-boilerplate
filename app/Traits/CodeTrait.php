<?php
namespace App\Traits;

trait CodeTrait
{
	public static function boot()
	{
		parent::boot();

		self::creating
		(
			function($model)
			{
				$model->code = self::getCode();
			}
		);
	}

	private static function getCode()
	{
		$codelength = config('codetrait.length', 10);
		$code = null;
		$k = 0;
		$valid = false;
		while (!$valid)
		{
			try
			{
				$k++;
				$code  = md5(\Carbon\Carbon::now()->format('Ym') . \Illuminate\Support\Str::random($codelength));
				$id    = \DB::table('codes')->insertGetId(['name' => $code, 'attempts' => $k]);
				$valid = ($id > 0);
			}
			catch (\Exception $e)
			{
				
			}
		}

		return $code;
	}
}