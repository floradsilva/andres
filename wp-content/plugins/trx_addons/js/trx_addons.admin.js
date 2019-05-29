/**
 * Admin utilities (for internal use only!)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

if (typeof TRX_ADDONS_STORAGE == 'undefined') var TRX_ADDONS_STORAGE = {};

jQuery(document).ready(function() {
	"use strict";

	// Media selector
	TRX_ADDONS_STORAGE['media_id'] = '';
	TRX_ADDONS_STORAGE['media_frame'] = [];
	TRX_ADDONS_STORAGE['media_link'] = [];
	jQuery('#customize-theme-controls,.widget-liquid-right,.widgets-holder-wrap,.form-field,.trx_addons_options_item_field').on('click', '.trx_addons_media_selector', function(e) {
		"use strict";
		trx_addons_show_media_manager(this);
		e.preventDefault();
		return false;
	});

	// Standard WP Color Picker
	if (jQuery('.trx_addons_color_selector').length > 0) {
		jQuery('.trx_addons_color_selector').wpColorPicker({
			// you can declare a default color here,
			// or in the data-default-color attribute on the input
			//defaultColor: false,
	
			// a callback to fire whenever the color changes to a valid color
			change: function(e, ui){
				jQuery(e.target).val(ui.color).trigger('change');
			},
	
			// a callback to fire when the input is emptied or an invalid color
			clear: function(e) {
				jQuery(e.target).prev().trigger('change')
			},
	
			// hide the color picker controls on load
			//hide: true,
	
			// show a group of common colors beneath the square
			// or, supply an array of colors to customize further
			//palettes: true
		});
	}

	// Refresh categories when post type is changed
	jQuery('.widget-liquid-right,.widgets-holder-wrap').on('change', '.widgets_param_post_type_selector', function() {
		"use strict";
		var cat_fld = jQuery(this).parent().next().find('select');
		var cat_lbl = jQuery(this).parent().next().find('label');
		trx_addons_fill_categories(this, cat_fld, cat_lbl);
		return false;
	});

	// Button in the ThemeREX Addons Options
	jQuery('.trx_addons_options_item_button input[type="button"]').on('click', function(e) {
		"use strict";
		jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
			action: jQuery(this).data('action'),
			nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
		}).done(function(response) {
			"use strict";
			var rez = {};
			if (response=='' || response==0) {
				rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
			} else {
				try {
					rez = JSON.parse(response);
				} catch (e) {
					rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
					console.log(response);
				}
			}
			alert(rez.error ? rez.error : TRX_ADDONS_STORAGE['msg_cpt_layouts_created']);
		});
		e.preventDefault();
		return false;
	});
	
});

// Show WP Media manager to select image
function trx_addons_show_media_manager(el) {
	"use strict";

	TRX_ADDONS_STORAGE['media_id'] = jQuery(el).attr('id');
	TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']] ) {
		TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].open();
		return false;
	}

	// Create the media frame.
	TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']] = wp.media({
		// Popup layout (if comment next row - hide filters and image sizes popups)
		frame: 'post',
		// Set the title of the modal.
		title: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('type') ? TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('type') : 'image'
		},
		// Multiple choise
		multiple: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].on( 'insert select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('linked-field')).eq(0);
		var attachment = null, attachment_url = '';
		if (TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('multiple')===true) {
			TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].state().get('selection').map( function( att ) {
				attachment_url += (attachment_url ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment_url = val + (val ? "\n" : '') + attachment_url;
		} else {
			attachment = TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].state().get('selection').first().toJSON();
			attachment_url = attachment.url;
			var sizes_selector = jQuery('.media-modal-content .attachment-display-settings select.size');
			if (sizes_selector.length > 0) {
				var size = trx_addons_get_listbox_selected_value(sizes_selector.get(0));
				if (size != '') attachment_url = attachment.sizes[size].url;
			}
		}
		field.val(attachment_url);
		if (attachment_url.indexOf('.jpg') > 0 || attachment_url.indexOf('.png') > 0 || attachment_url.indexOf('.gif') > 0) {
			var preview = field.siblings('.trx_addons_options_field_preview');
			if (preview.length != 0) {
				if (preview.find('img').length == 0)
					preview.append('<img src="'+attachment_url+'">');
				else 
					preview.find('img').attr('src', attachment_url);
			} else {
				preview = field.siblings('img');
				if (preview.length != 0)
					preview.attr('src', attachment_url);
			}
		}
		field.trigger('change');
	});

	// Finally, open the modal.
	TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].open();
	return false;
}


// Fill categories in specified field
function trx_addons_fill_categories(fld, cat_fld, cat_lbl) {
	"use strict";
	var cat_value = cat_fld.val();
	cat_lbl.append('<span class="sc_refresh iconaddons-spin3 animate-spin"></span>');
	var pt = jQuery(fld).val();
	// Prepare data
	var data = {
		action: 'trx_addons_change_post_type',
		nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
		post_type: pt
	};
	jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], data, function(response) {
		"use strict";
		var rez = {};
		try {
			rez = JSON.parse(response);
		} catch (e) {
			rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
			console.log(response);
		}
		if (rez.error === '') {
			var opt_list = '';
			for (var i in rez.data.ids) {
				opt_list += '<option class="'+rez.data.ids[i]+'" value="'+rez.data.ids[i]+'"'+(rez.data.ids[i]==cat_value ? ' selected="selected"' : '')+'>'+rez.data.titles[i]+'</option>';
			}
			cat_fld.html(opt_list);
			cat_lbl.find('span').remove();
		}
	});
	return false;
}
