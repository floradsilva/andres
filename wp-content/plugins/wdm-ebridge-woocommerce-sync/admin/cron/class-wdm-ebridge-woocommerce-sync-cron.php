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
	 * @var      string    $version    The current version of this plugin.
	 */
	private $api_url;


	/**
	 * The ebdrige api token.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $version    The current version of this plugin.
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
		include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

		$this->api_url   = get_option( 'ebridge_sync_api_url', '' );
		$this->api_token = get_option( 'ebridge_sync_api_token', '' );

		$created_web_categories = $this->create_webcategories();
		$created_brands         = $this->create_brands();

		echo __( $created_web_categories['success_count'] . ' WebCategories added.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo __( $created_brands['success_count'] . ' Brands added.<br />', 'wdm-ebridge-woocommerce-sync' );
	}

	public function create_webcategories() {
		$created_web_categories = array();
		$success_count          = 0;

		if ( $this->api_url && $this->api_token ) {
			$response      = wp_remote_get( $this->api_url . '/' . $this->api_token . '/webcategories' );
			$webcategories = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				foreach ( $webcategories->webCategories as $key => $webcategory ) {
					$success = $this->create_custom_category( $webcategory, 'product_cat' );
					$created_web_categories[ $webcategory->description ] = $success;
					if ( $success ) {
						$success_count++;
					}
				}
			}
		} else {
			echo __( 'Sorry, could not create webcategories. Please add valid Ebridge URL and Token.<br />', 'wdm-ebridge-woocommerce-sync' );
		}

		$created_web_categories['success_count'] = $success_count;

		return $created_web_categories;
	}

	public function create_brands() {
		$created_brands = array();
		$success_count  = 0;

		if ( $this->api_url && $this->api_token ) {
			$response = wp_remote_get( $this->api_url . '/' . $this->api_token . '/brands' );
			$brands   = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				foreach ( $brands->Brands as $key => $brand ) {
					$success                               = $this->create_custom_category( $brand, 'brand' );
					$created_brands[ $brand->description ] = $success;
					if ( $success ) {
						$success_count++;
					}
				}
			}
		} else {
			echo __( 'Sorry, could not create brands. Please add valid Ebridge URL and Token.<br />', 'wdm-ebridge-woocommerce-sync' );
		}

		$created_brands['success_count'] = $success_count;

		return $created_brands;
	}

	public function create_custom_category( $category, $taxonomy ) {
		$category_id   = sanitize_text_field( str_replace( '"', '', $category->description ) );
		$term          = term_exists( sanitize_title( $category->id ), $taxonomy );
		$success_count = 0;
		if ( ! $term ) {
			$success = wp_insert_term(
				$category_id,
				$taxonomy,
				// 'category',
				array(
					'description' => $category->id,
					'slug'        => sanitize_title( $category->id ),
				)
			);

			return is_wp_error( $success ) ? $success->get_error_message() : true;
		}

		return false;
	}
}

new Wdm_Ebridge_Woocommerce_Sync_Cron();
