/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsadmin = umsadmin || {};
umsadmin.Tadmin_config_cache = function($, objname, options)
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
						'_token': datasite._token
					}
				},
				'done': function(p_response)
				{
					if (p_response.message)
					{
						alert(p_response.message);
					}
				},
				'fail': function()
				{
					alert('Ocorreu um erro na solicitação.');
				},
				'exception': function()
				{
					alert('Ocorreu um erro na solicitação.');
				}
			}
		);
	}

	CjsBaseClass.call(this, $, objname, options);
};

umsadmin.admin_config_cache = new umsadmin.Tadmin_config_cache
(
	window.cjsbaseclass_jquery,
	'admin_config_cache',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto'
	}
);