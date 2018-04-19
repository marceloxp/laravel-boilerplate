/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */
var umsappadmin = umsappadmin || {};
umsappadmin.Tedit = function($, objname, options)
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
		self.addInputMasks();
		self.onChangeImages();
	};

	this.addInputMasks = function()
	{
		jQuery('#cep').simpleMask({ 'mask': 'cep', 'nextInput': false });
		jQuery('#cpf').simpleMask({ 'mask': 'cpf', 'nextInput': false });
		jQuery('#phone,#celular,#telefone').simpleMask({ 'mask': 'ddd-tel9', 'nextInput': false });
	};

	this.onChangeImages = function()
	{
		$(document).on
		(
			'change',
			'.input_image_file',
			function(e)
			{
				self.onChangeImage($(this)[0]);
			}
		);
	};

	this.onChangeImage = function(input)
	{
		if (input.files && input.files[0])
		{
			var $image_preview = $(input).closest('.image_form_group').find('.uploaded_image');
			var reader = new FileReader();
			reader.onload = function (e)
			{
				$image_preview.attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	};

	CjsBaseClass.call(this, $, objname, options);
};

$(function()
{
	//Initialize Select2 Elements
	$('.select2').select2();
});

umsappadmin.edit = new umsappadmin.Tedit
(
	window.cjsbaseclass_jquery,
	'edit',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto',
		'another_opt' : 'custom_value'
	}
);