/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsappadmin = umsappadmin || {};
umsappadmin.Tindex = function($, objname, options)
{
	'use strict';
	var self = this;

	this.create = function()
	{
		self.search = 
		{
			'field': '',
			'value': ''
		};
		self.order_template = '<div class="btn btn-default"><a class="btn-order-invert"><i class="fa fa-fw fa-arrow-{{direction}}" data-toggle="tooltip" data-original-title="Inverter" data-placement="bottom" style="color:black"></i></a> {{Caption}} <a class="btn-remove-order" data-field="{{field_name}}"><i class="fa fa-fw fa-close" style="color: red" data-toggle="tooltip" data-original-title="Remover" data-placement="bottom"></i></a></div>';
		self.events.onCreate();
	};

	this.onReady = function()
	{
		self.events.onReady();
	};

	this.start = function()
	{
		self.events.onStarted();
	};

	this.processTriggers = function()
	{

	};

	this.onElementsEvents = function()
	{
		$(document).on
		(
			'click',
			'.search_field',
			function(e)
			{
				e.preventDefault();
				self.onSearchFieldClick($(this));
			}
		);

		$(document).on
		(
			'submit',
			'#frmTable',
			function(e)
			{
				e.preventDefault();
				self.onSearchFormSubmit();
			}
		);

		$(document).on
		(
			'click',
			'#btn-table-add',
			function(e)
			{
				e.preventDefault();
				if ($(this).hasClass('pivot'))
				{
					self.onAddPivotButtonClick();
				}
				else
				{
					self.onCreateButtonClick();
				}
			}
		);

		$(document).on
		(
			'click',
			'#btn-table-edt',
			function(e)
			{
				e.preventDefault();
				self.onEditButtonClick();
			}
		);

		$(document).on
		(
			'click',
			'#btn-table-viw',
			function(e)
			{
				e.preventDefault();
				self.onViewButtonClick();
			}
		);

		$(document).on
		(
			'click',
			'.btn-table-pvt',
			function(e)
			{
				e.preventDefault();
				self.onPivotButtonClick($(this));
			}
		);

		$(document).on
		(
			'click',
			'#btn-table-many',
			function(e)
			{
				e.preventDefault();
				self.onManyButtonClick();
			}
		);

		$(document).on
		(
			'click',
			'#btn-table-del',
			function(e)
			{
				e.preventDefault();

				if ($(this).hasClass('pivot'))
				{
					self.onDeletePivotButtonClick();
				}
				else
				{
					self.onDeleteButtonClick();
				}
			}
		);

		$(document).on
		(
			'click',
			'#btn-table-exp',
			function(e)
			{
				e.preventDefault();
				self.onExportButtonClick();
			}
		);

		$(document).on
		(
			'click',
			'#ch-rows-all',
			function(e)
			{
				var checked = $(this).prop('checked');
				self.onCheckRowAllClick(checked);
			}
		);

		$(document).on
		(
			'click',
			'.ck-row',
			function(e)
			{
				self.onCheckRowClick();
			}
		);

		$(document).on
		(
			'click',
			'.btn-order-add',
			function(e)
			{
				if (empty($('#select-field-order').val()))
				{
					return;
				}
				if ($('.tooltip').length > 0)
				{
					jQuery('[data-toggle="tooltip"]').tooltip('hide');
					$('#select-field-order').focus();
				}
				self.onButtonAddOrderClick($(this));
			}
		);

		$(document).on
		(
			'click',
			'.btn-remove-order',
			function(e)
			{
				jQuery('[data-toggle="tooltip"]').tooltip('hide');
				self.onButtomRemoveOrderClick($(this));
			}
		);

		$(document).on
		(
			'click',
			'.btn-order-invert',
			function(e)
			{
				self.onButtonInvertClick($(this));
			}
		);
	};

	this.execute = function()
	{
		self.addDateRangePicker();
		self.ajustSearchForm();
		self.checkButtonsEdit();
	};

	this.onCheckRowClick = function()
	{
		self.checkButtonsEdit();

		if ($('.ck-row:checked').length < $('.ck-row').length)
		{
			if ($('#ch-rows-all').prop('checked') === true)
			{
				$('#ch-rows-all').prop('checked', false);
				return;
			}
		}

		if ($('.ck-row:checked').length == $('.ck-row').length)
		{
			if ($('#ch-rows-all').prop('checked') === false)
			{
				$('#ch-rows-all').prop('checked', true);
				return;
			}
		}
	};

	this.checkButtonsEdit = function()
	{
		if ($('.ck-row:checked').length == 1)
		{
			$('#btn-table-edt,#btn-table-viw,.btn-table-pvt,#btn-table-many').removeClass('disabled');
		}
		else
		{
			$('#btn-table-edt,#btn-table-viw,.btn-table-pvt,#btn-table-many').addClass('disabled');
		}

		if ($('.ck-row:checked').length > 0)
		{
			$('#btn-table-del').removeClass('disabled');
		}
		else
		{
			$('#btn-table-del').addClass('disabled');
		}
	};

	this.onCheckRowAllClick = function(p_checked)
	{
		$('.ck-row').prop('checked', p_checked);
		self.checkButtonsEdit();
	};

	this.onSearchFieldClick = function($element)
	{
		if ($element.attr('data-field') == '___clear')
		{
			self.markSearchField('clear');
			$('#frmTable').submit();
		}
		else
		{
			var field_name = $element.attr('data-field');
			self.markSearchField(field_name);
			$('#table_search').focus().select();
		}
	};

	this.markSearchField = function(p_field_name)
	{
		if (p_field_name == 'clear')
		{
			$('#btn-search-field').html('Buscar por&nbsp;');
			$('#table_search').val('');
			self.search.field = '';
			self.search.value = '';
		}
		else
		{
			var $element = $('#search-fields-items li > a[data-field="' + p_field_name + '"]');
			self.search.field = $element.attr('data-field');
			self.search.value = $('#table_search').val();
			$('#btn-search-field > span:first').text($element.attr('data-caption'));
		}
	};

	this.onSearchFormSubmit = function()
	{
		var obj = {};
		self.search.value = $('#table_search').val();

		if (self.search.value !== '')
		{
			if (self.search.field !== '')
			{
				obj.field = self.search.field;
				obj.value = self.search.value;
			}
		}

		var search_orders = self.mountOrderQuery();
		if (!empty(search_orders.fields))
		{
			obj.fields = search_orders.fields.join(',');
			obj.orders = search_orders.orders.join(',');
		}

		var date_ini = $('#range_ini').val();
		var date_end = $('#range_end').val();
		if(!empty(date_ini))
		{
			obj.range_ini = date_ini;
			obj.range_end = date_end;
			obj.range_field = $('#select-field-date').val();
		}

		var url_string = Url.stringify(obj);
		var url = $('#frmTable').attr('action');
		if (!empty(url_string))
		{
			url += '/?' + url_string;
		}

		window.location.href = url;
	};

	this.ajustSearchForm = function()
	{
		var field_name = Url.queryString('field');
		var field_value = Url.queryString('value');
		if ((!empty(field_name)) && (!empty(field_value)) )
		{
			self.markSearchField(field_name);
			$('#table_search').val(field_value);
		}

		var fields = Url.queryString('fields');
		var orders = Url.queryString('orders');
		if ( (!empty(fields)) && (!empty(orders)) )
		{
			fields = fields.split(',');
			orders = orders.split(',');
			
			if (fields.length == orders.length)
			{
				for (var k = 0; k < fields.length; k++)
				{
					self.addOrderButton(fields[k], (orders[k] == 'ASC' ? 'down' : 'up'));
				}
			}
		}
	};

	this.onCreateButtonClick = function()
	{
		var new_url = datasite.url.current + '/edit';
		window.location.href = new_url;
	};

	this.onAddPivotButtonClick = function()
	{
		var onMultipleSelect = function(p_ids)
		{
			self.log.print('ids selecionados: ', p_ids);
			self.onPivotMultipleSelect(p_ids);
		};

		var search_options = 
		{
			'model'      : datasite.params.model_name,
			'fields'     : ['id', 'name'],
			'multiple'   : true,
			'except_ids' : datasite.params.ids,
			'caption'    : 'Localizar ' + datasite.params.panel_title,
			'find'       :
			{
				'fields': ['name'],
				'value' : ''
			},
			'events':
			{
				'onMultipleSelect': onMultipleSelect
			}
		};

		admin_modal_search(search_options);
	};

	this.onPivotMultipleSelect = function(p_ids)
	{
		var url = datasite.url.admin + '/' + datasite.params.pivot_scope.model + '/' + datasite.params.pivot_scope.param + '/attach';

		self.ajax
		(
			{
				'options':
				{
					'slug'     : 'pivot-add',
					'exclusive': false,
					'url'      : url,
					'type'     : 'POST',
					'dataType' : 'json',
					'data'     :
					{
						'_token' : datasite.csrf_token,
						'ids'    : p_ids
					}
				},
				'before': function()
				{
					
				},
				'done': function(p_response)
				{
					if (p_response.success)
					{
						swal('Sucesso!', p_response.message, 'success')
						.then
						(
							function()
							{
								window.location.reload();
							}
						);
					}
					else
					{
						swal('Atenção!', p_response.message, 'warning');
					}
				},
				'fail': function()
				{
					swal('Atenção!', 'Ocorreu um erro na requisição.', 'error');
				},
				'always': function()
				{
					
				},
				'exception': function()
				{
					swal('Atenção!', 'Ocorreu um erro na requisição.', 'error');
				}
			}
		);
	};

	this.onDeletePivotButtonClick = function()
	{
		if ($('.ck-row:checked').length <= 0)
		{
			return;
		}

		this.confirmDelete
		(
			function()
			{
				var ids = [];
				$('.ck-row:checked').each
				(
					function()
					{
						ids.push($(this).attr('data-ids'));
					}
				);
				ids = ids.join(',');

				var url = datasite.url.admin + '/' + datasite.params.pivot_scope.model + '/' + datasite.params.pivot_scope.param + '/detach';
				self.log.print(url);

				self.ajax
				(
					{
						'options':
						{
							'slug'     : 'on-delete',
							'exclusive': true,
							'url'      : url,
							'type'     : 'POST',
							'dataType' : 'json',
							'data'     :
							{
								'_token': datasite.csrf_token,
								'ids'   : ids
							}
						},
						'before': function()
						{
							
						},
						'done': function(p_response)
						{
							if (p_response.success)
							{
								swal('Sucesso!', p_response.message, 'success')
								.then
								(
									function()
									{
										window.location.reload();
									}
								);
							}
							else
							{
								swal('Atenção!', p_response.message, 'warning');
							}
						},
						'fail': function()
						{
							swal('Atenção!', 'Ocorreu um erro na requisição.', 'error');
						},
						'always': function()
						{
							
						},
						'exception': function()
						{
							swal('Atenção!', 'Ocorreu um erro na requisição.', 'error');
						}
					}
				);
			}
		);
	};

	this.onEditButtonClick = function()
	{
		if ($('.ck-row:checked').length !== 1)
		{
			return;
		}
		var ids = $('.ck-row:checked').attr('data-ids');
		if (empty(ids)){ return; }
		var new_url = datasite.url.current + '/edit/' + ids;
		window.location.href = new_url;
	};

	this.onViewButtonClick = function()
	{
		if ($('.ck-row:checked').length !== 1)
		{
			return;
		}
		var ids = $('.ck-row:checked').attr('data-ids');
		if (empty(ids)){ return; }
		var new_url = datasite.url.current + '/show/' + ids;
		window.location.href = new_url;
	};

	this.onPivotButtonClick = function($button)
	{
		if ($('.ck-row:checked').length !== 1)
		{
			return;
		}
		var ids = $('.ck-row:checked').attr('data-ids');
		var route = $button.attr('data-link');
		if (empty(ids)){ return; }
		var new_url = datasite.url.admin + '/' + route + '/' + ids;
		window.open(new_url);
	};

	this.onManyButtonClick = function()
	{
		if ($('.ck-row:checked').length !== 1)
		{
			return;
		}
		var ids = $('.ck-row:checked').attr('data-ids');
		var route = $('#btn-table-many').attr('data-link');
		if (empty(ids)){ return; }
		var new_url = datasite.url.admin + '/' + datasite.params.table_name + '/' + ids + '/' + route;
		window.open(new_url);
	};

	this.confirmDelete = function(p_callback)
	{
		swal
		(
			{
				title     : 'Atenção!',
				text      : 'Deseja realmente excluir o(s) registro(s) selecionado(s)?',
				icon      : 'error',
				buttons   : true,
				dangerMode: true,
		})
		.then
		(
			(willDelete) =>
			{
				if (willDelete)
				{
					p_callback();
				}
			}
		);
	}

	this.onDeleteButtonClick = function()
	{
		if ($('.ck-row:checked').length <= 0)
		{
			return;
		}

		this.confirmDelete
		(
			function()
			{
				var ids = [];
				$('.ck-row:checked').each
				(
					function()
					{
						ids.push($(this).attr('data-ids'));
					}
				);
				ids = ids.join(',');

				self.ajax
				(
					{
						'options':
						{
							slug     : 'on-delete',
							exclusive: true,
							url      : datasite.url.current + '/delete',
							type     : 'POST',
							dataType : 'json',
							data     :
							{
								'_token': datasite.csrf_token,
								'ids'   : ids
							}
						},
						'before': function()
						{
							
						},
						'done': function(p_response)
						{
							if (p_response.success)
							{
								swal('Sucesso!', p_response.message, 'success')
								.then
								(
									function()
									{
										window.location.reload();
									}
								);
							}
							else
							{
								swal('Atenção!', p_response.message, 'warning');
							}
						},
						'fail': function()
						{
							swal('Atenção!', 'Ocorreu um erro na requisição.', 'error');
						},
						'always': function()
						{
							
						},
						'exception': function()
						{
							swal('Atenção!', 'Ocorreu um erro na requisição.', 'error');
						}
					}
				);
			}
		);
	};

	this.onExportButtonClick = function()
	{
		var url = datasite.url.current + '/export/' + window.location.search;
		window.location.href = url;
	};

	this.onButtonAddOrderClick = function($button)
	{
		var field_name = $('#select-field-order option:selected').val();
		var direction = $button.attr('data-dir');
		self.addOrderButton(field_name, direction);
		$('#select-field-order').val(0);
	};

	this.addOrderButton = function(p_field_name, p_direction)
	{
		var template = this.order_template;
		var caption = $('#select-field-order option[value="' + p_field_name + '"').text();
		self.log.print(p_field_name, caption);
		$('#select-field-order option[value="' + p_field_name + '"').prop('disabled', true);
		template = template.replace('{{direction}}', p_direction);
		template = template.replace('{{Caption}}', caption);
		template = template.replace('{{field_name}}', p_field_name);
		$('#div-orders').append(template);
	};

	this.onButtomRemoveOrderClick = function($button)
	{
		var field_name = $button.attr('data-field');
		self.log.print(field_name);
		$button.closest('.btn-default').remove();
		$('#select-field-order option[value="' + field_name + '"]').prop('disabled', false);
	};

	this.onButtonInvertClick = function($button)
	{
		var $element = $button.find('i:first');
		if ($element.hasClass('fa-arrow-up'))
		{
			$element.removeClass('fa-arrow-up').addClass('fa-arrow-down');
		}
		else
		{
			$element.removeClass('fa-arrow-down').addClass('fa-arrow-up');
		}
	};

	this.mountOrderQuery = function()
	{
		var result = { 'fields': [], 'orders': [] };
		$('#div-orders .btn-default').each
		(
			function()
			{
				var $element = $(this);
				var field_name = $element.find('.btn-remove-order').attr('data-field');
				var field_order = $element.find('a:first i').hasClass('fa-arrow-down') ? 'ASC' : 'DESC';
				result.fields.push(field_name);
				result.orders.push(field_order);
			}
		);

		return result;
	};

	this.addDateRangePicker = function()
	{
		var br_format = 'DD/MM/YYYY';
		var en_format = 'YYYY-MM-DD';
		var $element = jQuery('#search-date');

		var options =
		{
			timePicker: false,
			autoUpdateInput: false,
			autoApply: false,
			locale:
			{
				'format'     : br_format,
				'applyLabel' : 'Aplicar',
				'cancelLabel': 'Cancelar',
				'daysOfWeek' : ['D','S','T','Q','Q','S','S'],
				'monthNames' : ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']
			}
		};

		var range_ini   = Url.queryString('range_ini');
		var range_end   = Url.queryString('range_end');
		var range_field = Url.queryString('range_field');
		if (range_ini && range_end && range_field)
		{
			options.startDate       = moment(range_ini, en_format).format(br_format);
			options.endDate         = moment(range_end, en_format).format(br_format);
			$element.val(options.startDate + ' - ' + options.endDate);
			var element_prefix = $element.attr('data-prefix');
			$('#' + element_prefix + '_ini').val(moment(range_ini, en_format).format(en_format));
			$('#' + element_prefix + '_end').val(moment(range_end, en_format).format(en_format));
			$('#select-field-date').val(range_field);
		}

		$element.daterangepicker
		(
			options,
			function(start, end, label)
			{
				var element_prefix = $element.attr('data-prefix');
				$('#' + element_prefix + '_ini').val(start.format(en_format));
				$('#' + element_prefix + '_end').val(end.format(en_format));
			}
		);

		$element.on
		(
			'apply.daterangepicker',
			function(ev, picker)
			{
				$(this).val(picker.startDate.format(br_format) + ' - ' + picker.endDate.format(br_format));
			}
		);

		$element.on
		(
			'cancel.daterangepicker',
			function(ev, picker)
			{
				$(this).val('');
				var element_prefix = $element.attr('data-prefix');
				$('#' + element_prefix + '_ini').val('');
				$('#' + element_prefix + '_end').val('');
			}
		);
	};

	CjsBaseClass.call(this, $, objname, options);
};

$(function()
{
	$('.select2').select2();
});

umsappadmin.index = new umsappadmin.Tindex
(
	window.cjsbaseclass_jquery,
	'index',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto',
		'another_opt' : 'custom_value'
	}
);