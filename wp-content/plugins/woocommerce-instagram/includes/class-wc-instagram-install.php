<?php
/**
 * Installation related functions and actions
 *
 * Inspired in the WC_Install class.
 *
 * @package WC_Instagram
 * @since   2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WC_Instagram_Install.
 */
class WC_Instagram_Install {

	/**
	 * Database updates that need to be run per version.
	 *
	 * @var array
	 */
	private static $db_updates = array(
		'2.0.0' => array(
			'wc_instagram_update_200_migrate_settings',
			'wc_instagram_update_200_db_version',
		),
		'2.1.0' => array(
			'wc_instagram_update_210_renew_access',
			'wc_instagram_update_210_db_version',
		),
	);

	/**
	 * Background update class.
	 *
	 * @var object
	 */
	private static $background_updater;

	/**
	 * Init installation.
	 *
	 * @since 2.0.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'init', array( __CLASS__, 'init_background_updater' ), 5 );
		add_action( 'admin_init', array( __CLASS__, 'install_actions' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_notices' ), 20 );
		add_action( 'wc_instagram_updater_complete', array( __CLASS__, 'updated' ) );
	}

	/**
	 * Get the database updates.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_db_updates() {
		return self::$db_updates;
	}

	/**
	 * Init background updates.
	 *
	 * @since 2.0.0
	 */
	public static function init_background_updater() {
		include_once dirname( __FILE__ ) . '/class-wc-instagram-background-updater.php';
		self::$background_updater = new WC_Instagram_Background_Updater();
	}

	/**
	 * Check the plugin version and run the updater is necessary.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 *
	 * @since 2.0.0
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'wc_instagram_version' ), WC_INSTAGRAM_VERSION, '<' ) ) {
			self::install();
			do_action( 'wc_instagram_updated' );
		}
	}

	/**
	 * Install actions when an update button is clicked within the admin area.
	 *
	 * @since 2.0.0
	 */
	public static function install_actions() {
		if ( ! empty( $_GET['do_update_wc_instagram'] ) ) {
			check_admin_referer( 'wc_instagram_db_update', 'wc_instagram_db_update_nonce' );
			self::update();
		}

		if ( ! empty( $_GET['force_update_wc_instagram'] ) ) {
			check_admin_referer( 'wc_instagram_force_db_update', 'wc_instagram_force_db_update_nonce' );
			$blog_id = get_current_blog_id();
			do_action( 'wp_' . $blog_id . '_wc_instagram_updater_cron' );
			wp_safe_redirect( wc_instagram_get_settings_url() );
			exit;
		}
	}

	/**
	 * Removes any notices added to admin.
	 *
	 * @since 2.1.0
	 */
	private static function remove_notices() {
		include_once dirname( WC_INSTAGRAM_FILE ) . '/includes/admin/class-wc-instagram-admin-notices.php';

		// Remove not dismissed notice from a previous update.
		WC_Instagram_Admin_Notices::remove_notice( 'wc_instagram_updated' );
	}

	/**
	 * Add installer/updater notices.
	 *
	 * @since 2.0.0
	 */
	public static function add_notices() {
		if ( WC_Instagram_Admin_Notices::has_notice( 'wc_instagram_updated' ) ) {
			return;
		}

		self::update_notice();
	}

	/**
	 * Adds the update notices.
	 *
	 * @since 2.0.0
	 */
	public static function update_notice() {
		if ( self::needs_db_update() ) {
			if ( self::$background_updater->is_updating() || ! empty( $_GET['do_update_wc_instagram'] ) ) { // WPCS: CSRF ok.
				WC_Instagram_Admin_Notices::add_notice( 'updating' );
			} else {
				WC_Instagram_Admin_Notices::add_notice( 'update' );
			}
		} elseif ( self::display_api_changes_notice() ) {
			WC_Instagram_Admin_Notices::add_notice( 'api_changes' );
		}
	}

	/**
	 * Init installation.
	 *
	 * @since 2.0.0
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running the installation process.
		if ( 'yes' === get_transient( 'wc_instagram_installing' ) ) {
			return;
		}

		// Add transient to indicate that we are running the installation process.
		set_transient( 'wc_instagram_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		self::remove_notices();
		self::update_version();
		self::maybe_update_db();

		// Installation finished.
		delete_transient( 'wc_instagram_installing' );

		flush_rewrite_rules();
	}

	/**
	 * Update the plugin version to current.
	 *
	 * @since 2.0.0
	 */
	private static function update_version() {
		delete_option( 'wc_instagram_version' );
		add_option( 'wc_instagram_version', WC_INSTAGRAM_VERSION );
	}

	/**
	 * Update database version to current.
	 *
	 * @since 2.0.0
	 *
	 * @param string|null $version Optional. The new database version. Plugin version by default.
	 */
	public static function update_db_version( $version = null ) {
		delete_option( 'wc_instagram_db_version' );
		add_option( 'wc_instagram_db_version', is_null( $version ) ? WC_INSTAGRAM_VERSION : $version );
	}

	/**
	 * Update the database if necessary.
	 *
	 * @since 2.0.0
	 */
	private static function maybe_update_db() {
		if ( ! self::needs_db_update() ) {
			self::update_db_version();
		}
	}

	/**
	 * Get if the database needs to be updated or not.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private static function needs_db_update() {
		$needs_update = false;
		$db_version   = get_option( 'wc_instagram_db_version', null );
		$updates      = self::get_db_updates();

		// It's the first time we store the database version.
		if ( is_null( $db_version ) ) {
			// An older version of the plugin is installed.
			$needs_update = self::exists_older_settings();
		} elseif ( version_compare( $db_version, max( array_keys( $updates ) ), '<' ) ) {
			$needs_update = true;
		}

		return $needs_update;
	}

	/**
	 * Gets if it's necessary to display the 'API changes' notice.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function display_api_changes_notice() {
		return ( 'yes' === get_option( 'wc_instagram_display_api_changes_notice' ) && ! wc_instagram_is_settings_page() );
	}

	/**
	 * Gets if it exists any settings from older versions of this plugin in the database.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function exists_older_settings() {
		return ( false !== get_option( 'woocommerce-instagram-settings' ) );
	}

	/**
	 * Push all needed database updates to the queue for processing.
	 *
	 * @since 2.0.0
	 */
	private static function update() {
		$db_version    = get_option( 'wc_instagram_db_version' );
		$update_queued = false;

		foreach ( self::get_db_updates() as $version => $update_callbacks ) {
			if ( version_compare( $db_version, $version, '<' ) ) {
				foreach ( $update_callbacks as $update_callback ) {
					self::$background_updater->push_to_queue( $update_callback );
					$update_queued = true;
				}
			}
		}

		if ( $update_queued ) {
			self::$background_updater->save()->dispatch();
		}
	}

	/**
	 * Database updated.
	 *
	 * @since 2.1.0
	 */
	public static function updated() {
		self::update_db_version();

		WC_Instagram_Admin_Notices::add_notice(
			'wc_instagram_updated',
			_x( 'WooCommerce Instagram update complete. Thank you for updating to the latest version!', 'admin notice', 'woocommerce-instagram' )
		);
	}
}

WC_Instagram_Install::init();
