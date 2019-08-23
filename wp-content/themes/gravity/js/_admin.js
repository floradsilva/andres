/* global jQuery:false */
/* global GRAVITY_STORAGE:false */

jQuery(document).ready(function() {
	"use strict";

	// Init Media manager variables
	GRAVITY_STORAGE['media_id'] = '';
	GRAVITY_STORAGE['media_frame'] = [];
	GRAVITY_STORAGE['media_link'] = [];
	jQuery('.gravity_media_selector').on('click', function(e) {
		gravity_show_media_manager(this);
		e.preventDefault();
		return false;
	});

    // Point
    if(jQuery("#side-sortables .room_override_options_hot_spot").length > 0) {
        var HotSpot = jQuery("#side-sortables .room_override_options_hot_spot");
        var attachment = jQuery("#postimagediv #set-post-thumbnail img");
        var attachment_block = jQuery("#postimagediv #set-post-thumbnail");
        if(attachment.length > 0) {
            set_points(HotSpot,attachment,attachment_block);
        }
        HotSpot.change(function(){
            if(attachment.length > 0) {
                set_points(HotSpot,attachment,attachment_block);
            }
        });
    }

    function set_points(HotSpot,attachment,attachment_block){
        if(attachment.length > 0) {
            attachment_block.find('.point').remove();
            // point 1
            var left = HotSpot.find("input[name='room_options_hot_spot_left']").val();
            var top = HotSpot.find("input[name='room_options_hot_spot_top']").val();
            if (left && top) { attachment_block.append('<span class="point" style="top:'+top+'%;left:'+left+'%;">1</span>'); }

            // point 2
            var left_2 = HotSpot.find("input[name='room_options_hot_spot_left_2']").val();
            var top_2 = HotSpot.find("input[name='room_options_hot_spot_top_2']").val();
            if (left_2 && top_2) { attachment_block.append('<span class="point" style="top:'+top_2+'%;left:'+left_2+'%;">2</span>'); }

            // point 3
            var left_3 = HotSpot.find("input[name='room_options_hot_spot_left_3']").val();
            var top_3 = HotSpot.find("input[name='room_options_hot_spot_top_3']").val();
            if (left_3 && top_3) { attachment_block.append('<span class="point" style="top:'+top_3+'%;left:'+left_3+'%;">3</span>'); }

            // point 4
            var left_4 = HotSpot.find("input[name='room_options_hot_spot_left_4']").val();
            var top_4 = HotSpot.find("input[name='room_options_hot_spot_top_4']").val();
            if (left_4 && top_4) { attachment_block.append('<span class="point" style="top:'+top_4+'%;left:'+left_4+'%;">4</span>'); }

            // point 5
            var left_5 = HotSpot.find("input[name='room_options_hot_spot_left_5']").val();
            var top_5 = HotSpot.find("input[name='room_options_hot_spot_top_5']").val();
            if (left_5 && top_5) { attachment_block.append('<span class="point" style="top:'+top_5+'%;left:'+left_5+'%;">5</span>'); }
        }
    }
	
	// Hide empty override-optionses
	jQuery('.postbox > .inside').each(function() {
		"use strict";
		if (jQuery(this).html().length < 5) jQuery(this).parent().hide();
	});

	// Hide admin notice
	jQuery('#gravity_admin_notice .gravity_hide_notice').on('click', function(e) {
		jQuery('#gravity_admin_notice').slideUp();
		jQuery.post( GRAVITY_STORAGE['ajax_url'], {'action': 'gravity_hide_admin_notice'}, function(response){});
		e.preventDefault();
		return false;
	});
	
	// TGMPA Source selector is changed
	jQuery('.tgmpa_source_file').on('change', function(e) {
		var chk = jQuery(this).parents('tr').find('>th>input[type="checkbox"]');
		if (chk.length == 1) {
			if (jQuery(this).val() != '')
				chk.attr('checked', 'checked');
			else
				chk.removeAttr('checked');
		}
	});
		
	// Add icon selector after the menu item classes field
	jQuery('.edit-menu-item-classes').each(function() {
		"use strict";
		var icon = gravity_get_icon_class(jQuery(this).val());
		jQuery(this).after('<span class="gravity_list_icons_selector'+(icon ? ' '+icon : '')+'" title="'+GRAVITY_STORAGE['icon_selector_msg']+'"></span>');
	});
	jQuery('.gravity_list_icons_selector').on('click', function(e) {
		"use strict";
		var input_id = jQuery(this).prev().attr('id');
		var list = jQuery('.gravity_list_icons');
		if (list.length > 0) {
			list.find('span.gravity_list_active').removeClass('gravity_list_active');
			var icon = gravity_get_icon_class(jQuery(this).attr('class'));
			if (icon != '') list.find('span[class*="'+icon+'"]').addClass('gravity_list_active');
			var pos = jQuery(this).offset();
			list.data('input_id', input_id).css({'left': pos.left, 'top': pos.top}).fadeIn();
		}
		e.preventDefault();
		return false;
	});
	jQuery('.gravity_list_icons span').on('click', function(e) {
		"use strict";
		var list = jQuery(this).parent().fadeOut();
		var icon = gravity_alltrim(jQuery(this).attr('class').replace(/gravity_list_active/, ''));
		var input = jQuery('#'+list.data('input_id'));
		input.val(gravity_chg_icon_class(input.val(), icon));
		var selector = input.next();
		selector.attr('class', gravity_chg_icon_class(selector.attr('class'), icon));
		e.preventDefault();
		return false;
	});

	// Standard WP Color Picker
	if (jQuery('.gravity_color_selector').length > 0) {
		jQuery('.gravity_color_selector').wpColorPicker({
			// a callback to fire whenever the color changes to a valid color
			change: function(e, ui){
				"use strict";
				jQuery(e.target).val(ui.color).trigger('change');
			},
	
			// a callback to fire when the input is emptied or an invalid color
			clear: function(e) {
				"use strict";
				jQuery(e.target).prev().trigger('change')
			}
		});
	}
});

