<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.vsourz.com
 * @since             1.0.0
 * @package           Pinterest_Rich_Pins
 *
 * @wordpress-plugin
 * Plugin Name:       Pinterest Rich Pins For Woo-commerce
 * Plugin URI:        www.vsourz.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Vsourz Development Team
 * Author URI:        www.vsourz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pinterest-rich-pins
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PINTEREST_RICH_PINS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pinterest-rich-pins-activator.php
 */
function activate_pinterest_rich_pins() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pinterest-rich-pins-activator.php';
	Pinterest_Rich_Pins_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pinterest-rich-pins-deactivator.php
 */
function deactivate_pinterest_rich_pins() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pinterest-rich-pins-deactivator.php';
	Pinterest_Rich_Pins_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pinterest_rich_pins' );
register_deactivation_hook( __FILE__, 'deactivate_pinterest_rich_pins' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pinterest-rich-pins.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pinterest_rich_pins() {

	$plugin = new Pinterest_Rich_Pins();
	$plugin->run();

}
run_pinterest_rich_pins();

// Constant for prefix
if(!defined("PINTEREST_RICH_PINS_PREFIX")){
	define("PINTEREST_RICH_PINS_PREFIX","pintrest_wc_rich_pins_");
}

// Constant for text domain
if(!defined("PINTEREST_RICH_PINS_TEXT_DOMAIN")){
	define("PINTEREST_RICH_PINS_TEXT_DOMAIN","pintrest_wc_rich_pins");
}

// Constant for plugin name
if(!defined("PINTEREST_RICH_PINS_PLUGIN_SLUG")){
	define("PINTEREST_RICH_PINS_PLUGIN_SLUG","pintrest_wc_rich_pins");
}

// Constant for directory path
if(!defined("PINTEREST_RICH_PINS_DIR_PATH")){
	define("PINTEREST_RICH_PINS_DIR_PATH",plugin_dir_path(__FILE__));
}

// Constant for directory url
if(!defined("PINTEREST_RICH_PINS_DIR_URL")){
	define("PINTEREST_RICH_PINS_DIR_URL",plugin_dir_url(__FILE__));
}

// Constant for template path
if(!defined("PINTEREST_RICH_PINS_TEMPLATE_PATH")){
	define("PINTEREST_RICH_PINS_TEMPLATE_PATH",plugin_dir_path(__FILE__)."templates");
}

// Constant for theme folder path
if(!defined("PINTEREST_RICH_PINS_THEME_PATH")){
	define("PINTEREST_RICH_PINS_THEME_PATH",get_template_directory()."/woocommerce/".PINTEREST_RICH_PINS_PLUGIN_SLUG);
}

// Constant for adding item text
if(!defined("PINTEREST_RICH_PINS_ADD_PIN")){
	define("PINTEREST_RICH_PINS_ADD_PIN","Adding Item");
}

// Constant for updating item text
if(!defined("PINTEREST_RICH_PINS_UPDATE_PIN")){
	define("PINTEREST_RICH_PINS_UPDATE_PIN","Updating Item");
}

// Constant for deleting item text
if(!defined("PINTEREST_RICH_PINS_DELETE_PIN")){
	define("PINTEREST_RICH_PINS_DELETE_PIN","Deleting Item");
}

// Create prefix
global $wpdb;
$prefix = $wpdb->prefix."rich_pin_queue_";

// Constant for list entry table name
if(!defined("PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME")){
	define("PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME", $prefix."list_entry");
}

// Constant for list table name
if(!defined("PINTEREST_RICH_PINS_QUEUE_LIST_TABLE_NAME")){
	define("PINTEREST_RICH_PINS_QUEUE_LIST_TABLE_NAME", $prefix."list");
}

// Constant for attachment table name
if(!defined("PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME")){
	define("PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME", $wpdb->prefix."rich_pin_attachments");
}

/*
 * Defining the autoload function
 * Reference :: https://github.com/dirkgroenen/Pinterest-API-PHP
 * Check the default functions and use the library of the code available
 *
 * Need to set the redirect api to the Account set in the pinterest
 */
spl_autoload_register(function($class) {

    // project-specific namespace prefix
    $prefix = 'Vsourz\\Pinterest\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/Pinterest/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});