<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/includes
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wdm_Ebridge_Woocommerce_Sync_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WDM_EBRIDGE_WOOCOMMERCE_SYNC_VERSION' ) ) {
			$this->version = WDM_EBRIDGE_WOOCOMMERCE_SYNC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wdm-ebridge-woocommerce-sync';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wdm_Ebridge_Woocommerce_Sync_Loader. Orchestrates the hooks of the plugin.
	 * - Wdm_Ebridge_Woocommerce_Sync_i18n. Defines internationalization functionality.
	 * - Wdm_Ebridge_Woocommerce_Sync_Admin. Defines all hooks for the admin area.
	 * - Wdm_Ebridge_Woocommerce_Sync_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wdm-ebridge-woocommerce-sync-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wdm-ebridge-woocommerce-sync-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wdm-ebridge-woocommerce-sync-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/class-wdm-ebridge-woocommerce-sync-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/class-wdm-ebridge-woocommerce-sync-product-sync.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wdm-ebridge-woocommerce-sync-orders.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/class-wews-customer-order-history-sync.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wdm-ebridge-woocommerce-sync-public.php';

		/**
		 * These classes are the helper classes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/definitions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wdm-ebridge-woocommerce-sync-products.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wdm-ebridge-woocommerce-sync-customer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wdm-ebridge-woocommerce-sync-order.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wdm-ebridge-woocommerce-sync-helper-functions.php';
	
		$this->loader = new Wdm_Ebridge_Woocommerce_Sync_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wdm_Ebridge_Woocommerce_Sync_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wdm_Ebridge_Woocommerce_Sync_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin                = new Wdm_Ebridge_Woocommerce_Sync_Admin( $this->get_plugin_name(), $this->get_version() );
		$admin_settings              = new Wdm_Ebridge_Woocommerce_Sync_Settings( $this->get_plugin_name(), $this->get_version() );
		$product_sync                = new Wdm_Ebridge_Woocommerce_Sync_Product_Sync( $this->get_plugin_name(), $this->get_version() );
		$order_sync                  = new Wdm_Ebridge_Woocommerce_Sync_Orders( $this->get_plugin_name(), $this->get_version() );
		$customer_order_history_sync = new WEWS_Customer_Order_History_Sync( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'add_localize_script' );
		$this->loader->add_action( 'wp_ajax_upload_csv', $admin_settings, 'upload_csv' );
		$this->loader->add_action( 'wp_ajax_refresh_product_attributes', $admin_settings, 'refresh_product_attributes' );
		$this->loader->add_action( 'wp_ajax_add_connection_settings', $admin_settings, 'add_connection_settings' );
		$this->loader->add_action( 'admin_menu', $admin_settings, 'menu_page', 10 );
		$this->loader->add_action( 'admin_init', $admin_settings, 'setup_sections' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_taxonomy_brand' );
		// $this->loader->add_action( 'woocommerce_product_data_tabs', $plugin_admin, 'display_product_meta_tabs' );
		// $this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'add_additional_product_attributes' );
		$this->loader->add_action( 'admin_menu', $product_sync, 'menu_page', 10 );
		$this->loader->add_action( 'admin_init', $product_sync, 'setup_sections' );
		$this->loader->add_action( 'wp_ajax_fetch_products_to_sync', $product_sync, 'fetch_products_to_sync' );
		$this->loader->add_action( 'wp_ajax_update_product', $product_sync, 'update_product' );
		$this->loader->add_action( 'wp_ajax_delete_product', $product_sync, 'delete_product' );
		$this->loader->add_filter( 'bulk_actions-edit-product', $product_sync, 'register_sync_products_bulk_action' );
		$this->loader->add_filter( 'handle_bulk_actions-edit-product', $product_sync, 'sync_products_bulk_action_handler', 10, 3 );
		$this->loader->add_action( 'woocommerce_checkout_create_order', $order_sync, 'wews_create_order', 10, 2 );
		$this->loader->add_action( 'admin_menu', $customer_order_history_sync, 'menu_page', 10 );
		$this->loader->add_action( 'admin_init', $customer_order_history_sync, 'setup_sections' );
		$this->loader->add_action( 'wp_ajax_fetch_customers_to_sync', $customer_order_history_sync, 'fetch_customers_to_sync' );
		$this->loader->add_action( 'wp_ajax_get_customer_orders', $customer_order_history_sync, 'get_customer_orders' );
		$this->loader->add_action( 'wp_ajax_sync_order', $customer_order_history_sync, 'sync_order' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wdm_Ebridge_Woocommerce_Sync_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wdm_Ebridge_Woocommerce_Sync_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
