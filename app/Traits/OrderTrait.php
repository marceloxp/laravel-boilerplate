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

	public static function reorder($pos_ini, $ids_ini, $ids_end, $p_order)
	{
		try
		{
			$order     = $p_order;
			$asc       = ($order == 'asc');
			$fator     = ($asc) ? 1 : -1;
			$table_max = self::max('position');

			$use_ini = [];
			foreach ($ids_ini as $key => $id)
			{
				$use_ini[intval($id)] = intval($pos_ini+$key*$fator) + $table_max;
			}

			$use_end = [];
			foreach ($ids_end as $key => $id)
			{
				$use_end[intval($id)] = intval($pos_ini+$key*$fator);
			}

			\DB::beginTransaction();
			$ajusts = [$use_ini, $use_end];
			foreach ($ajusts as $ajust)
			{
				foreach ($ajust as $ids => $position)
				{
					self::where('id', $ids)->update(['position' => $position]);
				}
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