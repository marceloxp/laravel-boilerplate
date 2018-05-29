<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SearchmodalController extends AdminController
{
	public function index(Request $request)
	{
		$options = $request->get('options');
		$page = $request->query('page', 1);

		$model = sprintf('\App\Models\%s', ucfirst(strtolower($options['model'])));
		$table = $model::select();
		if (array_key_exists('find', $options))
		{
			foreach ($options['find']['fields'] as $key => $find_value)
			{
				$table->orWhere
				(
					$find_value,
					'like',
					'%' . str_replace(' ', '%', $options['find']['value']) . '%'
				);
			}
		}
		$table->select($options['fields']);
		$captions = $model::translateNameCaptions($options['fields']);

		$registers = $table->paginate(10);
		$data = $registers->toArray();
		$data = $data['data'];

		$table_header = collect($captions)->transform
		(
			function($value, $key)
			{
				return '<th>' . $value . '</th>';
			}
		)->values()->implode('');
		$table_header = '<tr>' . $table_header . '</tr>';

		$table = '<table class="table table-bordered table-condensed table-hover table-striped">';
		$table .= $table_header;

		$trs = collect($data)->transform
		(
			function($reg, $k) use ($options)
			{
				$ids = $reg['id'];
				$line = collect($reg)->transform
				(
					function($field_value, $field_name) use ($ids, $options)
					{
						$display_value = $field_value;
						if ($field_name == 'name')
						{
							if (str2bool($options['multiple']))
							{
								$display_value = sprintf('<div class="checkbox" style="margin-top: 0px; margin-bottom: 0px;"><label><input data-ids="%s" type="checkbox" name="register"> %s </label></div>', $ids, $field_value);
							}
							else
							{
								$value = $options['value'] ?? null;
								$checked = ($ids == $value) ? 'checked' : '';
								$display_value = sprintf('<div class="radio" style="margin-top: 0px; margin-bottom: 0px;"><label><input data-ids="%s" type="radio" %s name="register"> %s </label></div>', $ids, $checked, $field_value);
							}
						}
						return '<td>' . $display_value . '</td>';
					}
				)->values()->implode('');

				return '<tr>' . $line . '</tr>';
			}
		);

		$links = $registers->withPath('');
		$links = str_replace('<ul class="pagination">', '<ul class="pagination pull-left" style="margin: 0px">', $links);

		$table .= $trs->values()->implode('');
		$table .= '</table>';

		$result = '
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="frSearch">Pesquisar</label>
						<input class="form-control" type="text" id="frSearch" name="frSearch" value="' . ($options['find']['value'] ?? '') . '" placeholder="Pesquisar">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						' . $table . '
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-xs-6 col-md-6">
						' . $links . '
					</div>
					<div class="col-xs-6 col-md-6">
						<button type="button" class="btn btn-danger"  id="search-modal-cancel"><i class="fa fa-fw fa-close"></i> Cancelar</button>
						<button type="button" class="btn btn-success" id="search-modal-select"><i class="fa fa-fw fa-check"></i> Selecionar</button>
					</div>
				</div>
			</div>
			<script>window.search_options = ' . collect($data)->toJson() . '</script>
		';

		return $result;
	}
}