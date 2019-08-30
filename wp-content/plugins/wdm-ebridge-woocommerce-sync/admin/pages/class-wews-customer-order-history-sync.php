<?php

/**
 * Adds the Customer Order History Sync Page to Sync Ebridge Customer Order History.
 *
 * @since      1.0.0
 * @package    WEWS_Customer_Order_History_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin/pages
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class WEWS_Customer_Order_History_Sync {

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

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->api_url      = get_option( 'ebridge_sync_api_url', '' );
		$this->api_token    = get_option( 'ebridge_sync_api_token', '' );
		$this->customer_obj = new Wdm_Ebridge_Woocommerce_Sync_Customer();
		$this->order_obj    = new Wdm_Ebridge_Woocommerce_Sync_Order();
		$this->curl_args    = array(
			'timeout' => 6000,
		);
	}

	/**
	 * Register the menu for Customer Order History Sync
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function menu_page() {
		add_submenu_page( 'ebridge_sync', esc_html__( 'Customer Order History Sync', 'wdm-ebridge-woocommerce-sync' ), esc_html__( 'Customer Order History Sync', 'wdm-ebridge-woocommerce-sync' ), 'manage_options', 'ebridge_sync_customer_order_history_sync', array( &$this, 'render_submenu_page' ) );
	}

	/**
	 * Render the Customer Order History Sync menu page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_submenu_page() {
		?>
			<h2><?php echo __( 'Sync Ebridge Customer Order History with Woocommerce', 'wdm-ebridge-woocommerce-sync' ); ?></h2>

			<form id="customer_order_history_form" action="#" method="post" enctype="multipart/form-data">
				<div class="import_button">
					<input type="submit" id="customer_order_submit" name="customer_order_submit" class="button button-primary" value="<?php _e( 'Sync Customer Order History', 'wdm-ebridge-woocommerce-sync' ); ?>">
				</div>
			</form>

		<?php
	}

	public function setup_sections() {
	}

	public function fetch_customers_to_sync() {
		$response = array();

		$customers             = $this->customer_obj->get_customers( true );
		$response['customers'] = $customers;
		$response['message']   = __( 'The number of customers to update: ' . count( $customers ) . '<br />', 'wdm-ebridge-woocommerce-sync' );

		wp_send_json_success( $response );
	}

	public function get_customer_orders() {

		$response = array();

		if ( ( isset( $_POST['customer_id'] ) && isset( $_POST['ebridge_id'] ) ) ) {
			$customer_id = $_POST['customer_id'];
			$ebridge_id  = $_POST['ebridge_id'];

			$customer            = get_userdata( $customer_id );
			$customer_order_data = $this->get_customer_order_history( $ebridge_id );

			if ( isset( $customer_order_data ) ) {
				$response['message']       = __( 'Found ' . count( $customer_order_data ) . ' order(s) for the customer ' . $customer->user_nicename . ' with ebridge id ' . $ebridge_id . '.', 'wdm-ebridge-woocommerce-sync' );
				$response['order_data']    = $customer_order_data;
				$response['id']            = $customer_id;
				$response['customer_name'] = $customer->user_nicename;
				wp_send_json_success( $response );
			}

			$response['message'] = __( 'Failed to get orders for the user ' . $customer->user_nicename . ' with ebridge id ' . $ebridge_id . '.<br />', 'wdm-ebridge-woocommerce-sync' );
			wp_send_json_error( $response );

		}

		$response['message'] = __( 'Invalid data.<br />', 'wdm-ebridge-woocommerce-sync' );

		wp_send_json_error( $response );
	}


	public function sync_order() {

		ini_set( 'display_errors', 1 );
		ini_set( 'display_startup_errors', 1 );
		error_reporting( E_ALL );

		$response = array();

		if ( ( isset( $_POST['order_id'] ) && isset( $_POST['order_type'] ) ) ) {
			$ebridge_order_id   = $_POST['order_id'];
			$ebridge_order_type = $_POST['order_type'];

			$order_id = $this->order_obj->sync_order( $ebridge_order_id, $ebridge_order_type );

			if ( $order_id ) {
				$response['message'] = __( 'Updated Ebridge order #' . $ebridge_order_id . ' as Woocommerce Order #' . $order_id . '.', 'wdm-ebridge-woocommerce-sync' );
				$response['id']      = $order_id;
				wp_send_json_success( $response );
			}

			$response['message'] = __( 'Failed to update Ebridge order #' . $ebridge_order_id . '.<br />', 'wdm-ebridge-woocommerce-sync' );
			wp_send_json_error( $response );

		}

		$response['message'] = __( 'Invalid data.<br />', 'wdm-ebridge-woocommerce-sync' );

		wp_send_json_error( $response );
	}

	public function get_customer_order_history( $ebridge_id ) {
		$orders = array();

		if ( $this->api_url && $this->api_token ) {
			$response      = wp_remote_get( $this->api_url . '/' . $this->api_token . '/customers/' . $ebridge_id . '/orders', $this->curl_args );
			$json_response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) && isset( $json_response->customerOrderMatches ) ) {
				$customer_order_matches = $json_response->customerOrderMatches;

				foreach ( $customer_order_matches as $key => $ebridge_order ) {
					$orders[] = array(
						'id'   => $ebridge_order->orderId,
						'type' => $ebridge_order->orderType,
					);
				}
			}
		}

		return $orders;
	}


	// public function register_sync_products_bulk_action( $bulk_actions ) {
	// $bulk_actions['wews_sync'] = __( 'Sync', 'wdm-ebridge-woocommerce-sync' );
	// return $bulk_actions;
	// }


	// public function sync_products_bulk_action_handler( $redirect_to, $doaction, $product_ids ) {
	// if ( $doaction !== 'wews_sync' ) {
	// return $redirect_to;
	// }

	// $skus = array();

	// foreach ( $product_ids as $key => $product_id ) {
	// $product = new WC_Product( $product_id );
	// $skus[]  = $product->get_sku();
	// }
	// $redirect_to = admin_url() . 'admin.php?page=ebridge_sync_product_sync';

	// $redirect_to = add_query_arg( 'product_ids', $skus, $redirect_to );
	// $redirect_to = add_query_arg( 'product_id_count', count( $skus ), $redirect_to );

	// return $redirect_to;
	// }
}
