/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var webapp = webapp || {};
webapp.Tproduct_form = function($, objname, options)
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

webapp.product_form = new webapp.Tproduct_form
(
	window.cjsbaseclass_jquery,
	'product_form',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto',
		'another_opt' : 'custom_value'
	}
);