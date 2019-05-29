<?php

/**
 * Adds the Menu and Sub Menu Pages for Ebridge Settings.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync_Settings {

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
	 * Register the menu for Ebridge Sync
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function menu_page() {

		add_menu_page( __( 'Ebridge Sync', 'wdm-ebridge-woocommerce-sync' ), __( 'Ebridge Sync', 'wdm-ebridge-woocommerce-sync' ), 'administrator', 'ebridge_sync', array( $this, 'render_menu_page' ) );
		// add_submenu_page('booked-appointments', esc_html__('Pending1','booked'), esc_html__('Pending1','booked'), 'edit_booked_appointments', 'booked-pending', array(&$this, 'render_menu_page'));
	}

	public function setup_sections() {
		$args = array();
		add_settings_section( 'ebridge_sync_connection_settings_section', 'Connection Settings', array( $this, 'connection_settings_callback' ), 'ebridge_sync' );
		add_settings_field( 'connection_settings_field_1', 'Connection Settings Field 1', array( $this, 'ebridge_sync_connection_settings_field_callback' ), 'ebridge_sync', 'ebridge_sync_connection_settings_section', $args );
		register_setting( 'ebridge_sync_connection_settings', 'connection_settings_field_1' );

		add_settings_section( 'ebridge_sync_product_sync_section', 'Product Sync', array( $this, 'product_sync_callback' ), 'ebridge_sync' );
		add_settings_field( 'product_sync_field_1', 'Product Sync Field 1', array( $this, 'ebridge_sync_product_sync_field_callback' ), 'ebridge_sync', 'ebridge_sync_product_sync_section', $args );
		register_setting( 'ebridge_sync_product_sync', 'product_sync_field_1' );

		add_settings_section( 'ebridge_sync_pickup_service_settings_section', 'Pickup Service', array( $this, 'connection_settings_callback' ), 'ebridge_sync' );
		add_settings_field( 'connection_settings_field_1', 'Connection Settings Field 1', array( $this, 'ebridge_sync_connection_settings_field_callback' ), 'ebridge_sync', 'ebridge_sync_pickup_service_settings_section', $args );
		register_setting( 'ebridge_sync_connection_settings', 'connection_settings_field_1' );

		add_settings_section( 'ebridge_sync_customer_sync_settings_section', 'Customer Sync', array( $this, 'connection_settings_callback' ), 'ebridge_sync' );
		add_settings_field( 'connection_settings_field_1', 'Connection Settings Field 1', array( $this, 'ebridge_sync_connection_settings_field_callback' ), 'ebridge_sync', 'ebridge_sync_customer_sync_settings_section', $args );
		register_setting( 'ebridge_sync_connection_settings', 'connection_settings_field_1' );
	}

	public function connection_settings_callback()
	{}

	public function ebridge_sync_connection_settings_field_callback()
	{
		echo "Connection Settings";
	}

	public function product_sync_callback()
	{}

	public function ebridge_sync_product_sync_field_callback()
	{
		echo "Product Sync";
	}

	/**
	 * Render the Ebridge Sync menu page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_menu_page() {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2>Ebridge Sync Options</h2>
			<?php
				settings_errors();
				$active_tab = 'connection_settings';
			if ( isset( $_GET['tab'] ) ) {
				$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'connection_settings';
			}
			?>
			<h2 class="nav-tab-wrapper">
				<a href="?page=ebridge_sync&tab=connection_settings" class="nav-tab <?php echo $active_tab == 'connection_settings' ? 'nav-tab-active' : ''; ?>">Connection Settings</a>
				<a href="?page=ebridge_sync&tab=product_sync" class="nav-tab <?php echo $active_tab == 'product_sync' ? 'nav-tab-active' : ''; ?>">Product Sync</a>
				<a href="?page=ebridge_sync&tab=pickup_service" class="nav-tab <?php echo $active_tab == 'pickup_service' ? 'nav-tab-active' : ''; ?>">Pickup Service</a>
				<a href="?page=ebridge_sync&tab=customer_sync" class="nav-tab <?php echo $active_tab == 'customer_sync' ? 'nav-tab-active' : ''; ?>">Customer Sync</a>
			</h2>
			<form method="post" action="options.php">
				<?php
				if ( $active_tab === 'connection_settings' ) {
					settings_fields( 'ebridge_sync_connection_settings' );
					do_settings_sections( 'ebridge_sync' );
				}
				?>
				<?php
				if ( $active_tab === 'product_sync' ) {
					settings_fields( 'ebridge_sync_product_sync_settings' );
					do_settings_sections( 'ebridge_sync_product_sync' );
				}
				?>
				<?php
				if ( $active_tab === 'pickup_service' ) {
					settings_fields( 'ebridge_sync_pickup_service_settings' );
					do_settings_sections( 'ebridge_sync_pickup_service' );
				}
				?>
				<?php
				if ( $active_tab === 'customer_sync' ) {
					settings_fields( 'ebridge_sync_customer_sync_settings' );
					do_settings_sections( 'ebridge_sync_customer_sync' );
				}
				?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

}
