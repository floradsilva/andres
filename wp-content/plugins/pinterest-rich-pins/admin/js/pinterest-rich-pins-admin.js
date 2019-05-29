jQuery(document).ready(function(){
	if( ( jQuery("#post_type").length>0 && jQuery("#post_type").val() == "product" ) || ( jQuery("input[name='post_type']").length>0 && jQuery("input[name='post_type']").val() == "product" ) ){
		if(jQuery(".wrap .pinterest-error-message").length<1){
			jQuery(".wp-header-end").after('<div class="pinterest-error-message"></div>');
		}
	}
	
	jQuery(".pinterest_add_pin").click(function(){
		var id = jQuery(this).attr("data-id");
		
		if(id != "" && id.length>0){
			var data = {
						'action':'add_product_to_pinterest_pin',
						'id': id
					};
			jQuery(".pinterest_rich_pin_loader-image").show();
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST', 
				data : data,
				success: function(data) {
					if(data == "y"){
						jQuery(".pinterest-error-message").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																	'<p>Product added to Pinterest successfully.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
						
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					}
					else{
						jQuery(".pinterest-error-message").html('<div id="message" class="notice notice-error is-dismissible">'+
																	data+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
					}
					jQuery(".pinterest_rich_pin_loader-image").hide();
				},
				error: function(data){
					console.log(data);
					// Reload the page
					setTimeout(function() {
						location.reload();
					}, 2000);
					
					jQuery(".pinterest_rich_pin_loader-image").hide();
					
				}
			});
		}
		
	});
	
	jQuery(".pinterest_update_pin").click(function(){
		var id = jQuery(this).attr("data-id");
		
		if(id != "" && id.length>0){
			var data = {
						'action':'update_product_to_pinterest_pin',
						'id': id
					};
			jQuery(".pinterest_rich_pin_loader-image").show();
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST', 
				data : data,
				success: function(data) {
					if(data == "y"){
						jQuery(".pinterest-error-message").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																	'<p>Product added to Pinterest successfully.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
						
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					}
					else{
						jQuery(".pinterest-error-message").html('<div id="message" class="notice notice-error is-dismissible">'+
																	data+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
					}
					jQuery(".pinterest_rich_pin_loader-image").hide();
				},
				error: function(data){
					console.log(data);
					// Reload the page
					setTimeout(function() {
						location.reload();
					}, 2000);
					jQuery(".pinterest_rich_pin_loader-image").hide();
				}
			});
		}
		
	});
	
	jQuery(".pinterest_remove_pin").click(function(){
		var id = jQuery(this).attr("data-id");
		
		if(id != "" && id.length>0){
			var data = {
						'action':'remove_product_to_pinterest_pin',
						'id': id
					};
			jQuery(".pinterest_rich_pin_loader-image").show();
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST', 
				data : data,
				success: function(data) {
					if(data == "y"){
						jQuery(".pinterest-error-message").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																	'<p>Product removed from pinterest successfully.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
						
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					}
					else{
						jQuery(".pinterest-error-message").html('<div id="message" class="notice notice-error is-dismissible">'+
																	data+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
						
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					}
					jQuery(".pinterest_rich_pin_loader-image").hide();
				},
				error: function(data){
					console.log(data);
					// Reload the page
					setTimeout(function() {
						location.reload();
					}, 2000);
					jQuery(".pinterest_rich_pin_loader-image").hide();
				}
			});
		}
	});
	
	jQuery("#doaction").click(function(e){
		var thisVal = jQuery("#bulk-action-selector-top").val();
		
		if(thisVal == "add_to_pin" || thisVal == "remove_from_pin"){
			if(jQuery("tbody#the-list input[type='checkbox']:checked").length<1){
				alert("Please select at least one product to perform bulk action.");
				return false;
			}
			else{
				return true;
			}
		}
		return true;
	});
	
	/*
	 * Queue Page JS 
	 */
	
	jQuery(".remove_queue_action").click(function(){
		var id = jQuery(this).attr("data-id");
		
		if(id != "" && id.length>0){
			var data = {
						'action':'remove_action_from_queue',
						'id': id
					};
			jQuery(".pinterest_rich_pin_loader-image").show();
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST', 
				data : data,
				success: function(data) {
					if(data == "y"){
						jQuery(".pinterest-error-message").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																	'<p>Action removed from queue list.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
						
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					}
					else{
						jQuery(".pinterest-error-message").html('<div id="message" class="notice notice-error is-dismissible">'+
																	'<p>Action was not removed from queue list.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
					}
					jQuery(".pinterest_rich_pin_loader-image").hide();
				},
				error: function(data){
					console.log(data);
					// Reload the page
					setTimeout(function() {
						location.reload();
					}, 2000);
					jQuery(".pinterest_rich_pin_loader-image").hide();
				}
			});
		}
	});
	
	jQuery(".retry_queue_action").click(function(){
		var id = jQuery(this).attr("data-id");
		
		if(id != "" && id.length>0){
			var data = {
						'action':'retry_action_from_queue',
						'id': id
					};
			jQuery(".pinterest_rich_pin_loader-image").show();
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST', 
				data : data,
				success: function(data) {
					if(data == "y"){
						jQuery(".pinterest-error-message").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																	'<p>Queue list entry retried successfully.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
						
						// Reload the page
						setTimeout(function() {
							location.reload();
						}, 2000);
					}
					else{
						jQuery(".pinterest-error-message").html('<div id="message" class="notice notice-error is-dismissible">'+
																	'<p>Queue list entry was not retried.</p>'+
																	'<button type="button" class="notice-dismiss">'+
																		'<span class="screen-reader-text">Dismiss this notice.</span>'+
																	'</button>'+
																	'</div>');
						jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
					}
					jQuery(".pinterest_rich_pin_loader-image").hide();
				},
				error: function(data){
					console.log(data);
					// Reload the page
					setTimeout(function() {
						location.reload();
					}, 2000);
					jQuery(".pinterest_rich_pin_loader-image").hide();
				}
			});
		}
	});
	
	jQuery("#bulk_queue_action").click(function () {
		//var a = [];
		if(jQuery("#queue-bulk-action").val() != ''){
			if(jQuery('.queue_checkbox:checked').size() > 0){
				if(!confirm("Are you sure that you want to perform the bulk action?")){
					return false;
				}
				else{
					var quote_action_perform = jQuery("#queue-bulk-action").val();
					var checkValues = jQuery('input[name=queue_check]:checked').map(function()
						{
							return jQuery(this).val();
					}).get();
					jQuery(".pinterest_rich_pin_loader-image").show();
					
					jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
								action : 'queue_bulk_action_submit_admin',
								queueids : checkValues,
								queue_action : quote_action_perform
							},
						success: function(data) {
							if(data == "y"){
								jQuery(".pinterest-error-message").html('<div id="message" class="updated notice notice-success is-dismissible">'+
																			'<p>Bulk action executed successfully.</p>'+
																			'<button type="button" class="notice-dismiss">'+
																				'<span class="screen-reader-text">Dismiss this notice.</span>'+
																			'</button>'+
																			'</div>');
								jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
								
								// Reload the page
								setTimeout(function() {
									location.reload();
								}, 2000);
							}
							else{
								jQuery(".pinterest-error-message").html('<div id="message" class="notice notice-error is-dismissible">'+
																			'<p>Bulk action was not executes.</p>'+
																			'<button type="button" class="notice-dismiss">'+
																				'<span class="screen-reader-text">Dismiss this notice.</span>'+
																			'</button>'+
																			'</div>');
								jQuery("html,body").animate({ scrollTop: jQuery(".pinterest-error-message").offset().top-100 }, 1000);
							}
							
							jQuery(".pinterest_rich_pin_loader-image").hide();
						},
						error: function(data) {
							jQuery("#queue_spinner").hide();
							jQuery("#display_message_error").html('Something went wrong.');
							jQuery(".alert").show();
							jQuery( "#display_message_error" ).scrollTop(  );
							jQuery(".pinterest_rich_pin_loader-image").hide();
							return false;
						}
					});
				}
			}
			else{
				alert("Select at least one list item of queue to perform the action");
				return false;
			}
		}
	});
	
	jQuery('#ppp').change(function(){
		jQuery('#custom_filter_form').submit();
	});
	
	var clickaccordian=1;
	jQuery('.accordian-head').click(function(){
		if(clickaccordian == 1){
			jQuery('.panel-title').addClass('arrowdownacc');
			jQuery('.panel-title').removeClass('arrowupacc');
			clickaccordian=0;
		}
		else{
			jQuery('.panel-title').removeClass('arrowdownacc');
			jQuery('.panel-title').addClass('arrowupacc');

			clickaccordian=1;
		}

	});
	
	
	jQuery(".queue_checkbox").change(function(){
		var totalCheckbox = jQuery(".queue_checkbox").length;
		var totalChecked = jQuery(".queue_checkbox:checked").length;
		
		if(totalCheckbox > totalChecked){
			jQuery("#pinterest-rich_pin-checkAll").prop("checked",false);
		}
		else{
			jQuery("#pinterest-rich_pin-checkAll").prop("checked",true);
		}
	});
	
	jQuery("#pinterest-rich_pin-checkAll").change(function(){
		var thisCheck = jQuery(this).prop("checked");
		
		if(thisCheck){
			jQuery(".queue_checkbox").prop("checked",true);
		}
		else{
			jQuery(".queue_checkbox").prop("checked",false);
		}
	});
	
});