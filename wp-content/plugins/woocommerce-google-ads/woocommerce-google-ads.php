<?php
/**
Plugin Name: Google Ads for WooCommerce
Plugin URI: https://woo.kliken.com/
Description: The automated Google Shopping solution to get your products found on Google, and grow your WooCommerce Store!
Version: 1.0.1
Author: Kliken
Author URI: http://kliken.com/
Developer: Kliken
Developer URI: http://kliken.com/
Text Domain: woocommerce-google-ads
Domain path: /languages

Woo: 3866145:c59f6607f6bcd5e3359b62b26527da7e
WC requires at least: 3.0
WC tested up to: 3.5

Copyright: © 2009-2018 WooCommerce.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

@package Kliken WooCommerce Google Ads
 */

defined( 'ABSPATH' ) || exit;

define( 'KK_WC_PLUGIN_FILE', __FILE__ );
define( 'KK_WC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KK_WC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KK_WC_PLUGIN_REL_PATH', basename( dirname( __FILE__ ) ) );
define( 'KK_WC_AFFILIATE_ID', '82E7B644-DB42-40E9-9EDF-6FD10A4BAFB3' );
define( 'KK_WC_WOOKLIKEN_BASE_URL', 'https://woo.kliken.com/' );
define( 'KK_WC_AUTH_CALLBACK_URL', 'https://app.mysite-analytics.com/WebHooks/WooCommerceAuth/' );
define( 'KK_WC_AUTH_APP_NAME', 'Google Ads for WooCommerce' );

define( 'KK_WC_INTEGRATION_PAGE_ID', 'kk_wcintegration' );

define( 'KK_WC_ACTION_SAVE_ACCOUNT', 'kkwc_saveaccount' );
define( 'KK_WC_ACTION_DISMISS_NOTICE', 'kkwc_dismissnotice' );
define( 'KK_WC_ACTION_CHECK_ACCOUNT', 'kkwc_checkaccount' );

define( 'KK_WC_TRANSIENT_AUTH_REDIRECT', 'kk_wc_activation_redirect' );

define( 'KK_WC_WELCOME_MESSAGE', 'kk_wc_welcome_message' );
define( 'KK_WC_BOOTSTRAP_MESSAGE', 'kk_wc_bootstrap_message' );

require 'vendor/autoload.php';

/**
 * Starting point. Try to initiate the main instance of the plugin.
 */
function kk_wc_plugin() {
	static $plugin;

	if ( ! isset( $plugin ) ) {
		$plugin = new \Kliken\WcPlugin\Plugin();
	}

	return $plugin;
}

// Adopt this nice method from WooCommerce.
kk_wc_plugin()->maybe_run();
