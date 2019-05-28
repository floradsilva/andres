var start_time = parseInt((new Date().getTime())/1000);
var message_timer;
var sending = false;
function subscribe() {
	if (sending) return false;
	sending = true;
	clearTimeout(message_timer);
	jQuery(".input-error").removeClass("input-error");
	jQuery("#message").fadeOut(350);
	jQuery("#button i").attr("class", "csmm-fa csmm-fa-spinner csmm-fa-spin");
	jQuery("#button").addClass("disabled");
	var post_data = {
			"action"		: "csmm_submit",
			"csmm-email"	: encode64(jQuery("#email").val())
		};
	if (jQuery("#gdpr").is(":checked")) post_data["csmm-gdpr"] = "on";
	else post_data["csmm-gdpr"] = "off";
	jQuery.ajax({
		url: action,
		data: post_data,
		dataType: "jsonp",
		success: function(data) {
			jQuery("#message").removeClass("error");
			jQuery("#message").removeClass("success");
			jQuery("#button").removeClass("disabled");
			jQuery("#button i").attr("class", "csmm-fa csmm-fa-ok");
			try {
				var status = data.status;
				if (status == "OK") {
					jQuery("#message").addClass("success");
					jQuery("#message").html(data.message);
					jQuery("#message").fadeIn(350);
					message_timer = setTimeout(function() {jQuery("#message").fadeOut(350);}, 5000);
				} else if (status == "ERROR") {
					if (data["email"] == "ERROR") jQuery("#email").parent().addClass("input-error");
					if (data["gdpr"] == "ERROR") jQuery("#gdpr").parent().addClass("input-error");
				} else {
					jQuery("#message").addClass("error");
					jQuery("#message").html("Subscription temporarily unavailable.");
					jQuery("#message").fadeIn(350);
					message_timer = setTimeout(function() {jQuery("#message").fadeOut(350);}, 5000);
				}
			} catch(error) {
				jQuery("#message").addClass("error");
				jQuery("#message").html("Subscription temporarily unavailable.");
				jQuery("#message").fadeIn(350);
				message_timer = setTimeout(function() {jQuery("#message").fadeOut(350);}, 5000);
			}
			sending = false;
		}
	});
	return false;
}
function countdown() {
	var current_time = parseInt((new Date().getTime())/1000);
	var left_time = period - current_time + start_time;
	if (left_time <= 0) {
		location.reload();
	} else {
		var days = Math.floor(left_time/(24*3600));
		left_time -= days*24*3600;
		var hours = Math.floor(left_time/3600);
		left_time -= hours*3600;
		var minutes = Math.floor(left_time/60);
		var seconds = left_time - minutes*60;
		if (days > 999) {
			jQuery("#days-sh").html("9");
			jQuery("#days-sh").css("display", "inline-block");
			jQuery("#days-h").html("9");
			jQuery("#days-l").html("9");
		} else if (days > 99) {
			jQuery("#days-sh").html(Math.floor(days/100).toString());
			days = days - 100*Math.floor(days/100);
			jQuery("#days-sh").css("display", "inline-block");
			jQuery("#days-h").html(Math.floor(days/10).toString());
			jQuery("#days-l").html((days % 10).toString());
		} else if (days < 10) {
			jQuery("#days-h").html("0");
			jQuery("#days-l").html(days.toString());
		} else {
			jQuery("#days-sh").css("display", "none");
			jQuery("#days-h").html(Math.floor(days/10).toString());
			jQuery("#days-l").html((days % 10).toString());
		}
		if (hours < 10) {
			jQuery("#hours-h").html("0");
			jQuery("#hours-l").html(hours.toString());
		} else {
			jQuery("#hours-h").html(Math.floor(hours/10).toString());
			jQuery("#hours-l").html((hours % 10).toString());
		}		
		if (minutes < 10) {
			jQuery("#minutes-h").html("0");
			jQuery("#minutes-l").html(minutes.toString());
		} else {
			jQuery("#minutes-h").html(Math.floor(minutes/10).toString());
			jQuery("#minutes-l").html((minutes % 10).toString());
		}		
		if (seconds < 10) {
			jQuery("#seconds-h").html("0");
			jQuery("#seconds-l").html(seconds.toString());
		} else {
			jQuery("#seconds-h").html(Math.floor(seconds/10).toString());
			jQuery("#seconds-l").html((seconds % 10).toString());
		}		
		setTimeout("countdown()", 1000);
	}
}
function utf8encode(string) {
	string = string.replace(/\x0d\x0a/g, "\x0a");
	var output = "";
	for (var n = 0; n < string.length; n++) {
		var c = string.charCodeAt(n);
		if (c < 128) {
			output += String.fromCharCode(c);
		} else if ((c > 127) && (c < 2048)) {
			output += String.fromCharCode((c >> 6) | 192);
			output += String.fromCharCode((c & 63) | 128);
		} else {
			output += String.fromCharCode((c >> 12) | 224);
			output += String.fromCharCode(((c >> 6) & 63) | 128);
			output += String.fromCharCode((c & 63) | 128);
		}
	}
	return output;
}
function encode64(input) {
	var keyString = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var output = "";
	var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
	var i = 0;
	input = utf8encode(input);
	while (i < input.length) {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);
		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;
		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}
		output = output + keyString.charAt(enc1) + keyString.charAt(enc2) + keyString.charAt(enc3) + keyString.charAt(enc4);
	}
	return output;
}
jQuery(document).ready(function() {
	if (period > 0) countdown();
});