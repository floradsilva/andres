(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$( window ).load(
		function() {
			var update_ids    = [];
			var delete_ids    = [];
			var total_updated = 0;
			var update_ids_count;
			var delete_ids_count;

			$( '#customer_sync_form' ).validate(
				{
					rules: {
						customer_sync_csv: {
							required: true,
						}
					},
					messages: {
						customer_sync_csv: {
							required: 'Please select a valid csv file.',
						}
					},
					submitHandler: function (form) {
						event.preventDefault();
						$( "#message-wrap" ).remove();
						var formData = new FormData( document.getElementById( 'customer_sync_form' ) );
						formData.append( "action", "upload_csv" );
						$.ajax(
							{
								url: wews.wews_url,
								type: 'post',
								dataType: 'json',
								data:  formData,
								contentType: false,
								cache: false,
								processData: false,
								success: function (response) {
									console.log( response );

									if (response.success) {
										$( '<div id="message-wrap"><h3>Logs:</h3><p id="message">' + response.data.message + '</p></div>' ).insertAfter( '#customer_sync_form' );
									} else {
										alert( "Error uploading customer data." );
									}
								}
							}
						);
					}
				}
			);

			$( '#short-time-msg' ).click(
				function() {
					$( '#short-time-msg' ).hide();
				}
			);

			$( '#refresh_product_attributes' ).click(
				function() {
					$.ajax(
						{
							url: wews.wews_url,
							type: 'get',
							dataType: 'json',
							data: {
								action:'refresh_product_attributes',
							},
							success: function (response) {
								if (response.success) {
									location.reload();
								} else {
									console.log( "Some error." );
								}
							}
						}
					);
				}
			);

			$( '#connection_settings_form' ).validate(
				{
					rules: {
						ebridge_sync_api_url: {
							required: true,
						},
						ebridge_sync_api_token: {
							required: true,
						}
					},
					messages: {
						ebridge_sync_api_url: {
							required: 'Please enter a valid url.',
						},
						ebridge_sync_api_token: {
							required: 'Please enter a valid token.',
						}
					},
					submitHandler: function (form) {
						event.preventDefault();
						var formData = new FormData( document.getElementById( 'connection_settings_form' ) );
						formData.append( "action", "add_connection_settings" );
						$.ajax(
							{
								url: wews.wews_url,
								type: 'post',
								dataType: 'json',
								data:  formData,
								contentType: false,
								cache: false,
								processData: false,
								success: function (response) {
									$( "#short-time-msg" ).remove();
									$( "#error-msg" ).remove();
									if (response.data.success) {
										$( '<p id="short-time-msg">' + response.data.message + '</p>' ).insertAfter( '#submit' );
										// window.setTimeout( "document.getElementById('short-time-msg').style.display='none'", 3000 );
									} else {
										$( '<p id="error-msg">' + response.data.message + '</p>' ).insertAfter( '#submit' );
										// window.setTimeout( "document.getElementById('error-msg').style.display='none'", 3000 );
									}
								}
							}
						);
					}
				}
			);

			$( '#product_sync_form' ).validate(
				{
					rules: {
						selected_product_sync: {
							required: true,
						}
					},
					submitHandler: function (form) {
						event.preventDefault();
						$( '.import_button' ).prepend( '<div class="loader-container"><div class="loader"></div></div>' );
						$( "#message-wrap" ).remove();
						$( "#message-wrap-1" ).remove();
						var formData = new FormData( document.getElementById( 'product_sync_form' ) );
						formData.append( "action", "fetch_products_to_sync" );
						$.ajax(
							{
								url: wews.wews_url,
								type: 'post',
								dataType: 'json',
								data:  formData,
								contentType: false,
								cache: false,
								processData: false,
								success: function (response) {
									if (response.success) {
										$( '<div id="message-wrap"><h3>Logs:</h3><p id="message">' + response.data.message + '</p></div>' ).insertAfter( '#product_sync_form' );

										$( '<div id="message-wrap-1"><p id="message-brief"></p></div>' ).insertAfter( '#message-wrap' );

										$( '#message-brief' ).text();

										update_ids    = response.data.update_ids;
										delete_ids    = response.data.delete_ids;
										update_ids_count = response.data.update_ids_count;
										delete_ids_count = response.data.delete_ids_count;
										total_updated = 0;
										
										if ( update_ids.length ) {
											call_ajax_request( update_ids[0], 0, update_ids.length, 'update_product');											
										}

										if ( delete_ids.length ) {
											call_ajax_request( delete_ids[0], 0, delete_ids.length, 'delete_product');
										}
									} else {
										$( '<div id="message-wrap"><h3>Logs:</h3><p id="message">' + response.data.message + '</p></div>' ).insertAfter( '#product_sync_form' );
									}
									// $( '.loader-container' ).remove();
								}
							}
						);
					}
				}
			);

			var url_string              = window.location.href
			var url                     = new URL( url_string );
			var selected_products_count = url.searchParams.get( "product_id_count" );

			if (selected_products_count) {
				var update_ids_get    = [];
				var total_update = 0;

				for (let index = 0; index < selected_products_count; index++) {
					update_ids_get[index] = url.searchParams.get( "product_ids[" + index + "]" );
				}

				if (update_ids.length) {
					$( '<div id="message-wrap"><h3>Logs:</h3><p id="message"></p></div>' ).insertAfter( '#product_sync_form' );
					$("#message").animate({ scrollTop: $("#message")[0].scrollHeight}, 1000);
					update_ids_get.forEach(
						id_to_update => {
							// $( '#message' ).append( 'Syncing product ' + id_to_update + '.<br />' );
							$.ajax(
								{
									url: wews.wews_url,
									type: 'post',
									dataType: 'json',
									data:  {
										'action': 'update_product',
										'product_id' : id_to_update,
									},
									success: function (response) {
										if (response.success) {
											$( '#message' ).append( response.data.message + '<br />' );
											total_update++;
										} else {
											$( '#message' ).append( response.data.message + '<br />' );
										}
									}
								}
							);
						}
					);
				}

			}

			function call_ajax_request(id_to_modify, index, array_length, action) {
				$.ajax(
					{
						url: wews.wews_url,
						type: 'post',
						dataType: 'json',
						data:  {
							'action': action,
							'product_id' : id_to_modify,
						},
						success: function (response) {
							// if ( index === update_ids.length ) {
							// 	$( '.loader-container' ).remove();
							// }
							console.log(response);
							if (response.success) {
								$( '#message' ).append( response.data.message + '<br />' );
								total_updated++;
								$( '#message-brief' ).html( wews.updated_msg + ': '+ total_updated + '<br>' );
							} else {
								$( '#message' ).append( response.data.message + '<br />' );
							}
							index++;
							if (index < array_length ) {
								if (action == 'update_product') {
									call_ajax_request( update_ids[index], index, array_length, action );
								} else {
									call_ajax_request( delete_ids[index], index, array_length, action );
								}
							} else {
								$( '#message' ).append( '<br /><br />' + wews.upload_complete + '<br />' );
								$( '.loader-container' ).remove();
							}
						}
					}
				);
			}
		}
	);

})( jQuery );
