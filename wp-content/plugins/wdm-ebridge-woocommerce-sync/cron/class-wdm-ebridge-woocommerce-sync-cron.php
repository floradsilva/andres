#!/usr/bin/php
<?php

/**
 * Adds the Menu and Sub Menu Pages for Ebridge Settings.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync_Cron {

	/**
	 * The ebridge api url.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $api_url    The EBridge API URL.
	 */
	private $api_url;


	/**
	 * The ebdrige api token.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $api_token    The EBridge API Token.
	 */
	private $api_token;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct() {
		$old_url = dirname( __FILE__, 2 );
		$new_url = str_replace( DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'wdm-ebridge-woocommerce-sync', '', $old_url );

		require $new_url . DIRECTORY_SEPARATOR . 'wp-load.php';

		include_once plugin_dir_path( __DIR__ ) . 'includes/class-wdm-ebridge-woocommerce-sync-categories.php';
		include_once plugin_dir_path( __DIR__ ) . 'includes/class-wdm-ebridge-woocommerce-sync-products.php';

		// $args = array(
		// 	'limit' => -1,
		// );

		// Wews_Helper_Functions::clear_products_data( $args );

		$this->api_url                    = get_option( 'ebridge_sync_api_url', '' );
		$this->api_token                  = get_option( 'ebridge_sync_api_token', '' );
		$this->products_obj               = new Wdm_Ebridge_Woocommerce_Sync_Products();
		$this->categories_obj             = new Wdm_Ebridge_Woocommerce_Sync_Categories();
		$this->customer_obj               = new Wdm_Ebridge_Woocommerce_Sync_Customer();
		$this->order_obj                  = new Wdm_Ebridge_Woocommerce_Sync_Order();
		$this->customer_order_history_obj = new WEWS_Customer_Order_History_Sync( '', '' );

		// add_action( 'http_api_curl', array( $this, 'wdm_custom_curl_timeout' ), 9999, 1 );

		$this->sync_categories();
		$this->sync_brands();
		$this->sync_products();
		$this->sync_customer_order_history();
	}


	public function sync_categories() {
		$added_web_categories = $this->categories_obj->add_webcategories();

		echo '<pre>';
		echo __( $added_web_categories['success_count'] . ' WebCategories added.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo '</pre>';
	}



	public function sync_brands() {
		$added_brands = $this->categories_obj->add_brands();

		echo '<pre>';
		echo __( $added_brands['success_count'] . ' Brands added.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo '</pre>';
	}


	public function sync_products() {
		$last_updated_products      = $this->products_obj->get_last_updated_batched_product_ids();
		$added_products             = array();
		$added_products['products'] = array();
		$added_products['product_id'] = array();

		$success_count              = 0;

		foreach ( $last_updated_products['update_ids'] as $key => $value ) {
			$product_id = $this->products_obj->create_product( $value );

			if ( $product_id ) {
				$success_count++;
				$added_products['products'][] = $product_id;
				$added_products['product_id'][] = get_option( 'product_' . $value, '' );
			}
		}

		$added_products['success_count'] = $success_count;

		echo '<pre>';
		echo __( $added_products['success_count'] . ' Products updated.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo '</pre>';
	}



	public function sync_customer_order_history() {
		$customers = $this->customer_obj->get_customers( true );

		$added_customer_order_data = array();
		$success_count             = 0;

		foreach ( $customers as $key => $customer ) {
			$customer_data       = array();
			$customer_order_data = $this->customer_order_history_obj->get_customer_order_history( $customer['ebridge_id'] );

			foreach ( $customer_order_data as $key => $order_data ) {
				$ebridge_order_type = Wews_Helper_Functions::get_order_type_str_to_num( $order_data['type'], ORDER_TYPE );

				$order_id = $this->order_obj->sync_order( $order_data['id'], $ebridge_order_type );

				if ( is_numeric( $order_id ) ) {
					$customer_data[] = $order_id;
				}
			}

			$added_customer_order_data[ $customer['customer_id'] ] = $customer_data;
			$success_count++;
		}

		$added_customer_order_data['success_count'] = $success_count;

		echo '<pre>';
		echo __( $added_customer_order_data['success_count'] . ' Customers updated.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo '</pre>';
	}


	/**
	 * For testing purposes only. The function to add timeout during cron run
	 *
	 * @since    1.0.0
	 * @param      string $handle       The name of this plugin.
	 */
	public function wdm_custom_curl_timeout( $handle ) {
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 1000 );
		curl_setopt( $handle, CURLOPT_TIMEOUT, 1000 );
	}
}

new Wdm_Ebridge_Woocommerce_Sync_Cron();
