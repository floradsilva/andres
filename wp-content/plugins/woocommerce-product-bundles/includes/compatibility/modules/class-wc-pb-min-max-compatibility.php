<?php
/**
 * WC_PB_Min_Max_Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    5.6.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Min/Max Quantities Compatibility.
 *
 * @version  5.6.1
 */
class WC_PB_Min_Max_Compatibility {

	/**
	 * The bundled item object whose qty input is being filtered.
	 * @var WC_Bundled_Item
	 */
	public static $bundled_item = false;

	/**
	 * Unfiltered quantity input data used at restoration time.
	 * @var array
	 */
	public static $unfiltered_args = false;

	/**
	 * Initilize hooks.
	 */
	public static function init() {

		// Set global $bundled_item variable.
		add_action( 'woocommerce_after_bundled_item_cart_details', array( __CLASS__, 'restore_quantities_set' ), 9 );
		add_action( 'woocommerce_bundled_item_details', array( __CLASS__, 'restore_quantities_set' ), 34 );

		// Unset global $bundled_item variable.
		add_action( 'woocommerce_after_bundled_item_cart_details', array( __CLASS__, 'restore_quantities_unset' ), 11 );
		add_action( 'woocommerce_bundled_item_details', array( __CLASS__, 'restore_quantities_unset' ), 36 );

		// Restore bundled items quantities to the values they had before min/max interference.
		add_filter( 'woocommerce_quantity_input_args', array( __CLASS__, 'save_quantity_input_args' ), 0, 2 );
		add_filter( 'woocommerce_quantity_input_args', array( __CLASS__, 'restore_quantity_input_args' ), 11, 2 );

		// Double-check variation data quantities to account for "group of" option.
		add_filter( 'woocommerce_available_variation', array( __CLASS__, 'bundled_variation_data' ), 15, 3 );

		// Disable min cart item quantity validation for bundled items.
		add_filter( 'wc_min_max_quantity_minimum_allowed_quantity', array( __CLASS__, 'restore_allowed_quantity' ), 10, 4 );

		// Disable max cart item quantity validation for bundled items.
		add_filter( 'wc_min_max_quantity_maximum_allowed_quantity', array( __CLASS__, 'restore_allowed_quantity' ), 10, 4 );

		// Add bundled item and input cart quantity to the product.
		add_filter( 'woocommerce_cart_item_product', array( __CLASS__, 'add_bundled_item_to_product' ), 10, 3 );
	}

	/**
	 * Set global $bundled_item variable.
	 *
	 * @param  WC_Bundled_Item  $bundled_item
	 * @return void
	 */
	public static function restore_quantities_set( $bundled_item ) {
		self::$bundled_item = $bundled_item;
	}

	/**
	 * Unset global $bundled_item variable.
	 *
	 * @param  WC_Bundled_Item  $bundled_item
	 * @return void
	 */
	public static function restore_quantities_unset( $bundled_item ) {
		self::$bundled_item = false;
	}

	/**
	 * Save unmodified quantity args.
	 *
	 * @param  array   $data
	 * @param  object  $product
	 * @return array
	 */
	public static function save_quantity_input_args( $data, $product ) {

		if ( is_object( self::$bundled_item ) || isset( $product->wc_mmq_bundled_item ) ) {
			self::$unfiltered_args = $data;
		} else {
			self::$unfiltered_args = false;
		}

		return $data;
	}

