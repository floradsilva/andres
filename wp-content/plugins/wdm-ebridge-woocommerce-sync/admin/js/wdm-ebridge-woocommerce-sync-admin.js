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
						$( '.import_button' ).prepend( '<div class="loader-container"><div class="loader"></div></div>' );
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
									$( '.loader-container').remove();
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

										// $( '#message-brief' ).text( 'Total products fetched: ' + (response.data.update_ids_count + response.data.delete_ids_count) );
										
										var update_ids    = response.data.update_ids;
										var delete_ids    = response.data.delete_ids;
										var total_updated = 0;
										var index, i;

										if ( update_ids.length || delete_ids.length ) {
											for (index = 0; index < update_ids.length; index++) {
												const id_to_update = update_ids[index];
													$.ajax(
														{
															url: wews.wews_url,
															type: 'post',
															dataType: 'json',
															data:  {
																'action': 'update_product',
																'product_id' : id_to_update,
															},
															success: function (response_updated) {
																total_updated++;
																$( '#message' ).scrollTop( $( '#message' )[0].scrollHeight + 2 );
																if (response_updated.success) {
																	$( '#message' ).append( response_updated.data.message + '<br />' );
																	$( '#message-brief' ).text( wews.updated_msg + ': ' + total_updated );
																} else {
																	$( '#message' ).append( response_updated.data.message + '<br />' );
																}
																if ( total_updated === (update_ids.length) ) {
																	$( '.loader-container' ).remove();
																	$( '#message-brief' ).text( wews.updated_msg + ': ' + total_updated );
																	$( '#message-brief' ).append( '<br />' + wews.update_complete + '<br />' );
																}
															}
														}
													);
											}

											for (i = 0; i < delete_ids.length; i++) {
												const id_to_delete = delete_ids[i];
												$.ajax(
													{
														url: wews.wews_url,
														type: 'post',
														dataType: 'json',
														data:  {
															'action': 'delete_product',
															'product_id' : id_to_delete,
														},
														success: function (response_deleted) {
															total_updated++;
															$( '#message' ).scrollTop( $( '#message' )[0].scrollHeight );
															
															if (response_deleted.success) {
																$( '#message' ).append( response_deleted.data.message + '<br />' );
																$( '#message-brief' ).text( wews.updated_msg + ': ' + total_updated );
															} else {
																$( '#message' ).append( response.data.message + '<br />' );
															}

															if ( total_updated === (update_ids.length + delete_ids.length) ) {
																$( '#message-brief' ).text( wews.updated_msg + ': ' + total_updated );
																$( '#message-brief' ).append( wews.delete_complete + '<br />' );
																$( '.loader-container' ).remove();
															}
														}
													}
												);
											}
										} else {
											$( '.loader-container' ).remove();
										}
									} else {
										$( '<div id="message-wrap"><h3>Logs:</h3><p id="message">' + response.data.message + '</p></div>' ).insertAfter( '#product_sync_form' );
									}
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
				var update_ids    = [];
				var total_updated = 0;

				for (let index = 0; index < selected_products_count; index++) {
					update_ids[index] = url.searchParams.get( "product_ids[" + index + "]" );
				}

				if (update_ids.length) {
					$( '<div id="message-wrap"><h3>Logs:</h3><p id="message"></p></div>' ).insertAfter( '#product_sync_form' );
					update_ids.forEach(
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
										total_updated++;
										if (response.success) {
											$( '#message' ).append( response.data.message + '<br />' );
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
		}
	);

})( jQuery );
