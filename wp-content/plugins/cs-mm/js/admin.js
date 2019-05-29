var csmm_saving = false;
function csmm_switch_gdpr() {
	if (jQuery("#csmm_gdpr_enable").is(":checked")) {
		jQuery(".csmm-gdpr-depend").fadeIn(200);
	} else {
		jQuery(".csmm-gdpr-depend").fadeOut(200);
	}
}
function csmm_save_settings(_button) {
	if (csmm_saving) return false;
	csmm_saving = true;
	var button_object = _button;
	jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-spinner csmm-fa-spin");
	jQuery(button_object).addClass("csmm-button-disabled");
	jQuery(".csmm-message").slideUp(350);
	jQuery.ajax({
		type	: "POST",
		url		: csmm_ajax_handler, 
		data	: jQuery(".csmm-form").serialize(),
		success	: function(return_data) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			var data;
			try {
				var data = jQuery.parseJSON(return_data);
				if (data.status == "OK") {
					csmm_global_message_show('success', data.message);
				} else if (data.status == "ERROR") {
					jQuery(".csmm-message").html(data.message);
					jQuery(".csmm-message").slideDown(350);
				} else {
					jQuery(".csmm-message").html("Something went wrong. We got unexpected server response.");
					jQuery(".csmm-message").slideDown(350);
				}
			} catch(error) {
				jQuery(".csmm-message").html("Something went wrong. We got unexpected server response.");
				jQuery(".csmm-message").slideDown(350);
			}
			csmm_saving = false;
		},
		error	: function(XMLHttpRequest, textStatus, errorThrown) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			jQuery(".csmm-message").html("Something went wrong. We got unexpected server response.");
			jQuery(".csmm-message").slideDown(350);
			csmm_saving = false;
		}
	});
	return false;
}
function csmm_media_library_image(_button) {
	var input = jQuery(_button).parent().find("input");
	csmm_media_frame = wp.media({
		title: 'Select Image',
		library: {
			type: 'image'
		},
		multiple: false
	});
	csmm_media_frame.on("select", function() {
		var attachment = csmm_media_frame.state().get("selection").first();
		jQuery(input).val(attachment.attributes.url);
	});
	csmm_media_frame.open();
	return false;
}

var csmm_global_message_timer;
function csmm_global_message_show(_type, _message) {
	clearTimeout(csmm_global_message_timer);
	jQuery("#csmm-global-message").fadeOut(300, function() {
		jQuery("#csmm-global-message").attr("class", "");
		jQuery("#csmm-global-message").addClass("csmm-global-message-"+_type).html(_message);
		jQuery("#csmm-global-message").fadeIn(300);
		csmm_global_message_timer = setTimeout(function(){jQuery("#csmm-global-message").fadeOut(300);}, 5000);
	});
}
function csmm_aweber_connect(_button) {
	var button_object = _button;
	jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-spinner csmm-fa-spin");
	jQuery(button_object).addClass("csmm-button-disabled");
	var post_data = {action: "csmm-aweber-connect", csmm_aweber_oauth_id: jQuery("#csmm_aweber_oauth_id").val()};
	jQuery.ajax({
		type	: "POST",
		url		: csmm_ajax_handler, 
		data	: post_data,
		success	: function(return_data) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			try {
				//alert(return_data);
				var data = jQuery.parseJSON(return_data);
				var status = data.status;
				if (status == "OK") {
					jQuery("#csmm-aweber-group").slideUp(350, function() {
						jQuery("#csmm-aweber-group").html(data.html);
						jQuery("#csmm-aweber-group").slideDown(350);
					});
				} else if (status == "ERROR") {
					csmm_global_message_show('danger', data.message);
				} else {
					csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
				}
			} catch(error) {
				csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
			}
		},
		error	: function(XMLHttpRequest, textStatus, errorThrown) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
		}
	});
	return false;
}
function csmm_aweber_disconnect(_button) {
	var button_object = _button;
	jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-spinner csmm-fa-spin");
	jQuery(button_object).addClass("csmm-button-disabled");
	var post_data = {action: "csmm-aweber-disconnect"};
	jQuery.ajax({
		type	: "POST",
		url		: csmm_ajax_handler, 
		data	: post_data,
		success	: function(return_data) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			try {
				//alert(return_data);
				var data = jQuery.parseJSON(return_data);
				var status = data.status;
				if (status == "OK") {
					jQuery("#csmm-aweber-group").slideUp(350, function() {
						jQuery("#csmm-aweber-group").html(data.html);
						jQuery("#csmm-aweber-group").slideDown(350);
					});
				} else if (status == "ERROR") {
					csmm_global_message_show('danger', data.message);
				} else {
					csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
				}
			} catch(error) {
				csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
			}
		},
		error	: function(XMLHttpRequest, textStatus, errorThrown) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
		}
	});
	return false;
}
function csmm_addurl(object) {
	jQuery("#background-url-row-template").before("<tr>"+jQuery("#background-url-row-template").html()+"</tr>");
	return false;
}
function csmm_removeurl(object) {
	var row = jQuery(object).parentsUntil("tr").parent();
	jQuery(row).fadeOut(300, function() {
		jQuery(row).remove();
	});
	return false;
}
function csmm_add(object) {
	jQuery("#social-link-row-template").before("<tr>"+jQuery("#social-link-row-template").html()+"</tr>");
	return false;
}
function csmm_remove(object) {
	var row = jQuery(object).parentsUntil("tr").parent();
	jQuery(row).fadeOut(300, function() {
		jQuery(row).remove();
	});
	return false;
}
function csmm_toggleicons(object) {
	var row = jQuery(object).parentsUntil("tr").parent();
	jQuery(row).find(".social-icons").slideToggle(300);
	return false;
}
function csmm_selecticon(object, icon, icon_code) {
	var row = jQuery(object).parentsUntil("tr").parent();
	jQuery(row).find(".csmm_selectedicon").html(icon_code);
	jQuery(row).find(".csmm_social_icon").val(icon);
	jQuery(row).find(".social-icons").slideToggle(300);
	return false;
}
function csmm_activate(_button, _mode) {
	var button_object = _button;
	jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-spinner csmm-fa-spin");
	jQuery(button_object).addClass("csmm-button-disabled");
	var post_data = {"mode": _mode, action: "csmm-activate"};
	jQuery.ajax({
		type	: "POST",
		url		: csmm_ajax_handler, 
		data	: post_data,
		success	: function(return_data) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			try {
				var data = jQuery.parseJSON(return_data);
				var status = data.status;
				if (status == "OK") {
					if (_mode == 1) {
						jQuery("#csmm-status-off").hide();
						jQuery("#csmm-status-on").show();
					} else {
						jQuery("#csmm-status-on").hide();
						jQuery("#csmm-status-off").show();
					}
				} else if (status == "ERROR") {
					csmm_global_message_show('danger', data.message);
				} else {
					csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
				}
			} catch(error) {
				csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
			}
		},
		error	: function(XMLHttpRequest, textStatus, errorThrown) {
			jQuery(button_object).find("i").attr("class", "csmm-fa csmm-fa-ok");
			jQuery(button_object).removeClass("csmm-button-disabled");
			csmm_global_message_show('danger', "Something went wrong. We got unexpected server response.");
		}
	});
	return false;
}
