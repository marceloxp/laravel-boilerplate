<?php

namespace App\Http\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \App\Http\Utilities\Cached;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

	public static function getNumericFields()
	{
		return collect(self::getFieldsMetaData())->where('type', 'decimal')->keys()->all();
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
			}
		);

		self::saved(function($model){
			// ... code here
		});

		self::creating(function($model){
			// ... code here
		});

		self::created(function($model){
			// ... code here
		});

		self::updating(function($model){
			// ... code here
		});

		self::updated(function($model){
			// ... code here
		});

		self::deleting(function($model){
			// ... code here
		});

		self::deleted(function($model){
			// ... code here
		});
	}

	public static function table()
	{
		return \DB::table(self::getTableName());
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
			['getTableCaption', $table_name],
			function() use ($table_name)
			{
				$query = sprintf
				(
					'SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = "%s" AND TABLE_NAME = "%s";',
					db_database_name(),
					db_prefixed_table($table_name)
				);
				$result = collect(DB::select($query))->pluck('TABLE_COMMENT')->first();

				return $result;
			},
			5
		);

		return $result['data'];
	}

	public static function getTableName()
	{
		$instanced_model = with(new static);
		$result = $instanced_model->getTable();
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

	public static function getFieldMetaData($p_field_name)
	{
		$metadata = self::getFieldsMetaData();
		return $metadata[$p_field_name];
	}

	public static function getFieldsMetaData($appends = [])
	{
		$table_name = self::getTableName();

		$result = Cached::get
		(
			'sys-model',
			['getFieldsMetaData', $table_name],
			function() use ($table_name)
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
					$value['comment'] = self::getTableFieldCaption($value['table_name'], 'name');
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
						$value['has_relation'] = true;
					}

					$result[$field_name] = $value;
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
			$result['error'] = $e->getMessage();
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
			'errors' => new \Illuminate\Support\ViewErrorBag(),
			'label'  => true,
			'attr'   => []
		];
		$options = array_merge($options, $p_options);
		extract($options, EXTR_PREFIX_ALL, 'opt');

		$result = '';

		$field_value = $this->$p_field_name ?? old($p_field_name, '');

		$field_attr = ['class' => 'form-control', 'placeholder' => $metadata['comment']];
		$required = false;
		if (!$metadata['nullable'])
		{
			$required = true;
			$field_attr['required'] = 'required';
		}

		if ($opt_label)
		{
			$result .= $this->label($p_field_name, $required);
		}

		if ($p_field_name == 'id')
		{
			unset($field_attr['required']);
			$result = \Form::hidden($p_field_name, $field_value, $field_attr);
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
					$field_attr['maxlength'] = $metadata['max_length'];
					switch ($p_field_name)
					{
						case 'email':
						case 'mail':
							$result .= \Form::email($p_field_name, $field_value, $field_attr);
						break;
						case 'password':
							$result .= \Form::text($p_field_name, null, $field_attr);
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
					$result .= \Form::checkbox($p_field_name, '1', true, ['class' => 'form-check-input']);
					$result .= \Form::label($p_field_name, ($metadata['comment'] ?? $p_field_name), ['class' => 'form-check-label']);
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

		return $result;
	}

	public function label($p_field_name, $p_required = false)
	{
		$metadata = self::getFieldMetaData($p_field_name);
		$asterisk = ($p_required) ? '&nbsp;*' : '';
		$label_text = ($metadata['comment'] ?? $p_field_name) . $asterisk;
		return \Form::label($p_field_name, $label_text, ['class' => 'control-label']);
	}
}