function gravity_chg_icon_class(classes, icon) {
	"use strict";
	var chg = false;
	classes = gravity_alltrim(classes).split(' ');
	for (var i=0; i<classes.length; i++) {
		if (classes[i].indexOf('icon-') >= 0) {
			classes[i] = icon;
			chg = true;
			break;
		}
	}
	if (!chg) {
		if (classes.length == 1 && classes[0] == '')
			classes[0] = icon;
		else
			classes.push(icon);
	}
	return classes.join(' ');
}

function gravity_get_icon_class(classes) {
	"use strict";
	var classes = gravity_alltrim(classes).split(' ');
	var icon = '';
	for (var i=0; i<classes.length; i++) {
		if (classes[i].indexOf('icon-') >= 0) {
			icon = classes[i];
			break;
		}
	}
	return icon;
}

function gravity_show_media_manager(el) {
	"use strict";

	GRAVITY_STORAGE['media_id'] = jQuery(el).attr('id');
	GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']] ) {
		GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']].open();
		return false;
	}

	// Create the media frame.
	GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']] = wp.media({
		// Popup layout (if comment next row - hide filters and image sizes popups)
		frame: 'post',
		// Set the title of the modal.
		title: GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('type') ? GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('type') : 'image'
		},
		// Multiple choise
		multiple: GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']].on( 'insert select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('linked-field')).eq(0);
		var attachment = null, attachment_url = '';
		if (GRAVITY_STORAGE['media_link'][GRAVITY_STORAGE['media_id']].data('multiple')===true) {
			GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']].state().get('selection').map( function( att ) {
				attachment_url += (attachment_url ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment_url = val + (val ? "\n" : '') + attachment_url;
		} else {
			attachment = GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']].state().get('selection').first().toJSON();
			attachment_url = attachment.url;
			var sizes_selector = jQuery('.media-modal-content .attachment-display-settings select.size');
			if (sizes_selector.length > 0) {
				var size = gravity_get_listbox_selected_value(sizes_selector.get(0));
				if (size != '') attachment_url = attachment.sizes[size].url;
			}
		}
		field.val(attachment_url);
		if (attachment_url.indexOf('.jpg') > 0 || attachment_url.indexOf('.png') > 0 || attachment_url.indexOf('.gif') > 0) {
			var preview = field.siblings('.gravity_override_options_field_preview');
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
	GRAVITY_STORAGE['media_frame'][GRAVITY_STORAGE['media_id']].open();
	return false;
}
