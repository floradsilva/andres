<?php
/**
 * Admin functions
 *
 * @package WC_Instagram/Admin/Functions
 * @since   2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gets the current screen ID.
 *
 * @since 2.0.0
 *
 * @return string|false The screen ID. False otherwise.
 */
function wc_instagram_get_current_screen_id() {
	$screen_id = false;

	// It may not be available.
	if ( function_exists( 'get_current_screen' ) ) {
		$screen    = get_current_screen();
		$screen_id = isset( $screen, $screen->id ) ? $screen->id : false;
	}

	// Get the value from the request.
	if ( ! $screen_id && ! empty( $_REQUEST['screen'] ) ) {
		$screen_id = wc_clean( wp_unslash( $_REQUEST['screen'] ) ); // WPCS: CSRF ok, sanitization ok.
	}

	return $screen_id;
}

/**
 * Gets if we are in the WooCommerce Instagram settings page or not.
 *
 * @since 2.0.0
 *
 * @return bool
 */
function wc_instagram_is_settings_page() {
	return (
		is_admin() &&
		isset( $_GET['page'] ) && 'wc-settings' === $_GET['page'] &&
		isset( $_GET['tab'] ) && 'integration' === $_GET['tab'] &&
		isset( $_GET['section'] ) && 'instagram' === $_GET['section']
	); // WPCS: CSRF ok.
}

/**
 * Gets the authorization URL for the specified action.
 *
 * @since 2.1.0
 *
 * @param string $action The action.
 * @return string
 */
function wc_instagram_get_authorization_url( $action ) {
	return wp_nonce_url( wc_instagram_get_settings_url( array( 'action' => $action ) ), 'wc_instagram_' . $action, 'nonce' );
}
