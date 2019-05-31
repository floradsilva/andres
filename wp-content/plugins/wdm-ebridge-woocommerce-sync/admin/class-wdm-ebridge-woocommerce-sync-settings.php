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
				<?php
				switch ( $tab ) {
					case 'connection_settings':
						$this->connection_settings();
						break;
					case 'product_sync':
						$this->product_attributes();
						break;
					case 'pickup_service':
						$this->pickup_service();
						break;
					case 'customer_sync':
						$this->customer_sync();
						break;
					default:
						$this->connection_settings();
						break;
				}
				?>
		</div>
		<?php
	}

	public function pickup_service() {
		?>
		 <form method="post" action="options.php"> 
		<?php

		settings_fields( 'ebridge_sync_pickup_service' );
		do_settings_sections( 'ebridge_sync_pickup_service' );
		submit_button();

		?>
		 </form> 
		<?php
	}

	public function connection_settings() {
		?>
		 <form method="post" action="options.php"> 
		<?php

		settings_fields( 'ebridge_sync_connection_settings' );
		do_settings_sections( 'ebridge_sync_connection_settings' );
		submit_button();

		?>
		 </form> 
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

		// Settings for Product Attributes
		add_settings_section( 'ebridge_sync_product_attributes_section', __( 'Product Attributes', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'ebridge_sync_product_attributes_callback' ), 'ebridge_sync_product_attributes' );
		add_settings_field( 'product_attributes', __( 'Product Attibutes to Sync:', 'wdm-ebridge-woocommerce-sync' ), array( $this, 'product_attributes_callback' ), 'ebridge_sync_product_attributes', 'ebridge_sync_product_attributes_section', array( 'fieldname' => 'product_attributes' ) );
		register_setting( 'ebridge_sync_product_attributes', 'product_attributes' );
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

	public function product_attributes() {
		?>
		 <form method="post" action="options.php"> 
		<?php

		settings_fields( 'ebridge_sync_product_attributes' );
		do_settings_sections( 'ebridge_sync_product_attributes' );
		submit_button();

		?>
		 </form> 
		<?php
	}

	public function ebridge_sync_product_attributes_callback() {
	}

	public function product_attributes_callback( $args ) {
		$product_attributes_options = get_option( $args['fieldname'], array() );
		$api_url                    = get_option( 'ebridge_sync_api_url', '' );
		$api_token                  = get_option( 'ebridge_sync_api_token', '' );
		$product                    = 'P414TP1';
		$response                   = wp_remote_get( $api_url . '/' . $api_token . '/products/' . $product );

		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
			$body               = json_decode( wp_remote_retrieve_body( $response ) );
			$product_attributes = get_object_vars( $body->product );

			foreach ( $product_attributes as $key => $value ) {
				?>
					<div>
						<?php if ( in_array( $key, $product_attributes_options ) ) { ?>
							<input type="checkbox" class="" name="<?php echo $args['fieldname']; ?>[]" value="<?php echo $key; ?>" checked>
						<?php } else { ?>
							<input type="checkbox" class="" name="<?php echo $args['fieldname']; ?>[]" value="<?php echo $key; ?>">
						<?php } ?>
						<label for="<?php echo $key; ?>"><?php echo $key; ?></label>
					</div>
				<?php
			}
		} else {
			echo __( 'Some error. Please make sure the Ebridge URL and API Token are valid.', 'wdm-ebridge-woocommerce-sync' );
		}
	}

	public function customer_sync() {
		?>
		<form id="customer_sync_form" action="#" method="post" enctype="multipart/form-data">
			<div>
				<input type="file" name="customer_sync_csv" id="customer_sync_csv" class="file" accept=".csv" data-show-preview="false" data-show-upload="false" title="<?php _e( 'Select File', 'wdm-ebridge-woocommerce-sync' ); ?>">
			</div>
			<div class="wdm-input-group">
				<input type="submit" id="customer_sync_submit" name="customer_sync_submit" class="button button-primary" value="<?php _e( 'Import', 'wdm-ebridge-woocommerce-sync' ); ?>">
			</div>
		</form>
		<?php
	}

	public function add_localize_script() {
		 $args = $this->fetch_localized_script_data();
		wp_localize_script( $this->plugin_name, 'customer_sync', $args );
	}

	public function fetch_localized_script_data() {
		 $args = array(
			 'customer_sync_url' => admin_url( 'admin-ajax.php' ),
		 );
		return $args;
	}

	public function upload_csv() {
		if ( isset( $_FILES['customer_sync_csv'] ) ) {
			$files = $_FILES['customer_sync_csv'];

			$file            = array(
				'name'     => $files['name'],
				'type'     => $files['type'],
				'tmp_name' => $files['tmp_name'],
				'error'    => $files['error'],
				'size'     => $files['size'],
			);
			$attachment_path = $this->upload_attachment( $file );
			
			// $row = 1;
			// if ( ( $handle = fopen( $attachment_path, 'r' ) ) !== false ) {
			// 	while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
			// 		$num = count( $data );
			// 		echo "<p> $num fields in line $row: <br /></p>\n";
			// 		$row++;
			// 		for ( $c = 0; $c < $num; $c++ ) {
			// 			echo $data[ $c ] . "<br />\n";
			// 		}
					$request = new WP_REST_Request( 'GET', '/wp-json/wc/v3/customers/4' );
					$response = rest_do_request( $request );
					$server = rest_get_server();
					$data = $server->response_to_data( $response, false );
					$json = wp_json_encode( $data );

					var_dump($json);

			// 	}
			// 	fclose( $handle );
			// }
			wp_delete_file( $attachment_path );
		}
	}

	public function upload_attachment( $file_to_upload ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$uploadedfile = $file_to_upload;

		$upload_overrides = array(
			'test_form' => false,
		);

		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		if ( $movefile && ! isset( $movefile['error'] ) ) {
			return $movefile['file'];
		} else {
			return null;
		}
	}
}
