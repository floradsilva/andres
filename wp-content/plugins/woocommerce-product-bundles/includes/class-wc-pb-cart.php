<?php
/**
 * WC_PB_Cart class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.5.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Bundle cart functions and filters.
 *
 * @class    WC_PB_Cart
 * @version  5.13.0
 */
class WC_PB_Cart {

	/**
	 * Validation context for 'validate_bundle_configuration'.
	 * Possible values: 'add-to-cart'|'add-to-order'|'cart'.
	 *
	 * @var string
	 */
	protected $validation_context = null;

	/**
	 * The single instance of the class.
	 * @var WC_PB_Cart
	 *
	 * @since 5.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_PB_Cart instance. Ensures only one instance of WC_PB_Cart is loaded or can be loaded.
	 *
	 * @static
	 * @return WC_PB_Cart
	 * @since  5.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '5.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '5.0.0' );
	}

	/*
	 * Setup hooks.
	 */
	protected function __construct() {

		// Validate bundle add-to-cart.
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_add_to_cart' ), 10, 6 );

		// Validate cart quantity updates.
		add_filter( 'woocommerce_update_cart_validation', array( $this, 'update_cart_validation' ), 10, 4 );

		// Validate bundle configuration in cart.
		add_action( 'woocommerce_check_cart_items', array( $this, 'check_cart_items' ), 15 );

		// Add bundle-specific cart item data based on posted vars.
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 2 );

		// Add bundled items to the cart.
		add_action( 'woocommerce_add_to_cart', array( $this, 'bundle_add_to_cart' ), 10, 6 );

		// Modify cart items for bundled shipping strategy.
		add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item_filter' ), 10, 2 );

