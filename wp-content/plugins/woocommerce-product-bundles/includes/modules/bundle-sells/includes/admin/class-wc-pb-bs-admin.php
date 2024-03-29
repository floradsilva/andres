<?php
/**
 * WC_PB_BS_Admin class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    5.8.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin functions and filters.
 *
 * @class    WC_PB_BS_Admin
 * @version  5.8.0
 */
class WC_PB_BS_Admin {

	/**
	 * Setup hooks.
	 */
	public static function init() {

		// Display Bundle-Sells multi-select.
		add_action( 'woocommerce_product_options_related', array( __CLASS__, 'bundle_sells_options' ) );

		// Save posted Bundle-Sells.
		add_action( 'woocommerce_admin_process_product_object', array( __CLASS__, 'process_bundle_sells_options' ) );

		// Ajax search bundle-sells. Only simple products are allowed for now.
		add_action( 'wp_ajax_woocommerce_json_search_bundle_sells', array( __CLASS__, 'ajax_search_bundle_sells' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Filter hooks.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Display Bundle-Sells multiselect.
	 */
	public static function bundle_sells_options() {

		global $product_object;

		?>
		<div class="options_group hide_if_grouped hide_if_external hide_if_bundle">
			<p class="form-field ">
				<label for="crosssell_ids"><?php _e( 'Bundle-sells', 'woocommerce-product-bundles' ); ?></label>
				<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="bundle_sell_ids" name="bundle_sell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_bundle_sells" data-exclude="<?php echo intval( $product_object->get_id() ); ?>" data-limit="100" data-sortable="true">
					<?php

						$product_ids = WC_PB_BS_Product::get_bundle_sell_ids( $product_object, 'edit' );

						if ( ! empty( $product_ids ) ) {
							foreach ( $product_ids as $product_id ) {

								$product = wc_get_product( $product_id );

								if ( is_object( $product ) ) {
									echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
								}
							}
						}
					?>
				</select> <?php echo wc_help_tip( __( 'Bundle-sells are optional products that can be selected and added to the cart along with this product.', 'woocommerce-product-bundles' ) ); ?>
			</p>
			<?php

				woocommerce_wp_textarea_input( array(
					'id'            => 'wc_pb_bundle_sells_title',
					'value'         => esc_html( WC_PB_BS_Product::get_bundle_sells_title( $product_object, 'edit' ) ),
					'label'         => __( 'Bundle-sells title', 'woocommerce-product-bundles' ),
					'description'   => __( 'Text to display above the Bundle-Sells section.', 'woocommerce-product-bundles' ),
					'placeholder'   => __( 'e.g. "Frequently Bought Together"', 'woocommerce-product-bundles' ),
					'desc_tip'      => true
				) );
			?>
		</div>
		<?php
	}

	/**
	 * Process and save posted Bundle-Sells.
	 */
	public static function process_bundle_sells_options( $product ) {

		/*
		 * Process bundle-sell IDs.
		 */

		$bundle_sell_ids = ! empty( $_POST[ 'bundle_sell_ids' ] ) && is_array( $_POST[ 'bundle_sell_ids' ] ) ? array_map( 'intval', (array) $_POST[ 'bundle_sell_ids' ] ) : array();

		if ( ! empty( $bundle_sell_ids ) ) {
			$product->update_meta_data( '_wc_pb_bundle_sell_ids', $bundle_sell_ids );
		} else {
			$product->delete_meta_data( '_wc_pb_bundle_sell_ids' );
		}

		/*
		 * Process bundle-sells title.
		 */

		$title = ! empty( $_POST[ 'wc_pb_bundle_sells_title' ] ) ? $_POST[ 'wc_pb_bundle_sells_title' ] : false;

		if ( $title ) {
			$product->update_meta_data( '_wc_pb_bundle_sells_title', wp_kses_post( stripslashes( $title ) ) );
		} else {
			$product->delete_meta_data( '_wc_pb_bundle_sells_title' );
		}

	}

	/**
	 * Ajax search for bundled variations.
	 */
	public static function ajax_search_bundle_sells() {

		add_filter( 'woocommerce_json_search_found_products', array( __CLASS__, 'filter_ajax_search_results' ) );
		WC_AJAX::json_search_products( '', false );
		remove_filter( 'woocommerce_json_search_found_products', array( __CLASS__, 'filter_ajax_search_results' ) );
	}

	/**
	 * Include only simple products in bundle-sell results.
	 *
	 * @param  array  $search_results
	 * @return array
	 */
	public static function filter_ajax_search_results( $search_results ) {

		if ( ! empty( $search_results ) ) {

			$search_results_filtered = array();

			foreach ( $search_results as $product_id => $product_title ) {

				$product = wc_get_product( $product_id );

				if ( is_object( $product ) && $product->is_type( array( 'simple', 'subscription' ) ) ) {
					$search_results_filtered[ $product_id ] = $product_title;
				}
			}

			$search_results = $search_results_filtered;
		}

		return $search_results;
	}
}

WC_PB_BS_Admin::init();
