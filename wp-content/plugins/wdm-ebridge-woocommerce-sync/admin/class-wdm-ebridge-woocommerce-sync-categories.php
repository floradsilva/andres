<?php

/**
 * Fetches the Webcategories and Brands from Ebridge and adds it to WooCommerce.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync_Categories {

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


	/**
	 * Fetches and adds webcategories.
	 *
	 * @since    1.0.0
	 */
	public function add_webcategories() {
		$added_web_categories = array();
		$success_count        = 0;

		if ( $this->api_url && $this->api_token ) {
			$response      = wp_remote_get( $this->api_url . '/' . $this->api_token . '/webcategories' );
			$webcategories = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				foreach ( $webcategories->webCategories as $key => $webcategory ) {
					$success = self::create_custom_category( $webcategory, 'product_cat' );
					$added_web_categories[ $webcategory->description ] = $success;
					if ( $success ) {
						$success_count++;
					}
				}
			}
		} else {
			echo __( 'Sorry, could not add webcategories. Please add valid Ebridge URL and Token.<br />', 'wdm-ebridge-woocommerce-sync' );
		}

		$added_web_categories['success_count'] = $success_count;

		return $added_web_categories;
	}


	/**
	 * Fetches and adds brands.
	 *
	 * @since    1.0.0
	 */
	public function add_brands() {
		$added_brands  = array();
		$success_count = 0;

		if ( $this->api_url && $this->api_token ) {
			$response = wp_remote_get( $this->api_url . '/' . $this->api_token . '/brands' );
			$brands   = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				foreach ( $brands->Brands as $key => $brand ) {
					$success                             = self::create_custom_category( $brand, 'brand' );
					$added_brands[ $brand->description ] = $success;
					if ( $success ) {
						$success_count++;
					}
				}
			}
		} else {
			echo __( 'Sorry, could not add brands. Please add valid Ebridge URL and Token.<br />', 'wdm-ebridge-woocommerce-sync' );
		}

		$added_brands['success_count'] = $success_count;

		return $added_brands;
	}


	/**
	 * Generalised function to add a term belonging to a taxonomy to woocommerce.
	 *
	 * @since    1.0.0
	 * @param      string $term_obj       The ebridge term object.
	 * @param      string $taxonomy       The taxonomy to add to.
	 */
	public static function create_custom_category( $term_obj, $taxonomy ) {
		$term_id       = sanitize_text_field( str_replace( '"', '', $term_obj->description ) );
		$term          = term_exists( sanitize_title( $term_obj->id ), $taxonomy );
		$success_count = 0;
		if ( ! $term ) {
			$success = wp_insert_term(
				$term_id,
				$taxonomy,
				array(
					'description' => $term_obj->id,
					'slug'        => sanitize_title( $term_obj->id ),
				)
			);

			return is_wp_error( $success ) ? $success->get_error_message() : true;
		}

		return false;
	}
}

// new Wdm_Ebridge_Woocommerce_Sync_Categories();
