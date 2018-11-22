<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Payment extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];
	protected $casts   = ['discount' => 'float'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'       => 'required|max:124',
			'title'      => 'required|max:124',
			'type'       => 'in:creditcard,boleto,avista|required|max:10',
			'discount'   => 'required',
			'parcs'      => 'required',
		];
		return Role::_validate($request, $rules, $id);
	}
}