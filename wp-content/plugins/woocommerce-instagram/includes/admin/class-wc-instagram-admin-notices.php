<?php
/**
 * Display notices in the admin
 *
 * @package WC_Instagram/Admin
 * @since   2.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Instagram_Admin_Notices Class.
 */
class WC_Instagram_Admin_Notices {

	/**
	 * Stores notices.
	 *
	 * @var array
	 */
	private static $notices = array();

	/**
	 * Init.
	 *
	 * @since 2.1.0
	 */
	public static function init() {
		if ( ! class_exists( 'WC_Admin_Notices' ) ) {
			include_once dirname( WC_PLUGIN_FILE ) . '/includes/admin/class-wc-admin-notices.php';
		}

		add_action( 'admin_print_styles', array( __CLASS__, 'add_notices' ), 20 );
	}

	/**
	 * Get notices
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}

	/**
	 * Displays a notice.
	 *
	 * @since 2.1.0
	 *
	 * @param string $name        Notice name.
	 * @param string $notice_html Optional. Notice HTML.
	 */
	public static function add_notice( $name, $notice_html = '' ) {
		if ( ! empty( $notice_html ) ) {
			if ( method_exists( 'WC_Admin_Notices', 'add_custom_notice' ) ) {
				WC_Admin_Notices::add_custom_notice( $name, $notice_html );
			}
		} else {
			self::$notices = array_unique( array_merge( self::get_notices(), array( $name ) ) );
		}
	}

	/**
	 * Remove a notice from being displayed.
	 *
	 * @since 2.1.0
	 *
	 * @param string $name Notice name.
	 */
	public static function remove_notice( $name ) {
		if ( in_array( $name, self::get_notices(), true ) ) {
			self::$notices = array_diff( self::get_notices(), array( $name ) );
		} else {
			WC_Admin_Notices::remove_notice( $name );
		}
	}

	/**
	 * Gets if a notice is being shown or not.
	 *
	 * @since 2.1.0
	 *
	 * @param string $name Notice name.
	 * @return boolean
	 */
	public static function has_notice( $name ) {
		$has_notice = in_array( $name, self::get_notices(), true );

		if ( ! $has_notice ) {
			$has_notice = WC_Admin_Notices::has_notice( $name );
		}

		return $has_notice;
	}

	/**
	 * Gets if there are notices registered or not.
	 *
	 * @since 2.1.0
	 *
	 * @return bool
	 */
	public static function has_notices() {
		$notices = self::get_notices();

		return ( ! empty( $notices ) );
	}

	/**
	 * Add notices + styles if needed.
	 *
	 * @since 2.1.0
	 */
	public static function add_notices() {
		$notices = self::get_notices();

		if ( empty( $notices ) ) {
			return;
		}

		/*
		 * If the notices scripts has already been enqueued by WC, we don't need to check these conditions.
		 */
		if ( ! wp_style_is( 'woocommerce-activation' ) ) {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}

			$screen_id       = wc_instagram_get_current_screen_id();
			$show_on_screens = array(
				'dashboard',
				'plugins',
			);

			// Notices should only show on WooCommerce screens, the main dashboard, and on the plugins screen.
			if ( ! in_array( $screen_id, wc_get_screen_ids(), true ) && ! in_array( $screen_id, $show_on_screens, true ) ) {
				return;
			}

			self::enqueue_scripts();
		}

		add_action( 'admin_notices', array( __CLASS__, 'output_notices' ) );
	}

	/**
	 * Enqueues scripts.
	 *
	 * @since 2.1.0
	 */
	public static function enqueue_scripts() {
		wp_enqueue_style( 'woocommerce-activation', plugins_url( '/assets/css/activation.css', WC_PLUGIN_FILE ), array(), WC_VERSION );
		wp_style_add_data( 'woocommerce-activation', 'rtl', 'replace' );
	}

	/**
	 * Outputs the notices.
	 *
	 * @since 2.1.0
	 */
	public static function output_notices() {
		$notices = self::get_notices();

		foreach ( $notices as $name ) {
			$file = str_replace( '_', '-', $name );
			$path = dirname( __FILE__ ) . "/notices/{$file}.php";

			if ( is_readable( $path ) ) {
				include $path;
			}
		}
	}
}

WC_Instagram_Admin_Notices::init();
