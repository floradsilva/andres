<?php

/**
 * Helper functions.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/includes
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */

if ( ! class_exists( 'Wews_Helper_Functions' ) ) {
	class Wews_Helper_Functions {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
		}

		public static function get_order_type_str_to_num( $ebridge_order_type ) {
			$ebridge_order_type = ucfirst( strtolower( $ebridge_order_type ) );
			$order_types        = ORDER_TYPE;
			$ebridge_order_type = $order_types[ $ebridge_order_type ];

			return $ebridge_order_type;
		}


		public static function get_order_type_num_to_str( $ebridge_order_type ) {
			$ebridge_order_type = array_search( $ebridge_order_type, ORDER_TYPE );
			return $ebridge_order_type;
		}
	}
}
