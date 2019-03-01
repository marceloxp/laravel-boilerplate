<?php
namespace App\Traits;

trait TreeModelTrait
{
	public static function ping()
	{
		echo "pong";
	}

	public static function getAdminTree()
	{
		$registers = self::get();
		dump($registers);
	}
}