		// Load bundle data from session into the cart.
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 3 );

		// Refresh bundle configuration fields.
		add_filter( 'woocommerce_bundle_container_cart_item', array( $this, 'update_bundle_container_cart_item_configuration' ), 10, 2 );
		add_filter( 'woocommerce_bundled_cart_item', array( $this, 'update_bundled_cart_item_configuration' ), 10, 2 );

		// Ensure no orphans are in the cart at this point.
		add_action( 'woocommerce_cart_loaded_from_session', array( $this, 'cart_loaded_from_session' ) );

		// Sync quantities of bundled items with bundle quantity.
		add_action( 'woocommerce_after_cart_item_quantity_update', array( $this, 'update_quantity_in_cart' ), 1, 2 );

		// Ignore 'woocommerce_before_cart_item_quantity_zero' action under WC 3.7+.
		if ( ! WC_PB_Core_Compatibility::is_wc_version_gte( '3.7' ) ) {
			add_action( 'woocommerce_before_cart_item_quantity_zero', array( $this, 'update_quantity_in_cart' ), 1 );
		}

		// Remove bundled items on removing parent item.
		add_action( 'woocommerce_remove_cart_item', array( $this, 'cart_item_remove' ), 10, 2 );
		add_action( 'woocommerce_restore_cart_item', array( $this, 'cart_item_restore' ), 10, 2 );

		// Shipping fix - ensure that non-virtual containers/children, which are shipped, have a valid price that can be used for insurance calculations.
		// Additionally, bundled item weights may have to be added in the container.
		add_filter( 'woocommerce_cart_shipping_packages', array( $this, 'cart_shipping_packages' ), 5 );

		// Remove recurring component of bundled subscription-type products in statically-priced bundles.
		add_action( 'woocommerce_subscription_cart_before_grouping', array( $this, 'add_subcription_filter' ) );
		add_action( 'woocommerce_subscription_cart_after_grouping', array( $this, 'remove_subcription_filter' ) );

		// "Sold Individually" context support under WC 3.5+.
		if ( WC_PB_Core_Compatibility::is_wc_version_gte( '3.5' ) ) {
			add_filter( 'woocommerce_add_to_cart_sold_individually_found_in_cart', array( $this, 'sold_individually_found_in_cart' ), 10, 4 );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| API methods.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Session data loaded?
	 *
	 * @since  5.8.1
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function is_cart_session_loaded() {
		return did_action( 'woocommerce_cart_loaded_from_session' );
	}

	/**
	 * Gets the current validation context.
	 *
	 * @return string|null
	 */
	public function get_validation_context() {
		return $this->validation_context;
	}

	/**
	 * Validates and adds a bundle to the cart. Relies on specifying a bundle configuration array with all necessary data - @see 'get_posted_bundle_configuration()' for details.
	 *
	 * @param  mixed  $product_id      Id of the bundle to add to the cart.
	 * @param  mixed  $quantity        Quantity of the bundle.
	 * @param  array  $configuration   Bundle configuration - @see 'get_posted_bundle_configuration()'.
	 * @param  array  $cart_item_data  Custom cart item data to pass to 'WC_Cart::add_to_cart()'.
	 * @return string|WP_Error
	 */
	public function add_bundle_to_cart( $product_id, $quantity, $configuration = array(), $cart_item_data = array() ) {

		$bundle        = wc_get_product( $product_id );
		$added_to_cart = false;

		if ( $bundle ) {

			if ( $this->validate_bundle_configuration( $bundle, $quantity, $configuration ) ) {
				$added_to_cart = WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), array_merge( $cart_item_data, array( 'stamp' => $configuration, 'bundled_items' => array() ) ) );
			} else {

				// No other way to collect notices reliably, including notices from 3rd party extensions.
				$notices = wc_get_notices( 'error' );
				$message = __( 'The submitted bundle configuration could not be added to the cart.', 'woocommerce-product-bundles' );

				$added_to_cart = new WP_Error( 'woocommerce_bundle_configuration_invalid', $message, array( 'notices' => $notices ) );
			}

		} else {
			$message       = __( 'A bundle with this ID does not exist.', 'woocommerce-product-bundles' );
			$added_to_cart = new WP_Error( 'woocommerce_bundle_invalid', $message );
		}

		return $added_to_cart;
	}

	/**
	 * Parses a bundle configuration array to ensure that all mandatory cart item data fields are present.
	 * Can also be used to get an array with the minimum required data to fill in before calling 'add_bundle_to_cart'.
	 *
	 * @param  WC_Product_Bundle  $bundle         Product bundle whose configuration is being parsed or generated.
	 * @param  array              $configuration  Initial configuration array to parse. Leave empty to get a minimum array that you can fill with data - @see 'get_posted_bundle_configuration()'.
	 * @param  boolean            $strict_mode    Set true to initialize bundled product IDs to an empty string if undefined in the source array.
	 * @return array
	 */
	public function parse_bundle_configuration( $bundle, $configuration = array(), $strict_mode = false ) {

		$bundled_items       = $bundle->get_bundled_items();
		$parsed_configuration = array();

		foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

			$item_configuration = isset( $configuration[ $bundled_item_id ] ) ? $configuration[ $bundled_item_id ] : array();

			$defaults = array(
				'product_id' => $strict_mode ? '' : $bundled_item->get_product_id(),
				'quantity'   => $bundled_item->get_quantity( 'min' )
			);

			$parsed_configuration[ $bundled_item_id ] = wp_parse_args( $item_configuration, $defaults );

			$parsed_configuration[ $bundled_item_id ][ 'discount' ] = $bundled_item->get_discount();

			if ( $bundled_item->has_title_override() ) {
				$parsed_configuration[ $bundled_item_id ][ 'title' ] = isset( $item_configuration[ 'title' ] ) ? $item_configuration[ 'title' ] : $bundled_item->get_raw_title();
			}
		}

		return $parsed_configuration;
	}

	/**
	 * Build bundle configuration array from posted data. Array example:
	 *
	 *    $config = array(
	 *        134 => array(                             // ID of bundled item.
	 *            'product_id'        => 15,            // ID of bundled product.
	 *            'quantity'          => 2,             // Qty of bundled product, will fall back to min.
	 *            'discount'          => 50.0,          // Bundled product discount, defaults to the defined value.
	 *            'title'             => 'Test',        // Bundled product title, include only if overriding.
	 *            'optional_selected' => 'yes',         // If the bundled item is optional, indicate if chosen or not.
	 *            'attributes'        => array(         // Array of selected variation attribute names, sanitized.
	 *                'attribute_color' => 'black',
	 *                'attribute_size'  => 'medium'
	 *             ),
	 *            'variation_id'      => 43             // ID of chosen variation, if applicable.
	 *        )
	 *    );
	 *
	 * @param  mixed  $product
	 * @return array
	 */
	public function get_posted_bundle_configuration( $product ) {

		$posted_config = array();

		if ( is_numeric( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( is_object( $product ) && $product->is_type( 'bundle' ) ) {

			$product_id    = $product->get_id();
			$bundled_items = $product->get_bundled_items();

			if ( ! empty( $bundled_items ) ) {

				/*
				 * Choose between $_POST or $_GET for grabbing data.
				 * We will not rely on $_REQUEST because checkbox names may not exist in $_POST but they may well exist in $_GET, for instance when editing a bundle from the cart.
				 */

				$posted_data = $_POST;

				if ( empty( $_POST[ 'add-to-cart' ] ) && ! empty( $_GET[ 'add-to-cart' ] ) ) {
					$posted_data = $_GET;
				}

				/**
				 * 'woocommerce_product_bundle_field_prefix' filter.
				 *
				 * Used to post unique bundle data when posting multiple bundle configurations that could include the same bundle multiple times.
				 *
				 * @param  string  $prefix
				 * @param  mixed   $product_id
				 */
				$posted_field_prefix = apply_filters( 'woocommerce_product_bundle_field_prefix', '', $product_id );

				foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

					$posted_config[ $bundled_item_id ] = array();


					$bundled_product_id   = $bundled_item->get_product_id();
					$bundled_product_type = $bundled_item->product->get_type();
					$is_optional          = $bundled_item->is_optional();

					$bundled_item_quantity_request_key = $posted_field_prefix . 'bundle_quantity_' . $bundled_item_id;
					$bundled_product_qty               = isset( $posted_data[ $bundled_item_quantity_request_key ] ) ? absint( $posted_data[ $bundled_item_quantity_request_key ] ) : $bundled_item->get_quantity();

					$posted_config[ $bundled_item_id ][ 'product_id' ] = $bundled_product_id;

					if ( $bundled_item->has_title_override() ) {
						$posted_config[ $bundled_item_id ][ 'title' ] = $bundled_item->get_raw_title();
					}

					if ( $is_optional ) {

						$bundled_item_selected_request_key = $posted_field_prefix . 'bundle_selected_optional_' . $bundled_item_id;

						$posted_config[ $bundled_item_id ][ 'optional_selected' ] = isset( $posted_data[ $bundled_item_selected_request_key ] ) ? 'yes' : 'no';

						if ( 'no' === $posted_config[ $bundled_item_id ][ 'optional_selected' ] ) {
							$bundled_product_qty = 0;
						}
					}

					$posted_config[ $bundled_item_id ][ 'quantity' ] = $bundled_product_qty;

					// Store variable product configuration in stamp to avoid generating the same bundle cart id.
					if ( 'variable' === $bundled_product_type || 'variable-subscription' === $bundled_product_type ) {

						$attributes = $bundled_item->product->get_attributes();
						$variations = $bundled_item->get_children();
						$attr_stamp = array();

						// Store posted attribute values.
						foreach ( $attributes as $attribute ) {

							if ( ! $attribute->get_variation() ) {
								continue;
							}

							$attribute_name = $attribute->get_name();
							$taxonomy       = wc_variation_attribute_name( $attribute_name );

							$bundled_item_taxonomy_request_key = $posted_field_prefix . 'bundle_' . $taxonomy . '_' . $bundled_item_id;

							// Get value from post data.
							if ( isset( $posted_data[ $bundled_item_taxonomy_request_key ] ) && '' !== $posted_data[ $bundled_item_taxonomy_request_key ] ) {

								if ( $attribute->is_taxonomy() ) {
									$value = sanitize_title( stripslashes( $posted_data[ $bundled_item_taxonomy_request_key ] ) );
								} else {
									$value = html_entity_decode( wc_clean( stripslashes( $posted_data[ $bundled_item_taxonomy_request_key ] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
								}

								$attr_stamp[ $taxonomy ] = $value;

							// Value pre-selected?
							} else {

								$configurable_variation_attributes  = $bundled_item->get_product_variation_attributes( true );
								$selected_variation_attribute_value = $bundled_item->get_selected_product_variation_attribute( $attribute_name );

								if ( ! isset( $configurable_variation_attributes[ $attribute_name ] ) && '' !== $selected_variation_attribute_value ) {

									if ( $attribute->is_taxonomy() ) {

										foreach ( $attribute->get_terms() as $option ) {

											if ( $option->slug === sanitize_title( $selected_variation_attribute_value ) ) {
												$attr_stamp[ $taxonomy ] = $option->slug;
												break;
											}
										}

									} else {

										foreach ( $attribute->get_options() as $option ) {

											if ( sanitize_title( $selected_variation_attribute_value ) === $selected_variation_attribute_value ) {
												$found = $selected_variation_attribute_value === sanitize_title( $option );
											} else {
												$found = $selected_variation_attribute_value === $option;
											}

											if ( $found ) {
												$attr_stamp[ $taxonomy ] = $option;
												break;
											}
										}
									}
								}
							}
						}

						$posted_config[ $bundled_item_id ][ 'attributes' ] = $attr_stamp;

						// Store posted variation ID, or search for it.
						if ( sizeof( $variations ) > 1 ) {

							$bundled_item_variation_id_request_key = $posted_field_prefix . 'bundle_variation_id_' . $bundled_item_id;

							if ( ! empty( $posted_data[ $bundled_item_variation_id_request_key ] ) ) {

								$posted_config[ $bundled_item_id ][ 'variation_id' ] = $posted_data[ $bundled_item_variation_id_request_key ];

							} else {

								$data_store = WC_Data_Store::load( 'product' );

								if ( $found_variation_id = $data_store->find_matching_product_variation( $bundled_item->get_product(), $posted_config[ $bundled_item_id ][ 'attributes' ] ) ) {
									$posted_config[ $bundled_item_id ][ 'variation_id' ] = $found_variation_id;
								}
							}

						} else {

							$posted_config[ $bundled_item_id ][ 'variation_id' ] = current( $variations );
						}
					}
				}
			}
		}

		$posted_config = $this->parse_bundle_configuration( $product, $posted_config, true );

		return $posted_config;
	}

	/**
	 * Rebuilds posted form data associated with a bundle configuration.
	 *
	 * @since  5.8.0
	 *
	 * @param  WC_Product_Bundle  $bundle
	 * @param  array              $configuration
	 * @return boolean
	 */
	public function rebuild_posted_bundle_form_data( $configuration ) {

		$form_data = array();

		if ( ! empty( $configuration ) ) {
			foreach ( $configuration as $bundled_item_id => $bundled_item_configuration ) {

				if ( isset( $bundled_item_configuration[ 'optional_selected' ] ) ) {
					if ( 'yes' === $bundled_item_configuration[ 'optional_selected' ] ) {
						$form_data[ 'bundle_selected_optional_' . $bundled_item_id ] = $bundled_item_configuration[ 'optional_selected' ];
					} else {
						continue;
					}
				}

				if ( isset( $bundled_item_configuration[ 'quantity' ] ) ) {
					$form_data[ 'bundle_quantity_' . $bundled_item_id ] = $bundled_item_configuration[ 'quantity' ];
				}

				if ( isset( $bundled_item_configuration[ 'variation_id' ] ) ) {
					$form_data[ 'bundle_variation_id_' . $bundled_item_id ] = $bundled_item_configuration[ 'variation_id' ];
				}

				if ( isset( $bundled_item_configuration[ 'attributes' ] ) && is_array( $bundled_item_configuration[ 'attributes' ] ) ) {
					foreach ( $bundled_item_configuration[ 'attributes' ] as $tax => $val ) {
						$form_data[ 'bundle_' . $tax . '_' . $bundled_item_id ] = $val;
					}
				}
			}
		}

		/**
		 * 'woocommerce_rebuild_posted_bundle_form_data' filter.
		 *
		 * @since  5.8.0
		 *
		 * @param  array  $form_data
		 * @param  array  $configuration
		 */
		return apply_filters( 'woocommerce_rebuild_posted_bundle_form_data', $form_data, $configuration );
	}

	/**
	 * Validates the selected bundled items in a bundle configuration.
	 *
	 * @param  mixed   $product
	 * @param  int     $product_quantity
	 * @param  array   $configuration
	 * @param  string  $context
	 * @return boolean
	 */
	public function validate_bundle_configuration( $product, $product_quantity, $configuration, $context = '' ) {

		$is_configuration_valid = true;

		if ( is_numeric( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( is_object( $product ) && $product->is_type( 'bundle' ) ) {

			try {

				if ( '' === $context ) {

					/**
					 * 'woocommerce_bundle_validation_context' filter.
					 *
					 * @since  5.7.4
					 *
					 * @param  string             $context
					 * @param  WC_Product_Bundle  $context
					 */
					$context = apply_filters( 'woocommerce_bundle_validation_context', 'add-to-cart', $product );
				}

				$this->validation_context = $context;

				$product_id    = $product->get_id();
				$product_title = $product->get_title();

				// If a stock-managed product / variation exists in the bundle multiple times, its stock will be checked only once for the sum of all bundled quantities.
				// The stock manager class keeps a record of stock-managed product / variation ids.
				$bundled_stock = new WC_PB_Stock_Manager( $product );

				// Grab bundled items.
				$bundled_items = $product->get_bundled_items();

				if ( sizeof( $bundled_items ) ) {

					foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

						$bundled_product_id   = $bundled_item->get_product_id();
						$bundled_variation_id = '';
						$bundled_product_type = $bundled_item->product->get_type();

						// Optional.
						$is_optional           = $bundled_item->is_optional();
						$is_optional_selected  = $is_optional && isset( $configuration[ $bundled_item_id ][ 'optional_selected' ] ) && 'yes' === $configuration[ $bundled_item_id ][ 'optional_selected' ];

						if ( $is_optional && ! $is_optional_selected ) {
							continue;
						}

						// Check existence.
						if ( 'cart' === $context ) {

							$missing_contents = false;

							if ( ! isset( $configuration[ $bundled_item_id ] ) || empty( $configuration[ $bundled_item_id ][ 'product_id' ] ) ) {
								$missing_contents = true;
							} elseif ( isset( $configuration[ $bundled_item_id ][ 'optional_selected' ] ) && 'no' === $configuration[ $bundled_item_id ][ 'optional_selected' ] ) {
								$missing_contents = true;
							}

							if ( $missing_contents ) {

								$notice = sprintf( __( '&quot;%1$s&quot; cannot be purchased &ndash; some of its contents are missing from your cart.', 'woocommerce-product-bundles' ), $product_title );

								throw new Exception( $notice );
							}
						}

						// Check quantity.
						$item_quantity_min = $bundled_item->get_quantity();
						$item_quantity_max = $bundled_item->get_quantity( 'max' );

						if ( isset( $configuration[ $bundled_item_id ][ 'quantity' ] ) ) {
							$item_quantity = absint( $configuration[ $bundled_item_id ][ 'quantity' ] );
						} else {
							$item_quantity = $item_quantity_min;
						}

						if ( $item_quantity < $item_quantity_min ) {

							$reason = sprintf( __( 'The quantity of &quot;%1$s&quot; cannot be lower than %2$d.', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title(), $item_quantity_min );

							if ( 'add-to-cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be added to your cart. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} elseif ( 'cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be purchased. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} else {
								$notice = $reason;
							}

							throw new Exception( $notice );

						} elseif ( $item_quantity_max && $item_quantity > $item_quantity_max ) {

							$reason = sprintf( __( 'The quantity of &quot;%1$s&quot; cannot be higher than %2$d.', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title(), $item_quantity_max );

							if ( 'add-to-cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be added to your cart. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} elseif ( 'cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be purchased. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} else {
								$notice = $reason;
							}

							throw new Exception( $notice );
						}

						$quantity = $bundled_item->is_sold_individually() && $item_quantity <= 1 ? 1 : $item_quantity * $product_quantity;

						// If quantity is zero, continue.
						if ( $quantity == 0 ) {
							continue;
						}

						// Purchasable?
						if ( false === $bundled_item->is_purchasable() ) {

							$reason = sprintf( __( '&quot;%s&quot; cannot be purchased.', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title() );

							if ( 'add-to-cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be added to your cart. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} else {
								$notice = $reason;
							}

							throw new Exception( $notice );
						}

						// Validate variation id.
						if ( 'variable' === $bundled_product_type || 'variable-subscription' === $bundled_product_type ) {

							$bundled_variation_id = isset( $configuration[ $bundled_item_id ][ 'variation_id' ] ) ? $configuration[ $bundled_item_id ][ 'variation_id' ] : '';
							$bundled_variation    = $bundled_variation_id ? wc_get_product( $bundled_variation_id ) : false;

							if ( $bundled_variation ) {

								$is_variation_excluded = $bundled_item->has_filtered_variations() && ! in_array( $bundled_variation_id, $bundled_item->get_filtered_variations() );

								if ( $is_variation_excluded || $bundled_variation->get_parent_id() !== absint( $bundled_product_id ) || false === $bundled_variation->is_purchasable() ) {

									if ( 'add-to-cart' === $context ) {
										$reason = sprintf( __( 'The chosen &quot;%s&quot; variation cannot be purchased.', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title() );
									} else {
										$reason = sprintf( __( 'The chosen &quot;%s&quot; variation is unavailable.', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title() );
									}

									if ( 'add-to-cart' === $context ) {
										$notice = sprintf( __( '&quot;%1$s&quot; cannot be added to your cart. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
									} elseif ( 'cart' === $context ) {
										$notice = sprintf( __( '&quot;%1$s&quot; cannot be purchased. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
									} else {
										$notice = $reason;
									}

									throw new Exception( $notice );
								}

								// Add item for validation.
								$bundled_stock->add_item( $bundled_product_id, $bundled_variation, $quantity, array( 'bundled_item' => $bundled_item ) );
							}

							// Verify all attributes for the variable product were set.
							$attributes         = $bundled_item->product->get_attributes();
							$variation_data     = array();
							$missing_attributes = array();
							$all_set            = true;

							if ( $bundled_variation ) {

								$variation_data = wc_get_product_variation_attributes( $bundled_variation_id );

								// Verify all attributes.
								foreach ( $attributes as $attribute ) {

									if ( ! $attribute->get_variation() ) {
										continue;
									}

									$attribute_name = $attribute->get_name();
									$taxonomy       = wc_variation_attribute_name( $attribute_name );

									if ( isset( $configuration[ $bundled_item_id ][ 'attributes' ][ $taxonomy ] ) && isset( $configuration[ $bundled_item_id ][ 'variation_id' ] ) ) {

										$valid_value = isset( $variation_data[ $taxonomy ] ) ? $variation_data[ $taxonomy ] : '';

										if ( '' === $valid_value || $valid_value === $configuration[ $bundled_item_id ][ 'attributes' ][ $taxonomy ] ) {
											continue;
										}

										$missing_attributes[] = '&quot;' . wc_attribute_label( $attribute_name ) . '&quot;';

									} else {
										$missing_attributes[] = '&quot;' . wc_attribute_label( $attribute_name ) . '&quot;';
									}

									$all_set = false;
								}

							} else {
								$all_set = false;
							}

							if ( ! $all_set ) {

								if ( $missing_attributes ) {
									$reason = sprintf( _n( '%1$s is a required &quot;%2$s&quot; field.', '%1$s are required &quot;%2$s&quot; fields.', sizeof( $missing_attributes ), 'woocommerce-product-bundles' ), wc_format_list_of_items( $missing_attributes ), $bundled_item->get_raw_title() );
								} else {
									if ( 'add-to-cart' === $context ) {
										$reason = sprintf( __( 'Please choose &quot;%s&quot; options&hellip;', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title() );
									} else {
										$reason = sprintf( __( '&quot;%s&quot; is missing some required options.', 'woocommerce-product-bundles' ), $bundled_item->get_raw_title() );
									}
								}

								if ( 'add-to-cart' === $context ) {
									$notice = sprintf( __( '&quot;%1$s&quot; cannot be added to your cart. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
								} elseif ( 'cart' === $context ) {
									$notice = sprintf( __( '&quot;%1$s&quot; cannot be purchased. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
								} else {
									$notice = $reason;
								}

								throw new Exception( $notice );
							}

						} elseif ( 'simple' === $bundled_product_type || 'subscription' === $bundled_product_type ) {

							// Add item for validation.
							$bundled_stock->add_item( $bundled_product_id, false, $quantity, array( 'bundled_item' => $bundled_item ) );
						}

						/**
						 * Perform additional validation checks at bundled item level.
						 *
						 * @param  boolean          $is_configuration_valid
						 * @param  WC_Product       $product
						 * @param  WC_Bundled_Item  $bundled_item
						 * @param  int              $quantity
						 * @param  mixed            $bundled_variation_id
						 * @param  array            $configuration
						 */
						if ( false === apply_filters( 'woocommerce_bundled_item_' . str_replace( '-', '_', $context ) . '_validation', $is_configuration_valid, $product, $bundled_item, $quantity, $bundled_variation_id, $configuration ) ) {
							$is_configuration_valid = false;
							break;
						}
					}
				}

				if ( $is_configuration_valid ) {

					if ( ! empty( $bundled_items ) && false === WC_Product_Bundle::group_mode_has( $product->get_group_mode(), 'parent_item' ) ) {

						$items_added = $bundled_stock->get_items();

						if ( empty( $items_added ) ) {

							$reason = __( 'Please choose at least 1 item.', 'woocommerce-product-bundles' );

							if ( 'add-to-cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be added to your cart. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} elseif ( 'cart' === $context ) {
								$notice = sprintf( __( '&quot;%1$s&quot; cannot be purchased. %2$s', 'woocommerce-product-bundles' ), $product_title, $reason );
							} else {
								$notice = $reason;
							}

							throw new Exception( $notice );
						}
					}

					// Check stock for stock-managed bundled items when adding to cart. If out of stock, don't proceed.
					if ( 'add-to-cart' === $context ) {
						$is_configuration_valid = $bundled_stock->validate_stock( array(
							'context'         => $context,
							'throw_exception' => true
						) );
					}

					/**
					 * Perform additional validation checks at bundle level.
					 *
					 * @param  boolean              $result
					 * @param  mixed                $product_id
					 * @param  WC_PB_Stock_Manager  $bundled_stock
					 * @param  array                $configuration
					 */
					$is_configuration_valid = apply_filters( 'woocommerce_' . str_replace( '-', '_', $context ) . '_bundle_validation', $is_configuration_valid, $product_id, $bundled_stock, $configuration );
				}

			} catch ( Exception $e ) {

				if ( ! WC_PB_Core_Compatibility::is_rest_api_request() ) {

					$notice = $e->getMessage();

					if ( $notice ) {
						wc_add_notice( $notice, 'error' );
					}
				}

				$is_configuration_valid = false;
			}
		}

		$this->validation_context = null;

		return $is_configuration_valid;
	}

	/**
	 * Analyzes bundled cart items to characterize a bundle.
	 *
	 * @since  5.8.0
	 *
	 * @param  array   $cart_item
	 * @param  string  $key
	 * @return bool
	 */
	public function container_cart_item_contains( $cart_item, $key ) {

		$bundled_items = wc_pb_get_bundled_cart_items( $cart_item );
		$contains      = false;

		foreach ( $bundled_items as $bundled_item_key => $bundled_item ) {
			if ( 'sold_individually' === $key ) {
				if ( $bundled_item[ 'data' ]->is_sold_individually() ) {
					$contains = true;
					break;
				}
			}
		}

		return $contains;
	}

	/**
	 * When a bundle is static-priced, the price of all bundled items is set to 0.
	 * When the shipping mode is set to "bundled", all bundled items are marked as virtual when they are added to the cart.
	 * Otherwise, the container itself is a virtual product in the first place.
	 *
	 * @param  array              $cart_item
	 * @param  WC_Product_Bundle  $bundle
	 * @return array
	 */
	private function set_bundled_cart_item( $cart_item, $bundle ) {

		$bundled_item_id = $cart_item[ 'bundled_item_id' ];
		$bundled_item    = $bundle->get_bundled_item( $bundled_item_id );

		if ( $bundled_item ) {
			if ( false === $bundled_item->is_priced_individually() ) {

				$cart_item[ 'data' ]->set_regular_price( 0 );
				$cart_item[ 'data' ]->set_price( 0 );
				$cart_item[ 'data' ]->set_sale_price( '' );

				if ( WC_PB()->compatibility->is_subscription( $cart_item[ 'data' ] ) ) {

					if ( $cart_item[ 'data' ]->meta_exists( '_subscription_sign_up_fee' ) ) {
						$cart_item[ 'data' ]->update_meta_data( '_subscription_sign_up_fee', 0 );
					}

					$cart_item[ 'data' ]->wc_pb_block_sub = 'yes';
				}

			} else {

				$cart_item[ 'data' ]->set_price( $bundled_item->get_raw_price( $cart_item[ 'data' ], 'cart' ) );

				if ( $bundled_item->is_on_sale( 'cart' ) ) {
					$cart_item[ 'data' ]->set_sale_price( $cart_item[ 'data' ]->get_price( 'edit' ) );
				}
			}

			if ( $bundled_item->has_title_override() ) {

				$title = isset( $cart_item[ 'stamp' ][ $bundled_item_id ][ 'title' ] ) ? $cart_item[ 'stamp' ][ $bundled_item_id ][ 'title' ] : $bundled_item->get_raw_title();
				$cart_item[ 'data' ]->set_name( $title );
			}
		}

		if ( $cart_item[ 'data' ]->needs_shipping() ) {

			if ( false === $bundled_item->is_shipped_individually( $cart_item[ 'data' ] ) ) {

				/**
				 * 'woocommerce_bundled_item_has_bundled_weight' filter.
				 *
				 * When the shipping properties of a bundled product are overridden by its container ("Shipped Individually" option unchecked), the bundle container item weight is assumed static and the bundled product weight is ignored.
				 * You can use this filter to have the weight of bundled items appended to the container weight, instead of ignored, when the "Shipped Individually" option is unchecked.
				 *
				 * @param  boolean            $append_weight
				 * @param  WC_Product         $data
				 * @param  mixed              $bundled_item_id
				 * @param  WC_Product_Bundle  $bundle
				 */
				if ( apply_filters( 'woocommerce_bundled_item_has_bundled_weight', false, $cart_item[ 'data' ], $bundled_item_id, $bundle ) ) {

					$cart_item_weight = $cart_item[ 'data' ]->get_weight( 'edit' );

					if ( $cart_item[ 'data' ]->is_type( 'variation' ) && '' === $cart_item_weight ) {

						$parent_data      = $cart_item[ 'data' ]->get_parent_data();
						$cart_item_weight = $parent_data[ 'weight' ];
					}

					$cart_item[ 'data' ]->add_meta_data( '_wc_pb_bundled_weight', $cart_item_weight, true );
				}

				$cart_item[ 'data' ]->add_meta_data( '_wc_pb_bundled_value', $cart_item[ 'data' ]->get_price( 'edit' ), true );

				$cart_item[ 'data' ]->set_virtual( 'yes' );
				$cart_item[ 'data' ]->set_weight( '' );
			}
		}

		/**
		 * 'woocommerce_bundled_cart_item' filter.
		 *
		 * Last chance to filter bundled cart item data.
		 *
		 * @param  array              $cart_item
		 * @param  WC_Product_Bundle  $bundle
		 */
		return apply_filters( 'woocommerce_bundled_cart_item', $cart_item, $bundle );
	}

	/**
	 * Bundle container price must be set equal to the base price when individually-priced items exist.
	 *
	 * @param  array              $cart_item
	 * @param  WC_Product_Bundle  $bundle
	 * @return array
	 */
	private function set_bundle_container_cart_item( $cart_item ) {

		$bundle = $cart_item[ 'data' ];

		$bundle->set_object_context( 'cart' );

		/**
		 * 'woocommerce_bundle_container_cart_item' filter.
		 *
		 * Last chance to filter bundle container cart item data.
		 *
		 * @param  array              $cart_item
		 * @param  WC_Product_Bundle  $bundle
		 */
		return apply_filters( 'woocommerce_bundle_container_cart_item', $cart_item, $bundle );
	}

	/**
	 * Refresh parent item configuration fields that might be out-of-date.
	 *
	 * @param  array              $cart_item
	 * @param  WC_Product_Bundle  $bundle
	 * @return array
	 */
	public function update_bundle_container_cart_item_configuration( $cart_item, $bundle ) {

		if ( isset( $cart_item[ 'stamp' ] ) ) {
			$cart_item[ 'stamp' ] = $this->parse_bundle_configuration( $bundle, $cart_item[ 'stamp' ], true );
		}

		return $cart_item;
	}

	/**
	 * Refresh child item configuration fields that might be out-of-date.
	 *
	 * @param  array              $cart_item
	 * @param  WC_Product_Bundle  $bundle
	 * @return array
	 */
	public function update_bundled_cart_item_configuration( $cart_item, $bundle ) {

		if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {
			$cart_item[ 'stamp' ] = $bundle_container_item[ 'stamp' ];
		}

		return $cart_item;
	}

	/**
	 * Adds a bundled product to the cart. Must be done without updating session data, recalculating totals or calling 'woocommerce_add_to_cart' recursively.
	 * For the recursion issue, see: https://core.trac.wordpress.org/ticket/17817.
	 *
	 * @param  int    $bundle_id
	 * @param  mixed  $product
	 * @param  int    $quantity
	 * @param  int    $variation_id
	 * @param  array  $variation
	 * @param  array  $cart_item_data
	 * @return boolean
	 */
	private function bundled_add_to_cart( $bundle_id, $product, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data ) {

		if ( $quantity <= 0 ) {
			return false;
		}

		// Get the product / ID.
		if ( is_a( $product, 'WC_Product' ) ) {

			$product_id   = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$variation_id = $product->is_type( 'variation' ) ? $product->get_id() : $variation_id;
			$product_data = $product->is_type( 'variation' ) ? $product : wc_get_product( $variation_id ? $variation_id : $product_id );

		} else {

			$product_id   = absint( $product );
			$product_data = wc_get_product( $product_id );

			if ( $product_data->is_type( 'variation' ) ) {
				$product_id   = $product_data->get_parent_id();
				$variation_id = $product_data->get_id();
			} else {
				$product_data = wc_get_product( $variation_id ? $variation_id : $product_id );
			}
		}

		if ( ! $product_data ) {
			return false;
		}

		// Load cart item data when adding to cart.
		$cart_item_data = (array) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id, $quantity );

		// Generate a ID based on product ID, variation ID, variation data, and other cart item data.
		$cart_id = WC()->cart->generate_cart_id( $product_id, $variation_id, $variation, $cart_item_data );

		// See if this product and its options is already in the cart.
		$cart_item_key = WC()->cart->find_product_in_cart( $cart_id );

		// If cart_item_key is set, the item is already in the cart and its quantity will be handled by 'update_quantity_in_cart()'.
		if ( ! $cart_item_key ) {

			$cart_item_key = $cart_id;

			// Add item after merging with $cart_item_data - allow plugins and 'add_cart_item_filter()' to modify cart item.
			WC()->cart->cart_contents[ $cart_item_key ] = apply_filters( 'woocommerce_add_cart_item', array_merge( $cart_item_data, array(
				'key'          => $cart_item_key,
				'product_id'   => absint( $product_id ),
				'variation_id' => absint( $variation_id ),
				'variation'    => $variation,
				'quantity'     => $quantity,
				'data'         => $product_data
			) ), $cart_item_key );
		}

		/**
		 * 'woocommerce_bundled_add_to_cart' action.
		 *
		 * @see 'woocommerce_add_to_cart' action.
		 *
		 * @param  string  $cart_item_key
		 * @param  mixed   $bundled_product_id
		 * @param  int     $quantity
		 * @param  mixed   $variation_id
		 * @param  array   $variation_data
		 * @param  array   $cart_item_data
		 * @param  mixed   $bundle_id
		 */
		do_action( 'woocommerce_bundled_add_to_cart', $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data, $bundle_id );

		return $cart_item_key;
	}


	/*
	|--------------------------------------------------------------------------
	| Filter hooks.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check bundle cart item configurations on cart load.
	 */
	public function check_cart_items() {

		foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {

			if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

				$configuration = isset( $cart_item[ 'stamp' ] ) ? $cart_item[ 'stamp' ] : $this->get_posted_bundle_configuration( $cart_item[ 'data' ] );

				$this->validate_bundle_configuration( $cart_item[ 'data' ], $cart_item[ 'quantity' ], $configuration, 'cart' );
			}
		}
	}

	/**
	 * Validates add-to-cart for bundles.
	 *
	 * @param  boolean  $add
	 * @param  int      $product_id
	 * @param  int      $quantity
	 * @param  mixed    $variation_id
	 * @param  array    $variations
	 * @param  array    $cart_item_data
	 * @return boolean
	 */
	public function validate_add_to_cart( $add, $product_id, $quantity, $variation_id = '', $variations = array(), $cart_item_data = array() ) {

		if ( ! $add ) {
			return false;
		}

		/*
		 * Prevent bundled items from getting validated when re-ordering after cart session data has been loaded:
		 * They will be added by the container item on 'woocommerce_add_to_cart'.
		 */
		if ( $this->is_cart_session_loaded() ) {
			if ( ( isset( $cart_item_data[ 'is_order_again_bundled' ] ) || isset( $cart_item_data[ 'is_order_again_composited' ] ) ) ) {
				return false;
			}
		}

		// Get product type.
		$product_type = WC_Product_Factory::get_product_type( $product_id );

		if ( 'bundle' === $product_type ) {

			$bundle = wc_get_product( $product_id );

			if ( is_a( $bundle, 'WC_Product_Bundle' ) && false === $this->validate_bundle_add_to_cart( $bundle, $quantity, $cart_item_data ) ) {
				$add = false;
			}
		}

		return $add;
	}

	/**
	 * Validates add-to-cart for bundles.
	 * Basically ensures that stock for all bundled products exists before attempting to add them to cart.
	 *
	 * @since  5.6.0
	 *
	 * @param  WC_Product_Bundle  $bundle
	 * @param  int                $quantity
	 * @param  array              $cart_item_data
	 * @return boolean
	 */
	public function validate_bundle_add_to_cart( $bundle, $quantity, $cart_item_data ) {

		$is_valid = true;

		/**
		 * 'woocommerce_bundle_before_validation' filter.
		 *
		 * Early chance to stop/bypass any further validation.
		 *
		 * @param  boolean            $true
		 * @param  WC_Product_Bundle  $bundle
		 */
		if ( apply_filters( 'woocommerce_bundle_before_validation', true, $bundle ) ) {

			$configuration = isset( $cart_item_data[ 'stamp' ] ) ? $cart_item_data[ 'stamp' ] : $this->get_posted_bundle_configuration( $bundle );

			if ( ! $this->validate_bundle_configuration( $bundle, $quantity, $configuration ) ) {
				$is_valid = false;
			}

		} else {
			$is_valid = false;
		}

		return $is_valid;
	}

	/**
	 * Validates in-cart quantity changes.
	 *
	 * @param  bool    $passed
	 * @param  string  $cart_item_key
	 * @param  array   $cart_item
	 * @param  int     $quantity
	 * @return bool
	 */
	public function update_cart_validation( $passed, $cart_item_key, $cart_item, $quantity ) {

		if ( $parent_key = wc_pb_get_bundled_cart_item_container( $cart_item, false, true ) ) {

			$parent_item     = WC()->cart->cart_contents[ $parent_key ];
			$parent_quantity = $parent_item[ 'quantity' ];

			$bundled_item_id = $cart_item[ 'bundled_item_id' ];
			$bundled_item    = $parent_item[ 'data' ]->get_bundled_item( $bundled_item_id );

			$min_quantity    = $parent_quantity * $bundled_item->get_quantity( 'min' );
			$max_quantity    = $bundled_item->get_quantity( 'max' );
			$max_quantity    = $max_quantity ? $parent_quantity * $max_quantity : '';

			if ( $quantity % $parent_quantity != 0 ) {

				wc_add_notice( sprintf( __( 'Cart update failed. The quantity of &quot;%s&quot; must be a multiple of %d.', 'woocommerce-product-bundles' ), $cart_item[ 'data' ]->get_title(), $parent_quantity ), 'error' );
				return false;

			} elseif ( $quantity < $min_quantity ) {

				if ( $quantity > 0 || ( intval( $quantity ) === 0 && false === $bundled_item->is_optional() ) ) {
					wc_add_notice( sprintf( __( 'Cart update failed. The quantity of &quot;%s&quot; must be at least %d.', 'woocommerce-product-bundles' ), $cart_item[ 'data' ]->get_title(), $min_quantity ), 'error' );
					return false;
				}

			} elseif ( $max_quantity && $quantity > $max_quantity ) {

				wc_add_notice( sprintf( __( 'Cart update failed. The quantity of &quot;%s&quot; cannot be higher than %d.', 'woocommerce-product-bundles' ), $cart_item[ 'data' ]->get_title(), $max_quantity ), 'error' );
				return false;

			} else {

				// Update new quantity in 'stamp' array.
				WC()->cart->cart_contents[ $parent_key ][ 'stamp' ][ $bundled_item_id ][ 'quantity' ] = $quantity / $parent_quantity;
				foreach ( wc_pb_get_bundled_cart_items( $parent_item, false, true ) as $child_key ) {
					WC()->cart->cart_contents[ $child_key ][ 'stamp' ][ $bundled_item_id ][ 'quantity' ] = $quantity / $parent_quantity;
				}
			}
		}

		return $passed;
	}

	/**
	 * Redirect to the cart when editing a bundle "in-cart".
	 *
	 * @param  string  $url
	 * @return string
	 */
	public function edit_in_cart_redirect( $url ) {
		return wc_get_cart_url();
	}

	/**
	 * Filter the displayed notice after redirecting to the cart when editing a bundle "in-cart".
	 *
	 * @param  string  $url
	 * @return string
	 */
	public function edit_in_cart_redirect_message( $message ) {
		return __( 'Cart updated.', 'woocommerce' );
	}

	/**
	 * Adds bundle specific cart-item data.
	 * The 'stamp' var is a unique identifier for that particular bundle configuration.
	 *
	 * @param  array  $cart_item_data
	 * @param  int    $product_id
	 * @return array
	 */
	public function add_cart_item_data( $cart_item_data, $product_id ) {

		// Get product type.
		$product_type = WC_Product_Factory::get_product_type( $product_id );

		if ( 'bundle' === $product_type ) {

			$updating_bundle_in_cart = false;

			// Updating bundle in cart?
			if ( isset( $_POST[ 'update-bundle' ] ) ) {

				$updating_cart_key = wc_clean( $_POST[ 'update-bundle' ] );

				if ( isset( WC()->cart->cart_contents[ $updating_cart_key ] ) ) {

					$updating_bundle_in_cart = true;

					// Remove.
					WC()->cart->remove_cart_item( $updating_cart_key );

					// Redirect to cart.
					add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'edit_in_cart_redirect' ) );

					// Edit notice.
					add_filter( 'wc_add_to_cart_message_html', array( $this, 'edit_in_cart_redirect_message' ) );
				}
			}

			// Use posted data to build a bundle configuration 'stamp' array.
			if ( ! isset( $cart_item_data[ 'stamp' ] ) ) {

				$configuration = $this->get_posted_bundle_configuration( $product_id );

				foreach ( $configuration as $bundled_item_id => $bundled_item_configuration ) {

					/**
					 * 'woocommerce_bundled_item_cart_item_identifier' filter.
					 *
					 * Filters the config data array - use this to add any bundle-specific data that should result in unique container item ids being produced when the input data changes, such as add-ons data.
					 *
					 * @param  array  $posted_item_config
					 * @param  int    $bundled_item_id
					 * @param  mixed  $product_id
					 */
					$configuration[ $bundled_item_id ] = apply_filters( 'woocommerce_bundled_item_cart_item_identifier', $bundled_item_configuration, $bundled_item_id, $product_id );
				}

				$cart_item_data[ 'stamp' ] = $configuration;

				// Check "Sold Individually" option context.
				if ( false === WC_PB_Core_Compatibility::is_wc_version_gte( '3.5' ) && false === $updating_bundle_in_cart && ( $product = wc_get_product( $product_id ) ) && $product->is_type( 'bundle' ) && $product->is_sold_individually() ) {
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						if ( $product_id === $cart_item[ 'product_id' ] && 'product' === $product->get_sold_individually_context() ) {
							throw new Exception( sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', wc_get_cart_url(), __( 'View Cart', 'woocommerce' ), sprintf( __( 'You cannot add another &quot;%s&quot; to your cart.', 'woocommerce-product-bundles' ), $product->get_title() ) ) );
						} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) && $configuration === $cart_item[ 'stamp' ] ) {
							throw new Exception( sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', wc_get_cart_url(), __( 'View Cart', 'woocommerce' ), sprintf( __( 'You have already added an identical &quot;%s&quot; to your cart. You cannot add another one.', 'woocommerce-product-bundles' ), $product->get_title() ) ) );
						}
					}
				}
			}

			// Prepare additional data for later use.
			if ( ! isset( $cart_item_data[ 'bundled_items' ] ) ) {
				$cart_item_data[ 'bundled_items' ] = array();
			}
		}

		return $cart_item_data;
	}

	/**
	 * Adds bundled items to the cart on the 'woocommerce_add_to_cart' action.
	 * The 'bundled_by' var is added to each item to identify between bundled and standalone instances of products.
	 * Important: Recursively calling the core add_to_cart function can lead to issus with the contained action hook: https://core.trac.wordpress.org/ticket/17817.
	 *
	 * @param  string  $bundle_cart_key
	 * @param  int     $bundle_id
	 * @param  int     $bundle_quantity
	 * @param  int     $variation_id
	 * @param  array   $variation
	 * @param  array   $cart_item_data
	 * @return void
	 */
	public function bundle_add_to_cart( $bundle_cart_key, $bundle_id, $bundle_quantity, $variation_id, $variation, $cart_item_data ) {

		if ( ! $this->is_cart_session_loaded() ) {
			return;
		}

		if ( wc_pb_is_bundle_container_cart_item( $cart_item_data ) ) {

			// Note: The resulting cart item ID is unique.
			$bundled_items_cart_data = array( 'bundled_by' => $bundle_cart_key, 'stamp' => $cart_item_data[ 'stamp' ] );

			// The bundle.
			$bundle = WC()->cart->cart_contents[ $bundle_cart_key ][ 'data' ];

			if ( empty( $cart_item_data[ 'stamp' ] ) ) {
				throw new Exception( sprintf( __( 'The requested configuration of &quot;%s&quot; cannot be purchased at the moment.', 'woocommerce-product-bundles' ), $bundle->get_title() ) );
				return false;
			}

			// Now add all items - yay.
			foreach ( $cart_item_data[ 'stamp' ] as $bundled_item_id => $bundled_item_stamp ) {

				if ( ! $bundle->has_bundled_item( $bundled_item_id ) ) {
					throw new Exception( sprintf( __( 'The requested configuration of &quot;%s&quot; cannot be purchased at the moment.', 'woocommerce-product-bundles' ), $bundle->get_title() ) );
					return false;
				}

				$bundled_item           = $bundle->get_bundled_item( $bundled_item_id );
				$bundled_item_cart_data = $bundled_items_cart_data;

				if ( isset( $bundled_item_stamp[ 'optional_selected' ] ) && 'no' === $bundled_item_stamp[ 'optional_selected' ] ) {
					continue;
				}

				if ( isset( $bundled_item_stamp[ 'quantity' ] ) && absint( $bundled_item_stamp[ 'quantity' ] ) === 0 ) {
					continue;
				}

				$bundled_item_cart_data[ 'bundled_item_id' ] = $bundled_item_id;

				$item_quantity = isset( $bundled_item_stamp[ 'quantity' ] ) ? absint( $bundled_item_stamp[ 'quantity' ] ) : $bundled_item->get_quantity();
				$quantity      = $bundled_item->is_sold_individually() ? 1 : $item_quantity * $bundle_quantity;
				$product       = $bundled_item->get_product();
				$product_id    = $bundled_item->get_product_id();

				if ( $product->is_type( array( 'simple', 'subscription' ) ) ) {

					$variation_id = '';
					$variations   = array();

				} elseif ( $product->is_type( array( 'variable', 'variable-subscription' ) ) ) {

					if ( isset( $bundled_item_stamp[ 'variation_id' ] ) && isset( $bundled_item_stamp[ 'attributes' ] ) ) {
						$variation_id = $bundled_item_stamp[ 'variation_id' ];
						$variations   = $bundled_item_stamp[ 'attributes' ];
					} else {
						throw new Exception( sprintf( __( 'The requested configuration of &quot;%s&quot; cannot be purchased at the moment.', 'woocommerce-product-bundles' ), $bundle->get_title() ) );
						return false;
					}
				}

				/**
				 * 'woocommerce_bundled_item_cart_data' filter.
				 *
				 * An opportunity to copy/load child cart item data from the parent cart item data array.
				 *
				 * @param  array  $bundled_item_cart_data
				 * @param  array  $cart_item_data
				 */
				$bundled_item_cart_data = apply_filters( 'woocommerce_bundled_item_cart_data', $bundled_item_cart_data, $cart_item_data );

				/**
				 * 'woocommerce_bundled_item_before_add_to_cart' action.
				 *
				 * @param  int    $product_id
				 * @param  int    $quantity
				 * @param  int    $variation_id
				 * @param  array  $variations
				 * @param  array  $bundled_item_cart_data
				 */
				do_action( 'woocommerce_bundled_item_before_add_to_cart', $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data );

				// Add to cart.
				$bundled_item_cart_key = $this->bundled_add_to_cart( $bundle_id, $product, $quantity, $variation_id, $variations, $bundled_item_cart_data );

				if ( $bundled_item_cart_key && ! in_array( $bundled_item_cart_key, WC()->cart->cart_contents[ $bundle_cart_key ][ 'bundled_items' ] ) ) {
					WC()->cart->cart_contents[ $bundle_cart_key ][ 'bundled_items' ][] = $bundled_item_cart_key;
				}

				/**
				 * 'woocommerce_bundled_item_before_add_to_cart' action.
				 *
				 * @param  int    $product_id
				 * @param  int    $quantity
				 * @param  int    $variation_id
				 * @param  array  $variations
				 * @param  array  $bundled_item_cart_data
				 */
				do_action( 'woocommerce_bundled_item_after_add_to_cart', $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data );
			}
		}
	}

	/**
	 * When a bundle is static-priced, the price of all bundled items is set to 0.
	 * When the shipping mode is set to "bundled", all bundled items are marked as virtual when they are added to the cart.
	 * Otherwise, the container itself is a virtual product in the first place.
	 *
	 * @param  array   $cart_item
	 * @param  string  $cart_key
	 * @return array
	 */
	public function add_cart_item_filter( $cart_item, $cart_key ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

			$cart_item = $this->set_bundle_container_cart_item( $cart_item );

		} elseif ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundle          = $bundle_container_item[ 'data' ];
			$bundled_item_id = $cart_item[ 'bundled_item_id' ];

			if ( $bundle->has_bundled_item( $bundled_item_id ) ) {
				$cart_item = $this->set_bundled_cart_item( $cart_item, $bundle );
			}
		}

		return $cart_item;
	}

	/**
	 * Reload all bundle-related session data in the cart.
	 *
	 * @param  array  $cart_item
	 * @param  array  $cart_session_item
	 * @param  array  $cart_item_key
	 * @return array
	 */
	public function get_cart_item_from_session( $cart_item, $cart_session_item, $cart_item_key ) {

		if ( ! isset( $cart_item[ 'stamp' ] ) && isset( $cart_session_item[ 'stamp' ] ) ) {
			$cart_item[ 'stamp' ] = $cart_session_item[ 'stamp' ];
		}

		if ( wc_pb_is_bundle_container_cart_item( $cart_session_item ) ) {

			if ( $cart_item[ 'data' ]->is_type( 'bundle' ) ) {

				if ( ! isset( $cart_item[ 'bundled_items' ] ) ) {
					$cart_item[ 'bundled_items' ] = $cart_session_item[ 'bundled_items' ];
				}

				$cart_item = $this->set_bundle_container_cart_item( $cart_item );

			} else {

				if ( isset( $cart_item[ 'bundled_items' ] ) ) {
					unset( $cart_item[ 'bundled_items' ] );
				}
			}
		}

		if ( wc_pb_maybe_is_bundled_cart_item( $cart_session_item ) ) {

			// Load 'bundled_by' field.
			if ( ! isset( $cart_item[ 'bundled_by' ] ) ) {
				$cart_item[ 'bundled_by' ] = $cart_session_item[ 'bundled_by' ];
			}

			if ( ! isset( $cart_item[ 'bundled_item_id' ] ) ) {
				$cart_item[ 'bundled_item_id' ] = $cart_session_item[ 'bundled_item_id' ];
			}

			if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_session_item ) ) {

				$bundle = $bundle_container_item[ 'data' ];

				if ( $bundle->is_type( 'bundle' ) && $bundle->has_bundled_item( $cart_item[ 'bundled_item_id' ] ) ) {
					$cart_item = $this->set_bundled_cart_item( $cart_item, $bundle );
				}
			}
		}

		return $cart_item;
	}

	/**
	 * Ensure any cart items marked as bundled have a valid parent. If not, silently remove them.
	 *
	 * @param  WC_Cart  $cart
	 * @return void
	 */
	public function cart_loaded_from_session( $cart ) {

		if ( empty( $cart->cart_contents ) ) {
			return;
		}

		foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {

			if ( wc_pb_maybe_is_bundled_cart_item( $cart_item ) ) {
				// Remove orphaned child items from the cart.
				$container_item = wc_pb_get_bundled_cart_item_container( $cart_item );
				if ( ! $container_item || ! isset( $container_item[ 'bundled_items' ] ) || ! is_array( $container_item[ 'bundled_items' ] ) || ! in_array( $cart_item_key, $container_item[ 'bundled_items' ] ) ) {
					unset( WC()->cart->cart_contents[ $cart_item_key ] );
				} elseif ( isset( $cart_item[ 'bundled_item_id' ] ) && $container_item[ 'data' ]->is_type( 'bundle' ) && ! $container_item[ 'data' ]->has_bundled_item( $cart_item[ 'bundled_item_id' ] ) ) {
					unset( WC()->cart->cart_contents[ $cart_item_key ] );
				}
			} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
				// Remove childless, hidden parents from the cart.
				if ( false === WC_Product_Bundle::group_mode_has( $cart_item[ 'data' ]->get_group_mode(), 'parent_item' ) ) {
					$bundled_items = wc_pb_get_bundled_cart_items( $cart_item );
					if ( empty( $bundled_items ) ) {
						unset( WC()->cart->cart_contents[ $cart_item_key ] );
					}
				}
			}
		}
	}

	/**
	 * Keep quantities between bundled products and container items in sync.
	 *
	 * @param  string   $cart_item_key
	 * @param  integer  $quantity
	 * @return void
	 */
	public function update_quantity_in_cart( $cart_item_key, $quantity = 0 ) {

		if ( ! empty( WC()->cart->cart_contents[ $cart_item_key ] ) ) {

			if ( $quantity == 0 || $quantity < 0 ) {
				$quantity = 0;
			} else {
				$quantity = WC()->cart->cart_contents[ $cart_item_key ][ 'quantity' ];
			}

			if ( wc_pb_is_bundle_container_cart_item( WC()->cart->cart_contents[ $cart_item_key ] ) ) {

				// Get bundled cart items.
				$bundled_cart_items = wc_pb_get_bundled_cart_items( WC()->cart->cart_contents[ $cart_item_key ] );

				// Change the quantity of all bundled items that belong to the same bundle config.
				if ( ! empty( $bundled_cart_items ) ) {
					foreach ( $bundled_cart_items as $key => $value ) {
						if ( $value[ 'data' ]->is_sold_individually() && $quantity > 0 ) {
							WC()->cart->set_quantity( $key, 1, false );
						} elseif ( isset( $value[ 'stamp' ] ) && isset( $value[ 'bundled_item_id' ] ) && isset( $value[ 'stamp' ][ $value[ 'bundled_item_id' ] ] ) ) {
							$bundle_quantity = $value[ 'stamp' ][ $value[ 'bundled_item_id' ] ][ 'quantity' ];
							WC()->cart->set_quantity( $key, $quantity * $bundle_quantity, false );
						}
					}
				}
			}
		}
	}

	/**
	 * Remove bundled cart items with parent.
	 *
	 * @param  string   $cart_item_key
	 * @param  WC_Cart  $cart
	 * @return void
	 */
	public function cart_item_remove( $cart_item_key, $cart ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart->removed_cart_contents[ $cart_item_key ] ) ) {

			$bundled_item_cart_keys = wc_pb_get_bundled_cart_items( $cart->removed_cart_contents[ $cart_item_key ], $cart->cart_contents, true );

			foreach ( $bundled_item_cart_keys as $bundled_item_cart_key ) {

				$remove = $cart->cart_contents[ $bundled_item_cart_key ];
				$cart->removed_cart_contents[ $bundled_item_cart_key ] = $remove;

				/** WC core action. */
				do_action( 'woocommerce_remove_cart_item', $bundled_item_cart_key, $cart );

				unset( $cart->cart_contents[ $bundled_item_cart_key ] );
			}
		}
	}

	/**
	 * Restore bundled cart items with parent.
	 *
	 * @param  string   $cart_item_key
	 * @param  WC_Cart  $cart
	 * @return void
	 */
	public function cart_item_restore( $cart_item_key, $cart ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart->cart_contents[ $cart_item_key ] ) ) {

			$bundled_item_cart_keys = wc_pb_get_bundled_cart_items( $cart->cart_contents[ $cart_item_key ], $cart->removed_cart_contents, true );

			foreach ( $bundled_item_cart_keys as $bundled_item_cart_key ) {

				$remove = $cart->removed_cart_contents[ $bundled_item_cart_key ];
				$cart->cart_contents[ $bundled_item_cart_key ] = $remove;

				/** WC core action. */
				do_action( 'woocommerce_restore_cart_item', $bundled_item_cart_key, $cart );

				unset( $cart->removed_cart_contents[ $bundled_item_cart_key ] );
			}
		}
	}

	/**
	 * Shipping fix - add the value of any children that are not shipped individually to the container value and, optionally, add their weight to the container weight, as well.
	 *
	 * @param  array  $packages
	 * @return array
	 */
	public function cart_shipping_packages( $packages ) {

		if ( ! empty( $packages ) ) {

			foreach ( $packages as $package_key => $package ) {

				if ( ! empty( $package[ 'contents' ] ) ) {
					foreach ( $package[ 'contents' ] as $cart_item_key => $cart_item_data ) {

						if ( wc_pb_is_bundle_container_cart_item( $cart_item_data ) ) {

							// Let CP handle things here if needed.
							if ( WC_PB()->compatibility->is_composited_cart_item( $cart_item_data ) ) {
								continue;
							}

							$bundle     = unserialize( serialize( $cart_item_data[ 'data' ] ) );
							$bundle_qty = $cart_item_data[ 'quantity' ];

							/*
							 * Container needs shipping: Aggregate the prices of any children that are physically packaged in their parent and, optionally, aggregate their weights into the parent, as well.
							 */

							if ( $bundle->needs_shipping() ) {

								$bundled_weight = 0.0;
								$bundled_value  = 0.0;

								$bundle_totals = array(
									'line_subtotal'     => $cart_item_data[ 'line_subtotal' ],
									'line_total'        => $cart_item_data[ 'line_total' ],
									'line_subtotal_tax' => $cart_item_data[ 'line_subtotal_tax' ],
									'line_tax'          => $cart_item_data[ 'line_tax' ],
									'line_tax_data'     => $cart_item_data[ 'line_tax_data' ]
								);

								foreach ( wc_pb_get_bundled_cart_items( $cart_item_data, WC()->cart->cart_contents, true ) as $child_item_key ) {

									$child_cart_item_data   = WC()->cart->cart_contents[ $child_item_key ];
									$bundled_product        = $child_cart_item_data[ 'data' ];
									$bundled_product_qty    = $child_cart_item_data[ 'quantity' ];
									$bundled_product_value  = $bundled_product->get_meta( '_wc_pb_bundled_value', true );
									$bundled_product_weight = $bundled_product->get_meta( '_wc_pb_bundled_weight', true );

									// Aggregate price of physically packaged child item - already converted to virtual.

									if ( $bundled_product_value ) {

										$bundled_value += $bundled_product_value * $bundled_product_qty;

										$bundle_totals[ 'line_subtotal' ]     += $child_cart_item_data[ 'line_subtotal' ];
										$bundle_totals[ 'line_total' ]        += $child_cart_item_data[ 'line_total' ];
										$bundle_totals[ 'line_subtotal_tax' ] += $child_cart_item_data[ 'line_subtotal_tax' ];
										$bundle_totals[ 'line_tax' ]          += $child_cart_item_data[ 'line_tax' ];

										$packages[ $package_key ][ 'contents_cost' ] += $child_cart_item_data[ 'line_total' ];

										$child_item_line_tax_data = $child_cart_item_data[ 'line_tax_data' ];

										$bundle_totals[ 'line_tax_data' ][ 'total' ]    = array_merge( $bundle_totals[ 'line_tax_data' ][ 'total' ], $child_item_line_tax_data[ 'total' ] );
										$bundle_totals[ 'line_tax_data' ][ 'subtotal' ] = array_merge( $bundle_totals[ 'line_tax_data' ][ 'subtotal' ], $child_item_line_tax_data[ 'subtotal' ] );
									}

									// Aggregate weight of physically packaged child item - already converted to virtual.

									if ( $bundled_product_weight ) {
										$bundled_weight += $bundled_product_weight * $bundled_product_qty;
									}
								}

								if ( $bundled_value > 0 ) {
									$bundle_price = $bundle->get_price( 'edit' );
									$bundle->set_price( (double) $bundle_price + $bundled_value / $bundle_qty );
								}

								$packages[ $package_key ][ 'contents' ][ $cart_item_key ] = array_merge( $cart_item_data, $bundle_totals );

								if ( $bundled_weight > 0 ) {
									$bundle_weight = $bundle->get_weight( 'edit' );
									$bundle->set_weight( (double) $bundle_weight + $bundled_weight / $bundle_qty );
								}

								$packages[ $package_key ][ 'contents' ][ $cart_item_key ][ 'data' ] = $bundle;
							}
						}
					}
				}
			}
		}

		return $packages;
	}

	/**
	 * Treat bundled subs as non-sub products when bundled in statically-priced bundles.
	 * Method: Do not add product in any subscription cart group.
	 *
	 * @return bool
	 */
	public function add_subcription_filter() {
		add_filter( 'woocommerce_is_subscription', array( $this, 'is_subscription_filter' ), 100, 3 );
	}

	/**
	 * Treat bundled subs as non-sub products when bundled in statically-priced bundles.
	 * Method: Do not add product in any subscription cart group.
	 *
	 * @return bool
	 */
	public function remove_subcription_filter() {
		remove_filter( 'woocommerce_is_subscription', array( $this, 'is_subscription_filter' ), 100, 3 );
	}

	/**
	 * Treat bundled subs as non-sub products when bundled in statically-priced bundles.
	 *
	 * @param  bool        $is_sub
	 * @param  string      $product_id
	 * @param  WC_Product  $product
	 * @return bool
	 */
	public function is_subscription_filter( $is_sub, $product_id, $product ) {
		if ( is_object( $product ) && isset( $product->wc_pb_block_sub ) && 'yes' === $product->wc_pb_block_sub ) {
			$is_sub = false;
		}

		return $is_sub;
	}

	/**
	 * "Sold Individually" context support under WC 3.5+.
	 *
	 * @since  5.8.1
	 *
	 * @param  bool    $found
	 * @param  int     $product_id
	 * @param  int     $variation_id
	 * @param  array   $cart_item
	 * @return bool
	 */
	public function sold_individually_found_in_cart( $found, $product_id, $variation_id, $cart_item ) {

		$updating_bundle_in_cart = false;

		// Updating bundle in cart?
		if ( isset( $_POST[ 'update-bundle' ] ) ) {
			$updating_cart_key       = wc_clean( $_POST[ 'update-bundle' ] );
			$updating_bundle_in_cart = isset( WC()->cart->cart_contents[ $updating_cart_key ] );
		}

		if ( $updating_bundle_in_cart ) {
			return $found;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product->is_type( 'bundle' ) ) {
			return $found;
		}

		if ( ! $product->is_sold_individually() ) {
			return $found;
		}

		// Check "Sold Individually" option context.
		foreach ( WC()->cart->get_cart() as $search_cart_item ) {
			if ( $product_id === $search_cart_item[ 'product_id' ] && 'product' === $product->get_sold_individually_context() ) {
				$found = true;
			} elseif ( wc_pb_is_bundle_container_cart_item( $search_cart_item ) && isset( $cart_item[ 'stamp' ] ) && $cart_item[ 'stamp' ] === $search_cart_item[ 'stamp' ] ) {
				throw new Exception( sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', wc_get_cart_url(), __( 'View Cart', 'woocommerce' ), sprintf( __( 'You have already added an identical &quot;%s&quot; to your cart. You cannot add another one.', 'woocommerce-product-bundles' ), $product->get_title() ) ) );
			}
		}

		return $found;
	}

	/**
	 * Deprecated class methods.
	 *
	 * @deprecated
	 */
	public function get_bundled_cart_item_container( $cart_item ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', 'wc_pb_get_bundled_cart_item_container()' );
		return wc_pb_get_bundled_cart_item_container( $cart_item );
	}
	public function is_bundled_cart_item( $cart_item ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', 'wc_pb_is_bundled_cart_item()' );
		return wc_pb_is_bundled_cart_item( $cart_item );
	}
	public function is_bundle_container_cart_item( $cart_item ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', 'wc_pb_is_bundle_container_cart_item()' );
		return wc_pb_is_bundle_container_cart_item( $cart_item );
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated methods.
	|--------------------------------------------------------------------------
	*/

	public function order_again( $cart_item_data, $order_item, $order ) {
		_deprecated_function( __METHOD__ . '()', '5.8.0', 'WC_PB_Order_Again::order_again_cart_item_data()' );
		return WC_PB_Order_Again::order_again_cart_item_data( $cart_item_data, $order_item, $order );
	}
	public function coupon_is_valid_for_product( $valid, $product, $coupon, $item ) {
		_deprecated_function( __METHOD__ . '()', '5.8.0', 'WC_PB_Coupon::coupon_is_valid_for_product()' );
		return WC_PB_Coupon::coupon_is_valid_for_product( $valid, $product, $coupon, $item );
	}
	public function format_subtotal( $product, $subtotal ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', 'WC_PB_Display::format_subtotal()' );
		return WC_PB()->display->format_subtotal( $product, $subtotal );
	}
	public function cart_item_price_html( $price, $values, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', 'WC_PB_Display::cart_item_price()' );
		return WC_PB()->display->cart_item_price( $price, $values, $cart_item_key );
	}
	public function item_subtotal( $subtotal, $values, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', 'WC_PB_Display::item_subtotal()' );
		return WC_PB()->display->cart_item_subtotal( $subtotal, $values, $cart_item_key );
	}
	public function cart_item_quantity( $quantity, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', 'WC_PB_Display::cart_item_quantity()' );
		return WC_PB()->display->cart_item_quantity( $quantity, $cart_item_key );
	}
	public function cart_item_remove_link( $link, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', 'WC_PB_Display::cart_item_remove_link()' );
		return WC_PB()->display->cart_item_remove_link( $link, $cart_item_key );
	}
	public function woo_bundles_validation( $add, $product_id, $product_quantity, $variation_id = '', $variations = array(), $cart_item_data = array() ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::validate_add_to_cart()' );
		return $this->validate_add_to_cart( $add, $product_id, $product_quantity, $variation_id, $variations, $cart_item_data );
	}
	public function woo_bundles_add_cart_item_data( $cart_item_data, $product_id ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::add_cart_item_data()' );
		return $this->add_cart_item_data( $cart_item_data, $product_id );
	}
	public function woo_bundles_add_bundle_to_cart( $bundle_cart_key, $bundle_id, $bundle_quantity, $variation_id, $variation, $cart_item_data ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::bundle_add_to_cart()' );
		return $this->bundle_add_to_cart( $bundle_cart_key, $bundle_id, $bundle_quantity, $variation_id, $variation, $cart_item_data );
	}
	public function woo_bundles_add_cart_item_filter( $cart_item, $cart_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::add_cart_item_filter()' );
		return $this->add_cart_item_filter( $cart_item, $cart_key );
	}
	public function woo_bundles_get_cart_data_from_session( $cart_item, $cart_session_item, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::get_cart_item_from_session()' );
		return $this->get_cart_item_from_session( $cart_item, $cart_session_item, $cart_item_key );
	}
	public function woo_bundles_cart_item_quantity( $quantity, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_quantity()' );
		return $this->cart_item_quantity( $quantity, $cart_item_key );
	}
	public function woo_bundles_cart_item_remove_link( $link, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_remove_link()' );
		return $this->cart_item_remove_link( $link, $cart_item_key );
	}
	public function woo_bundles_update_quantity_in_cart( $cart_item_key, $quantity = 0 ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::update_quantity_in_cart()' );
		return $this->update_quantity_in_cart( $cart_item_key, $quantity );
	}
	public function woo_bundles_order_again( $cart_item_data, $order_item, $order ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::order_again()' );
		return $this->order_again( $cart_item_data, $order_item, $order );
	}
	public function woo_bundles_cart_item_price_html( $price, $values, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_price_html()' );
		return $this->cart_item_price_html( $price, $values, $cart_item_key );
	}
	public function woo_bundles_item_subtotal( $subtotal, $values, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::item_subtotal()' );
		return $this->item_subtotal( $subtotal, $values, $cart_item_key );
	}
	public function woo_bundles_cart_item_removed( $cart_item_key, $cart ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_removed()' );
		return cart_item_removed( $cart_item_key, $cart );
	}
	public function woo_bundles_cart_item_restored( $cart_item_key, $cart ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_restored()' );
		return $this->cart_item_restored( $cart_item_key, $cart );
	}
	public function woo_bundles_shipping_packages_fix( $packages ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_shipping_packages()' );
		return $this->cart_shipping_packages( $packages );
	}
	public function woo_bundles_coupon_validity( $valid, $product, $coupon, $cart_item ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::coupon_is_valid_for_product()' );
		return $this->coupon_is_valid_for_product( $valid, $product, $coupon, $cart_item );
	}
}
