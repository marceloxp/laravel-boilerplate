<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
