<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wisdmlabs.com
 * @since             1.0.0
 * @package           Wdm_Ebridge_Woocommerce_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       WDM Ebridge WooCommerce Sync
 * Plugin URI:        https://wisdmlabs.com/wdm-ebridge-woocommerce-sync/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            WisdmLabs
 * Author URI:        https://wisdmlabs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wdm-ebridge-woocommerce-sync
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	echo __( 'Please activate WooCommerce Plugin before activating the plugin.', 'wdm-ebridge-woocommerce-sync' );
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WDM_EBRIDGE_WOOCOMMERCE_SYNC_VERSION', '1.0.0' );

// Define WC_PLUGIN_URL.
if ( ! defined( 'WEWS_PLUGIN_URL' ) ) {
	define( 'WEWS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Define WC_PLUGIN_URL.
if ( ! defined( 'WEWS_PLUGIN_PATH' ) ) {
	define( 'WEWS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wdm-ebridge-woocommerce-sync-activator.php
 */
function activate_wdm_ebridge_woocommerce_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wdm-ebridge-woocommerce-sync-activator.php';
	Wdm_Ebridge_Woocommerce_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wdm-ebridge-woocommerce-sync-deactivator.php
 */
function deactivate_wdm_ebridge_woocommerce_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wdm-ebridge-woocommerce-sync-deactivator.php';
	Wdm_Ebridge_Woocommerce_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wdm_ebridge_woocommerce_sync' );
register_deactivation_hook( __FILE__, 'deactivate_wdm_ebridge_woocommerce_sync' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wdm-ebridge-woocommerce-sync.php';

// Define WEWS_FETCH_SIZE.
if ( ! defined( 'WEWS_FETCH_SIZE' ) ) {
	define( 'WEWS_FETCH_SIZE', 100 );
}

// Define WEWS_MAX_NET_QUANTITY.
if ( ! defined( 'WEWS_MAX_NET_QUANTITY' ) ) {
	define( 'WEWS_MAX_NET_QUANTITY', 100 );
}


// Define WEWS_FETCH_CUSTOMER_SIZE.
if ( ! defined( 'WEWS_FETCH_CUSTOMER_SIZE' ) ) {
	define( 'WEWS_FETCH_CUSTOMER_SIZE', 10 );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wdm_ebridge_woocommerce_sync() {
	$plugin = new Wdm_Ebridge_Woocommerce_Sync();
	$plugin->run();
}

run_wdm_ebridge_woocommerce_sync();
