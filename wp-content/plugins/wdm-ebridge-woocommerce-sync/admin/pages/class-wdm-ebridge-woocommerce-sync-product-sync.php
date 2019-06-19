<?php

/**
 * Adds the Product Sync Page to Sync Ebridge Products.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync_Product_Sync {

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
		$this->products    = new Wdm_Ebridge_Woocommerce_Sync_Products();
	}

	/**
	 * Register the menu for Ebridge Sync
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function menu_page() {

		add_submenu_page( 'ebridge_sync', esc_html__( 'Product Sync', 'wdm-ebridge-woocommerce-sync' ), esc_html__( 'Product Sync', 'wdm-ebridge-woocommerce-sync' ), 'manage_options', 'ebridge_sync_product_sync', array( &$this, 'render_submenu_page' ) );
	}

	/**
	 * Render the Ebridge Sync menu page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_submenu_page() {
		?>
			<h2><?php echo __( 'Sync Ebridge Products with Woocommerce', 'wdm-ebridge-woocommerce-sync' ); ?></h2>

			<form id="product_sync_form" action="#" method="post" enctype="multipart/form-data">
				<div class="import_button">
					<select name="selected_product_sync" id="selected_product_sync">
						<option value="sync_all"><?php echo __( 'Sync All Products', 'wdm-ebridge-woocommerce-sync' ); ?></option>
						<option value="sync_updated"><?php echo __( 'Sync Recently Updated Products', 'wdm-ebridge-woocommerce-sync' ); ?></option>
						<!-- <option value="sync_batched"><?php// echo __( 'Sync Products in Batches', 'wdm-ebridge-woocommerce-sync' ); ?></option> -->
					</select>
					<input type="submit" id="product_sync_submit" name="product_sync_submit" class="button button-primary" value="<?php _e( 'Sync Products', 'wdm-ebridge-woocommerce-sync' ); ?>">
				</div>
			</form>
<!-- 
			<div id="message-wrap">
				<h3>Logs:</h3>
				<p id="message"><?php// echo __( 'Logs', 'wdm-ebridge-woocommerce-sync' ); ?></p>
			</div> -->

		<?php
	}

	public function setup_sections() {
	}

	public function ebridge_sync_product_attributes_callback() {
	}

	public function fetch_products_to_sync() {
		$response = array();

		if ( isset( $_POST['selected_product_sync'] ) ) {
			$selected_sync_type = $_POST['selected_product_sync'];

			if ( $selected_sync_type === 'sync_all' ) {
				$all_products        = $this->products->get_batched_product_ids();
				$response            = $all_products;
				$response['message'] = __( 'Total items found: ' . ( $all_products['update_ids_count'] + $all_products['delete_ids_count'] ) . '<br />Total items to update: ' . $all_products['update_ids_count'] . '<br /> Total items to delete: ' . $all_products['delete_ids_count'], 'wdm-ebridge-woocommerce-sync' );

				wp_send_json_success( $response );
			} elseif ( $selected_sync_type === 'sync_updated' ) {
				$last_updated_products = $this->products->get_last_updated_batched_product_ids();
				$response              = $last_updated_products;
				$response['message']   = __( 'Total items found: ' . ( $last_updated_products['update_ids_count'] + $last_updated_products['delete_ids_count'] ) . '<br />Total items to update: ' . $last_updated_products['update_ids_count'] . '<br /> Total items to delete: ' . $last_updated_products['delete_ids_count'] . '<br />', 'wdm-ebridge-woocommerce-sync' );

				wp_send_json_success( $response );
			} //elseif ( $selected_sync_type === 'sync_batched' ) {
			// $updated_products    = $this->products->get_batched_product_ids();
			// $response            = $updated_products;
			// $response['message'] = __( 'Total items found: ' . ( $updated_products['update_ids_count'] + $updated_products['delete_ids_count'] ) . '<br />Total items to update: ' . $updated_products['update_ids_count'] . '<br /> Total items to delete: ' . $updated_products['delete_ids_count'] . '<br />', 'wdm-ebridge-woocommerce-sync' );
			// wp_send_json_success( $response );
			// }
		}

		$response['message'] = __( 'Invalid option selected.', 'wdm-ebridge-woocommerce-sync' );

		wp_send_json_error( $response );
	}

	public function update_product() {
		$response = array();

		if ( isset( $_POST['product_id'] ) ) {
			$product_id = $_POST['product_id'];
			$product    = $this->products->create_product( $product_id );

			if ( $product ) {
				$response['message'] = __( "Updated product $product_id as $product.", 'wdm-ebridge-woocommerce-sync' );
				wp_send_json_success( $response );
			}

			$response['message'] = __( "Failed to update product $product_id.", 'wdm-ebridge-woocommerce-sync' );
			wp_send_json_error( $response );

		}

		$response['message'] = __( 'Invalid product id.', 'wdm-ebridge-woocommerce-sync' );

		wp_send_json_error( $response );
	}


	public function delete_product() {
		$response = array();

		if ( isset( $_POST['product_id'] ) ) {
			$product_id = $_POST['product_id'];
			$product    = $this->products->delete_product( $product_id );

			if ( $product ) {
				$response['message'] = __( "Updated product $product_id as $product.", 'wdm-ebridge-woocommerce-sync' );
				wp_send_json_success( $response );
			}

			$response['message'] = __( "Failed to delete product $product_id.", 'wdm-ebridge-woocommerce-sync' );
			wp_send_json_error( $response );

		}

		$response['message'] = __( 'Invalid product id.', 'wdm-ebridge-woocommerce-sync' );

		wp_send_json_error( $response );
	}


	public function register_sync_products_bulk_action( $bulk_actions ) {
		$bulk_actions['wews_sync'] = __( 'Sync', 'wdm-ebridge-woocommerce-sync' );
		return $bulk_actions;
	}


	public function sync_products_bulk_action_handler( $redirect_to, $doaction, $product_ids ) {
		if ( $doaction !== 'wews_sync' ) {
			return $redirect_to;
		}

		$skus = array();

		foreach ( $product_ids as $key => $product_id ) {
			$product = new WC_Product( $product_id );
			$skus[]  = $product->get_sku();
		}
		$redirect_to = admin_url() . 'admin.php?page=ebridge_sync_product_sync';

		$redirect_to = add_query_arg( 'product_ids', $skus, $redirect_to );
		$redirect_to = add_query_arg( 'product_id_count', count( $skus ), $redirect_to );

		return $redirect_to;
	}
}
