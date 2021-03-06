<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \App\Http\Utilities\Cached;
use \Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Utilities\Carbex;

class MasterModel extends Model
{
	public $errors;
	public function __construct(array $attributes = [])
	{
		$this->errors = new \Illuminate\Support\ViewErrorBag;
		parent::__construct($attributes);
	}

	public function getCreatedAtAttribute($value) { return ($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : ''; }
	public function getUpdatedAtAttribute($value) { return ($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : ''; }
	public function getDeletedAtAttribute($value) { return ($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : ''; }

	public function getCarbonCreatedAtAttribute() { $value = self::getOriginal()['created_at']; return ($value) ? Carbon::parse($value) : null; }
	public function getCarbonUpdatedAtAttribute() { $value = self::getOriginal()['updated_at']; return ($value) ? Carbon::parse($value) : null; }
	public function getCarbonDeletedAtAttribute() { $value = self::getOriginal()['deleted_at']; return ($value) ? Carbon::parse($value) : null; }

	public function getCarbexCreatedAtAttribute() { $value = self::getOriginal()['created_at']; return ($value) ? Carbex::parse($value) : null; }
	public function getCarbexUpdatedAtAttribute() { $value = self::getOriginal()['updated_at']; return ($value) ? Carbex::parse($value) : null; }
	public function getCarbexDeletedAtAttribute() { $value = self::getOriginal()['deleted_at']; return ($value) ? Carbex::parse($value) : null; }

	public static function getPivotFields()
	{
		return collect(self::getFieldsMetaData())->where('type', 'pivot')->keys()->all();
	}

	public static function getNumericFields()
	{
		return collect(self::getFieldsMetaData())->where('type', 'decimal')->keys()->all();
	}

	public static function getLongTextFields()
	{
		return collect(self::getFieldsMetaData())->where('type', 'longtext')->keys()->all();
	}

	public static function hasField($p_field)
	{
		return collect(self::getFieldsMetaData())->where('name', $p_field)->keys()->count() > 0;
	}

	public static function anyField($p_field)
	{
		foreach ($p_field as $field_name)
		{
			if (collect(self::getFieldsMetaData())->where('name', $field_name)->keys()->count() > 0)
			{
				return $field_name;
			}
		}
		return false;
	}

	public function scopeLoopNext($q)
	{
		$table_name   = self::getTableName();
		$session_name = sprintf('mode.db.loop.%s.id', $table_name);
		$id           = session($session_name);
		if (!$id)
		{
			$register = with(clone $q)->orderById()->first();
		}
		else
		{
			$register = with(clone $q)->where('id', '>', $id)->orderById()->first();
			if (!$register)
			{
				$register = with(clone $q)->orderById()->first();
			}
		}
		session([$session_name => $register->id]);
		return $q->where('id', $register->id)->first();
	}

	public function scopeLoopReset($q)
	{
		$table_name   = self::getTableName();
		$session_name = sprintf('mode.db.loop.%s.id', $table_name);
		$register     = self::orderById()->first();
		session([$session_name => $register->id]);
		return $q->where('id', $register->id);
	}

	public function scopeRandom($q)
	{
		return $q->inRandomOrder();
	}

	public function scopeFirstRandom($q)
	{
		return $q->random()->first();
	}

	public function scopeOrderById($q)
	{
		return $q->orderBy('id', 'ASC');
	}

	public function scopeOrderByIdDesc($q)
	{
		return $q->orderBy('id', 'DESC');
	}

	public function scopeLastById($q)
	{
		return $q->orderBy('id', 'DESC')->first();
	}

	public function scopeSelectId($q)
	{
		return $q->select('id');
	}

	public function scopeById($q, $p_id)
	{
		return $q->where('id', $p_id);
	}

	public function scopeSlimExists($q)
	{
		return $q->select('id')->exists();
	}

	public static function scopeDateBetweenNow($q, $p_field_ini, $p_field_end)
	{
		return $q->where($p_field_ini, '<=', Carbex::now()->toSqlDate())->where($p_field_end, '>=', Carbex::now()->toSqlDate());
	}

	public static function scopeDateTimeBetweenNow($q, $p_field_ini, $p_field_end)
	{
		return $q->where($p_field_ini, '<=', Carbex::now())->where($p_field_end, '>=', Carbex::now());
	}

	public static function scopeDateBetween($q, $p_date, $p_field_ini, $p_field_end)
	{
		return $q->where($p_field_ini, '<=', $p_date)->where($p_field_end, '>=', $p_date);
	}

	public static function scopeGetRandom($q)
	{
		return $q->inRandomOrder()->first();
	}

	public static function scopeSimpleList($q)
	{
		return collect(json_decode(collect($q->get())->toJson(), true))->flatten()->toArray();
	}

	public function cacheKey($p_str_append = '')
	{
		if (!$p_str_append)
		{
			return sprintf
			(
				'model-%s-%s',
				$this->getModelName(),
				$this->getKey()
			);
		}
		return sprintf
		(
			'model-%s-%s-%s',
			$this->getModelName(),
			$this->getKey(),
			$p_str_append
		);
	}

	public static function boot()
	{
		parent::boot();

		self::retrieved
		(
			function($model)
			{
				$fields = self::getNumericFields();
				foreach ($fields as $field_name)
				{
					$model->$field_name = (new \App\Http\Utilities\Money($model->$field_name))->value;
				}

				$fields = self::getLongTextFields();
				foreach ($fields as $field_name)
				{
					$model->$field_name = html_purifier($model->$field_name);
				}
			}
		);

		self::saving
		(
			function($model)
			{
				$fields = self::getNumericFields();
				foreach ($fields as $field_name)
				{
					$model->$field_name = (new \App\Http\Utilities\Money($model->$field_name))->value;
				}

				$fields = self::getLongTextFields();
				foreach ($fields as $field_name)
				{
					$model->$field_name = html_purifier($model->$field_name);
				}
			}
		);

		self::saved(function($model){
			Cached::forget(self::getModelName());
		});

		self::creating(function($model){
			// ... code here
		});

		self::created(function($model){
			Cached::forget(self::getModelName());
		});

		self::updating(function($model){
			// ... code here
		});

		self::updated(function($model){
			Cached::forget(self::getModelName());
		});

		self::deleting(function($model){
			// ... code here
		});

		self::deleted(function($model){
			Cached::forget(self::getModelName());
		});
	}

	public static function table()
	{
		return \DB::table(self::getTableName());
	}

	public static function getPivotConfig($p_args)
	{
		$result = [];
		foreach ($p_args as $table_name => $icon)
		{
			$result[] =
			[
				'name'    => db_get_pivot_table_name([self::getTableName(), $table_name], false),
				'caption' => db_get_comment_table(self::getSchemaName(), $table_name),
				'icon'    => $icon
			];
		}
		return $result;
	}

	public static function getUpdateValues($p_field_name)
	{
		return
		[
			'old' => self::getOriginal()->$p_field_name,
			'new' => self::toArray()[$p_field_name],
		];
	}

	public function isChanging($p_field_name)
	{
		$old = $this->getOriginal();
		if (empty($old))
		{
			return true;
		}
		$new = $this->toArray();

		if ( (!array_key_exists($p_field_name, $old)) || (!array_key_exists($p_field_name, $new)) )
		{
			return false;
		}

		return ($old[$p_field_name] != $new[$p_field_name]);
	}

	public static function ajustFormValues($request)
	{
		$fields = self::getNumericFields();
		foreach ($fields as $field_name)
		{
			$value = (new \App\Http\Utilities\Money($request->input($field_name)))->value;
			$request->merge([$field_name => $value]);
		}
	}

	public static function getTableCaption()
	{
		$table_name = self::getTableName();

		$result = Cached::get
		(
			'sys-model',
			['getTableCaption', self::getSchemaName(), $table_name],
			function() use ($table_name)
			{
				return db_get_comment_table(self::getSchemaName(), $table_name);
			},
			5
		);

		return $result['data'];
	}

	public static function getTableName($add_schema = false)
	{
		$instanced_model = with(new static);
		if ($add_schema)
		{
			$result = sprintf('%s.%s', $instanced_model->getConnection()->getConfig()['schema'], $instanced_model->getTable());
		}
		else
		{
			$result = $instanced_model->getTable();
		}
		unset($instanced_model);
		return $result;
	}

	public static function getSchemaName()
	{
		$instanced_model = with(new static);
		$result = $instanced_model->getConnection()->getConfig()['schema'];
		unset($instanced_model);
		return $result;
	}

	public static function getTableSlug()
	{
		$result = self::getTableName();
		return Str::slug($result, '-');
	}

	public static function getInstance()
	{
		return with(new static);
	}

	public static function getModelName()
	{
		$instanced_model = with(new static);
		$result = (new \ReflectionClass($instanced_model))->getShortName();
		unset($instanced_model);
		return $result;
	}

	public static function getModelSlug()
	{
		$result = self::getModelName();
		return Str::slug($result, '-');
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
			['getTableFieldCaption', self::getSchemaName(), $p_table_name, $p_field_name],
			function() use ($p_table_name, $p_field_name)
			{
				$query = sprintf
				(
					"
						SELECT
							column_name,
							col_description((table_schema||'.'||table_name)::regclass::oid, ordinal_position) as caption
						FROM
							information_schema.columns
						WHERE
							table_catalog = '%s'
							AND
							table_schema = '%s'
							AND
							table_name = '%s'
							AND
							column_name = '%s';
					",
					db_database_name(),
					self::getSchemaName(),
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
							`REFERENCED_TABLE_NAME` = "%s"
							AND
							`REFERENCED_COLUMN_NAME` = "id"
						;
					',
					db_database_name(),
					db_database_name(),
					db_prefixed_table($table_name)
				);

				$relations = DB::select($query);
				foreach ($relations as $relation)
				{
					$table = $relation->table_name;
					$pivot = $table;
					$ligation = db_trim_table_prefix($table);
					$table = db_trim_table_prefix($table);
					$table = trim($table, $table_name);
					$table = trim($table, '_');
					$model = $table;
					$table = \Illuminate\Support\Str::plural($table);
					$prop  = $table;
					$table = db_prefixed_table($table);

					$primary = db_get_primary_key($prop);
					$relation = sprintf('%s_%s', $model, $primary);

					$result[] = compact('pivot','table','ligation','prop','model','primary','relation');
				}

				return $result;
			}
		);

		return $result['data'];
	}

	public static function getHasParentId($p_table_name)
	{
		$result = Cached::get
		(
			'sys-model',
			['getHasParentId', self::getSchemaName(), $p_table_name],
			function() use ($p_table_name)
			{
				$result = [];

				$query = sprintf
				(
					"
						SELECT EXISTS
						(
							SELECT
								column_name
							FROM
								information_schema.columns
							WHERE
								table_catalog = '%s'
								AND
								table_schema = '%s'
								AND
								table_name = '%s'
								AND
								column_name = 'parent_id'
						) AS has_parent_id;
					",
					db_database_name(),
					self::getSchemaName(),
					$p_table_name
				);

				$register      = DB::select($query);
				$has_parent_id = intval($register[0]->has_parent_id);
				$result        = ($has_parent_id == 1);
				return $result;
			}
		);

		return $result['data'];
	}

	public static function getFieldMetaData($p_field_name)
	{
		$metadata = self::getFieldsMetaData();
		return $metadata[$p_field_name];
	}

	public static function getFieldsMetaData($appends = [], $level = 0)
	{
		$table_name = self::getTableName();

		$result = Cached::get
		(
			'sys-model',
			['getFieldsMetaData', self::getSchemaName(), $table_name],
			function() use ($table_name, $appends, $level)
			{
				// SIMPLE RELATIONS
				$query = sprintf
				(
					"
						SELECT tc.table_schema,
							   tc.constraint_name,
							   tc.table_name,
							   kcu.column_name  AS field_name,
							   ccu.table_schema AS foreign_table_schema,
							   ccu.table_name   AS ref_table,
							   ccu.column_name  AS field_index
						FROM
							 information_schema.table_constraints AS tc
								 JOIN information_schema.key_column_usage AS kcu
									  ON tc.constraint_name = kcu.constraint_name
										  AND tc.table_schema = kcu.table_schema
								 JOIN information_schema.constraint_column_usage AS ccu
									  ON ccu.constraint_name = tc.constraint_name
										  AND ccu.table_schema = tc.table_schema
						WHERE
							  tc.table_schema = '%s'
							  AND
							  tc.table_name = '%s'
							  AND
							  tc.constraint_type = 'FOREIGN KEY';
						;
					",
					self::getSchemaName(),
					$table_name
				);

				$fields_relations = DB::select($query);
				$relations = [];
				foreach ($fields_relations as $ref)
				{
					$value = (array)$ref;
					$value =
					[
						'prop_name'     => str_to_singular($value['ref_table']),
						'schema_name'   => self::getSchemaName(),
						'table_name'    => $value['table_name'],
						'table_model'   => ucfirst(self::getSchemaName()) . '\\' . ucfirst(str_to_singular($value['table_name'])),
						'field_name'    => $value['field_name'],
						'ref_schema'    => $value['foreign_table_schema'],
						'ref_table'     => $value['ref_table'],
						'ref_model'     => ucfirst($value['foreign_table_schema']) . '\\' . ucfirst(str_to_singular($value['ref_table'])),
						'field_index'   => $value['field_index'],
						'custom_field'  => str_to_singular($value['ref_table']),
						'has_parent_id' => self::getHasParentId($value['ref_table']),
						'comment'       => ''
					];
					$value['comment'] = self::getTableFieldCaption($value['table_name'], $value['field_name']);
					$relations[$ref->field_name] = $value;
				}

				if ($level == 0)
				{
					// PIVOT RELATION
					$query = sprintf
					(
						"
							SELECT
								tc.table_name
							FROM information_schema.table_constraints AS tc
									 JOIN information_schema.key_column_usage AS kcu
										  ON tc.constraint_name = kcu.constraint_name
											  AND tc.table_schema = kcu.table_schema
									 JOIN information_schema.constraint_column_usage AS ccu
										  ON ccu.constraint_name = tc.constraint_name
											  AND ccu.table_schema = tc.table_schema
							WHERE tc.constraint_catalog = '%s'
							  AND tc.table_schema = '%s'
							  AND tc.constraint_type = 'FOREIGN KEY'
							  AND ccu.table_name = '%s'
							  AND kcu.column_name = '%s'
							  AND tc.table_name LIKE '%%%s%%'
							;
						",
						db_database_name(),
						self::getSchemaName(),
						$table_name,
						sprintf('%s_id', str_plural_2_singular($table_name)),
						str_plural_2_singular($table_name)
					);
					$pivot_tables = DB::select($query);

					$pivot_relations = [];
					foreach ($pivot_tables as $pivot_table)
					{
						$query = sprintf
						(
							"
								SELECT
									ccu.table_name   AS ref_table
								FROM
									information_schema.table_constraints AS tc
									JOIN information_schema.key_column_usage AS kcu
									ON tc.constraint_name = kcu.constraint_name
									AND tc.table_schema = kcu.table_schema
									JOIN information_schema.constraint_column_usage AS ccu
									ON ccu.constraint_name = tc.constraint_name
									AND ccu.table_schema = tc.table_schema
								WHERE
									tc.constraint_catalog = '%s'
									AND
									tc.table_schema = '%s'
									AND
									tc.table_name = '%s'
									AND
									tc.constraint_type = 'FOREIGN KEY'
									AND
									kcu.column_name != '%s'
								;
							",
							db_database_name(),
							self::getSchemaName(),
							$pivot_table->table_name,
							sprintf('%s_id', str_plural_2_singular($table_name))
						);
						$pivot_table           = DB::select($query);
						$referenced_table_name = $pivot_table[0]->ref_table;
						$pivot_field_name      = $referenced_table_name;
						$pivot_relations[]     = $pivot_field_name;
					}
				}

				// TABLE SCHEMA
				if (substr($table_name, 0, 2) !== 'vw')
				{
					$query = sprintf
					(
						"
						SELECT column_name                                                          AS name,
							   udt_name                                                             AS type,
							   (SELECT EXISTS(select kcu.table_schema,
													 kcu.table_name,
													 tco.constraint_name,
													 kcu.ordinal_position as position,
													 kcu.column_name      as key_column
											  from information_schema.table_constraints tco
													   join information_schema.key_column_usage kcu
															on kcu.constraint_name = tco.constraint_name and
															   kcu.constraint_schema = tco.constraint_schema and
															   kcu.constraint_name = tco.constraint_name
											  where tco.constraint_type = 'PRIMARY KEY'
												and kcu.table_name = information_schema.columns.table_name
												and kcu.column_name = information_schema.columns.column_name
											  order by kcu.table_schema, kcu.table_name, position)) AS pri,
							   column_default                                                       AS default_value,
							   (SELECT pgd.description
								FROM pg_catalog.pg_statio_all_tables as st
										 inner join pg_catalog.pg_description pgd on (pgd.objoid = st.relid)
										 inner join information_schema.columns c
													on (pgd.objsubid = c.ordinal_position and c.table_schema = st.schemaname and
														c.table_name = st.relname)
								WHERE table_name = information_schema.columns.table_name AND st.schemaname = information_schema.columns.table_schema
								  AND column_name = information_schema.columns.column_name)         AS comment,
							   (select cc.check_clause
								from information_schema.table_constraints tc
										 join information_schema.check_constraints cc
											  on tc.constraint_schema = cc.constraint_schema and tc.constraint_name = cc.constraint_name
										 join pg_namespace nsp on nsp.nspname = cc.constraint_schema
										 join pg_constraint pgc
											  on pgc.conname = cc.constraint_name and pgc.connamespace = nsp.oid and pgc.contype = 'c'
										 join information_schema.columns col
											  on col.table_schema = tc.table_schema and col.table_name = tc.table_name and
												 col.ordinal_position = ANY (pgc.conkey)
								where tc.constraint_schema not in ('pg_catalog', 'information_schema')
								  and tc.table_name = information_schema.columns.table_name
								  and col.column_name = information_schema.columns.column_name
								  and tc.table_schema = information_schema.columns.table_schema
								group by tc.table_schema, tc.table_name, tc.constraint_name, cc.check_clause, col.column_name
								order by tc.table_schema, tc.table_name),
							   character_maximum_length                                             AS max_length,
							   is_nullable                                                          AS nullable
							FROM information_schema.columns
							WHERE table_catalog = '%s'
							  AND table_schema = '%s'
							  AND table_name = '%s'
							ORDER BY table_name, ordinal_position;
						",
						db_database_name(),
						self::getSchemaName(),
						$table_name
					);
				}
				else
				{
					$query = sprintf
					(
						"
							SELECT
							    pg_attribute.attname        AS name,
							    pg_catalog.pg_type.typname  AS type,
							    pg_attribute.attname = 'id' AS pri,
							    null                        AS default_value,
							    col_description(pg_attribute.attrelid, pg_attribute.attnum) AS comment,
							    null                        AS check_clause,
							    null                        AS max_length,
							    false                       AS nullable
							FROM pg_catalog.pg_class
							        INNER JOIN pg_catalog.pg_namespace
							                    ON pg_class.relnamespace = pg_namespace.oid
							        INNER JOIN pg_catalog.pg_attribute
							                    ON pg_class.oid = pg_attribute.attrelid AND pg_attribute.attnum > 0
							        INNER JOIN  pg_catalog.pg_type
							                    ON pg_catalog.pg_type.oid = pg_attribute.atttypid
							WHERE
							    pg_class.relkind = 'm'
							    AND pg_attribute.attnum >= 1
							    AND pg_namespace.nspname = '%s'
							    AND pg_class.relname = '%s'
							ORDER BY
							    pg_class.relname, pg_attribute.attnum, name;
						",
						self::getSchemaName(),
						$table_name
					);
				}

				$result = [];
				$fields_schema = DB::select($query);
				foreach ($fields_schema as $value)
				{
					$field_name = $value->name;
					$value = (array)$value;
					$value['pri'] = (boolean)$value['pri'];
					$value['has_pivot'] = false;
					$value['is_appends'] = false;
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

					if (strpos($value['check_clause'], ')::text = ANY ((ARRAY[') !== false)
					{
						$str = $value['check_clause'];
						$str = str_replace('(((' . $field_name . ')::text = ANY ((ARRAY[', '', $str);
						$str = str_replace('::character varying', '', $str);
						$str = str_replace('])::text[])))', '', $str);
						$str = str_replace("'", '', $str);
						$str = str_replace(", ", ',', $str);
						$enum = explode(",", $str);
						$value['options'] = $enum;
						$value['type'] = 'enum';
					}
					unset($value['check_clause']);

					if (strpos($value['default_value'], 'nextval(') !== false)
					{
						$value['default_value'] = null;
					}

					if (strpos($value['default_value'], '::character varying') !== false)
					{
						$value['default_value'] = str_replace('::character varying', '', $value['default_value']);
						$value['default_value'] = trim($value['default_value'], "'");
					}

					$value['has_relation'] = false;
					if (array_key_exists($field_name, $relations))
					{
						$value['relation'] = $relations[$field_name];
						$value['has_relation'] = true;
					}

					$result[$field_name] = $value;
				}

				if ($level == 0)
				{
					foreach ($pivot_relations as $pivot_table_name)
					{
						$pivot_model = db_table_name_to_model(self::getSchemaName(), $pivot_table_name);

						$metadata = sprintf('\App\Models\%s', $pivot_model)::getFieldsMetaData($appends, ($level+1));
						if (array_key_exists('name', $metadata))
						{
							$dynamic                   = array_merge($metadata['name']);
							$dynamic['has_pivot']      = true;
							$dynamic['name']           = $pivot_table_name;
							$dynamic['type']           = 'pivot';
							$dynamic['list_field_id']  = db_table_name_to_field_id($pivot_table_name);
							$dynamic['metadata']       = array_merge($metadata);
							$result[$pivot_table_name] = $dynamic;
						}
					}
				}

				return $result;
			}
		);

		foreach ($appends as $field_name => $field_caption)
		{
			$field_type = 'appends';
			$hook_name  = hook_name(sprintf('master_model_field_type_%s_%s', $table_name, $field_name));
			$field_type = \Hook::apply_filters($hook_name, $field_type);

			$result['data'][$field_name] =
			[
				'name'         => $field_name,
				'is_appends'   => true,
				'type'         => $field_type,
				'pri'          => false,
				'comment'      => $field_caption,
				'max_length'   => null,
				'nullable'     => false,
				'has_relation' => false
			];
		}

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
				'error'   => '',
				'all'     => []
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
					$str_error                       = $errors->first($field_name);
					$result['fields'][$field_name][] = $str_error;
					$result['all'][]                 = $str_error;
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
			$result['error']   = $e->getMessage();
			$result['all']     = $e->getMessage();
		}

		return $result;
	}

	public function setErrors($p_errors)
	{
		$this->errors = $p_errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getError($p_input_name)
	{
		return $this->errors->get($p_input_name);
	}

	public function hasError($p_input_name)
	{
		return count($this->errors->get($p_input_name)) > 0;
	}

	public function getErrorAsString($p_input_name)
	{
		$result = $this->errors->get($p_input_name);
		$result = implode('; ', $result);
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

	public function old($p_field_name, $p_default_value = '')
	{
		return $this->$p_field_name ?? old($p_field_name, $p_default_value);
	}

	public function input($p_field_name, $p_options = [])
	{
		$metadata = self::getFieldMetaData($p_field_name);
		$options =
		[
			'errors'        => new \Illuminate\Support\ViewErrorBag(),
			'default_value' => '',
			'label'         => true,
			'divwrap'       => false,
			'field_attr'    => []
		];
		$options = array_merge($options, $p_options);
		extract($options, EXTR_PREFIX_ALL, 'opt');
		$field_attr = array_merge($opt_field_attr);

		$result = '';
		if ($opt_divwrap)
		{
			$result = sprintf('<div class="%s">', $p_field_name);
		}

		$field_value = $this->$p_field_name ?? old($p_field_name, $opt_default_value);

		$required = false;
		if (!$metadata['nullable'])
		{
			$required = true;
			$field_attr['required'] = 'required';
		}
		$field_attr = array_merge($field_attr, ['class' => 'form-control', 'placeholder' => $metadata['comment'] . (($required) ? ' *' : '')]);

		if ($opt_label)
		{
			$result .= $this->label($p_field_name, $required);
		}

		if ($p_field_name == 'id')
		{
			unset($field_attr['required']);
			$result = \Form::hidden($p_field_name, $field_value, $field_attr);
		}
		elseif ($p_field_name == 'city_id')
		{
			$state_id = null;
			if ($this->hasField('state_id'))
			{
				// TODO
			}
			else
			{
				$state_value = $this->state ?? old('state');
				if (!empty($state_value))
				{
					$state = db_select_one(\App\Models\Common\State::class, ['id'], ['uf' => $state_value], true);
					$state_id = $state->id;
				}
			}

			if (\App\Models\Common\City::hasField('state_id'))
			{
				if (!empty($state_id))
				{
					$options = \App\Models\Common\City::select(['id','name'])
						->where('state_id', $state_id)
						->get()
						->pluck('name','id')
						->toArray()
					;
				}
				else
				{
					$options = [];
				}
				$result .= \Form::select($p_field_name, $options, $field_value, $field_attr);
			}
		}
		elseif ($metadata['has_relation'])
		{
			$relation  = $metadata['relation']['ref_model'];
			$secondary = DB::table($metadata['relation']['ref_table'])->select('id','name')->pluck('name','id')->toArray();
			$result   .= \Form::select($p_field_name, $secondary, $field_value, $field_attr);
			if (!empty($this->hasError($p_field_name)))
			{
				$result .= sprintf('<small id="%sHelp" class="form-text text-danger">%s</small>', $p_field_name, $this->getErrorAsString($p_field_name));
			}
		}
		else
		{
			switch ($metadata['type'])
			{
				case 'varchar':
					if (!array_key_exists('maxlength', $field_attr))
					{
						$field_attr['maxlength'] = $metadata['max_length'];
					}
					switch ($p_field_name)
					{
						case 'cellphone':
						case 'phone':
						case 'cep':
						case 'cpf':
						case 'born':
							$result .= \Form::tel($p_field_name, $field_value, $field_attr);
						break;
						case 'email':
						case 'mail':
							$result .= \Form::email($p_field_name, $field_value, $field_attr);
						break;
						case 'password':
							$field_value = '';
							$field_attr['autocomplete'] = 'new-password';
							if (!empty($this->id))
							{
								$field_attr['placeholder'] = str_replace(' *', '', $field_attr['placeholder']);
							}
							$required = false;
							$result .= \Form::password($p_field_name, $field_attr);
						break;
						default:
							$result .= \Form::text($p_field_name, $field_value, $field_attr);
						break;
					}

					if (!empty($this->hasError($p_field_name)))
					{
						$result .= sprintf('<small id="%sHelp" class="form-text text-danger">%s</small>', $p_field_name, $this->getErrorAsString($p_field_name));
					}
				break;
				case 'text':
					$field_attr['maxlength'] = $metadata['max_length'];
					$result .= \Form::textarea($p_field_name, $field_value, $field_attr);
					if (!empty($this->hasError($p_field_name)))
					{
						$result .= sprintf('<small id="%sHelp" class="form-text text-danger">%s</small>', $p_field_name, $this->getErrorAsString($p_field_name));
					}
				break;
				case 'enum':
					$list = array_combine($metadata['options'], $metadata['options']);
					$result .= \Form::select($p_field_name, $list, $field_value, $field_attr);
					if (!empty($this->hasError($p_field_name)))
					{
						$result .= sprintf('<small id="%sHelp" class="form-text text-danger">%s</small>', $p_field_name, $this->getErrorAsString($p_field_name));
					}
				break;
				case 'tinyint':
					$result  = '<div class="form-check">';
					$result .= \Form::checkbox($p_field_name, '1', $field_value, ['class' => 'form-check-input']);

					$asterisk   = ($required) ? '&nbsp;*' : '';
					$label_text = ($metadata['comment'] ?? $p_field_name);
					$hook_name  = hook_name(sprintf('site_cadastro_label_%s', $p_field_name));
					$label_text = \Hook::apply_filters($hook_name, $label_text);
					$label_text = $label_text . $asterisk;

					$result .= \Form::label($p_field_name, ($metadata['comment'] ?? $p_field_name) . (($required) ? ' *' : ''), ['class' => 'form-check-label']);
					$result .= '</div>';
					if (!empty($this->hasError($p_field_name)))
					{
						$result .= sprintf('<small id="%sHelp" class="form-text text-danger">%s</small>', $p_field_name, $this->getErrorAsString($p_field_name));
					}
				break;
				default:
					dump('MasterModel Input');
					dump($metadata);
				break;
			}
		}

		if ($opt_divwrap)
		{
			$result .= '</div>';
		}

		return $result;
	}

	public function label($p_field_name, $p_required = false)
	{
		$metadata   = self::getFieldMetaData($p_field_name);
		$asterisk   = ($p_required) ? '&nbsp;*' : '';
		$label_text = ($metadata['comment'] ?? $p_field_name);
		$hook_name  = hook_name(sprintf('site_cadastro_label_%s', $p_field_name));
		$label_text = \Hook::apply_filters($hook_name, $label_text);
		$label_text = $label_text . $asterisk;

		return \Form::label($p_field_name, $label_text, ['class' => 'control-label']);
	}
}