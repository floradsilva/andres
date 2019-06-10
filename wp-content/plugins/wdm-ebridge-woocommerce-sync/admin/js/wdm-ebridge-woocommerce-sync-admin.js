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
						$( "#message-wrap" ).remove();
						var formData = new FormData( document.getElementById( 'customer_sync_form' ) );
						formData.append( "action", "upload_csv" );
						$.ajax(
							{
								url: customer_sync.customer_sync_url,
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
							url: customer_sync.customer_sync_url,
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
								url: customer_sync.customer_sync_url,
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
		}
	);

})( jQuery );
