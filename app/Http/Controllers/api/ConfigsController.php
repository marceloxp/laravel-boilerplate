<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Config;
use App\Http\Umstudio\Cached;

class ConfigsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = Cached::get
		(
			'admin',
			['config', 'get'],
			function()
			{
				return Config::select('id','name','value')->get();
			}
		);

		return response($data['value'])->withHeaders($data['header']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

			$valid = Config::validate($request->all());
            if (!$valid['success'])
            {
                return $valid;
            }

            $register = Config::create($request->all());
            if ($register->save())
            {
                $result['success'] = true;
                $result['tag']     = $register->id;
                $result['message'] = 'Dados gravados com sucesso.';
            }
        }
        catch(\Exception $e)
        {
            $result['success'] = false;
            $result['message'] = 'Ocorreu um erro na gravação dos dados.';
            $result['error']   = $e->getMessage();
        }

        return $result;
    }
}