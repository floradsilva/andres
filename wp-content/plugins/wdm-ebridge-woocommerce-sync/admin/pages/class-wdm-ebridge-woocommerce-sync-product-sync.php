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
					<input type="submit" id="product_sync_submit" name="product_sync_submit" class="button button-primary" value="<?php _e( 'Sync Products', 'wdm-ebridge-woocommerce-sync' ); ?>">
				</div>
			</form>

			<div id="message-wrap">
				<h3>Logs:</h3>
				<p id="message"><?php echo __('Logs', 'wdm-ebridge-woocommerce-sync'); ?></p>
			</div>

		<?php
	}

	public function setup_sections() {
	}

	public function ebridge_sync_product_attributes_callback() {
	}
}
