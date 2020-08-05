<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Http\Utilities\Result;

class CoreAjaxController extends AdminController
{
	public function index(Request $request, $schema, $table, $action)
	{
		$controller_method = str_camel(sprintf('on-%s-%s-%s', $schema, $table, $action));
		if (!method_exists($this, $controller_method))
		{
			return Result::error(sprintf('Método não definido (%s).', $controller_method));
		}
		return $this->$controller_method($request, $request->get('ids'));
	}
}