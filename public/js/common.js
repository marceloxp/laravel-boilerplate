/* g'lobal CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsapp = umsapp || {};
umsapp.Tcommon = function($, objname, options)
{
	'use strict';
	var self = this;
	
	this.create = function()
	{
		self.var_name = 'var_value';
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
	
	};

	this.execute = function()
	{
		// AUTO STARTED CODE ON CLASS READY AND STARTED
	};

	CjsBaseClass.call(this, $, objname, options);
};

umsapp.common = new umsapp.Tcommon
(
	window.cjsbaseclass_jquery,
	'common',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto',
		'another_opt' : 'custom_value'
	}
);