/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsappadmin = umsappadmin || {};
umsappadmin.Ttree = function($, objname, options)
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
		
	};

	this.execute = function()
	{
		// jQuery('.tree').treegrid( { 'treeColumn': 1 } );
	};

	CjsBaseClass.call(this, $, objname, options);
};

umsappadmin.tree = new umsappadmin.Ttree
(
	window.cjsbaseclass_jquery,
	'tree',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto'
	}
);