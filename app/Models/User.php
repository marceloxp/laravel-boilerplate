<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;
use App\Traits\AuditTrait;

class User extends MasterModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
	use Authenticatable, Authorizable, CanResetPassword, AuditTrait;

	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public function roles()
	{
		return $this->belongsToMany(\App\Models\Role::class);
	}

	/**
	* Check multiple roles
	* @param array $roles
	*/
	public function hasAnyRole($roles)
	{
		return null !== $this->roles()->whereIn('name', $roles)->first();
	}

	/**
	* Check one role
	* @param string $role
	*/
	public function hasRole($role)
	{
		return null !== $this->roles()->where('name', $role)->first();
	}

	/**
	* @param string|array $roles
	*/
	public function authorizeRoles($roles)
	{
		if (empty($roles))
		{
			return true;
		}

		if (is_array($roles))
		{
			return $this->hasAnyRole($roles) || abort(500, 'This action is unauthorized.');
		}

		return $this->hasRole($roles) || abort(500, 'This action is unauthorized.');
	}

    public static function validate($request, $id = '')
    {
		$rules = 
		[
			'name'  => 'required|min:3|max:150',
			'email' => 'required|min:5|max:255|unique:users,email,' . $id
		];

		if (!$id)
		{
			$rules['password'] = 'required|min:4|max:255';
		}

		return User::_validate($request, $rules, $id);
    }
}