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

		add_menu_page( __( 'Ebridge Sync', 'wdm-ebridge-woocommerce-sync' ), __( 'Ebridge Sync', 'wdm-ebridge-woocommerce-sync' ), 'administrator', 'ebridge_sync', array( $this, 'render_settings_page' ) );
		// add_submenu_page('ebridge_sync', esc_html__('Settings','wdm-ebridge-woocommerce-sync'), esc_html__('Settings','wdm-ebridge-woocommerce-sync'), 'administrator', 'ebridge_sync_settings', array(&$this, 'render_settings_page'));
	}

	/**
	 * Render the Ebridge Sync menu page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_menu_page() {

	}

	/**
	 * Render the Ebridge Sync settings page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_settings_page() {
		$tabs = array(
			'connection_settings' => 'Connection Settings',
			'product_sync'        => 'Product Sync',
			'pickup_service'      => 'Pickup Service',
			'customer_sync'       => 'Customer Sync',
		);

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'connection_settings';

		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">
			<h2>Ebridge Sync Options</h2>
			<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $tabs as $key => $value ) {
					$active = ( $key == $tab ) ? 'nav-tab-active' : '';
					echo '<a class="nav-tab ' . $active . '" href="?page=ebridge_sync&tab=' . esc_attr( $key ) . '">' . esc_html( $value ) . '</a>';
				}
				?>
			</h2>
			<!-- <form method="post" id="mainform" action="?page=ebridge_sync&amp;tab=<?php echo esc_attr( $tab ); ?>"> -->
			<form method="post" action="options.php">
				<?php
				switch ( $tab ) {
					case 'connection_settings':
						$this->connection_settings();
						break;
					case 'product_sync':
						// $this->candidate_notification_editor();
						echo 'P';
						break;
					case 'pickup_service':
						$this->pickup_service();
						break;
					case 'customer_sync':
						// $this->candidate_notification_editor();
						echo 'Cu';
						break;
					default:
						$this->connection_settings();
						break;
				}
				?>
			</form>
		</div>
		<?php
	}

	public function pickup_service() {
		?>
		<?php
			settings_fields( 'ebridge_sync_pickup_service' );
			do_settings_sections( 'ebridge_sync_pickup_service' );
			submit_button();
		?>
		<?php
	}

	public function connection_settings() {
		?>
		<?php
			settings_fields( 'ebridge_sync_connection_settings' );
			do_settings_sections( 'ebridge_sync_connection_settings' );
			submit_button();
		?>
		<?php
	}

	public function setup_sections() {
		// Settings for Connection Settings section
		add_settings_section( 'ebridge_sync_connection_settings_section', __( 'Connection Settings', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'ebridge_sync_connection_settings_callback' ), 'ebridge_sync_connection_settings' );
		add_settings_field( 'ebridge_sync_api_url', __( 'EBridge API URL:', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'ebridge_sync_api_url_callback' ), 'ebridge_sync_connection_settings', 'ebridge_sync_connection_settings_section', array( 'fieldname' => 'ebridge_sync_api_url' ) );
		add_settings_field( 'ebridge_sync_api_token', __( 'API Token:', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'ebridge_sync_api_token_callback' ), 'ebridge_sync_connection_settings', 'ebridge_sync_connection_settings_section', array( 'fieldname' => 'ebridge_sync_api_token' ) );
		register_setting( 'ebridge_sync_connection_settings', 'ebridge_sync_api_url' );
		register_setting( 'ebridge_sync_connection_settings', 'ebridge_sync_api_token' );

		// Settings for Pickup Service
		add_settings_section( 'ebridge_sync_pickup_service_section', __( 'Pickup Service', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'ebridge_sync_pickup_service_callback' ), 'ebridge_sync_pickup_service' );
		add_settings_field( 'pickup_service', __( 'Activate Pickup Service:', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'pickup_service_callback' ), 'ebridge_sync_pickup_service', 'ebridge_sync_pickup_service_section', array( 'fieldname' => 'pickup_service' ) );
		register_setting( 'ebridge_sync_pickup_service', 'pickup_service' );
	}

	public function ebridge_sync_pickup_service_callback() {
	}

	public function pickup_service_callback( $args ) {
		$api_url = get_option( $args['fieldname'], false );

		if ( $api_url ) {
			?>
			<input type="checkbox" class="" name="<?php echo $args['fieldname']; ?>" id="<?php echo $args['fieldname']; ?>" checked>
			<?php
		} else {
			?>
			<input type="checkbox" class="" name="<?php echo $args['fieldname']; ?>" id="<?php echo $args['fieldname']; ?>">
			<?php
		}
	}

	public function ebridge_sync_api_url_sanitize( $url ) {
		return esc_url_raw( $url );
	}

	public function ebridge_sync_connection_settings_callback() {
	}

	public function ebridge_sync_api_url_callback( $args ) {
		$api_url = get_option( $args['fieldname'], '' );
		?>
		<input type="text" class="" name="<?php echo $args['fieldname']; ?>" id="<?php echo $args['fieldname']; ?>" value="<?php echo $api_url; ?>">
		<?php
	}

	public function ebridge_sync_api_token_callback( $args ) {
		$api_token = get_option( $args['fieldname'], '' );
		?>
		<input type="text" name="<?php echo $args['fieldname']; ?>" id="<?php echo $args['fieldname']; ?>" value="<?php echo $api_token; ?>">
		<?php
	}

}
