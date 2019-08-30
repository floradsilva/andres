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
			$( '#customer_order_history_form' ).validate(
				{
					submitHandler: function (form) {
						event.preventDefault();
						$( '.import_button' ).prepend( '<div class="loader-container"><div class="loader"></div></div>' );
						$("div[id^='message']").remove();


						var formData = new FormData( document.getElementById( 'customer_order_history_form' ) );
						formData.append( "action", "fetch_customers_to_sync" );
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
										$( '<div class="message-wrap"><h3>Logs:</h3><p id="message">' + response.data.message + '<br /></p></div>' ).insertAfter( '#customer_order_history_form' );

										$( '<div class="message-wrap-1"><p id="message-brief"></p></div>' ).insertAfter( '.message-wrap' );

										var index;
										var customers     = response.data.customers;
										var total_updated = 0;

										if (customers.length) {
											for (index = 0; index < customers.length; index++) {
												var customer_to_update = customers[index];
												var first              = true;

												$.ajax(
													{
														url: wews.wews_url,
														type: 'post',
														dataType: 'json',
														data:  {
															'action': 'get_customer_orders',
															'customer_id' : customer_to_update['customer_id'],
															'ebridge_id': customer_to_update['ebridge_id']
														},
														success: function (response_updated) {
															console.log( response_updated );
															total_updated++;

															if (response_updated.success) {
																if ( first ) {
																	first = false;
																	$( '<div class="message-wrapper-' + response_updated.data.id + '"><p id="message-brief-' + response_updated.data.id + '">'+ wews.updating_customer_msg + ': ' + response_updated.data.customer_name + '<br /></p></div>' ).insertAfter( '.message-wrap-1' );
																} else {
																	$( '<div class="message-wrapper-' + response_updated.data.id + '"><p id="message-brief-' + response_updated.data.id + '">'+ wews.updating_customer_msg + ': ' + response_updated.data.customer_name + '<br /></p></div>' ).insertAfter( '.message-wrap-1' );
																}

																// $( '#message-brief-' + response_updated.data.id ).text(  );

																var order_data = response_updated.data.order_data;

																order_data.forEach(
																	order => {
																			$.ajax(
																				{
																					url: wews.wews_url,
																					type: 'post',
																					dataType: 'json',
																					data:  {
																						'action': 'sync_order',
																						'order_id' : order['id'],
																						'order_type': order['type']
																					},

																					success: function (response_order) {
																						console.log( response_order );
																						if (response_order.success) {
																							$( '#message-brief-' + response_updated.data.id ).append( response_order.data.message + '<br />' );

																						} else {
																							$( '#message-brief-' + response_updated.data.id ).append( response_order.data.message + '<br />' );
																						}
																					}
																				}														
																			);
																		}
																);

																$( '#message-brief-' + response_updated.data.id ).append( response_updated.data.message + '<br />' );
																$( '#message-brief' ).text( wews.updated_customers_msg + ': ' + total_updated );
															} else {
																$( '#message' ).append( response_updated.data.message + '<br />' );
															}
															if ( total_updated === (customers.length) ) {
																$( '.loader-container' ).remove();
																$( '#message-brief' ).text( wews.updated_customers_msg + ': ' + total_updated );
																$( '#message-brief' ).append( '<br />' + wews.update_complete + '<br />' );
															}
														}
													}
												);
											}
										} else {
											$( '.loader-container' ).remove();
											$( '#message-brief' ).text( wews.no_customers_msg + '' );
										}

									} else {
										$( '<div class="message-wrap"><h3>Logs:</h3><p id="message">' + response.data.message + '</p></div>' ).insertAfter( '#customer_order_history_form' );
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
