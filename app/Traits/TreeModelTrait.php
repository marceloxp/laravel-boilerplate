<?php
namespace App\Traits;

trait TreeModelTrait
{
	public static function hasChildsIds($p_parents)
	{
		$parents = (is_array($p_parents)) ? array_merge($p_parents) : array_merge([$p_parents]);
		$table = self::table()->whereIn('parent_id', $parents)->whereNull('deleted_at')->count();
		return ($table > 0);
	}

	public static function getChildsIds($p_parent)
	{
		$result     = [];
		$parents    = (is_array($p_parent)) ? array_merge($p_parent) : array_merge([$p_parent]);
		$childs     = self::table()->whereIn('parent_id', $parents)->whereNull('deleted_at')->get(['id'])->pluck('id')->toArray();
		$result     = array_merge($result, $childs);
		$has_childs = self::hasChildsIds($childs);
		if ($has_childs)
		{
			$childs = self::getChildsIds($result);
			$result = array_merge($result, $childs);
		}
		return $result;
	}

	private static function getLevel($p_array, $p_level = 0)
	{
		$result = collect($p_array)->map
		(
			function($item, $key) use ($p_level)
			{
				$item['level'] = $p_level;
				if (!empty($item['child']))
				{
					$item['child'] = self::getLevel($item['child'], ($p_level+1));
				}
				return $item;
			}
		);

		return ($p_level === 0) ? $result : $result->toArray();
	}

	private static function array_push_nodes($p_array, $p_nodes)
	{
		$result = array_merge($p_array);

		if (array_key_exists('id', $p_nodes))
		{
			array_push($result, $p_nodes);
		}
		else
		{
			foreach ($p_nodes as $node)
			{
				array_push($result, $node);
			}
		}

		return $result;
	}

	public static function alignTreeToLeft($p_array)
	{
		$result = [];
		if (!is_array($p_array)) { ddd(['ops', $p_array]); }
		if (array_key_exists('level', $p_array)) { ddd('ops'); }
		else
		{
			reset($p_array);
			foreach ($p_array as $key => $value)
			{
				if (!empty($value['child']))
				{
					$child = array_merge($value['child']);
					unset($value['child']);
					$childs = self::alignTreeToLeft($child);
					$result = self::array_push_nodes($result, $value);
					$result = self::array_push_nodes($result, $childs);
				}
				else
				{
					unset($value['child']);
					$result = self::array_push_nodes($result, $value);
				}
			}
		}
		return $result;
	}

	public static function getTree($p_slug, $p_fields)
	{
		$use_fields = (!in_array('parent_id', $p_fields)) ? array_merge($p_fields, ['parent_id']) : array_merge($p_fields);
		$ids        = [];
		$master_id  = self::table()->where('slug', $p_slug)->whereNull('deleted_at')->first(['id']);
		if (empty($master_id))
		{
			return collect([]);
		}
		$master_id  = $master_id->id;
		$childs     = self::getChildsIds($master_id);
		$ids        = array_merge([$master_id], $childs);
		$registers  = self::table()->select($use_fields)->whereIn('id', $ids)->whereNull('deleted_at')->get();
		$registers  = collect($registers->toArray())->map(function ($item, $key) { return (array)$item; });

		$default_fields = config('nestable.body');
		$array_fields = array_merge($default_fields, $use_fields);
		\Config::set('nestable.body', $array_fields);

		$registers = \Nestable::make($registers->toArray());
		$registers = collect($registers->renderAsArray());
		$registers = self::getLevel($registers->toArray());

		return $registers;
	}

	public static function getTreeAligned($p_slug, $p_fields)
	{
		$registers = self::getTree($p_slug, $p_fields);
		$registers = self::alignTreeToLeft($registers->toArray());
		return collect($registers);
	}
}