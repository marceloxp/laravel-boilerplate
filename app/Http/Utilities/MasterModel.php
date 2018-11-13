<?php

namespace App\Http\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \App\Http\Utilities\Cached;
use Carbon\Carbon;

class MasterModel extends Model
{
	public function getCreatedAtAttribute($value) { return ($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : ''; }
	public function getUpdatedAtAttribute($value) { return ($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : ''; }
	public function getDeletedAtAttribute($value) { return ($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : ''; }

	public static function getTableName()
	{
		$instanced_model = with(new static);
		$result = $instanced_model->getTable();
		unset($instanced_model);
		return $result;
	}

	public static function getModelName()
	{
		$instanced_model = with(new static);
		$result = (new \ReflectionClass($instanced_model))->getShortName();
		unset($instanced_model);
		return $result;
	}

	public static function translateNameCaptions($p_field_names)
	{
		$metadata = with(new static)->getFieldsMetaData();
		$result = [];
		foreach ($p_field_names as $key => $value)
		{
			$result[$value] = $metadata[$value]['comment'] ?? $value;
		}
		return $result;
	}

	public static function translateCaptions($p_field_names)
	{
		$metadata = with(new static)->getFieldsMetaData();
		$result = [];
		foreach ($p_field_names as $key => $value)
		{
			$result[] = $metadata[$value]['comment'] ?? $value;
		}
		return $result;
	}

	public static function getTableFieldCaption($p_table_name, $p_field_name)
	{
		$result = Cached::get
		(
			'sys-model',
			['getTableFieldCaption', $p_table_name, $p_field_name],
			function() use ($p_table_name, $p_field_name)
			{
				$query = sprintf
				(
					'SELECT "caption", COLUMN_COMMENT AS `caption` FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = "%s" AND TABLE_NAME = "%s%s" AND COLUMN_NAME = "%s" LIMIT 1;',
					env('DB_DATABASE'),
					env('DB_TABLE_PREFIX'),
					$p_table_name,
					$p_field_name
				);
				$result = collect(DB::select($query))->pluck('caption')->first();

				return $result;
			},
			5
		);

		return $result['data'];
	}

	public static function getPivotMetaData()
	{
		$table_name = self::getTableName();

		$result = Cached::get
		(
			'sys-model',
			['getPivotMetaData', $table_name],
			function() use ($table_name)
			{
				$result = [];

				$query = sprintf
				(
					'
						SELECT
							`TABLE_NAME`      AS table_name,
							`CONSTRAINT_NAME` AS constraint_name
						FROM
							`INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE`
						WHERE
							`TABLE_SCHEMA` = "%s"
							AND
							`REFERENCED_TABLE_SCHEMA` = "%s"
							AND
							`REFERENCED_TABLE_NAME` = "%s%s"
							AND
							`REFERENCED_COLUMN_NAME` = "id"
						;
					',
					env('DB_DATABASE'),
					env('DB_DATABASE'),
					env('DB_TABLE_PREFIX'),
					$table_name
				);

				$relations = DB::select($query);
				foreach ($relations as $relation)
				{
					$table = $relation->table_name;
					$pivot = $table;
					$ligation = ltrim($table, env('DB_TABLE_PREFIX'));
					$table = ltrim($table, env('DB_TABLE_PREFIX'));
					$table = trim($table, $table_name);
					$table = trim($table, '_');
					$model = $table;
					$table = str_plural($table);
					$prop  = $table;
					$table = env('DB_TABLE_PREFIX') . $table;

					$primary = db_get_primary_key($prop);
					$relation = sprintf('%s_%s', $model, $primary);
					
					$result[] = compact('pivot','table','ligation','prop','model','primary','relation');
				}

				return $result;
			}
		);
		
		return $result['data'];
	}

	public static function getFieldsMetaData($appends = [])
	{
		$table_name = self::getTableName();

		$result = Cached::get
		(
			'sys-model',
			['getFieldsMetaData', $table_name],
			function() use ($appends, $table_name)
			{
				$query = sprintf
				(
					'
						SELECT 
							`TABLE_NAME` AS table_name,
							`COLUMN_NAME` AS field_name,
							`REFERENCED_TABLE_NAME` AS ref_table,
							`REFERENCED_COLUMN_NAME` AS field_index
						FROM
							`INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE`
						WHERE
							`TABLE_SCHEMA` = "%s"
							AND
							`REFERENCED_TABLE_NAME` IS NOT NULL
							AND
							`TABLE_NAME` = "%s%s"
					',
					env('DB_DATABASE'),
					env('DB_TABLE_PREFIX'),
					$table_name
				);

				$fields_relations = DB::select($query);
				$relations = [];
				foreach ($fields_relations as $ref)
				{
					$value = (array)$ref;

					$value = 
					[
						'table_name'   => trim($value['table_name'], env('DB_TABLE_PREFIX')),
						'table_model'  => str_singular(trim($value['table_name'], env('DB_TABLE_PREFIX'))),
						'field_name'   => $value['field_name'],
						'ref_table'    => trim($value['ref_table'], env('DB_TABLE_PREFIX')),
						'ref_model'    => str_singular(trim($value['ref_table'], env('DB_TABLE_PREFIX'))),
						'field_index'  => $value['field_index'],
						'custom_field' => str_singular(trim($value['ref_table'], env('DB_TABLE_PREFIX'))),
						'comment'      => ''
					];
					$value['comment'] = self::getTableFieldCaption($value['ref_table'], 'name');
					$relations[$ref->field_name] = $value;
				}

				$query = sprintf
				(
					'
						SELECT 
							COLUMN_NAME              AS `name`,
							DATA_TYPE                AS `type`,
							COLUMN_TYPE              AS `rawtype`,
							(COLUMN_KEY = "PRI")     AS `pri`,
							COLUMN_COMMENT           AS `comment`,
							CHARACTER_MAXIMUM_LENGTH AS `max_length`,
							(IS_NULLABLE = "YES") AS `nullable`
						FROM 
							information_schema.COLUMNS
						WHERE
							TABLE_SCHEMA = "%s"
							AND
							TABLE_NAME = "%s%s"
						ORDER BY
							ORDINAL_POSITION
					',
					env('DB_DATABASE'),
					env('DB_TABLE_PREFIX'),
					$table_name
				);

				$result = [];
				$fields_schema = DB::select($query);
				foreach ($fields_schema as $value)
				{
					$field_name = $value->name;
					$value = (array)$value;
					$value['pri'] = (boolean)$value['pri'];
					$value['nullable'] = (boolean)$value['nullable'];
					switch ($field_name)
					{
						case 'id':
							$value['comment'] = 'ID';
						break;
						case 'created_at':
							$value['comment'] = 'Criação';
						break;
						case 'updated_at':
							$value['comment'] = 'Atualização';
						break;
						case 'deleted_at':
							$value['comment'] = 'Exclusão';
						break;
					}

					if ($value['type'] == 'enum')
					{
						$str = $value['rawtype'];
						preg_match("/^enum\(\'(.*)\'\)$/", $str, $matches);
						$enum = explode("','", $matches[1]);
						$value['options'] = $enum;
					}

					unset($value['rawtype']);

					$value['has_relation'] = false;
					if (array_key_exists($field_name, $relations))
					{
						$value['relation'] = $relations[$field_name];
						$value['comment']  = $relations[$field_name]['comment'];
						$value['has_relation'] = true;
					}

					$result[$field_name] = $value;
				}

				foreach ($appends as $field_name => $field_caption)
				{
					$result[$field_name] = 
					[
						'name'         => $field_name,
						'type'         => 'appends',
						'pri'          => false,
						'comment'      => $field_caption,
						'max_length'   => null,
						'nullable'     => false,
						'has_relation' => false
					];
				}

				return $result;
			}
		);
		
		return $result['data'];
	}

	public static function _validate($request, $rules, $id = null)
	{
		try
		{
			$result =
			[
				'result'  => true,
				'success' => false,
				'tag'     => 0,
				'message' => '',
				'fields'  => [],
				'error'   => ''
			];

			if ($id)
			{
				$rules['id'] = 'required';
			}

			$form_data = (is_array($request)) ? $request : $request->all();
			$validator = Validator::make($form_data, $rules);

			if (($validator->fails()))
			{
				$result['success'] = false;
				$result['message'] = 'Entrada de dados inválida.';
				$result['all']     = [ $result['message'] ];
				$result['fields']  = [];

				$errors = $validator->errors();

				foreach ($errors->keys() as $field_name)
				{
					$str_error          = $errors->first($field_name);
					$result['fields'][] = [$field_name => $str_error];
					$result['all'][]    = $str_error;
				}
			}
			else
			{
				$result['success'] = true;
			}
		}
		catch(\Exception $e)
		{
			$result['success'] = false;
			$result['message'] = 'Ocorreu um erro na validação dos dados.';
			$result['error'] = $e->getMessage();
		}

		return $result;
	}

	public function scopeMasterMany($query, $p_master_model, $p_many_model, $p_many_id)
	{
		$MasterModel  = $p_master_model;
		$ManyModel    = $p_many_model;
		$master_name  = class_basename($MasterModel);
		$many_name    = class_basename($ManyModel);
		$order_name   = [$master_name, $many_name];
		$pivot_table  = sprintf('%s_%s', strtolower($order_name[0]), strtolower($order_name[1]));
		$master_model = new $MasterModel;
		$master_table = $master_model->getTable();
		$many_model   = new $ManyModel;
		$many_table   = $many_model->getTable();

		return $query->join
		(
			$pivot_table,
			sprintf('%s.id', $many_table),
			'=',
			sprintf('%s.%s_id', $pivot_table, strtolower($many_name))
		)
		->where
		(
			sprintf('%s.%s_id', $pivot_table, strtolower($master_name)),
			$p_many_id
		);
	}

	public function money($p_value)
	{
		return number_format($p_value, 2, ',', '.');
	}
}