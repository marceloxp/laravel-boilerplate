<?php
if (!function_exists('html_to_attr'))
{
	function html_to_attr($p_attr)
	{
		$result = join
		(
			' ',
			array_map
			(
				function($key) use ($p_attr)
				{
					if(is_bool($p_attr[$key]))
					{
						return $p_attr[$key] ? $key : '';
					}
					return sprintf('%s="%s"', $key, $p_attr[$key]);
				},
				array_keys($p_attr)
			)
		);
		return $result;
	}
}

if (!function_exists('array_to_dropdown'))
{
	// laravel-nestable - helper
	function array_to_dropdown($p_array, $p_options = [])
	{
		function __itemToOptions($p_item, $p_level, $p_options)
		{
			$registers = (array_key_exists('id', $p_item)) ? [$p_item] : $p_item;
			foreach ($registers as $register)
			{
				$child      = $register['child'];
				$has_child  = (!empty($child));
				$optgroup   = $p_options['optgroup'];
				$prefix     = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $p_level);

				if ($optgroup && $has_child)
				{
					$result[] = sprintf('<optgroup data-slug="%s" value="%s" label="%s%s">', $register['slug'], $register['id'], $prefix, $register['name']);
					$result[] = implode(PHP_EOL, __itemToOptions($register['child'], ($p_level+1), $p_options));
					$result[] = '</optgroup>';
					return $result;
				}

				$selected = ($p_options['value'] == $register['id']) ? 'selected' : '';
				$item     = sprintf('<option value="%s" data-caption="%s" data-slug="%s" %s>%s%s</option>', $register['id'], $register['name'], $register['slug'], $selected, $prefix, $register['name']);
				$result[] = $item;

				if (!empty($register['child']))
				{
					foreach ($register['child'] as $item)
					{
						$result[] = implode(PHP_EOL, __itemToOptions($item, ($p_level+1), $p_options));
					}
				}
			}

			return $result;
		}

		$default = 
		[
			'add_first' => true,
			'name'      => 'select',
			'attr'      => [],
			'optgroup'  => false
		];

		$level     = 0;
		$options   = array_merge($default, $p_options);
		$html_attr = html_to_attr($options['attr']);
		$result    = [];
		$result[]  = sprintf('<select name="%s" %s >', $options['name'], $html_attr);
		if ($options['add_first'])
		{
			$level    = 1;
			$result[] = sprintf('<option value="0" data-caption="Topo" data-slug="topo">Topo</option>');
		}

		$p_array = collect($p_array)->toArrayDeep();
		foreach ($p_array as $_key => $_value)
		{
			$result[] = implode(PHP_EOL, __itemToOptions($_value, $level, $options));
		}
		$result[] = '</select>';

		$node_html = implode(PHP_EOL, $result);

		return $node_html;
	}
}
