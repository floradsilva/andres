<?php

/**
 * Adding the code to map WooCommerce and Ebridge Orders.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/includes
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */

if ( ! class_exists( 'Wdm_Ebridge_Woocommerce_Sync_Order' ) ) {
	class Wdm_Ebridge_Woocommerce_Sync_Order {

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
			$this->curl_args = array(
				'timeout' => 6000,
			);
			add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', array( $this, 'handle_ebridge_meta_query' ), 10, 2 );
		}

		public function get_order_data( $ebridge_order_id, $ebridge_order_type ) {
			$order_data = false;

			if ( ! is_numeric( $ebridge_order_type ) ) {
				$ebridge_order_type = ucfirst( strtolower( $ebridge_order_type ) );
				$order_types        = ORDER_STATUS;
				$ebridge_order_type = $order_types[ $ebridge_order_type ];
			}

			if ( $this->api_url && $this->api_token ) {
				$url = $this->api_url . '/' . $this->api_token . '/orders/' . $ebridge_order_id . ',' . $ebridge_order_type;

				$response      = wp_remote_get( $url, $this->curl_args );
				$json_response = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) ) {
					$order_details = $json_response->salesOrder;
					return $order_details;
				}
			}

			return $order_data;
		}


		public function sync_order( $ebridge_order_id, $ebridge_order_type ) {
			$order_data = $this->get_order_data( $ebridge_order_id, $ebridge_order_type );

			if ( $order_data ) {
				if ( Wews_Helper_Functions::is_valid_order_type( $order_data->orderType ) ) {

					$order = $this->find_order_by_ebridge_order_id( $ebridge_order_id );

					if ( ! $order ) {
						$order = new WC_Order();
						$order->add_meta_data( 'ebridge_order_id', $ebridge_order_id );
						$order->save();
					}

					$order = $this->sync_order_data( $order, $order_data );

					return $order->get_id();
				}
			}

			return false;
		}


		public function sync_order_data( $order, $order_data ) {
			$ebridge_order_type = Wews_Helper_Functions::get_order_type_from_status_str( $order_data->orderType, ORDER_STATUS );

			$order->update_meta_data( 'ebridge_order_type', $ebridge_order_type );

			$this->set_billing_address( $order, $order_data );
			$this->set_shipping_address( $order, $order_data );
			$order->add_order_note( $order_data->shippingInstructions );
			$order->update_meta_data( 'ebridge_order_id', $order_data->orderId );
			$order->update_meta_data( 'ebridge_order_type_details', Wews_Helper_Functions::get_order_type_num_to_str( $order_data->orderType, ORDER_STATUS ) );
			// $order->update_meta_data( 'ebridge_order_total', $order_data->total );
			$order->update_meta_data( 'ebridge_order_customerId', $order_data->customerId );
			$order->update_meta_data( 'ebridge_locationId', $order_data->locationId );
			$order->update_meta_data( 'ebridge_orderDate', $order_data->orderDate );
			$order->update_meta_data( 'ebridge_deliveryDate', $order_data->deliveryDate );
			$order->update_meta_data( 'ebridge_deliveryStatus', $order_data->deliveryStatus );
			$order->update_meta_data( 'ebridge_deliveryTime', $order_data->deliveryTime );
			$order->update_meta_data( 'ebridge_shipDate', $order_data->shipDate );
			$order->update_meta_data( 'ebridge_shippingInstructions', $order_data->shippingInstructions );
			$order->update_meta_data( 'ebridge_deliveryStatus', $order_data->deliveryStatus );
			$order->update_meta_data( 'ebridge_deliveryTime', $order_data->deliveryTime );

			$order = $this->update_special_information( $order, $order_data->specialOrderInformation );
			$this->map_line_item_data( $order, $order_data->lineItems );
			$this->set_totals( $order, $order_data );
			// $order->set_date_created( $order_data->orderDate );
			// $order->maybe_set_date_completed();

			$customer = Wdm_Ebridge_Woocommerce_Sync_Customer::get_user_by_ebridge_id( $order_data->customerId );

			if ( $customer ) {
				$order->set_customer_id( $customer->ID );
			}
			$order->save();

			return $order;
		}


		public function set_totals( $order, $order_data ) {
			$totals        = $order_data->orderTotals;
			$balance       = $totals->balance;
			$invoice_total = $totals->invoiceTotal;

			if ( $balance === $invoice_total ) {
				$order->update_status( 'pending' );
			} elseif ( 0 === intval( $balance ) ) {
				$order->update_status( 'completed' );
			}

			$order->update_meta_data( 'order_balance', $totals->balance );

			if ( $totals->discount ) {
				$order->set_discount_total( $totals->discount );
				$order->update_meta_data( 'order_discount', $totals->discount );
			}

			if ( $totals->delivery ) {
				$fee = new stdClass();

				$fee->name      = 'Delivery Charges';
				$fee->amount    = $totals->delivery;
				$fee->taxable   = false;
				$fee->tax_class = '';

				$delivery_item_id = $order->add_fee( $fee );

				$order->update_meta_data( 'order_delivery', $totals->delivery );
			}

			if ( $totals->fees ) {
				$fee = new stdClass();

				$fee->name      = 'Fees';
				$fee->amount    = $totals->fees;
				$fee->taxable   = false;
				$fee->tax_class = '';

				$fee_item_id = $$order->add_fee( $fee );
				$order->update_meta_data( 'order_fees', $totals->fees );
			}

			if ( $totals->install ) {
				$fee = new stdClass();

				$fee->name      = 'Installation Charges';
				$fee->amount    = $totals->install;
				$fee->taxable   = false;
				$fee->tax_class = '';

				$installation_item_id = $order->add_fee( $fee );
				$order->update_meta_data( 'order_installation_charges', $totals->install );
			}

			if ( $totals->tax ) {
				$fee = new stdClass();

				$fee->name      = 'Tax';
				$fee->amount    = $totals->tax;
				$fee->taxable   = false;
				$fee->tax_class = '';

				$tax_item_id = $order->add_fee( $fee );

				$order->update_meta_data( 'order_tax', $totals->tax );
			}

			$order->update_meta_data( 'order_total', $totals->invoiceTotal );
			$order->calculate_totals();
		}


		public function map_line_item_data( $order, $line_items ) {
			$order->remove_order_items();

			foreach ( $line_items as $index => $line_item ) {
				// foreach ($line_item as $key => $value) {
				// $order->update_meta_data( 'ebridge_line_item_' . $index . '_' . $key, $value );
				// }

				$sku        = $line_item->id;
				$product_id = wc_get_product_id_by_sku( $sku );
				$item_id    = $order->add_product(
					wc_get_product( $product_id ),
					$line_item->quantity,
					[
						'subtotal' => $line_item->price,
						'total'    => $line_item->price,
					]
				);
			}

			$order->set_currency( 'USD' );
		}


		public function update_special_information( $order, $special_order_information ) {

			if ( count( $special_order_information ) ) {
				$special_order_information = $special_order_information[0];

				foreach ( $special_order_information as $key => $value ) {
					$order->update_meta_data( 'ebridge_SpecialOrderInformation_' . $key, $value );
				}
			}
			return $order;
		}


		public function set_billing_address( $order, $order_data ) {
			$billing_address = $order_data->addresses[0];
			$address_type    = $billing_address->addressType;
			$address_type    = array_search( $address_type, ADDRESS_TYPE );

			if ( 'Billing' === $address_type ) {
				$name = explode( ' ', $billing_address->name );

				if ( count( $name ) > 0 ) {
					$order->set_billing_first_name( $name[0] );
				}

				if ( count( $name ) > 1 ) {
					$order->set_billing_first_name( $name[1] );
				}

				if ( $billing_address->address1 ) {
					$order->set_billing_address_1( $billing_address->address1 );
				}

				if ( $billing_address->address2 ) {
					$order->set_billing_address_2( $billing_address->address2 );
				}

				if ( $billing_address->city ) {
					$order->set_billing_city( $billing_address->city );
				}

				if ( $billing_address->state ) {
					$order->set_billing_state( $billing_address->state );
				}

				if ( $billing_address->zipCode ) {
					$order->set_billing_postcode( $billing_address->zipCode );
				}
			}
		}


		public function set_shipping_address( $order, $order_data ) {
			$shipping_address = $order_data->addresses[1];
			$address_type     = $shipping_address->addressType;
			$address_type     = array_search( $address_type, ADDRESS_TYPE );

			if ( 'Shipping' === $address_type ) {
				$name = explode( ' ', $shipping_address->name );

				if ( count( $name ) > 0 ) {
					$order->set_shipping_first_name( $name[0] );
				}

				if ( count( $name ) > 1 ) {
					$order->set_shipping_first_name( $name[1] );
				}

				if ( $shipping_address->address1 ) {
					$order->set_shipping_address_1( $shipping_address->address1 );
				}

				if ( $shipping_address->address2 ) {
					$order->set_shipping_address_2( $shipping_address->address2 );
				}

				if ( $shipping_address->city ) {
					$order->set_shipping_city( $shipping_address->city );
				}

				if ( $shipping_address->state ) {
					$order->set_shipping_state( $shipping_address->state );
				}

				if ( $shipping_address->zipCode ) {
					$order->set_shipping_postcode( $shipping_address->zipCode );
				}
			}
		}


		public function find_order_by_ebridge_order_id( $ebridge_order_id ) {
			$orders = wc_get_orders( array( 'ebridge_order_id' => $ebridge_order_id ) );

			if ( is_array( $orders ) && ( count( $orders ) > 0 ) ) {
				return $orders[0];
			}

			return false;
		}


		/**
		 * Tasks to do when the status of a subscription_order is in processing.
		 */
		public function on_processing_status_ebridge_order( $order_id ) {
			$order = wc_get_order( $order_id );

			$success = $this->add_processed_payment( $order );

			// =============================================================
				$path = WEWS_PLUGIN_PATH;
				$file = $path . $order_id . '_test.txt';
				ob_start();
				echo "<pre>";
				echo "===================order=================<br>";
				var_dump( $order->get_meta_data() );
				echo "================================================<br>";
				echo "</pre>";
				$output = ob_get_contents();
				ob_end_clean();

				// Write the contents back to the file
				file_put_contents($file, $output);
			// =============================================================
		}


		public function add_processed_payment( $order ) {
			if ( $this->api_url && $this->api_token ) {
				$url = $this->api_url . '/' . $this->api_token . '/orders/deposits';

				$deposit_json     = array();
				$deposit_json['custNumber'] = $order->get_meta( 'ebridge_order_customerId' );
				$deposit_json['orderId'] = $order->get_meta( 'ebridge_order_id' );
				$deposit_json['locationId'] = 99;
				$deposit_json['depositAmount'] = $order->get_total();
				$deposit_json['depositType'] = 'WBPAY';
				$deposit_json['rejectUnauthorized'] = false;

				// $deposit_json['emvToken'] = 'String content';
				// $deposit_json['financeAccountNumber'] = 'String content';
				// $deposit_json['staffId'] = 'String content';
				// $deposit_json['authorizationNumber'] = 'String content';
				// $deposit_json['paymentOptionDetails'] = {};

				// [{
				// 	"paymentOptionDetails":{
				// 		"creditCard":{
				// 			"creditCardNumber":"String content",
				// 			"expirationMonth":"String content",
				// 			"expirationYear":"String content",
				// 			"CCVN":"String content",
				// 			"magneticStripeData":"String content"
				// 		},
				// 		"check":{
				// 			"checkNumber":"String content",
				// 			"driversLicenseNumber":"String content"
				// 		}
				// 	}
				// }]
				


				$response = wp_remote_request(
					$url,
					array(
						'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
						'body'        => array( json_encode( $deposit_json ) ),
						'method'      => 'PUT',
						'data_format' => 'body',
						'timeout'     => 6000,
					)
				);

				$json_response = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) ) {
					$deposit_details = $json_response->applyDeposits[0];

					if ( 0 === $deposit_details->updateStatus ) {
						$order->add_order_note( __('The payment of ' . $order->get_total() . ' synced with Ebridge.', 'wdm-ebridge-woocommerce-sync' ) );
						$order->save();

						return true;
					}
				}
			}

			return false;
		}


		/**
		 * Handle a custom 'ebridge_order_id' query var to get orders with the 'ebridge_order_id' meta.
		 *
		 * @param array $query - Args for WP_Query.
		 * @param array $query_vars - Query vars from WC_Order_Query.
		 * @return array modified $query
		 */
		public function handle_ebridge_meta_query( $query, $query_vars ) {
			if ( ! empty( $query_vars['ebridge_order_id'] ) ) {
				$query['meta_query'][] = array(
					'key'   => 'ebridge_order_id',
					'value' => esc_attr( $query_vars['ebridge_order_id'] ),
				);
			}

			return $query;
		}
	}
}
