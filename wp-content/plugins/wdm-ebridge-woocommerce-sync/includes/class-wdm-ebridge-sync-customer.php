<?php

/**
 * Adding the code to map WooCommerce and Ebridge Customers.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/include
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */

if ( ! class_exists( 'Wdm_Ebridge_Woocommerce_Sync_Customer' ) ) {
	class Wdm_Ebridge_Woocommerce_Sync_Customer {

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
		}

		public function find_ebridge_customer_by_phone_email( $phone, $email = '' ) {
			if ( $this->api_url && $this->api_token ) {
				$url = $this->api_url . '/' . $this->api_token . '/customers/search/' . $phone;

				$response      = wp_remote_get( $url, $this->curl_args );
				$json_response = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) ) {
					$customer_details = $json_response->customerLoginMatches;
					foreach ( $customer_details as $key => $customer_detail ) {
						if ( $customer_detail->emailAddress === $email ) {
							$this->update_current_user_ebridge( $customer_detail->id, $phone, $email );
							return $customer_detail->id;
						}
					}

					$this->update_current_user_ebridge( $customer_details[0]->id, $phone, $customer_details[0]->emailAddress );
					return $customer_details[0]->id;
				}
			}
			return false;
		}

		public function create_ebridge_customer( $data ) {
			return false;
		}

		public function update_current_user_ebridge( $ebridge_id, $phone, $email ) {
			$current_user_id = get_current_user_id();

			if ( 0 !== $current_user_id ) {
				$current_user    = get_user_by( 'id', $current_user_id );
				$user_email      = $current_user->user_email;
				$user_ebridge_id = get_user_meta( $current_user_id, 'ebridge_customer_id', true );
				$user_phone      = get_user_meta( $current_user_id, 'phone_number', true );

				if ( ( $user_email === $email ) || ( $user_ebridge_id === $ebridge_id ) || ( $user_phone === $phone ) ) {
					update_user_meta( $current_user_id, 'ebridge_customer_id', $ebridge_id );
					update_user_meta( $current_user_id, 'phone_number', $phone );

					$args = array(
						'ID'         => $current_user->id,
						'user_email' => $email,
					);

					wp_update_user( $args );
				}
			}
		}
	}
}

