<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuditTrait
{
	public static function boot()
	{
		parent::boot();

		self::created
		(
			function($model)
			{
				$user = Auth::user();
				$user_id = @$user->id ?? null;
				$user_name = @$user->name ?? null;

				$data = 
				[
					'user_id'    => $user_id,
					'username'   => $user_name,
					'name'       => 'created',
					'table'      => $model->table,
					'url'        => url()->full(),
					'ip'         => request()->ip(),
					'useragent'  => request()->server('HTTP_USER_AGENT'),
					'oldvalue'   => collect([])->toJson(),
					'newvalue'   => collect($model->toArray())->except(['password'])->toJson(),
					'flags'      => 0,
				];

				\App\Models\Common\Audit::create($data)->save();
			}
		);

		self::updating
		(
			function($model)
			{
				$user = Auth::user();
				$user_id = @$user->id ?? null;
				$user_name = @$user->name ?? null;

				$data = 
				[
					'user_id'    => $user_id,
					'username'   => $user_name,
					'name'       => 'updated',
					'table'      => $model->table,
					'url'        => url()->full(),
					'ip'         => request()->ip(),
					'useragent'  => request()->server('HTTP_USER_AGENT'),
					'oldvalue'   => collect($model->getOriginal())->except(['password'])->toJson(),
					'newvalue'   => collect($model->toArray())->except(['password'])->toJson(),
					'flags'      => 0,
				];

				\App\Models\Common\Audit::create($data)->save();
			}
		);
	}
}