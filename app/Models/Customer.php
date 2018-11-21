<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utilities\Cached;
use App\Http\Utilities\MasterModel;

class Customer extends MasterModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];
	
	public function __construct()
	{
		$this->caption = 'Clientes';
		parent::__construct();
	}

	public function address_type()
	{
		return $this->belongsTo(\App\Models\AddressType::class);
	}

    public static function validate($request, $id = '')
    {
		$rules = 
		[
			'address_type_id' => 'required',
			'name'            => 'required|max:128',
			'username'        => 'required|max:128',
			'born'            => 'required|max:10',
			'cpf'             => 'required|max:24',
			'email'           => 'required|max:128',
			'phone_prefix'    => 'required|max:3',
			'phone'           => 'required|max:24',
			'cep'             => 'required|max:10',
			'state'           => 'in:AC,AL,AM,AP,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RO,RS,RR,SC,SE,SP,TO|required|max:2',
			'city'            => 'required|max:128',
			'address'         => 'required|max:256',
			'address_number'  => 'max:64',
			'complement'      => 'max:128',
			'neighborhood'    => 'required|max:128',
			'newsletter'      => 'required',
			'rules'           => 'required',
			'status'          => 'in:Ativo,Inativo|required|max:7',
			'ip'              => 'max:64',
		];

		if (empty($id))
		{
			$rules[] = ['password' => 'required|max:128'];
		}

		return Config::_validate($request, $rules, $id);
    }
}
