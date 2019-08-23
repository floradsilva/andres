<?php

/**
 * Fired during plugin activation
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Pinterest_Rich_Pins_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		pinterest_rich_pin_activate_settings();
		
		/*
		 * To add a folder in uploads
		 */
		
		// Get real path for our folder
		$upload_dir = wp_upload_dir();
		$rootPath = $upload_dir['basedir'] . '/pinterest_rich_pins';
		
		if (!file_exists($rootPath)) {
			wp_mkdir_p($rootPath, 0777, true);
		}
	}

}

/*
 * This function is to set common settings when plugin is activated
 */
function pinterest_rich_pin_activate_settings(){
	pinterest_rich_pin_create_entry_table();
	pinterest_rich_pin_create_list_table();
	pinterest_rich_pin_create_attachments_table();
	pinterest_rich_pin_create_default_crone();
}

/*
 * This function is to create main table if not exists when plugin is activated
 */
function pinterest_rich_pin_create_entry_table(){
	
	global $wpdb;
	$table_name = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
	
	$charset_collate = $wpdb->get_charset_collate();
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {
        $sql = "CREATE TABLE " . $table_name . " (
             `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
			 `queue_id` BIGINT(20),
			 `action` varchar(255), 	
			 `status` varchar(255),
			 `product_id` int(20),
			 `image_id` BIGINT(20),
			 `created_by` varchar(255),
			 `description` LONGTEXT,
			 `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			 `execute_date` TIMESTAMP NOT NULL,
			  UNIQUE KEY id (id)
		)$charset_collate;";
		
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
	else{
		$wpdb->query("ALTER TABLE `wp_demo_rich_pin_queue_list_entry` CHANGE `description` `pinterest_id` BIGINT(20) NULL DEFAULT NULL");
		$wpdb->query("ALTER TABLE `wp_demo_rich_pin_queue_list_entry` DROP `execute_date`");
	}
}

/*
 * This function is to create meta table if not exists when plugin is activated
 */
function pinterest_rich_pin_create_list_table(){
	global $wpdb;
	$table_name = PINTEREST_RICH_PINS_QUEUE_LIST_TABLE_NAME;
	
	$charset_collate = $wpdb->get_charset_collate();
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {
        $sql = "CREATE TABLE " . $table_name . " (
             `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
			 `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE KEY id (id)
		)$charset_collate;";
		
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

/*
 * This function is to create meta table if not exists when plugin is activated
 */
function pinterest_rich_pin_create_attachments_table(){
	global $wpdb;
	$table_name = PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME;
	
	$charset_collate = $wpdb->get_charset_collate();
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {
        $sql = "CREATE TABLE " . $table_name . " (
             `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
			 `product_id` BIGINT(20) NOT NULL,
			 `attachment_id` BIGINT(20) NOT NULL,
			 `pinterest_id` BIGINT(20),
			 `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE KEY id (id)
		)$charset_collate;";
		
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

/*
 * This function is to initialize crone events
 */
function pinterest_rich_pin_create_default_crone(){
	$timestamp = time();
	$recurrence = "hourly";
	$hook = "pinterest_rich_pin_cron_schedules_hooks";
	
	// Make sure this event hasn't been scheduled
	if( !wp_next_scheduled( $hook ) ) {
		// Schedule the event
		wp_schedule_event( $timestamp, $recurrence, $hook );
	}
}