<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Pinterest_Rich_Pins_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Get the timestamp of the next scheduled run
		$timestamp = wp_next_scheduled( 'pinterest_rich_pin_cron_schedules_hooks' );
		
		// Un-schedule the event
		wp_unschedule_event( $timestamp, 'pinterest_rich_pin_cron_schedules_hooks' );
	}

}
