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

if (!function_exists('admin_index_button'))
{
	// admin_index_button('btn-custom-action', 'one', 'btn-info', true, 'fas fa-times-circle', 'More info');
	function admin_index_button($p_button_id, $p_type, $p_color_style, $p_disabled, $p_icon, $p_text)
	{
		$result = sprintf
		(
			'<button id="%s" type="button" class="admin-index-button %s btn %s %s"><i class="%s"></i>&nbsp;&nbsp;%s</button>',
			$p_button_id,
			(mb_strtolower($p_type) == 'one') ? 'btn-check-one' : 'btn-check-many',
			$p_color_style,
			($p_disabled) ? 'disabled' : '',
			$p_icon,
			$p_text
		);
		return $result;
	}
}