<?php
namespace App\Traits;

use \App\Http\Utilities\Result;

trait OrderTrait
{
	public static function boot()
	{
		parent::boot();

		self::saving(function($model){
			if ($model->position === null) { $model->position = self::getNextPosition(); }
		});

		self::creating(function($model){
			if ($model->position === null) { $model->position = self::getNextPosition(); }
		});

		self::updating(function($model){
			if ($model->position === null) { $model->position = self::getNextPosition(); }
		});
	}

	public static function getNextPosition()
	{
		return (self::max('position') + 1);
	}

	public static function reorder($pos_ini, $ids_ini, $ids_end)
	{
		try
		{
			$order = ($ids_ini[0] < $ids_ini[1]) ? 'asc' : 'desc';
			$asc   = ($order == 'asc');
			$fator = ($asc) ? 1 : -1;

			$use_pos = 0;
			$use_ini = [];
			$use_end = [];

			foreach ($ids_ini as $key => $id_ini)
			{
				if ($id_ini != $ids_end[$key])
				{
					$use_ini[] = $id_ini;
					$use_end[] = $ids_end[$key];
					if ($use_pos === 0)
					{
						$use_pos = ($asc) ? ($pos_ini + $key) : ($pos_ini - $key);
					}
				}
			}

			$range_count = count($use_ini);
			$table_max   = self::max('position');
			$range_ini   = $table_max + $use_pos;
			$range_end   = $range_ini + $range_count;
			$randoms     = range($range_ini, $range_end-1);

			\DB::beginTransaction();

			foreach ($randoms as $key => $rand_position)
			{
				$ids = $use_ini[$key];
				$position = $rand_position;
				self::where('id', $ids)->update(['position' => $position]);
			}

			$position = $use_pos;
			foreach ($use_end as $ids)
			{
				self::where('id', $ids)->update(['position' => $position]);
				$position = $position + $fator;
			}

			\DB::commit();
			return Result::success('Registros reordenados com sucesso.');
		}
		catch (\Exception $e)
		{
			\DB::rollback();
			return Result::exception($e);
		}
	}
}