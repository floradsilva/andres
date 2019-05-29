<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$msg = view_log_message();

?><div class="wrap">
	<h1 class="wp-heading-inline"><?php _e('Log',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></h1>
	<input type="button" class="page-title-action" value="<?php _e('Delete Log',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" id="delete_log_pinterest_rich_pin" />
	<hr class="wp-header-end">
	<div class="pinterest-rich-pin-log-msg"></div><?php
	if(!empty($msg)){
		?><div class="pinterest-log-screen">
			<div class="view-log-outer">
				<div class="view-log"><?php echo $msg; ?></div>
			</div>
		</div><?php
	}
	else{
		?><div class="no-log-message"><?php _e("No log to display.",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></div><?php
	}
?></div>
<script>
	jQuery(document).ready(function (){
		
		// Scroll down
		objDiv = jQuery(".view-log-outer");
		objDiv.scrollTop(objDiv.prop("scrollHeight"));
		
		jQuery("#delete_log_pinterest_rich_pin").click(function(){
			if(confirm("<?php _e("Are you sure that you want to delete all log?",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>")){
				var data = { 'action':'empty_log_pinterest_rich_pin' };

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data : data,
					success: function(data) {
						if(data == "y" ){
							jQuery(".pinterest-rich-pin-log-msg").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																		'<p><?php _e("Log deleted successfully.",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></p>'+
																		'<button type="button" class="notice-dismiss">'+
																			'<span class="screen-reader-text">Dismiss this notice.</span>'+
																		'</button>'+
																		'</div>');
						}
						else {
							jQuery(".pinterest-rich-pin-log-msg").html('<div id="message" class="notice notice-error is-dismissible">'+
																		'<p><?php _e("Log deleted successfully.",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></p>'+
																		'<button type="button" class="notice-dismiss">'+
																			'<span class="screen-reader-text">Dismiss this notice.</span>'+
																		'</button>'+
																		'</div>');
						}


						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-rich-pin-log-msg").offset().top-100 }, 1000);
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					},
					error: function(data){
						console.log(data);

						location.reload();
					}
				});
			}
		});
	});
</script>