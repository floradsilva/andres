<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Pinterest_Rich_Pins_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pintrest_wc_rich_pins',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
