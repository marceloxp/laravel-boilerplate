<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\MasterModel;

class Contact extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name'       => 'required|max:255',
			'subject'    => 'required|max:255',
			'state'      => 'in:AC,AL,AM,AP,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RO,RS,RR,SC,SE,SP,TO|required|max:2',
			'city'       => 'required|max:128',
			'email'      => 'required|max:128',
			'phone'      => 'required|max:128',
			'message'    => 'required|max:65535',
		];
		return Role::_validate($request, $rules, $id);
	}
	
}