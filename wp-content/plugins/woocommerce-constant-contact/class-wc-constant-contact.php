<?php
/**
 * WooCommerce Constant Contact
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Constant Contact to newer
 * versions in the future. If you wish to customize WooCommerce Constant Contact for your
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-constant-contact/ for more information.
 *
 * @author      SkyVerge
 * @copyright   Copyright (c) 2013-2019, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

/**
 * WooCommerce Constant Contact main plugin class.
 *
 * @since 1.0
 */
class WC_Constant_Contact extends Framework\SV_WC_Plugin {


	/** plugin version number */
	const VERSION = '1.10.1';

	/** @var WC_Constant_Contact single instance of this plugin */
	protected static $instance;

	/** plugin id */
	const PLUGIN_ID = 'constant_contact';

	/** @var \WC_Constant_Contact_API instance */
	private $api;

	/** @var \WC_Constant_Contact_Frontend instance */
	protected $frontend;

	/** @var \WC_Constant_Contact_Settings instance */
	protected $settings;

	/** @var \WC_Constant_Contact_Points_And_Rewards instance */
	protected $points_and_rewards;


	/**
	 * Initializes the plugin
	 *
	 * @since 1.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'text_domain' => 'woocommerce-constant-contact',
				'dependencies' => array(
					'php_extensions' => array(
						'dom',
					),
				),
			)
		);

		// include required files
		$this->includes();

		// load widget
		add_action( 'widgets_init', array( $this, 'init_widget' ) );

		// log API if debug mode is enabled
		if ( 'on' !== get_option( 'wc_constant_contact_debug_mode' ) ) {
			remove_action( 'wc_' . $this->get_id() . '_api_request_performed', array( $this, 'log_api_request' ), 10 );
		}

		// admin
		if ( is_admin() && ! is_ajax() ) {

			// load dashboard
			add_action( 'wp_dashboard_setup', array( $this, 'init_dashboard' ) );

			// add settings page
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_settings_page' ) );
		}
	}


	/**
	 * Includes required files.
	 *
	 * @since 1.0
	 */
	private function includes() {

		$this->frontend = $this->load_class( '/includes/class-wc-constant-contact-frontend.php', 'WC_Constant_Contact_Frontend' );

		if ( $this->is_plugin_active( 'woocommerce-points-and-rewards.php' ) ) {

			$this->points_and_rewards = $this->load_class( '/includes/class-wc-constant-contact-points-and-rewards.php', 'WC_Constant_Contact_Points_and_Rewards' );
		}
	}


	/**
	 * Loads and initializes the plugin lifecycle handler.
	 *
	 * @since 1.10.0
	 */
	protected function init_lifecycle_handler() {

		require_once( $this->get_plugin_path() . '/includes/Lifecycle.php' );

		$this->lifecycle_handler = new SkyVerge\WooCommerce\Constant_Contact\Lifecycle( $this );
	}


	/**
	 * Gets the frontend class handler.
	 *
	 * @since 1.7.0
	 *
	 * @return \WC_Constant_Contact_Frontend
	 */
	public function get_frontend_instance() {

		return $this->frontend;
	}


	/**
	 * Gets the Points and Rewards handler instance.
	 *
	 * @since 1.7.0
	 *
	 * @return \WC_Constant_Contact_Points_And_Rewards
	 */
	public function get_points_and_rewards_instance() {

		return $this->points_and_rewards;
	}


	/**
	 * Gets the settings handler instance.
	 *
	 * @since 1.7.0
	 *
	 * @return \WC_Constant_Contact_Settings
	 */
	public function get_settings_instance() {

		return $this->settings;
	}


	/**
	 * Lazy loads the Constant Contact API object.
	 *
	 * @since 1.0
	 *
	 * @return null|\WC_Constant_Contact_API
	 */
	public function get_api() {

		if ( is_object( $this->api ) ) {
			return $this->api;
		}

		$username = get_option( 'wc_constant_contact_username' );
		$password = get_option( 'wc_constant_contact_password' );
		$api_key  = get_option( 'wc_constant_contact_api_key' );

		// bail if required info is not available
		if ( ! $username || ! $password || ! $api_key ) {
			return null;
		}

		// load API wrapper
		require_once( $this->get_plugin_path() . '/includes/api/class-wc-constant-contact-api.php' );
		require_once( $this->get_plugin_path() . '/includes/api/class-wc-constant-contact-api-request.php' );
		require_once( $this->get_plugin_path() . '/includes/api/class-wc-constant-contact-api-response.php' );

		return $this->api = new \WC_Constant_Contact_API( $username, $password, $api_key );
	}


	/**
	 * Loads the 'Subscribe' widget
	 *
	 * @internal
	 *
	 * @since 1.0
	 */
	public function init_widget() {

		require_once( $this->get_plugin_path() . '/includes/class-wc-constant-contact-widget.php' );

		register_widget( 'WC_Constant_Contact_Widget' );
	}


