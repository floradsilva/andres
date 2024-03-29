<?php

class WCCH_Track_History {

	/**
	 * Fire up the engines.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Connect to WooCommerce
		add_action( 'wcch_visited_url', array( $this, 'update_customer_history' ), 10, 3 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_customer_history' ) );

		// Uncomment the following action to enable devmode
		// add_action( 'get_header', array( $this, 'devmode' ) );

	}

	/**
	 * Initialize tracking of browsing history.
	 *
	 * @since 1.0.0
	 */
	public function update_customer_history( $page_url = '', $timestamp = 0, $referrer = '' ) {

		// Grab browsing history from the current session
		$history = $this->get_customer_history( $referrer );
		$history[] = array( 'url' => esc_url( $page_url ), 'time' => absint( $timestamp ) );

		// Push the updated history to the current session
		$user_hash = WCCH_Cookie_Helper::get_cookie();
		wcch_set_page_history( $user_hash, $history );

	} /* update_customer_history() */

	/**
	 * Get browsing history from session.
	 *
	 * @since 1.0.0
	 */
	private function get_customer_history( $referrer = '' ) {

		$user_hash = WCCH_Cookie_Helper::get_cookie();
		$customer_history = wcch_get_page_history( $user_hash );

		// If user has an established history, return that
		if ( ! empty( $customer_history ) ) {
			return (array) $customer_history;

		// Otherwise, return an array with the original referrer
		} else {
			$referrer = esc_url( $referrer )
				? $referrer
				: __( 'Direct Traffic', 'woocommerce-customer-history' );

			return array( array( 'url' => $referrer, 'time' => time() ) );
		}

	} /* get_customer_history() */

	/**
	 * Save browsing history as order meta.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $order_id Order post ID.
	 */
	public function save_customer_history( $order_id = 0 ) {

		// Grab browsing history from the current session
		$customer_history = $this->get_customer_history();

		// If browsing history was captured, sanitize and store the URLs
		if ( is_array( $customer_history ) && ! empty( $customer_history ) ) {

			// Setup a clean, safe array for the database
			$sanitized_history = array();

			// Sanitize the referrer a bit differently
			// than the rest because it may not be a URL.
			$referrer = array_shift( $customer_history );
			$sanitized_history[] = array(
				'url'  => sanitize_text_field( $referrer->url ),
				'time' => absint( $referrer->time ),
			);

			// Sanitize each additional URL
			foreach ( $customer_history as $history ) {
				$sanitized_history[] = array(
					'url'  => esc_url_raw( $history->url ),
					'time' => absint( $history->time ),
				);
			}

			// Add one final timestamp for order complete
			$sanitized_history[] = array(
				'url'  => __( 'Order Complete', 'edduh' ),
				'time' => time(),
			);

			// Store sanitized history as post meta
			update_post_meta( $order_id, '_user_history', $sanitized_history );
			WCCH_Cookie_Helper::delete_history_data();
		}

	} /* save_customer_history() */

	/**
	 * Handle developer debug data.
	 *
	 * Usage: Hook to get_header, append "?devmode=true" to any front-end URL.
	 * To view tracked history, add "&output=history".
	 * To view session object, add "&output=session".
	 * To reset tracked history, add "&reset=history".
	 *
	 * @since 1.0.0
	 */
	public function devmode() {

		// Only proceed if URL querystring cotnains "devmode=true"
		if ( isset($_GET['devmode']) && 'true' == $_GET['devmode'] ) {

			// Output user history if URL querystring contains 'output=history'
			if ( isset($_GET['output']) && 'history' == $_GET['output'] ) {
				var_dump( WCCH_Cookie_Helper::get_cookie() );
				echo '<pre>' . print_r( $this->get_customer_history(), 1 ) . '</pre>';
			}

			// Output user history cookie if URL querystring contains 'output=cookie'
			if ( isset($_GET['output']) && 'cookie' == $_GET['output'] ) {
				echo '<pre>' . print_r( $_COOKIE, 1 ) . '</pre>';
			}

			// Clear customer_history and dump us back at the homepage if URL querystring contains 'history=reset'
			if ( isset($_GET['history']) && 'reset' == $_GET['history'] ) {
				WCCH_Cookie_Helper::delete_history_data();
				wp_redirect( site_url() );
				exit;
			}

		}

	} /* devmode() */

}
return new WCCH_Track_History;
