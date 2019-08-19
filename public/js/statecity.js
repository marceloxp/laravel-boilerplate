/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsapp = umsapp || {};
umsapp.Tstatecity = function($, objname, options)
{
	'use strict';
	var self = this;

	this.create = function()
	{
		self.$state = $('select[name="state"]');
		self.$city  = $('select[name="city_id"]');

		self.$state.addClass('join_select_state');
		self.$city.addClass('join_select_city');

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
			'.join_select_state',
			function(e)
			{
				e.preventDefault();
				var $first_option = self.$city.find('option:first');
				var first_value = $first_option.val();
				self.$city.empty();
				if (empty(first_value))
				{
					self.$city.append($first_option);
				}
				var state_value = self.$state.val();
				var cities = jQuery.grep
				(
					statecity,
					function(value)
					{
						return value.state_uf == state_value;
					}
				);
				var city;
				for (var k in cities)
				{
					city = cities[k];
					$(
						'<option/>',
						{
							'value' : city.city_id,
							'text'  : city.city_name
						}
					).appendTo(self.$city);
				}
			}
		);
	};

	this.execute = function()
	{
		// AUTO STARTED CODE ON CLASS READY AND STARTED
	};

	CjsBaseClass.call(this, $, objname, options);
};

umsapp.statecity = new umsapp.Tstatecity
(
	window.cjsbaseclass_jquery,
	'statecity',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto',
		'another_opt' : 'custom_value'
	}
);