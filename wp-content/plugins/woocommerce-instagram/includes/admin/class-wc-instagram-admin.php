<?php
/**
 * WooCommerce Instagram Admin
 *
 * @package WC_Instagram/Admin
 * @since   2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WC_Instagram_Admin.
 */
class WC_Instagram_Admin {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'admin_init', array( $this, 'auth_redirect' ) );
		add_action( 'admin_init', array( $this, 'add_notices' ), 30 ); // After the installer/updater notices.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'plugin_action_links_' . WC_INSTAGRAM_BASENAME, array( $this, 'action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Include any classes we need within admin.
	 *
	 * @since 2.0.0
	 */
	public function includes() {
		include_once 'wc-instagram-admin-functions.php';
		include_once 'class-wc-instagram-admin-notices.php';

		if ( wc_instagram_has_business_account() ) {
			include_once 'meta-boxes/class-wc-instagram-meta-box-product-data.php';
		}
	}

	/**
	 * Checks for the redirect request to connect or disconnect the Instagram account.
	 *
	 * @since 2.0.0
	 */
	public function auth_redirect() {
		// It isn't the Instagram settings page.
		if ( ! wc_instagram_is_settings_page() ) {
			return;
		}

		$action = ( ! empty( $_GET['action'] ) ? wc_clean( wp_unslash( $_GET['action'] ) ) : '' ); // WPCS: CSRF ok.
		$nonce  = ( ! empty( $_GET['nonce'] ) ? wp_unslash( $_GET['nonce'] ) : '' ); // WPCS: CSRF ok, sanitization ok.

		if ( $action && $nonce && wp_verify_nonce( $nonce, 'wc_instagram_' . $action ) ) {
			if ( 'connect' === $action ) {
				wp_redirect( wc_instagram_get_auth_url() );
				exit();
			} elseif ( 'disconnect' === $action ) {
				wc_instagram_disconnect();
				WC_Admin_Settings::add_message( _x( 'Your Instagram account was disconnected successfully.', 'settings notice', 'woocommerce-instagram' ) );
			}
		} elseif ( $nonce && wp_verify_nonce( $nonce, 'wc_instagram_auth' ) ) {
			$notice = 'error';

			$args = array(
				'access_token' => ( ! empty( $_GET['access_token'] ) ? wc_clean( wp_unslash( $_GET['access_token'] ) ) : '' ),
				'expires_at'   => ( ! empty( $_GET['expires_at'] ) ? wc_clean( wp_unslash( $_GET['expires_at'] ) ) : '' ),
			);

			// Verify authentication.
			if ( $args['access_token'] && wc_instagram_connect( $args ) ) {
				$notice = 'connected';

				// Delete the migration notice after connecting the account.
				delete_option( 'wc_instagram_display_api_changes_notice' );
			} elseif ( ! empty( $_GET['error'] ) ) {
				$notice = 'failed';

				wc_instagram_log_api_error(
					'Authentication failed.',
					array(
						'error'       => wc_clean( wp_unslash( $_GET['error'] ) ),
						'code'        => ( ! empty( $_GET['error_code'] ) ? wc_clean( wp_unslash( $_GET['error_code'] ) ) : '' ),
						'reason'      => ( ! empty( $_GET['error_reason'] ) ? wc_clean( wp_unslash( $_GET['error_reason'] ) ) : '' ),
						'description' => ( ! empty( $_GET['error_description'] ) ? wc_clean( wp_unslash( $_GET['error_description'] ) ) : '' ),
					)
				);
			}

			// Add the notice on redirect.
			wp_safe_redirect( wc_instagram_get_settings_url( array( 'notice' => $notice ) ) );
		} elseif ( ! empty( $_GET['notice'] ) && empty( $_POST ) ) {
			$notice = wc_clean( wp_unslash( $_GET['notice'] ) );

			if ( 'connected' === $notice ) {
				WC_Admin_Settings::add_message( _x( 'Your Instagram account was connected successfully.', 'settings notice', 'woocommerce-instagram' ) );
			} elseif ( 'failed' === $notice ) {
				WC_Admin_Settings::add_error( _x( 'Authentication failed or canceled by the user.', 'settings error', 'woocommerce-instagram' ) );
			} else {
				WC_Admin_Settings::add_error( _x( 'An unexpected error occurred.', 'settings error', 'woocommerce-instagram' ) );
			}
		}
	}

	/**
	 * Adds the admin notices.
	 *
	 * @since 2.1.0
	 */
	public function add_notices() {
		// There is an installer/updater notice.
		if ( WC_Instagram_Admin_Notices::has_notices() ) {
			return;
		}

		if ( wc_instagram_is_connected() ) {
			$expires_at = wc_instagram_get_setting( 'expires_at' );

			if ( $expires_at && $expires_at < time() ) {
				WC_Instagram_Admin_Notices::add_notice( 'renew_access' );
			}
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
		$screen_id = wc_instagram_get_current_screen_id();

		if ( 'product' === $screen_id ) {
			wp_enqueue_style( 'wc-instagram-admin', WC_INSTAGRAM_URL . 'assets/css/admin.css', array(), WC_INSTAGRAM_VERSION );
		}
	}

	/**
	 * Adds custom links to the plugins page.
	 *
	 * @since 2.0.0
	 *
	 * @param array $links The plugin links.
	 * @return array The filtered plugin links.
	 */
	public function action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( wc_instagram_get_settings_url() ),
			_x( 'View WooCommerce Instagram settings', 'aria-label: settings link', 'woocommerce-instagram' ),
			_x( 'Settings', 'plugin action link', 'woocommerce-instagram' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Adds custom links to this plugin on the plugins screen.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( WC_INSTAGRAM_BASENAME === $file ) {
			$row_meta = array(
				'docs' => sprintf(
					'<a href="%1$s" aria-label="%2$s">%3$s</a>',
					esc_url( 'https://docs.woocommerce.com/document/woocommerce-instagram/' ),
					esc_attr_x( 'View WooCommerce Instagram documentation', 'aria-label: documentation link', 'woocommerce-instagram' ),
					esc_html_x( 'Docs', 'plugin row link', 'woocommerce-instagram' )
				),
			);

			$links = array_merge( $links, $row_meta );
		}

		return $links;
	}
}

return new WC_Instagram_Admin();
