<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class XpCollectionServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// $a = [ ['id' => 1, 'name' => 'Marcelo', 'color' => 'bg-green'], ['id' => 2, 'name' => 'Gomes', 'color' => 'bg-orange'] ]; collect($a)->toBootstrapLabel();
		\Illuminate\Support\Collection::macro
		(
			'toBootstrapLabel',
			function()
			{
				return $this->map
				(
					function($value)
					{
						return bs_label($value['id'], $value['name'], $value['color']);
					}
				);
			}
		);

		// $a = ['Marcelo', 'Gomes']; collect($a)->toText();
		// $a = [ ['id' => 1, 'name' => 'Marcelo', 'color' => 'bg-green'], ['id' => 2, 'name' => 'Gomes', 'color' => 'bg-orange'] ]; collect($a)->toBootstrapLabel()->toText();
		\Illuminate\Support\Collection::macro
		(
			'toText',
			function($p_glue = '')
			{
				return implode($p_glue, $this->toArray());
			}
		);

		// collect([ ['id' => 1, 'name' => 'Tony'], ['id' => 2, 'name' => 'Loki'], ['id' => 4, 'name' => 'Peter'] ])->extract('name');
		\Illuminate\Support\Collection::macro
		(
			'extract',
			function($p_field_name)
			{
				$result = collect([]);
				$this->each
				(
					function($item, $key) use ($p_field_name, $result)
					{
						$result->push($item[$p_field_name]);
					}
				);
				return $result;
			}
		);
	}
}