	/**
	 * Adds a settings page.
	 *
	 * @internal
	 *
	 * @since 1.3.1
	 *
	 * @param array $settings
	 * @return array
	 */
	public function add_settings_page( $settings ) {

		if ( ! $this->settings instanceof \WC_Constant_Contact_Settings ) {
			$this->settings = $this->load_class( '/includes/admin/class-wc-constant-contact-settings.php', 'WC_Constant_Contact_Settings' );
		}

		$settings[] = $this->settings;

		return $settings;
	}


	/**
	 * Loads admin dashboard stats.
	 *
	 * @internal
	 *
	 * @since 1.0
	 */
	public function init_dashboard() {

		if ( current_user_can( 'manage_woocommerce' ) && $this->get_api() ) {

			wp_add_dashboard_widget( 'wc_constant_contact_dashboard', __( 'Email List Subscribers', 'woocommerce-constant-contact' ), array( $this, 'render_dashboard' ) );
		}
	}


	/**
	 * Renders admin dashboard stats.
	 *
	 * Only includes total email subscribers at the moment.
	 *
	 * @internal
	 *
	 * @since 1.0
	 */
	public function render_dashboard() {

		if ( false === ( $stats = get_transient( 'wc_constant_contact_stats' ) ) ) {

			try {
				$stats = $this->get_api()->get_stats( get_option( 'wc_constant_contact_email_list' ) );
			} catch ( Framework\SV_WC_API_Exception $e ) {
				$this->log( sprintf( __( 'Error loading stats: %s', 'woocommerce-constant-contact' ), $e->getMessage() ) );
			}

			if ( ! empty( $stats ) ) {
				set_transient( 'wc_constant_contact_stats', $stats, 60 * 60 * 1 );
			}
		}

		if ( empty( $stats ) ) {

			?>
			<div class="error inline">
				<p><?php esc_html_e( 'Unable to load stats from Constant Contact', 'woocommerce-constant-contact' ); ?></p>
			</div>
			<?php

		} else {

			?>
			<style type="text/css">ul.wc_constant_contact_stats{overflow:hidden;zoom:1}ul.wc_constant_contact_stats li{width:22%;padding:0 1.4%;float:left;font-size:0.8em;border-left:1px solid #fff;border-right:1px solid #ececec;text-align:center} ul.wc_constant_contact_stats li:first-child{border-left:0} ul.wc_constant_contact_stats li:last-child{border-right:0} ul.wc_constant_contact_stats strong{font-family:Georgia,"Times New Roman","Bitstream Charter",Times,serif;font-size:4em;line-height:1.2em;font-weight:normal;text-align:center;display:block}</style>
			<h2><?php echo esc_html( $stats['list_name'] ); ?></h2>
			<ul class="wc_constant_contact_stats">
				<li><strong><?php echo esc_html( $stats['list_subscribers'] ); ?></strong> <?php echo _n( 'Subscriber', 'Subscribers', $stats['list_subscribers'], 'woocommerce-constant-contact' ); ?></li>
			</ul>
			<?php
		}
	}


	/**
	 * Returns the main Constant Contact instance.
	 *
	 * Ensures only one instance is/can be loaded.
	 *
	 * @since 1.4.0
	 *
	 * @return \WC_Constant_Contact
	 */
	public static function instance() {

		if ( null === self::$instance ) {

			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Gets the plugin name, localized.
	 *
	 * @since 1.2
	 *
	 * @return string the plugin name
	 */
	public function get_plugin_name() {

		return __( 'WooCommerce Constant Contact', 'woocommerce-constant-contact' );
	}


	/**
	 * Returns __FILE__.
	 *
	 * @since 1.2
	 *
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {

		return __FILE__;
	}


	/**
	 * Gets the URL to the settings page.
	 *
	 * @since 1.2
	 *
	 * @param string $_ unused
	 * @return string URL to the settings page
	 */
	public function get_settings_url( $_ = '' ) {

		return admin_url( 'admin.php?page=wc-settings&tab=constant_contact' );
	}


	/**
	 * Gets the plugin documentation URL.
	 *
	 * @since 1.5.0
	 * @return string
	 */
	public function get_documentation_url() {

		return 'http://docs.woocommerce.com/document/woocommerce-constant-contact/';
	}


	/**
	 * Gets the plugin support URL.
	 *
	 * @since  1.5.0
	 *
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/marketplace-ticket-form/';
	}


	/**
	 * Gets the plugin sales page URL.
	 *
	 * @since 1.10.0
	 *
	 * @return string
	 */
	public function get_sales_page_url() {

		return 'https://woocommerce.com/products/woocommerce-constant-contact/';
	}


	/**
	 * Determines if the current is the plugin settings page.
	 *
	 * @since 1.2
	 *
	 * @return bool
	 */
	public function is_plugin_settings() {

		return isset( $_GET['page'], $_GET['tab'] )
			   && 'wc-settings'      === $_GET['page']
			   && 'constant_contact' === $_GET['tab'];
	}


}


/**
 * Returns the One True Instance of Constant Contact.
 *
 * @since 1.4.0
 *
 * @return \WC_Constant_Contact
 */
function wc_constant_contact() {

	return \WC_Constant_Contact::instance();
}
