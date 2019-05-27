<?php
if (!function_exists('admin_breadcrumb'))
{
	function admin_breadcrumb($p_array, $p_icon = '')
	{
		$result  = '<ol class="breadcrumb">';
		foreach ($p_array as $key => $label)
		{
			if ( (!empty($p_icon)) && ($key === 0) )
			{
				$result .= sprintf('<li><a href="#"><i class="%s"></i> %s</a></li>', $p_icon, $label);
			}
			else
			{
				$result .= sprintf('<li><a href="#">%s</a></li>', $label);
			}
		}
		$result .= '</ol>';

		return $result;
	}
}