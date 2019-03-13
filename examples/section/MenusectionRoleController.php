<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Admin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use \App\Http\Utilities\Result;
use Hook;

class MenusectionRoleController extends MasterManyController
{
	public function __construct()
	{
		parent::__construct
		(
			'Seções',
			\App\Models\Menusection::class,
			\App\Models\Role::class
		);
	}
}