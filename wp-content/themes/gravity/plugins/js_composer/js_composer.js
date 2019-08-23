/* global jQuery:false */
/* global GRAVITY_STORAGE:false */

jQuery(document).on('action.ready_gravity', gravity_js_composer_init);
jQuery(document).on('action.init_shortcodes', gravity_js_composer_init);
jQuery(document).on('action.init_hidden_elements', gravity_js_composer_init);

function gravity_js_composer_init(e, container) {
	"use strict";
	if (arguments.length < 2) var container = jQuery('body');
	if (container===undefined || container.length === undefined || container.length == 0) return;

	container.find('.vc_message_box_closeable:not(.inited)').addClass('inited').on('click', function(e) {
		"use strict";
		jQuery(this).fadeOut();
		e.preventDefault();
		return false;
	});
}