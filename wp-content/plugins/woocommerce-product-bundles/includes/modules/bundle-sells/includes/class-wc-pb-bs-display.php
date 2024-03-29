<?php
/**
 * WC_PB_BS_Display class
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
 * Display-related functions and filters.
 *
 * @class    WC_PB_BS_Display
 * @version  5.8.0
 */
class WC_PB_BS_Display {

	/**
	 * Setup hooks.
	 */
	public static function init() {

		// Add hooks to display Bundle-Sells.
		add_action( 'woocommerce_before_add_to_cart_form', array( __CLASS__, 'add_bundle_sells_display_hooks' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Application layer functions.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Adds logic for overriding bundled-item template file locations.
	 *
	 * @return void
	 */
	public static function apply_bundled_item_template_overrides() {
		add_filter( 'woocommerce_locate_template', array( __CLASS__, 'get_bundled_item_template_location' ), 10, 3 );
	}

	/**
	 * Resets all added logic for overriding bundled-item template file locations.
	 *
	 * @return void
	 */
	public static function reset_bundled_item_template_overrides() {
		remove_filter( 'woocommerce_locate_template', array( __CLASS__, 'get_bundled_item_template_location' ), 10, 3 );
	}

	/*
	|--------------------------------------------------------------------------
	| Filter/action hooks.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Add hooks necessary to display Bundle-Sells in single-product templates.
	 */
	public static function add_bundle_sells_display_hooks() {

		global $product;

		if ( $product->is_type( 'variable' ) ) {
			add_action( 'woocommerce_single_variation', array( __CLASS__, 'display_bundle_sells' ), 19 );
		} else {
			add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'display_bundle_sells' ), 1000 );
		}
	}

	/**
	 * Displays Bundle-Sells above the add-to-cart button.
	 *
	 * @return void
	 */
	public static function display_bundle_sells() {

		global $product;

		$bundle_sell_ids = WC_PB_BS_Product::get_bundle_sell_ids( $product );

		if ( ! empty( $bundle_sell_ids ) ) {

			/*
			 * This is not a Bundle-type product.
			 * But if it was, then we could re-use the PB templates... without writing new code.
			 * Let's "fake" it.
			 */
			$bundle = WC_PB_BS_Product::get_bundle( $bundle_sell_ids, $product );

			do_action( 'woocommerce_before_bundled_items', $bundle );

			if ( false === wp_style_is( 'wc-bundle-css', 'enqueued' ) ) {
				wp_enqueue_style( 'wc-bundle-css' );
			}

			if ( false === wp_script_is( 'wc-add-to-cart-bundle', 'enqueued' ) ) {
				wp_enqueue_script( 'wc-add-to-cart-bundle' );
			}

			wp_register_style( 'wc-pb-bs-single', WC_PB()->plugin_url() . '/includes/modules/bundle-sells/assets/css/single-product.css', false, WC_PB()->version, 'all' );
			wp_enqueue_style( 'wc-pb-bs-single' );

			/*
			 * Show Bundle-Sells section title.
			 */
			$bundle_sells_title = WC_PB_BS_Product::get_bundle_sells_title( $product );

			if ( $bundle_sells_title ) {
				wc_get_template( 'single-product/bundle-sells-section-title.php', array(
					'title' => wpautop( do_shortcode( wp_kses_post( $bundle_sells_title ) ) )
				), false, WC_PB()->plugin_path() . '/includes/modules/bundle-sells/templates/' );
			}

			/*
			 * Show Bundle-Sells.
			 */
			?>
			<div class="bundle_form bundle_sells_form"><?php

				foreach ( $bundle->get_bundled_items() as $bundled_item ) {
					// Neat, isn't it?
					self::apply_bundled_item_template_overrides();
					do_action( 'woocommerce_bundled_item_details', $bundled_item, $bundle );
					self::reset_bundled_item_template_overrides();
				}

				?>
				<div class="bundle_data bundle_data_<?php echo $bundle->get_id(); ?>" data-bundle_price_data="<?php echo esc_attr( json_encode( $bundle->get_bundle_price_data() ) ); ?>" data-bundle_id="<?php echo $bundle->get_id(); ?>">
					<div class="bundle_wrap">
						<div class="bundle_error" style="display:none">
							<div class="woocommerce-info">
								<ul class="msg"></ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php

			do_action( 'woocommerce_after_bundled_items', $bundle );
		}
	}

	/**
	 * Filters the default bundled-item template file location for use in bundle-selling context.
	 *
	 * @param  string  $template
	 * @param  string  $template_name
	 * @param  string  $template_path
	 * @return string
	 */
	public static function get_bundled_item_template_location( $template, $template_name, $template_path ) {

		if ( false === strpos( $template_path, WC_PB()->plugin_path() . '/includes/modules/bundle-sells' ) ) {

			if ( 'single-product/bundled-item-quantity.php' === $template_name ) {

				$template = wc_locate_template( 'single-product/bundle-sell-quantity.php', '', WC_PB()->plugin_path() . '/includes/modules/bundle-sells/templates/' );

			} else {

				/**
				 * 'wc_pb_bundle_sell_template_name' filter.
				 *
				 * Use this to override the PB templates with new ones when used in Bundle-Sells context.
				 *
				 * @param  string  $template_name  Original template name.
				 */
				$template_name_override = apply_filters( 'wc_pb_bundle_sell_template_name', $template_name );

				if ( $template_name_override !== $template_name ) {
					$template = wc_locate_template( $template_name_override, '', WC_PB()->plugin_path() . '/includes/modules/bundle-sells/templates/' );
				}
			}
		}

		return $template;
	}
}

WC_PB_BS_Display::init();
