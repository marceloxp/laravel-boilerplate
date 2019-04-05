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

		// collect([ ['id' => 1, 'name' => ' Tony '], ['id' => 2, 'name' => '   Loki  '], ['id' => 4, 'name' => 'Peter'] ])->trim('name');
		// collect([' Tony ', '   Loki  ', 'Peter'])->trim();
		\Illuminate\Support\Collection::macro
		(
			'trim',
			function($p_field_name = '')
			{
				$this->transform
				(
					function($item, $key) use ($p_field_name)
					{
						if ($p_field_name)
						{
							$item[$p_field_name] = trim($item[$p_field_name]);
						}
						else
						{
							$item = trim($item);
						}
						return $item;
					}
				);
				return $this;
			}
		);

		// collect([ ['id' => 1, 'name' => 'Tony'], ['id' => 2, 'name' => 'Loki'], ['id' => 4, 'name' => 'Peter'] ])->toLower('name');
		// collect(['Tony', 'Loki', 'Peter'])->toLower();
		\Illuminate\Support\Collection::macro
		(
			'toLower',
			function($p_field_name = '')
			{
				$this->transform
				(
					function($item, $key) use ($p_field_name)
					{
						if ($p_field_name)
						{
							$item[$p_field_name] = mb_strtolower($item[$p_field_name]);
						}
						else
						{
							$item = mb_strtolower($item);
						}
						return $item;
					}
				);
				return $this;
			}
		);

		// collect([ ['id' => 1, 'name' => 'Tony Stark'], ['id' => 2, 'name' => 'Loki'], ['id' => 4, 'name' => 'Peter Paker'] ])->slugify('name');
		// collect(['Tony', 'Loki', 'Peter'])->slugify();
		\Illuminate\Support\Collection::macro
		(
			'slugify',
			function($p_field_name = '', $p_separator = '-')
			{
				$this->transform
				(
					function($item, $key) use ($p_field_name, $p_separator)
					{
						if ($p_field_name)
						{
							$item[$p_field_name] = str_slugify($item[$p_field_name], $p_separator);
						}
						else
						{
							$item = str_slugify($item, $p_separator);
						}
						return $item;
					}
				);
				return $this;
			}
		);
	}
}