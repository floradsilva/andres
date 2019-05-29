<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
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
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/includes
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Pinterest_Rich_Pins {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pinterest_Rich_Pins_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PINTEREST_RICH_PINS_VERSION' ) ) {
			$this->version = PINTEREST_RICH_PINS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pinterest-rich-pins';

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
	 * - Pinterest_Rich_Pins_Loader. Orchestrates the hooks of the plugin.
	 * - Pinterest_Rich_Pins_i18n. Defines internationalization functionality.
	 * - Pinterest_Rich_Pins_Admin. Defines all hooks for the admin area.
	 * - Pinterest_Rich_Pins_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pinterest-rich-pins-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pinterest-rich-pins-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pinterest-rich-pins-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pinterest-rich-pins-public.php';
		
		/**
		 * The class responsible for defining all actions related to data base insert and get methods
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/Pinterest/class-db.php';
		
		/**
		 * The class responsible for defining all functions for general usage
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pinterest-rich-pins-functions.php';
		
		/**
		 * The class responsible for defining all log actions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-logging.php';

		$this->loader = new Pinterest_Rich_Pins_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pinterest_Rich_Pins_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pinterest_Rich_Pins_i18n();

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

		$plugin_admin = new Pinterest_Rich_Pins_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Display error messsage
		$this->loader->add_action( 'admin_notices', $plugin_admin,'pinterest_rich_pin_plugin_display_message',0 );
		
		// Add the menu page
		$this->loader->add_action('admin_menu',$plugin_admin, 'register_pinterest_rich_pin_custom_submenu_page',99);
		
		// Add the menu page
		$this->loader->add_action('add_meta_boxes_product',$plugin_admin, 'add_metaboxes_product_callback');
		
		// Save post product
		$this->loader->add_action('save_post_product',$plugin_admin, 'save_post_product_callback');
		
		// Add bulk actions
		$this->loader->add_filter('bulk_actions-edit-product',$plugin_admin, 'pinterest_rich_pin_custom_bulk_actions');
		$this->loader->add_filter('handle_bulk_actions-edit-product',$plugin_admin, 'pinterest_rich_pin_custom_bulk_actions_handler', 10, 3);
		
		// All default action will be executed from here
		$this->loader->add_action('init',$plugin_admin, 'pinterest_rich_pin_init_action');
		
		// Manage custom column functionality
		$this->loader->add_filter( 'manage_edit-product_columns',$plugin_admin, 'pinterest_rich_pin_products_add_columns', 10 ) ;
		$this->loader->add_action( 'manage_product_posts_custom_column',$plugin_admin, 'pinterest_rich_pin_products_columns', 10, 2 );
		
		// Cron register hooks
		$this->loader->add_action('pinterest_rich_pin_cron_schedules_hooks', $plugin_admin,'pinterest_rich_pin_cron_schedules_hooks_callback');
		// $this->loader->add_action('admin_init', $plugin_admin,'pinterest_rich_pin_cron_schedules_hooks_callback');
		
		// Admin footer
		$this->loader->add_action('admin_footer', $plugin_admin, 'pinterest_rich_pin_add_loader');
		
		// Display error messsage
		$this->loader->add_action( 'admin_notices', $plugin_admin,'pinterest_rich_pin_error_notice',0 );
		
		//////// Ajax Request Callback ////////
		
		// To add product to pinterest pin
		$this->loader->add_action( 'wp_ajax_add_product_to_pinterest_pin',$plugin_admin, 'add_product_to_pinterest_pin_callback' );
		$this->loader->add_action( 'wp_ajax_update_product_to_pinterest_pin',$plugin_admin, 'update_product_to_pinterest_pin_callback' );
		$this->loader->add_action( 'wp_ajax_remove_product_to_pinterest_pin',$plugin_admin, 'remove_product_to_pinterest_pin_callback' );
		
		// Empty log
		$this->loader->add_action( 'wp_ajax_empty_log_pinterest_rich_pin',$plugin_admin, 'empty_log_pinterest_rich_pin_callback' );
		
		// Remove action from queue
		$this->loader->add_action( 'wp_ajax_remove_action_from_queue',$plugin_admin, 'remove_action_from_queue_callback' );
		$this->loader->add_action( 'wp_ajax_retry_action_from_queue',$plugin_admin, 'retry_action_from_queue_callback' );
		
		// Bulk Action Submit
		$this->loader->add_action( 'wp_ajax_queue_bulk_action_submit_admin',$plugin_admin, 'queue_bulk_action_submit_admin_callback' );
		
		// API Check
		$this->loader->add_action( 'wp_ajax_pinterest_rich_pin_api_check',$plugin_admin, 'pinterest_rich_pin_api_check_callback' );
		
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pinterest_Rich_Pins_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		// Overwrite Yoast Seo Plugin Meta Tags
		$this->loader->add_filter( 'wpseo_metadesc',$plugin_public, 'pinterest_rich_pins_meta_tags_callback', 10, 1 );
		$this->loader->add_filter( 'wpseo_title',$plugin_public, 'pinterest_rich_pins_title_callback', 10, 1 );
		$this->loader->add_filter( 'wpseo_opengraph_type',$plugin_public, 'pinterest_rich_pins_wpseo_opengraph_type_callback', 10, 1 );
		$this->loader->add_action( 'wpseo_opengraph',$plugin_public, 'pinterest_rich_pins_wpseo_opengraph_callback' );
		$this->loader->add_filter( 'wpseo_opengraph_image',$plugin_public, 'pinterest_rich_pins_wpseo_opengraph_image_callback', 10, 1 );
		$this->loader->add_action( 'wp_head',$plugin_public, 'pinterest_rich_pins_extra_meta_callback', 1, 1 );
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
	 * @return    Pinterest_Rich_Pins_Loader    Orchestrates the hooks of the plugin.
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
