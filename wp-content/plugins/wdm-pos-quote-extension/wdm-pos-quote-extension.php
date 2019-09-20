<?php
/**
 * Plugin Name: Wdm POS Quote Extension
 * Description: A plugin to add quote functionality to POS order screen.
 * Version: 1.0.0
 * Author: Wisdmlabs
 * Author URI: https://wisdmlabs.com
 * Text Domain: wdm-pos-quote-extension
 * License: GPL2+
 */


if ( ! in_array( 'woocommerce-openpos/woocommerce-openpos.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	echo __( 'Please activate WooCommerce Open POS Plugin before activating the plugin.', 'wdm-pos-quote-extension' );
	die;
}


/* Define WC_PLUGIN_FILE. */
if ( ! defined( 'WPQE_PLUGIN_PATH' ) ) {
	define( 'WPQE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Load dependencies.
require  WPQE_PLUGIN_PATH . 'includes/class-pos-quote.php';



// Initialize our classes.
