<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Paymenttype extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'        => 'required|max:124',
			'description' => 'required|max:124',
		];
		return Role::_validate($request, $rules, $id);
	}
}