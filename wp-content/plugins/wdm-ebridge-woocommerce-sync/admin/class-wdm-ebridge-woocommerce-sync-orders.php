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
			$this->api_url      = get_option( 'ebridge_sync_api_url', '' );
			$this->api_token    = get_option( 'ebridge_sync_api_token', '' );
			$this->products_obj = new Wdm_Ebridge_Woocommerce_Sync_Products();
			$this->customer_obj = new Wdm_Ebridge_Woocommerce_Sync_Customer();
		}

		public function wews_create_order( $order, $data ) {
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			error_reporting( E_ALL );

			/* Check the product availability for each product in the cart. */
			$products = $order->get_items( 'line_item' );

			foreach ( $products as $key => $value ) {
				$product_id   = $value->legacy_values['product_id'];
				$product      = new WC_Product( $product_id );
				$ebridge_id   = $product->get_sku();
				$availability = $this->check_availability( $product, $ebridge_id );

				if ( ! $availability ) {
					throw new Exception( __( 'Sorry, but the product ' . $product->get_title() . ' is currently not available. Please remove the product ' . $product->get_title() . ' from cart and try again.', 'wdm-ebridge-woocommerce-sync' ) );
				}
			}

			$customer = $this->find_or_create_ebridge_customers( $order, $data );

			if ( ! $customer ) {
				throw new Exception( __( 'Unable to place this order. Please make sure the billing phone and email address is valid.', 'wdm-ebridge-woocommerce-sync' ) );
			}

			$ebridge_order = $this->create_ebridge_order( $order, $data, $customer );

			if ( ! $ebridge_order ) {
				throw new Exception( __( 'Unable to place this order due to technical difficulties. Please try again after some time.', 'wdm-ebridge-woocommerce-sync' ) );
			}
		}


		public function check_availability( $product, $ebridge_id ) {
			$response        = wp_remote_get( $this->api_url . '/' . $this->api_token . '/products/' . $ebridge_id );
			$ebridge_product = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ( wp_remote_retrieve_response_code( $response ) == 200 ) && isset( $ebridge_product->product ) ) {
				$ebridge_product = $ebridge_product->product;

				if ( $product->is_type( 'bundle' ) ) {
					$product = $this->products_obj->set_product_availability( $product, $ebridge_product );
				} elseif ( isset( $ebridge_product->inventory ) ) {
					$net_quantity = $ebridge_product->inventory->netQuantityAvailable;
					if ( is_numeric( $net_quantity ) ) {
						$product->set_manage_stock( true );
						$product->set_stock_quantity( $net_quantity );
					} else {
						$product->set_backorders( 'notify' );
					}

					if ( isset( $ebridge_product->inventory->locations ) ) {
						$locations = $ebridge_product->inventory->locations;

						foreach ( $locations as $key => $location ) {
							if ( is_numeric( $location->leadDays ) && ( 0 === $net_quantity ) ) {
								$product->set_backorders( 'notify' );
							}
						}
					}
				}

				$product->save();

				if ( $product->get_stock_quantity() > 0 ) {
					return true;
				}
			}

			return false;
		}


		public function find_or_create_ebridge_customers( $order, $data ) {
			$current_user_id = get_current_user_id();

			if ( 0 !== $current_user_id ) {
				$current_user = get_user_by( 'id', $current_user_id );
				$phone        = get_user_meta( $current_user_id, 'phone_number', true );
				$email        = $current_user->user_email;
				$customer        = get_user_meta( $current_user_id, 'ebridge_customer_id', true );
			}

			if ( ( ! ( isset( $customer ) ) ) || ( ! $customer ) ) {
				if ( ( ! ( isset( $phone ) && $phone ) ) && ( isset( $data['billing_phone'] ) ) ) {
					$phone = $data['billing_phone'];
				}

				if ( ( ! ( isset( $email ) && $email ) ) && ( isset( $data['billing_email'] ) ) ) {
					$email = $data['billing_email'];
				}

				$customer = $this->customer_obj->find_ebridge_customer_by_phone_email( $phone, $email );

				if ( ! $customer ) {
					$customer = $this->customer_obj->create_ebridge_customer( $data, $order );
				}
			}

			return $customer;
		}

		public function create_ebridge_order( $order, $data, $customer ) {
			if ( $this->api_url && $this->api_token ) {
				$url = $this->api_url . '/' . $this->api_token . '/orders/';

				$order_json                   = array();
				$order_json['billingAddress'] = $this->get_billing_address_data( $order, $data, $customer );
				$order_json['cartItems'] = $this->get_cart_item_data( $order, $data, $customer );
				$order_json['cellPhone'] = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';
				$order_json['charges']   = $this->get_delivery_and_installation_charges( $order, $data, $customer );
				$order_json['customerId'] = $customer;
				$order_json['emailAddress'] = isset( $data['billing_email'] ) ? $data['billing_email'] : '';
				$order_json['salesTax']        = 0;
				// $order_json['sellLocationId']  = '99';
				// $order_json['shipLocationId']  = '99';
				$order_json['shippingAddress'] = $this->get_shipping_address_data( $order, $data, $customer );
				// $order_json['emvToken']             = 'String content';
				// $order_json['homePhone'] = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';
				// $order_json['marketingCode1']       = 'String content';
				// $order_json['marketingCode2']       = 'String content';
				// $order_json['omitAutoEmail'] = 'String content';
				// $order_json['orderComments']        = 'String content';
				// $order_json['orderId']              = 'String content';
				// $order_json['password']             = 'String content';
				// $order_json['pickUpStatus']   = 'String content';
				// $order_json['pickupDate']     = 'String content';
				// $order_json['pickupLocation'] = 'String content';
				// $order_json['regNumber']            = 'String content';
				// $order_json['routeCode']            = 'String content';
				// $order_json['salesPerson']          = 'String content';
				// $order_json['cartId']               = 'String content';
				// $order_json['customerPONumber']     = 'String content';
				// $order_json['deliveryDate'] = 'String content';
				// $order_json['discount']             = array( 1 );
				// $order_json['creditCardType']       = 'String content';
				// $order_json['creditcard']           = array( 1 );
				// $order_json['shippingInstructions'] = 'String content';
				// $order_json['staffId']              = 'String content';
				// $order_json['workPhone'] = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';

				echo "<pre>";
				echo "===================order_json=================<br>";
				print_r( $order_json );
				echo "================================================<br>";
				echo "</pre>";
				die;
	
				$response = wp_remote_post(
					$url,
					array(
						'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
						'body'        => json_encode( $order_json ),
						'method'      => 'POST',
						'data_format' => 'body',
						'timeout'     => 6000,
					)
				);

				$json_response = json_decode( wp_remote_retrieve_body( $response ) );


				if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) ) {
					$order_details = $json_response->salesOrderCreationResponse->createdSalesOrders;
					$this->map_order_data( $order_details[0], $order );		
					return $order_details[0]->orderId;
				}
			}
			
			return false;
		}


		public function get_billing_address_data( $order, $data, $customer ) {
			$billing_address_data = array();

			$billing_address_data['firstName']     = isset( $data['billing_first_name'] ) ? $data['billing_first_name'] : '';
			$billing_address_data['middleInitial'] = '';
			$billing_address_data['lastName']      = isset( $data['billing_last_name'] ) ? $data['billing_last_name'] : '';
			$billing_address_data['address1']      = isset( $data['billing_address_1'] ) ? $data['billing_address_1'] : '';
			$billing_address_data['address2']      = isset( $data['billing_address_2'] ) ? $data['billing_address_2'] : '';
			$billing_address_data['city']          = isset( $data['billing_city'] ) ? $data['billing_city'] : '';
			$billing_address_data['state']         = isset( $data['billing_state'] ) ? $data['billing_state'] : '';
			$billing_address_data['zipCode']       = isset( $data['billing_postcode'] ) ? $data['billing_postcode'] : '';
			$billing_address_data['cellPhone']     = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';
			$billing_address_data['emailAddress']  = isset( $data['billing_email'] ) ? $data['billing_email'] : '';
			// $billing_address_data['workPhone']     = $data['billing_phone'];
			// $billing_address_data['homePhone']     = $data['billing_phone'];
			// $billing_address_data['prefix']        = $data[''];
			// $billing_address_data['suffix']        = $data[''];

			return $billing_address_data;
		}


		public function get_shipping_address_data( $order, $data, $customer ) {
			if ( $data['ship_to_different_address'] ) {
				$shipping_address_data = array();

				$shipping_address_data['firstName']     = isset( $data['shipping_first_name'] ) ? $data['shipping_first_name'] : '';
				$shipping_address_data['middleInitial'] = '';
				$shipping_address_data['lastName']      = isset( $data['shipping_last_name'] ) ? $data['shipping_last_name'] : '';
				$shipping_address_data['address1']      = isset( $data['shipping_address_1'] ) ? $data['shipping_address_1'] : '';
				$shipping_address_data['address2']      = isset( $data['shipping_address_2'] ) ? $data['shipping_address_2'] : '';
				$shipping_address_data['city']          = isset( $data['shipping_city'] ) ? $data['shipping_city'] : '';
				$shipping_address_data['state']         = isset( $data['shipping_state'] ) ? $data['shipping_state'] : '';
				$shipping_address_data['zipCode']       = isset( $data['shipping_postcode'] ) ? $data['shipping_postcode'] : '';
				$shipping_address_data['cellPhone']     = isset( $data['shipping_phone'] ) ? $data['shipping_phone'] : '';
				$shipping_address_data['emailAddress']  = isset( $data['shipping_email'] ) ? $data['shipping_email'] : '';
				// $shipping_address_data['workPhone']     = $data['shipping_phone'];
				// $shipping_address_data['homePhone']     = $data['shipping_phone'];
				// $shipping_address_data['prefix']        = $data[''];
				// $shipping_address_data['suffix']        = $data[''];
			} else {
				$shipping_address_data = $this->get_billing_address_data( $order, $data, $customer );
			}

			return $shipping_address_data;
		}


		public function get_delivery_and_installation_charges( $order, $data, $customer ) {
			$delivery_and_installation_charges = array();

			return $delivery_and_installation_charges;
		}


		public function get_cart_item_data( $order, $data, $customer ) {
			$cart_item_data = array();

			$cart_item = array();
			$items = $order->get_items( 'line_item' );

			echo "<pre>";
			echo "===================items=================<br>";
			var_dump( $items );
			echo "================================================<br>";
			echo "</pre>";
			die;

			foreach ($items as $item) {
				$product        = $item->get_product();
				
				$cart_item['description'] = $product->get_name();
				$cart_item['id'] = $product->get_sku();				
				$cart_item['lineItemDeliveryType'] = 2;
				$cart_item['price'] = $product->get_price();
				$cart_item['quantity'] = $item->get_quantity();
				$cart_item['lineItemCommentData'] = "WooCommerce Order";
				// $cart_item['vendorModelOverride'] = "";
				// $cart_item['asIsSerialNumber'] = "";
				// $cart_item['externalIdentifier'] = "";
				// $cart_item['isStockSameAsShip'] = "";
				// $cart_item['kitId'] = "";
				// $cart_item['lineDiscountCode'] = "";
				// $cart_item['pickupStockLocationId'] = "";
				// $cart_item['configurationOptions'] = "";
				// $cart_item['deliveryStockLocationId'] = "";
				
				$cart_item_data[] = $cart_item;
			}

			// {
			// 	"configurationOptions":[{
			// 		"cost":12678967.543233,
			// 		"optionType":"String content",
			// 		"price":12678967.543233,
			// 		"value":"String content"
			// 	}],
			// }

			return $cart_item_data;
		}


		public function map_order_data( $ebridge_order, $order ) {
			$order->add_meta_data( 'ebridge_order_id', $ebridge_order->orderId );
			$order->add_meta_data( 'ebridge_order_type', $ebridge_order->orderType );
			$order->add_meta_data( 'ebridge_order_total', $ebridge_order->total );
			$order->add_meta_data( 'ebridge_order_customerId', $ebridge_order->customerId );			
		}
	}

}
