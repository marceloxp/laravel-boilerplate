/* g'lobal CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var webapp = webapp || {};
webapp.Tcommon = function($, objname, options)
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

webapp.common = new webapp.Tcommon
(
	window.cjsbaseclass_jquery,
	'common',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto'
	}
);

function appendHeadStyle()
{
	$('head').append('<style id="swal_image" type="text/css"> .swal-modal { width: 570px; }  </style>');
}

function removeHeadStyle()
{
	$('#swal_image').remove();
}

function swal_image(element)
{
	appendHeadStyle();
	swal({'content': $(element).clone().removeAttr('style').css({'height': '500px', 'width': '500px'})[0]})
	.then
	(
		function()
		{
			removeHeadStyle();
		}
	);
}

function swal_confirm(p_text, p_callback)
{
	swal
	(
		{
			text       : p_text,
			icon       : 'warning',
			buttons    : true,
			dangerMode : true,
		}
	)
	.then
	(
		(will_confirm) =>
		{
			if (will_confirm)
			{
				if (p_callback !== undefined) { p_callback(); }
			}
		}
	);
}

function swal_success_reload(p_text)
{
	swal
	(
		{
			text : p_text,
			icon : 'success'
		}
	)
	.then
	(
		() =>
		{
			swal_reload();
		}
	);
}

function swal_error_reload(p_text)
{
	swal
	(
		{
			text : p_text,
			icon : 'error'
		}
	)
	.then
	(
		() =>
		{
			swal_reload();
		}
	);
}

function swal_wait()
{
	swal
	(
		{
			text : 'Aguarde',
			'buttons': false
		}
	)
}

function swal_reload()
{
	swal_wait();
	window.location.reload();
}