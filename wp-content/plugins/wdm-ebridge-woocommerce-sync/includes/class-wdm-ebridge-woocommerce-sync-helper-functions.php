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

		public static function get_order_type_str_to_num( $ebridge_order_type, $type ) {
			$ebridge_order_type = ucfirst( strtolower( $ebridge_order_type ) );
			$order_types        = $type;
			$ebridge_order_type = $order_types[ $ebridge_order_type ];

			return $ebridge_order_type;
		}


		public static function get_order_type_num_to_str( $ebridge_order_type, $type ) {
			$ebridge_order_type = array_search( $ebridge_order_type, $type );
			return $ebridge_order_type;
		}


		public static function get_order_type_from_status_num( $ebridge_order_type, $type ) {

			if ( is_numeric( $ebridge_order_type ) ) {
				$ebridge_order_type = array_search( $ebridge_order_type, $type );
			}

			$ebridge_order_type = ucfirst( strtolower( $ebridge_order_type ) );
			$ebridge_order_type = ORDER_STATUS_TO_TYPE[ $ebridge_order_type ];

			return $ebridge_order_type;
		}


		public static function get_order_type_from_status_str( $ebridge_order_type, $type ) {
			if ( is_numeric( $ebridge_order_type ) ) {
				$ebridge_order_type = array_search( $ebridge_order_type, $type );
			}

			$ebridge_order_type = ucfirst( strtolower( $ebridge_order_type ) );
			$ebridge_order_type = ORDER_STATUS_TO_TYPE[ $ebridge_order_type ];
			$ebridge_order_type = array_search( $ebridge_order_type, ORDER_TYPE );

			return $ebridge_order_type;
		}


		public static function is_valid_order_type( $ebridge_order_type ) {
			if ( is_numeric( $ebridge_order_type ) ) {
				$ebridge_order_type = array_search( $ebridge_order_type, ORDER_STATUS );
			}

			$ebridge_order_type = ucfirst( strtolower( $ebridge_order_type ) );
			$ebridge_order_type = array_search( $ebridge_order_type, VALID_ORDER_STATUSES );

			if ( $ebridge_order_type ) {
				return true;
			}

			return false;
		}



		/**
		 * Clear all the products and related data
		 *
		 * @since    1.0.0
		 * @param    Array $args    Arguments to wc_get_products
		 */

		public static function clear_products_data( $args ) {
			$products = wc_get_products( $args );

			foreach ( $products as $key => $product ) {
				$sku        = $product->get_sku();
				$product_id = $product->get_id();

				$deleted = delete_option( 'product_' . $sku );

				$product->delete( true );

				if ( $parent_id = wp_get_post_parent_id( $product_id ) ) {
					wc_delete_product_transients( $parent_id );
				}
			}

			delete_option( 'wews_product_update_start' );
			delete_option( 'wews_product_delete_start' );
			delete_option( 'wews_customer_update_start' );
			delete_option( 'wews_product_all_update_start' );
			delete_option( 'wews_product_all_delete_start' );
			delete_option( 'ebridge_sync_last_updated_date' );
			delete_option( 'ebridge_sync_last_updated_time' );
			delete_option( 'ebridge_sync_web_server_time_zone_offset' );
		}
	}
}
