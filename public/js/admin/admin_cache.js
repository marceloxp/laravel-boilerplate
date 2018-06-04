/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsadmin = umsadmin || {};
umsadmin.Tadmin_cache_index = function($, objname, options)
{
	'use strict';
	var self = this;

	this.create = function()
	{
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
			'change',
			'#usecache',
			function(e)
			{
				e.preventDefault();
				var value = $('#usecache').val();
				self.changeCacheUse(value);
			}
		);
	};

	this.execute = function()
	{
		// AUTO STARTED CODE ON CLASS READY AND STARTED
	};

	this.changeCacheUse = function(p_value)
	{
		self.ajax
		(
			{
				'options':
				{
					slug     : 'admin_change_cache_use',
					exclusive: true,
					autowait : 'auto',
					url      : datasite.url.admin + '/cache/use',
					type     : 'POST',
					dataType : 'json',
					data     :
					{
						'use'   : p_value,
						'_token': datasite.csrf_token
					}
				},
				'done': function(p_response)
				{
					if (p_response.message)
					{
						swal('Sucesso!', p_response.message, 'success');
					}
				},
				'fail': function()
				{
					swal('Atenção!', 'Ocorreu um erro na solicitação.', 'error');
				},
				'exception': function()
				{
					swal('Atenção!', 'Ocorreu um erro na solicitação.', 'error');
				}
			}
		);
	}

	CjsBaseClass.call(this, $, objname, options);
};

umsadmin.admin_cache_index = new umsadmin.Tadmin_cache_index
(
	window.cjsbaseclass_jquery,
	'admin_cache_index',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto'
	}
);