	/**
	 * Restore min/max bundled item quantities to the values they had before min/max interference.
	 *
	 * @param  array   $data
	 * @param  object  $product
	 * @return array
	 */
	public static function restore_quantity_input_args( $data, $product ) {

		if ( is_array( self::$unfiltered_args ) ) {

			$min_qty      = 0;
			$max_qty      = '';
			$input_qty    = 1;
			$group_of_qty = 0;

			if ( isset( self::$unfiltered_args[ 'min_value' ] ) ) {
				if ( self::$unfiltered_args[ 'min_value' ] > 0 || self::$unfiltered_args[ 'min_value' ] === 0 ) {
					$min_qty = absint( self::$unfiltered_args[ 'min_value' ] );
				}
			} elseif ( isset( $data[ 'min_value' ] ) && ( $data[ 'min_value' ] > 0 || $data[ 'min_value' ] === 0 ) ) {
				$min_qty = absint( $data[ 'min_value' ] );
			}

			if ( isset( self::$unfiltered_args[ 'max_value' ] ) ) {
				if ( self::$unfiltered_args[ 'max_value' ] > 0 || self::$unfiltered_args[ 'max_value' ] === 0 ) {
					$max_qty = absint( self::$unfiltered_args[ 'max_value' ] );
				}
			} elseif ( isset( $data[ 'max_value' ] ) && ( $data[ 'max_value' ] > 0 || $data[ 'max_value' ] === 0 ) ) {
				$max_qty = absint( $data[ 'max_value' ] );
			}

			if ( isset( self::$unfiltered_args[ 'input_value' ] ) ) {
				$input_qty = absint( self::$unfiltered_args[ 'input_value' ] );
			} elseif ( isset( $data[ 'input_value' ] ) ) {
				$input_qty = absint( $data[ 'input_value' ] );
			}


			if ( ! isset( $product->wc_mmq_bundled_item ) ) {

				if ( isset( $data[ 'group_of' ] ) ) {
					$group_of_qty = $data[ 'group_of' ];
				} elseif ( $product instanceof WC_Product ) {
					$group_of_qty = absint( $product->get_meta( 'group_of_quantity', true ) );
				}

				if ( $group_of_qty > 1 ) {
					if ( $min_qty > $group_of_qty ) {
						$modulo  = $min_qty % $group_of_qty;
						$min_qty = $modulo ? $min_qty + $modulo : $min_qty;
					} elseif ( $min_qty > 0 ) {
						$min_qty = max( $min_qty, $group_of_qty );
					}
				}

				$input_qty = max( $input_qty, $min_qty );
			}

			if ( empty( $max_qty ) || $max_qty >= $min_qty ) {
				$data[ 'min_value' ]   = $min_qty;
				$data[ 'max_value' ]   = $max_qty;
				$data[ 'input_value' ] = $input_qty;
			} else {
				$data[ 'min_value' ]   = $min_qty;
				$data[ 'max_value' ]   = $min_qty;
				$data[ 'input_value' ] = $min_qty;
				$data[ 'step' ]        = 1;
			}
		}

		return $data;
	}

	/**
	 * Double-check bundled variation data quantities to account for "group of" option.
	 *
	 * @param  array                 $variation_data
	 * @param  WC_Product            $bundled_product
	 * @param  WC_Product_Variation  $bundled_variation
	 * @return array
	 */
	public static function bundled_variation_data( $variation_data, $bundled_product, $bundled_variation ) {

		if ( $bundled_variation->get_meta( 'min_max_rules', true ) ) {
			$group_of_quantity = $bundled_variation->get_meta( 'variation_group_of_quantity', true );
		} else {
			$group_of_quantity = $bundled_product->get_meta( 'group_of_quantity', true );
		}

		if ( $group_of_quantity > 1 ) {

			$data = array(
				'group_of'    => absint( $group_of_quantity ),
				'min_value'   => $variation_data[ 'min_qty' ],
				'max_value'   => $variation_data[ 'max_qty' ],
				'input_value' => isset( $variation_data[ 'input_value' ] ) ? $variation_data[ 'input_value' ] : $variation_data[ 'min_qty' ]
			);

			self::$unfiltered_args = $data;

			$fixed_args = self::restore_quantity_input_args( $data, false );

			self::$unfiltered_args = false;

			$variation_data[ 'min_qty' ]     = $fixed_args[ 'min_value' ];
			$variation_data[ 'max_qty' ]     = $fixed_args[ 'max_value' ];
			$variation_data[ 'input_value' ] = $fixed_args[ 'input_value' ];
		}

		return $variation_data;
	}

	/**
	 * Restore allowed min/max quantity for bundled items to empty, so min/max cart validation rules do not apply.
	 *
	 * @param  string  $qty_meta
	 * @param  string  $checking_id
	 * @param  string  $cart_item_key
	 * @param  array   $cart_item
	 * @return array
	 */
	public static function restore_allowed_quantity( $qty_meta, $checking_id, $cart_item_key, $cart_item ) {

		if ( wc_pb_is_bundled_cart_item( $cart_item ) ) {
			$qty_meta = '';
		}

		return $qty_meta;
	}

	/**
	 * Add bundled item and input cart quantity to the product.
	 *
	 * @param  WC_Product  $product
	 * @param  array       $cart_item
	 * @param  string      $cart_item_key
	 * @return WC_Product
	 */
	public static function add_bundled_item_to_product( $product, $cart_item, $cart_item_key ) {

		if ( wc_pb_is_bundled_cart_item( $cart_item ) ) {

			if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

				$bundle = $bundle_container_item[ 'data' ];

				if ( 'bundle' === $bundle->get_type() && $bundled_item = $bundle->get_bundled_item( $cart_item[ 'bundled_item_id' ] ) ) {
					$product->wc_mmq_bundled_item = $bundled_item;
				}
			}
		}

		return $product;
	}
}

WC_PB_Min_Max_Compatibility::init();
