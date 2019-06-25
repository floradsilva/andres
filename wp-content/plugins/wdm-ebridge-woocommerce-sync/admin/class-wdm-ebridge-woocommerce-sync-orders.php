<?php

/**
 * Adding the hooks and actions to map WooCommerce and Ebridge Orders.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */

if ( ! class_exists( 'Wdm_Ebridge_Woocommerce_Sync_Orders' ) ) {
	class Wdm_Ebridge_Woocommerce_Sync_Orders {



		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;


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
		 */
		public function __construct() {
			$this->api_url   = get_option( 'ebridge_sync_api_url', '' );
			$this->api_token = get_option( 'ebridge_sync_api_token', '' );
		}

		public function product_availability( $order, $data ) {
			$products = $order->get_items( 'line_item' );

			foreach ( $products as $key => $value ) {
				$product_id   = $value->legacy_values['product_id'];
				$product      = new WC_Product( $product_id );
				$ebridge_id   = $product->get_sku();
				$availability = $this->check_availability( $product, $ebridge_id );

				if ( ! $availability ) {
					throw new Exception( 'The product ' . $product->get_title() . ' not available.' );
				}
			}

			$customer = $this->find_or_create_customers( $order );

			throw new Exception(  );

		}


		public function check_availability( $product, $ebridge_id ) {
			$response        = wp_remote_get( $this->api_url . '/' . $this->api_token . '/products/' . $ebridge_id );
			$ebridge_product = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ( wp_remote_retrieve_response_code( $response ) == 200 ) && isset( $ebridge_product->product ) ) {
				$ebridge_product = $ebridge_product->product;

				if ( isset( $ebridge_product->inventory ) ) {
					$net_quantity = $ebridge_product->inventory->netQuantityAvailable;
					$product->set_manage_stock( true );
					$product->set_stock_quantity( $net_quantity );

					if ( isset( $ebridge_product->inventory->locations ) ) {
						$locations = $ebridge_product->inventory->locations;

						foreach ( $locations as $key => $location ) {
							if ( is_numeric( $location->leadDays ) && ( $net_quantity === 0 ) ) {
								$product->set_backorders( 'notify' );
							}
						}
					}

					$product->save();

					if ( isset( $net_quantity ) && ( $net_quantity > 0 ) ) {
						return true;
					}
				}
			}

			return false;
		}


		public function find_or_create_customers( $order ) {
			$billing_phone = $order->get_billing_phone();

			// if ( $billing_phone ) {
			// $response        = wp_remote_get( $this->api_url . '/' . $this->api_token . '/customers/search/' . $ebridge_id );
			// $ebridge_product = json_decode( wp_remote_retrieve_body( $response ) );
			// }

			if ( $billing_phone ) {
				$args = array(
					'method'   => 'POST',
					// 'timeout' => 45,
					// 'redirection' => 5,
					// 'httpversion' => '1.0',
					'blocking' => true,
					// 'headers' => array(),
					'body'     =>
						array(
							'emailAddress' => $order->get_billing_email(),
							'password'     => '1234xyz',
							'firstName' => 'test',
							'lastName'  => 'wisdmlabs',
							'homePhone' => $billing_phone,
							'workPhone' => $billing_phone,
							'cellphone' => $billing_phone,
						),

					// 'cookies' => array(),
				);

				$response        = wp_remote_post( $this->api_url . '/' . $this->api_token . '/customer', $args );
				$ebridge_product = json_decode( wp_remote_retrieve_body( $response ) );

				return $ebridge_product;
			}

		}
	}
}
