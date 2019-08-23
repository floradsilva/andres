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
	 * .
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $updated_products    The Array of updated products.
	 */
	private $updated_products;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct() {
		// include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
		include_once $_SERVER['PWD'] . '/wp-load.php';
		include_once WEWS_PLUGIN_PATH . 'includes/class-wdm-ebridge-woocommerce-sync-categories.php';
		include_once WEWS_PLUGIN_PATH . 'includes/class-wdm-ebridge-woocommerce-sync-products.php';

		$this->api_url   = get_option( 'ebridge_sync_api_url', '' );
		$this->api_token = get_option( 'ebridge_sync_api_token', '' );
		add_action( 'http_api_curl', array( $this, 'sar_custom_curl_timeout' ), 9999, 1 );

		$categories_sync      = new Wdm_Ebridge_Woocommerce_Sync_Categories();
		$added_web_categories = $categories_sync->add_webcategories();
		$added_brands         = $categories_sync->add_brands();

		$products_sync  = new Wdm_Ebridge_Woocommerce_Sync_Products();
		$added_products = $products_sync->update_products();

		echo __( $added_web_categories['success_count'] . ' WebCategories added.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo __( $added_brands['success_count'] . ' Brands added.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo __( $added_products['success_count'] . ' Products updated.<br />', 'wdm-ebridge-woocommerce-sync' );
		echo '<pre>';
		print_r( $added_web_categories );
		print_r( $added_brands );
		print_r( $added_products );
		echo '</pre>';
	}

	/**
	 * For testing purposes only. The function to add timeout during cron run
	 *
	 * @since    1.0.0
	 * @param      string $handle       The name of this plugin.
	 */
	public function sar_custom_curl_timeout( $handle ) {
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 1000 );
		curl_setopt( $handle, CURLOPT_TIMEOUT, 1000 );
	}
}

new Wdm_Ebridge_Woocommerce_Sync_Cron();