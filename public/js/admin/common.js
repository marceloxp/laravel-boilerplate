/* g'lobal CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsadmin = umsadmin || {};
umsadmin.Tcommon = function($, objname, options)
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
			'click',
			'.btn-select-add',
			function(e)
			{
				e.preventDefault();
				self.onSelectAddClick($(this));
			}
		);
	};

	this.onSelectAddClick = function($button)
	{
		var $select = $button.closest('.row').find('select');

		swal
		(
			'Digite o nome do novo Ítem',
			{
				content: 'input',
				buttons: [true, 'OK'],
			}
		)
		.then
		(
			function(input_text)
			{
				if (input_text !== null)
				{
					$select.append( $('<option>', { 'value': input_text, text: input_text } ) ).val(input_text);
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

umsadmin.common = new umsadmin.Tcommon
(
	window.cjsbaseclass_jquery,
	'common',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto'
	}
);