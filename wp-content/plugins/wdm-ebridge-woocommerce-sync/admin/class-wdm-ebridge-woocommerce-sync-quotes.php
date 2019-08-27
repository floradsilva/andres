<?php

/**
 * Adding the hooks and actions to map WooCommerce and Ebridge Quotes.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */

if ( ! class_exists( 'Wdm_Ebridge_Woocommerce_Sync_Quotes' ) ) {
	class Wdm_Ebridge_Woocommerce_Sync_Quotes {
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

		public function wews_create_ebridge_quote( $enquiry_id ) {
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			error_reporting( E_ALL );

			global $wpdb;
			$enquiry_tbl     = getEnquiryDetailsTable();
			$sql             = $wpdb->prepare( "SELECT * FROM $enquiry_tbl WHERE enquiry_id = '%d'", $enquiry_id );
			$enquiry_details = $wpdb->get_row( $sql );

			$customer = $this->find_ebridge_customers( $enquiry_details );

			$ebridge_quote = $this->create_ebridge_quote( $enquiry_details, $customer );
		}


		public function find_ebridge_customers( $data ) {
			$current_user_id = get_current_user_id();
			$enquiry_details = self::get_single_enquiry_data( $data->enquiry_id );

			if ( 0 !== $current_user_id ) {
				$current_user = get_user_by( 'id', $current_user_id );
				$phone        = get_user_meta( $current_user_id, 'phone_number', true );
				$email        = $current_user->user_email;
				$customer     = get_user_meta( $current_user_id, 'ebridge_customer_id', true );
			}

			if ( ( ! ( isset( $customer ) ) ) || ( ! $customer ) ) {
				if ( ( ! ( isset( $phone ) && $phone ) ) && ( isset( $enquiry_details['Phone number'] ) ) ) {
					$phone = $enquiry_details['Phone number'];
				}

				if ( ( ! ( isset( $email ) && $email ) ) && ( isset( $data->email ) ) ) {
					$email = $data->email;
				}

				$customer = $this->customer_obj->find_ebridge_customer_by_phone_email( $phone, $email );
			}

			return $customer;
		}

		public function create_ebridge_quote( $data, $customer ) {
			if ( $this->api_url && $this->api_token ) {
				$url             = $this->api_url . '/' . $this->api_token . '/quotes/';
				$enquiry_details = self::get_single_enquiry_data( $data->enquiry_id );

				$quote_json                   = array();
				$quote_json['billingAddress'] = $this->get_billing_address_data( $enquiry_details, $customer );
				$quote_json['cartItems']      = $this->get_cart_item_data( $data, $customer );
				$quote_json['cellPhone']      = isset( $enquiry_details['Phone number'] ) ? $enquiry_details['Phone number'] : '';
				$quote_json['charges']        = $this->get_delivery_and_installation_charges( $data, $customer );
				$quote_json['customerId']     = $customer;
				$quote_json['emailAddress']   = isset( $data->email ) ? $data->email : '';
				// $quote_json['salesTax']             = 0;
				$quote_json['sellLocationId']  = '99';
				$quote_json['shipLocationId']  = '99';
				$quote_json['shippingAddress'] = $this->get_shipping_address_data( $enquiry_details, $customer );
				// $quote_json['emvToken']             = 'String content';
				// $quote_json['homePhone']            = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';
				// $quote_json['marketingCode1']       = 'String content';
				// $quote_json['marketingCode2']       = 'String content';
				// $quote_json['omitAutoEmail']        = 'String content';
				// $quote_json['orderComments']        = 'String content';
				// $quote_json['orderId']              = 'String content';
				// $quote_json['password']             = 'String content';
				// $quote_json['pickUpStatus']         = 'String content';
				// $quote_json['pickupDate']           = 'String content';
				// $quote_json['pickupLocation']       = 'String content';
				// $quote_json['regNumber']            = 'String content';
				// $quote_json['routeCode']            = 'String content';
				// $quote_json['salesPerson']          = 'String content';
				// $quote_json['cartId']               = 'String content';
				// $quote_json['customerPONumber']     = 'String content';
				// $quote_json['deliveryDate']         = 'String content';
				// $quote_json['discount']             = array( 1 );
				// $quote_json['creditCardType']       = 'String content';
				// $quote_json['creditcard']           = array( 1 );
				// $quote_json['shippingInstructions'] = 'String content';
				// $quote_json['staffId']              = 'String content';
				// $quote_json['workPhone']            = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';

				$response = wp_remote_post(
					$url,
					array(
						'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
						'body'        => json_encode( $quote_json ),
						'method'      => 'POST',
						'data_format' => 'body',
						'timeout'     => 6000,
					)
				);

				$json_response = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) ) {
					$quote_details = $json_response->salesOrderCreationResponse->createdSalesOrders;

					foreach ( $quote_details[0] as $key => $value ) {
						$this->map_quote_data( $data->enquiry_id, $key, $value );
					}

					return $quote_details[0]->quoteId;
				}
			}

			return false;
		}


		public function get_billing_address_data( $enquiry_details, $customer ) {
			$name = $enquiry_details['name'];
			$name = explode( ' ', $name );

			$billing_address_data = array();

			$billing_address_data['firstName']     = ( count( $name ) > 0 ) ? $name[0] : '';
			$billing_address_data['middleInitial'] = '';
			$billing_address_data['lastName']      = ( count( $name ) > 1 ) ? $name[1] : '';
			$billing_address_data['address1']      = isset( $enquiry_details['Address Line 1'] ) ? $enquiry_details['Address Line 1'] : '';
			$billing_address_data['address2']      = isset( $enquiry_details['Address Line 2'] ) ? $enquiry_details['Address Line 2'] : '';
			$billing_address_data['city']          = isset( $enquiry_details['City'] ) ? $enquiry_details['City'] : '';
			$billing_address_data['state']         = $this->get_state_code( $enquiry_details['State'] );
			$billing_address_data['zipCode']       = isset( $enquiry_details['Zip Code'] ) ? $enquiry_details['Zip Code'] : '';
			$billing_address_data['cellPhone']     = isset( $enquiry_details['Phone number'] ) ? $enquiry_details['Phone number'] : '';
			$billing_address_data['emailAddress']  = isset( $enquiry_details['email'] ) ? $enquiry_details['email'] : '';

			return $billing_address_data;
		}


		public function get_shipping_address_data( $enquiry_details, $customer ) {
			$shipping_address_data = $this->get_billing_address_data( $enquiry_details, $customer );

			return $shipping_address_data;
		}

		public function get_delivery_and_installation_charges( $data, $customer ) {
			$delivery_and_installation_charges = array();

			return $delivery_and_installation_charges;
		}


		public function get_cart_item_data( $data, $customer ) {
			$cart_item_data = array();

			$cart_item = array();
			$items     = unserialize( $data->product_details );

			foreach ( $items as $item ) {
				$product = wc_get_product( $item['id'] );

				$cart_item['description'] = $product->get_name();
				$cart_item['id']          = $product->get_sku();
				// $cart_item['lineItemDeliveryType']    = 2;
				$cart_item['price']               = $item['price'];
				$cart_item['quantity']            = $item['quant'];
				$cart_item['lineItemCommentData'] = 'WooCommerce Quote';
				// $cart_item['vendorModelOverride']     = '';
				// $cart_item['asIsSerialNumber']        = '';
				// $cart_item['externalIdentifier']      = '';
				// $cart_item['isStockSameAsShip']       = '';
				// $cart_item['kitId']                   = '';
				// $cart_item['lineDiscountCode']        = '';
				// $cart_item['pickupStockLocationId']   = '';
				// $cart_item['configurationOptions']    = '';
				// $cart_item['deliveryStockLocationId'] = '';

				$cart_item_data[] = $cart_item;
			}

			// {
			// "configurationOptions":[{
			// "cost":12678967.543233,
			// "optionType":"String content",
			// "price":12678967.543233,
			// "value":"String content"
			// }],
			// }

			return $cart_item_data;
		}


		public function map_quote_data( $enquiry_id, $key, $value ) {
			global $wpdb;
			$meta_tbl = getEnquiryMetaTable();

			$wpdb->insert(
				$meta_tbl,
				array(
					'enquiry_id' => $enquiry_id,
					'meta_key'   => $key,
					'meta_value' => $value,
				),
				array(
					'%d',
					'%s',
					'%s',
				)
			);
		}


		/**
		 * Get personal data (key/value pairs) for a single enquiry.
		 *
		 * @param int $enquiry_id enquiry id.
		 *
		 * @return array
		 */
		public static function get_single_enquiry_data( $enquiry_id ) {
			global $wpdb;
			$enquiry_table_name      = getEnquiryDetailsTable();
			$enquiry_meta_table_name = getEnquiryMetaTable();
			$enquiry_data            = array();
			$sql                     = "SELECT enquiry_id, name, email, message, phone_number, subject, enquiry_ip FROM $enquiry_table_name WHERE enquiry_id = $enquiry_id";
			$data                    = $wpdb->get_row( $sql, ARRAY_A );
			$sql                     = "SELECT meta_key,meta_value FROM $enquiry_meta_table_name WHERE enquiry_id = $enquiry_id";
			$meta_data               = $wpdb->get_results( $sql, ARRAY_A );

			foreach ( $meta_data as $key => $value ) {
				$data[ $value['meta_key'] ] = $value['meta_value'];
			}

			$meta_keys_to_remove = array( '_admin_quote_created', 'enquiry_lang_code', 'quotation_lang_code', '_unread_enquiry' );

			// Remove keys which we don't want to export
			foreach ( $meta_keys_to_remove as $key => $value ) {
				unset( $data[ $value ] );
			}

			foreach ( $data as $key => $value ) {
				$enquiry_data[ $key ] = $value;
			}

			return $enquiry_data;
		}


		public static function get_state_code( $state ) {
			$state_codes = STATES_POSTAL_CODES;
			$state_code  = $state_codes[ ucfirst( strtolower( $state ) ) ];

			return ( isset( $state_code ) ) ? $state_code : '';
		}
	}
}
