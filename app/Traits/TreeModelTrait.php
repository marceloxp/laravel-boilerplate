<?php
namespace App\Traits;

trait TreeModelTrait
{
	public static function hasChildsIds($p_parents)
	{
		$parents = (is_array($p_parents)) ? array_merge($p_parents) : array_merge([$p_parents]);
		$table = \App\Http\Utilities\Cached::get
		(
			self::getModelName(),
			['hasChildsIds', implode('-', $parents)],
			function() use ($parents)
			{
				return self::whereIn('parent_id', $parents)->get(['id'])->count();
			}
		);
		$table = $table['data'];
		return ($table > 0);
	}

	public static function getChildsIds($p_parent)
	{
		$result  = [];
		$parents = (is_array($p_parent)) ? array_merge($p_parent) : array_merge([$p_parent]);
		$childs  = \App\Http\Utilities\Cached::get
		(
			self::getModelName(),
			['getChildsIds', implode('-', $parents)],
			function() use ($parents)
			{
				return self::whereIn('parent_id', $parents)->get(['id'])->pluck('id')->toArray();
			}
		);
		$childs     = $childs['data'];
		$result     = array_merge($result, $childs);
		$has_childs = self::hasChildsIds($childs);
		if ($has_childs)
		{
			$childs = self::getChildsIds($result);
			$result = array_merge($result, $childs);
		}
		return $result;
	}

	public static function getPath($p_register_id, $p_first = true)
	{
		$result = \App\Http\Utilities\Cached::get
		(
			self::getModelName(),
			['getpath', $p_register_id],
			function() use ($p_register_id, $p_first)
			{
				$result = collect([]);
				$table = self::whereId($p_register_id)->get()->first();
				$result->push($table);
				if (!empty($table->parent_id))
				{
					$parents = self::getPath($table->parent_id, false);
					$parents->each
					(
						function($item, $key) use ($result)
						{
							$result->push($item);
						}
					);
				}
				if ($p_first)
				{
					$result = $result->reverse();
				}

				return $result;
			}
		);
		return $result['data'];
	}

	public static function getStrPath($p_register_id, $p_separator = ' > ')
	{
		$path = self::getPath($p_register_id);
		$result = $path->extract('name')->toText($p_separator);
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

	public static function getTree($p_fields, $fields_schema)
	{
		$use_fields = (!in_array('parent_id', $p_fields)) ? array_merge($p_fields, ['parent_id']) : array_merge($p_fields);
		$select_fields = collect($use_fields)->filter(function ($key, $value) use ($fields_schema) { return $fields_schema[$key]['is_appends'] == false; })->toArray();

		$ids       = [];
		$master_id = 0;
		$childs    = self::getChildsIds($master_id);
		$ids       = array_merge([$master_id], $childs);
		$order     = (array_key_exists('order', $fields_schema)) ? 'order' : 'id';
		
		$registers = \App\Http\Utilities\Cached::get
		(
			self::getModelName(),
			['getTree', implode('-', $p_fields)],
			function() use ($ids, $order, $select_fields)
			{
				return self::whereIn('id', $ids)->orderBy($order)->get($select_fields);
			}
		)['data'];

		$registers      = collect($registers->toArray())->map(function ($item, $key) { return (array)$item; });
		$default_fields = config('nestable.body');
		$array_fields   = array_merge($default_fields, $use_fields);
		\Config::set('nestable.body', $array_fields);

		$registers = \Nestable::make($registers->toArray());
		$registers = collect($registers->renderAsArray());
		$registers = self::getLevel($registers->toArray());

		return $registers;
	}

	public static function getTreeAligned($p_fields, $fields_schema)
	{
		$registers = self::getTree($p_fields, $fields_schema);
		$registers = self::alignTreeToLeft($registers->toArray());
		return collect($registers);
	}
}