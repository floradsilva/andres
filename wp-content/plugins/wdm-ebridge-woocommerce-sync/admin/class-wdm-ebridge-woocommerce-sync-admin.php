<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdm_Ebridge_Woocommerce_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdm_Ebridge_Woocommerce_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wdm-ebridge-woocommerce-sync-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'wdm-validator', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js', array( 'jquery' ), null, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wdm-ebridge-woocommerce-sync-admin.js', array( 'jquery' ), $this->version, true );

		wp_register_script( 'customer-order-history', plugin_dir_url( __FILE__ ) . 'js/customer-order-history.js', array( 'jquery' ), $this->version, true );

		if ( ( isset( $_GET['page'] ) ) && ( 'ebridge_sync_customer_order_history_sync' === $_GET['page'] ) ) {
			wp_enqueue_script( 'customer-order-history' );
		}
	}

	public function create_taxonomy_brand() {
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => __( 'Brands', 'wdm-ebridge-woocommerce-sync' ),
			'singular_name'              => __( 'Brand', 'wdm-ebridge-woocommerce-sync' ),
			'search_items'               => __( 'Search Brands', 'wdm-ebridge-woocommerce-sync' ),
			'popular_items'              => __( 'Popular Brands', 'wdm-ebridge-woocommerce-sync' ),
			'all_items'                  => __( 'All Brands', 'wdm-ebridge-woocommerce-sync' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Brand', 'wdm-ebridge-woocommerce-sync' ),
			'update_item'                => __( 'Update Brand', 'wdm-ebridge-woocommerce-sync' ),
			'add_new_item'               => __( 'Add New Brand', 'wdm-ebridge-woocommerce-sync' ),
			'new_item_name'              => __( 'New Writer Brand', 'wdm-ebridge-woocommerce-sync' ),
			'separate_items_with_commas' => __( 'Separate brands with commas', 'wdm-ebridge-woocommerce-sync' ),
			'add_or_remove_items'        => __( 'Add or remove brands', 'wdm-ebridge-woocommerce-sync' ),
			'choose_from_most_used'      => __( 'Choose from the most used brands', 'wdm-ebridge-woocommerce-sync' ),
			'not_found'                  => __( 'No brands found.', 'wdm-ebridge-woocommerce-sync' ),
			'menu_name'                  => __( 'Brands', 'wdm-ebridge-woocommerce-sync' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'brand' ),
		);

		register_taxonomy( 'brand', 'product', $args );
	}


	public function display_product_meta_tabs( $product_tabs ) {
		$product_tabs['additional_product_attributes'] = array(
			'label'    => __( 'Additional Product Attributes', 'wdm-ebridge-woocommerce-sync' ),
			'target'   => 'add_additional_product_attributes',
			'priority' => 60,
			'class'    => array(),
		);

		return $product_tabs;
	}


	public function add_additional_product_attributes() {
		// include_once plugin_dir_path( __FILE__ ) . '/partials/wdm-ebridge-woocommerce-sync-admin-additional-product-attributes-tab.php';
	}

	public function add_localize_script() {
		$args = $this->fetch_localized_script_data();
		wp_localize_script( $this->plugin_name, 'wews', $args );
	}

	public function fetch_localized_script_data() {
		$args = array(
			'wews_url'              => admin_url( 'admin-ajax.php' ),
			'update_complete'       => __( 'Completed updating elements.', 'wdm-ebridge-woocommerce-sync' ),
			'delete_complete'       => __( 'Completed deleting elements.', 'wdm-ebridge-woocommerce-sync' ),
			'fetched_msg'           => __( 'Total products fetched', 'wdm-ebridge-woocommerce-sync' ),
			'updated_msg'           => __( 'Total products updated', 'wdm-ebridge-woocommerce-sync' ),
			'updated_customers_msg' => __( 'Total customers updated', 'wdm-ebridge-woocommerce-sync' ),
			'updating_customer_msg' => __( 'Updating customer', 'wdm-ebridge-woocommerce-sync' ),
			'no_customers_msg'      => __( 'No customers to update.', 'wdm-ebridge-woocommerce-sync' ),
		);
		return $args;
	}
}
