<?php

namespace App\Http\Middleware;

use Closure;

class CheckReferer
{
	private function getErrorResult($p_message)
	{
		return [
			'result'  => true,
			'success' => false,
			'tag'     => 0,
			'message' => $p_message,
		];
	}

	private function returnJsonError($p_message)
	{
		return response()->json($this->getErrorResult($p_message));
	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$is_ajax = $request->ajax();
		if (!$is_ajax)
		{
			abort(500, 'Requisição inválida.');
		}

		try
		{
			$url = $_SERVER['HTTP_REFERER'] ?? null;
			if (empty($url))
			{
				return $this->returnJsonError('Requisição inválida.');
			}

			$parsed = parse_url($url);
			$site = $parsed['host'] ?? null;
			if (empty($site))
			{
				return $this->returnJsonError('Origem de dados inválida.');
			}

			$authorized = \App\Models\Site::where('url', $site)->count();
			if (!$authorized)
			{
				return $this->returnJsonError('Origem de dados não autorizada.');
			}

			$request->headers->add(compact('url','site'));

			return $next($request);
		}
		catch (\Exception $e)
		{
			return $this->returnJsonError($e->getMessage());
		}
    }
}
