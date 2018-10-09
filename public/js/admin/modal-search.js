/* g'lobal CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsadmin = umsadmin || {};
umsadmin.Tmodal_search = function($, objname, options)
{
	'use strict';
	var self = this;
	var $ = jQuery;
	
	this.create = function()
	{
		self.default_options = 
		{
			'debug'      : self.options.debug,
			'highlighted': self.options.highlighted
		};

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
			'#search-modal-select',
			function(e)
			{
				e.preventDefault();
				self.selectRegister();
			}
		);

		$(document).on
		(
			'click',
			'#search-modal-cancel',
			function(e)
			{
				e.preventDefault();
				self.cancelModalSearch();
			}
		);

		$(document).on
		(
			'click',
			'#search-modal .modal-footer ul.pagination li > a',
			function(e)
			{
				e.preventDefault();
				var page = $(this).attr('href');
				page = page.replace('?page=', '');
				self.getPage(page);
			}
		);

		$(document).on
		(
			'click',
			'.search-modal-field',
			function(e)
			{
				e.preventDefault();
				self.openModalByInput($(this));
			}
		);

		$(document).on
		(
			'keydown',
			'#frSearch',
			function(e)
			{
				self.lock('autosearch');
			}
		);

		$(document).on
		(
			'keyup',
			'#frSearch',
			function(e)
			{
				self.unlock('autosearch');
				if (self.tm_search !== undefined)
				{
					clearTimeout(self.tm_search);
					delete self.tm_search;
				}

				self.tm_search = setTimeout
				(
					function()
					{
						if (!self.islocked('autosearch'))
						{
							self.search_str = $('#frSearch').val();
							if (self.last_search !== undefined)
							{
								if (self.last_search == self.search_str)
								{
									self.log.print('IS THE SAME SEARCH!!!');
									return;
								}
							}
							self.autoSearch();
							self.lock('autosearch');
						}
					},
					750
				);
			}
		);
	};

	this.autoSearch = function()
	{
		if ( (self.search_str == '') && (self.last_search != self.search_str) )
		{
			self.options.find.value = '';
			self.setOptions(self.options);
			self.last_search = '';
			return;
		}

		self.last_search = self.search_str;
		if ( (self.search_str != '') && (self.search_str.length >= 3) )
		{
			self.options.find.value = self.search_str;
			self.setOptions(self.options);
		}
	};

	this.execute = function()
	{
		if (self.make_on_ready !== undefined)
		{
			self.setOptions(self.make_on_ready);
		}
	};

	this.openModalByInput = function($element)
	{
		var model   = $element.attr('data-model');
		var caption = $element.attr('data-caption');
		var field   = $element.attr('data-field');
		var value   = $('#' + field).val();

		self.setOptions
		(
			{
				'model'   : model,
				'fields'  : ['id', 'name'],
				'multiple': false,
				'caption' : 'Localizar ' + caption,
				'field'   : field,
				'value'   : value,
				'find'    :
				{
					'fields': ['name'],
					'value' : ''
				}
			}
		);
	};

	this.cancelModalSearch = function()
	{
		window.search_options = [];
		$('#search-modal').modal('hide');
		self.trigger('on-search-modal-cancel');
	};

	this.getRegisterById = function(p_ids)
	{
		var register;
		for(var k in window.search_options)
		{
			register = window.search_options[k];
			if (register.id == p_ids)
			{
				return register;
			}
		}
		return null;
	};

	this.selectRegister = function()
	{
		if (self.options.multiple === false)
		{
			if ($('#search-modal input[name=register]:checked').length <= 0) { return; }
			var ids = $('#search-modal input[name=register]:checked').attr('data-ids');
			var value = self.getRegisterById(ids);
			self.trigger('on-search-modal-select', value);
			$('#search-modal').modal('hide');

			if (self.options.field !== undefined)
			{
				$('#' + self.options.field + '_text').val(value.id + ' - ' + value.name);
				$('#' + self.options.field).val(value.id);
			}
		}
		else
		{
			var ids = [];
			$('#search-modal input[name=register]:checked').each
			(
				function()
				{
					ids.push($(this).attr('data-ids'));
				}
			);
			
			$('#search-modal').modal('hide');

			if (self.options.events !== undefined)
			{
				if (self.options.events.onMultipleSelect !== undefined)
				{
					self.options.events.onMultipleSelect(ids);
				}
			}
		}
	};

	this.show = function()
	{
		window.search_options = {};
		$('#search-modal .modal-title').html(self.options.caption);
		$('#search-modal').modal();
		self.getPage(1);
	};

	this.disableModal = function()
	{
		$('#search-modal input[name=register]').prop('disabled', true);
		$('#search-modal #search-modal-cancel').prop('disabled', true);
		$('#search-modal #search-modal-select').prop('disabled', true);
		$('#search-modal .modal-all-body').css('opacity', '0.5');
	};

	this.enableModal = function()
	{
		$('#search-modal .modal-all-body').css('opacity', '1');
		setTimeout
		(
			function()
			{
				$('#frSearch').select().focus();
			},
			500
		);
	};

	this.getPage = function(p_page)
	{
		self.disableModal();
		var options = Object.assign({}, self.options);
		delete options.events;
		self.ajax
		(
			{
				'options':
				{
					slug     : 'modal-search',
					exclusive: true,
					autowait : 'auto',
					url      : datasite.url.admin + '/modal-search?page=' + p_page,
					type     : 'GET',
					dataType : 'html',
					data     :
					{
						'_token' : datasite.csrf_token,
						'options': options
					}
				},
				'done': function(p_response)
				{
					$('#search-modal .modal-all-body').html(p_response);
				},
				'fail': function()
				{
					swal('Atenção!', 'Ocorreu um erro na solicitação!', 'error');
				},
				'always': function()
				{
					self.enableModal();
				},
				'exception': function()
				{
					swal('Atenção!', 'Ocorreu um erro na solicitação!', 'error');
				}
			}
		);
	};

	this.setOptions = function(p_options)
	{
		window.search_options = [];

		if (self.default_options === undefined)
		{
			self.make_on_ready = p_options;
			return;
		}

		self.options = Object.assign(self.default_options, p_options);

		if (self.options.fields === undefined)
		{
			self.options.fields = ['id', 'name'];
		}
		if (self.options.caption === undefined)
		{
			self.options.caption = 'Buscar em ' + self.options.table;
		}

		self.show();
	}

	CjsBaseClass.call(this, $, objname, options);
};

window.search_options = [];

umsadmin.modal_search = new umsadmin.Tmodal_search
(
	window.cjsbaseclass_jquery,
	'modal_search',
	{
		'debug'      : CJS_DEBUG_MODE_1,
		'highlighted': 'auto'
	}
);

function admin_modal_search(p_options)
{
	umsadmin.modal_search.setOptions(p_options);
}

// admin_modal_search
// (
// 	{
// 		'model'   : 'State',
// 		'fields'  : ['id', 'uf', 'name'],
// 		'multiple': false,
// 		'caption' : 'Localizar Estado',
// 		'field'   : 'state_id',
// 		'find'    :
// 		{
// 			'fields': ['name'],
// 			'value' : ''
// 		},
// 		'events':
// 		{
// 			'onSelect'         : function(p_id) { console.log('On Select', p_id); },
// 			'onMultipleSelect' : function(p_ids){ console.log('On Multiple Select', p_ids); },
// 		}
// 	}
// );

// admin_modal_search( { 'model': 'State', 'fields': ['id', 'uf', 'name'], 'multiple': false, 'caption': 'Localizar Estado', 'field': 'state_id', 'find': {'fields': ['name'], 'value': ''} } );
