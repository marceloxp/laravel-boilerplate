/* global CjsBaseClass,CJS_DEBUG_MODE_0,CJS_DEBUG_MODE_1,CJS_DEBUG_MODE_2 */

/* 
	Create SLUG from a string
	This function rewrite the string prototype and also 
	replace latin and other special characters.

	Forked by Gabriel Froes - https://gist.github.com/gabrielfroes
	Original Author: Mathew Byrne - https://gist.github.com/mathewbyrne/1280286
 */
if (!String.prototype.slugify) {
	String.prototype.slugify = function () {

	return  this.toString().toLowerCase()
	.replace(/[àÀáÁâÂãäÄÅåª]+/g, 'a')       // Special Characters #1
	.replace(/[èÈéÉêÊëË]+/g, 'e')       	// Special Characters #2
	.replace(/[ìÌíÍîÎïÏ]+/g, 'i')       	// Special Characters #3
	.replace(/[òÒóÓôÔõÕöÖº]+/g, 'o')       	// Special Characters #4
	.replace(/[ùÙúÚûÛüÜ]+/g, 'u')       	// Special Characters #5
	.replace(/[ýÝÿŸ]+/g, 'y')       		// Special Characters #6
	.replace(/[ñÑ]+/g, 'n')       			// Special Characters #7
	.replace(/[çÇ]+/g, 'c')       			// Special Characters #8
	.replace(/[ß]+/g, 'ss')       			// Special Characters #9
	.replace(/[Ææ]+/g, 'ae')       			// Special Characters #10
	.replace(/[Øøœ]+/g, 'oe')       		// Special Characters #11
	.replace(/[%]+/g, 'pct')       			// Special Characters #12
	.replace(/\s+/g, '-')           		// Replace spaces with -
    .replace(/[^\w\-]+/g, '')       		// Remove all non-word chars
    .replace(/\-\-+/g, '-')         		// Replace multiple - with single -
    .replace(/^-+/, '')             		// Trim - from start of text
    .replace(/-+$/, '');            		// Trim - from end of text
    
	};
}

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
		self.maskMoney();
		self.verifySlugFields();
		self.addSummerNote();
		self.setFirstFocus();
		jQuery('.icp-auto').iconpicker();
	};

	this.addSummerNote = function()
	{
		if (jQuery('.summernote').length > 0)
		{
			jQuery('.summernote').summernote
			(
				{
					'height': 300,
					'codemirror':
					{
						'mode'        : 'text/html',
						'htmlMode'    : true,
						'lineNumbers' : true,
						'theme'       : 'monokai'
					},
				}
			);
		}
	};

	this.addInputMasks = function()
	{
		jQuery('#cep').simpleMask({ 'mask': 'cep', 'nextInput': false });
		jQuery('#cpf').simpleMask({ 'mask': 'cpf', 'nextInput': false });
		jQuery('#phone,#celular,#telefone').simpleMask({ 'mask': 'ddd-tel9', 'nextInput': false });
	};

	this.setFirstFocus = function()
	{
		$('#frmTable input, #frmTable select, #frmTable textarea').not(':hidden').eq(0).select().focus();
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

	this.maskMoney = function()
	{
		jQuery('input[data-type="decimal"]').priceFormat
		(
			{
				prefix             : '',
				centsSeparator     : ',',
				thousandsSeparator : '.'
			}
		);
	};

	this.verifySlugFields = function()
	{
		if ($('#slug').length > 0)
		{
			$('#slug').attr('readonly', 'readonly');
			var $element = $('#slug').closest('.field').prev().find('input').eq(0);
			$(document).on
			(
				'keyup change',
				'#' + $element.attr('id'),
				function(e)
				{
					e.preventDefault();
					var value = $element.val();
					var slug = value.slugify();
					$('#slug').val(slug);
				}
			);

		}
	};

	CjsBaseClass.call(this, $, objname, options);
};

$(function()
{
	$('.select2').select2();
});

umsappadmin.edit = new umsappadmin.Tedit
(
	window.cjsbaseclass_jquery,
	'edit',
	{
		'debug'       : CJS_DEBUG_MODE_1,
		'highlighted' : 'auto'
	}
);