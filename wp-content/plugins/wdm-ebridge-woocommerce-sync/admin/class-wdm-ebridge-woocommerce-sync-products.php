<?php

/**
 * Adds the Menu and Sub Menu Pages for Ebridge Settings.
 *
 * @since      1.0.0
 * @package    Wdm_Ebridge_Woocommerce_Sync
 * @subpackage Wdm_Ebridge_Woocommerce_Sync/admin
 * @author     WisdmLabs <helpdesk@wisdmlabs.com >
 */
class Wdm_Ebridge_Woocommerce_Sync_Products {


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
	 * The ebridge api url.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $api_url    The EBridge API URL.
	 */
	private $api_url;


	/**
	 * The ebdrige api token.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $api_token    The EBridge API Token.
	 */
	private $api_token;


	/**
	 * .
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $updated_products    The Array of updated products.
	 */
	private $updated_products;



	/**
	 * .
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $default_attributes    The Array of default product attributes.
	 */

	private $default_attributes = array( 'replacementCost', 'seo', 'images', 'kitComponents', 'description', 'benefits', 'id', 'msrp', 'promoPrice', 'weight', 'showAvailability', 'dimension', 'webCategories', 'brandId', 'brandDescription', 'availableOnWeb', 'webCategoryIds' );

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->api_url   = get_option( 'ebridge_sync_api_url', '' );
		$this->api_token = get_option( 'ebridge_sync_api_token', '' );
	}


	/**
	 * Key function to Sync with Ebridge Products.
	 *
	 * @since    1.0.0
	 */
	public function update_products() {
		$updated_products       = array();
		$this->updated_products = array();
		$success_count          = 0;

		if ( $this->api_url && $this->api_token ) {
			$last_updated_date           = get_option( 'ebridge_sync_last_updated_date', '' );
			$last_updated_time           = get_option( 'ebridge_sync_last_updated_time', '00:00' );
			$web_server_time_zone_offset = get_option( 'ebridge_sync_web_server_time_zone_offset', '0' );

			if ( $last_updated_date ) {
				$args = array(
					'beginDate'               => $last_updated_date,
					'beginTime'               => $last_updated_time,
					'webServerTimeZoneOffset' => $web_server_time_zone_offset,
				);
				$url  = 'https://ebridge.storis.com/lrelease/2.0.26.26UNDERPRICED/storisapiv3.svc/restssl/gF-2FGRXhkmMCmn9nU49-2FI-2FJixjoQ9ixf-2BIlwmdklJpPY-3D/productsync?beginDate=' . $last_updated_date . '&beginTime=' . $last_updated_time . '&webServerTimeZoneOffset=' . $web_server_time_zone_offset;
			} else {
				$url = $this->api_url . '/' . $this->api_token . '/productsync?returnMode=2';
			}

			$url = $this->api_url . '/' . $this->api_token . '/productsync?returnMode=2';
			echo $url;
			$response = wp_remote_get( $url );
			$products = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$update_product_ids = $products->updatedProductIds ? $products->updatedProductIds : array();

				$no_updated_products = count( $update_product_ids );
				for ( $index = 0; $index < $no_updated_products; $index++ ) {
				// for ( $index = 100; $index < 110; $index++ ) {
					$success = $this->create_product( $update_product_ids[ $index ] );
					$updated_products[ $update_product_ids[ $index ] ]       = $success;
					$this->updated_products[ $update_product_ids[ $index ] ] = $success;

					if ( $success ) {
						if ( $success ) {
							$success_count++;
						}
					}
				}

				$delete_product_ids = $products->deletedProductIds ? $products->deletedProductIds : array();

				$no_deleted_products = count( $delete_product_ids );
				for ( $index = 0; $index < $no_deleted_products; $index++ ) {
				// for ( $index = 120; $index < 130; $index++ ) {
					$success = $this->delete_product( $delete_product_ids[ $index ] );
					$updated_products[ $delete_product_ids[ $index ] ]       = $success;
					$this->updated_products[ $delete_product_ids[ $index ] ] = $success;

					if ( $success ) {
						if ( $success ) {
							$success_count++;
						}
					}
				}
			}

			update_option( 'ebridge_sync_last_updated_date', date( 'm-d-Y' ) );
			update_option( 'ebridge_sync_last_updated_time', date( 'H:i' ) );
		} else {
			echo __( 'Sorry, could not add products. Please add valid Ebridge URL and Token.<br />', 'wdm-ebridge-woocommerce-sync' );
		}

		$updated_products['success_count'] = $success_count;

		return $updated_products;
	}


	/**
	 * Fetches Product from Ebridge and adds/updates it with WooCommerce.
	 *
	 * @since    1.0.0
	 * @param      string $id       The Ebridge Product Id.
	 */
	public function create_product( $id ) {
		$response = wp_remote_get( $this->api_url . '/' . $this->api_token . '/products/' . $id );
		$product  = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ( wp_remote_retrieve_response_code( $response ) == 200 ) && isset( $product->product ) ) {
			$product = $product->product;
			if ( ! empty( $product->kitComponents ) ) {
				return $this->create_grouped_product( $product );
			} else {
				return $this->create_simple_product( $product );
			}
		}

		return false;
	}



	/**
	 * Creates/Updates a WooCommerce Grouped Product.
	 *
	 * @since    1.0.0
	 * @param      object $product_obj       The Ebridge Grouped Product Object.
	 */
	public function create_grouped_product( $product_obj ) {
		$product_id     = get_option( 'product_' . $product_obj->id, '' );
		$child_products = array();

		foreach ( $product_obj->kitComponents as $key => $value ) {
			$child_product_id = get_option( 'product_' . $value->id, '' );

			if ( ! $child_product_id ) {
				$success                              = $this->create_product( $value->id );
				$this->updated_products[ $value->id ] = $success;

				if ( $success ) {
					$child_products[] = $success;
				}
			} else {
				$child_products[] = $child_product_id;
			}
		}

		if ( ! $product_id ) {
			$product = new WC_Product_Grouped();
		} else {
			$product = new WC_Product_Grouped( $product_id );
		}

		$product->set_children( $child_products );
		$product = $this->set_product_common_data( $product, $product_obj );

		return $product->get_id();
	}


	/**
	 * Creates/Updates a WooCommerce Simple Product.
	 *
	 * @since    1.0.0
	 * @param      object $product_obj       The Ebridge Product Object.
	 */
	public function create_simple_product( $product_obj ) {
		$product_id = get_option( 'product_' . $product_obj->id, '' );

		if ( ! $product_id ) {
			$product = new WC_Product_Simple();
		} else {
			$product = new WC_Product_Simple( $product_id );
		}

		$product = $this->set_product_common_data( $product, $product_obj );

		return $product->get_id();
	}


	/**
	 * Throws a WooCommerce Product into trash.
	 *
	 * @since    1.0.0
	 * @param      string $product_id       The Ebridge Product Id.
	 */
	public function delete_product( $product_id ) {
		$product_id = get_option( 'product_' . $product_id, '' );

		if ( $product_id ) {
			$product = wc_get_product( $product_id );
			$product->set_status( 'trash' );
			delete_option( 'product_' . $product_id );

			return $product_id;
		}

		return $product_id;
	}


	public function categories_to_set( $ebridge_web_categories ) {
		$categories = array();

		if ( isset( $ebridge_web_categories ) ) {
			foreach ( $ebridge_web_categories as $key => $value ) {
				$category = get_term_by(
					'slug',
					sanitize_title( $value->id ),
					'product_cat'
				);

				if ( ! $category ) {
					include_once plugin_dir_path( __FILE__ ) . 'class-wdm-ebridge-woocommerce-sync-categories.php';
					echo plugin_dir_path( __FILE__ ) . 'class-wdm-ebridge-woocommerce-sync-categories.php';
					Wdm_Ebridge_Woocommerce_Sync_Categories::create_custom_category( $value, 'product_cat' );

					$category = get_term_by(
						'slug',
						sanitize_title( $value->id ),
						'product_cat'
					);
				}
				$categories[] = $category->term_id;
			}
		}

		return $categories;
	}

	public function brand_to_set( $brand_id, $brand_description ) {
		$brand = get_term_by(
			'slug',
			sanitize_title( $brand_id ),
			'brand'
		);

		if ( ! $brand ) {
			wp_insert_term(
				sanitize_text_field( str_replace( '"', '', $brand_description ) ),
				'brand',
				array(
					'description' => $brand_id,
					'slug'        => sanitize_title( $brand_id ),
				)
			);

			$brand = get_term_by(
				'slug',
				sanitize_title( $brand_id ),
				'brand'
			);
		}

		return array( $brand->term_id );
	}

	public function meta_to_add( $product, $product_obj ) {
		$product_attributes_checked = get_option( 'product_attributes_checked', array() );

		foreach ( $product_obj as $key => $value ) {
			if ( $product->get_meta( $key ) ) {
				$product->delete_meta_data( $key );
			}

			if ( in_array( $key, $product_attributes_checked ) && ( ! in_array( $key, $this->default_attributes ) ) ) {
				$product->update_meta_data( $key, maybe_serialize( $value ) );
			}
		}

		return $product;
	}


	public function set_product_common_data($product, $product_obj)
	{
		$product->set_name( $product_obj->description );
		$product->set_description( $product_obj->benefits );
		$product->set_sku( $product_obj->id );
		$product->set_regular_price( $product_obj->msrp );
		$product->set_sale_price( $product_obj->promoPrice );
		$product->set_weight( $product_obj->weight );

		if ( $product_obj->showAvailability ) {
			$product->set_stock_status( 'instock' );
		} else {
			$product->set_stock_status( 'outofstock' );
		}

		if ( isset( $product_obj->dimension ) ) {
			$product->set_length( $product_obj->dimension->depth );
			$product->set_width( $product_obj->dimension->width );
			$product->set_height( $product_obj->dimension->height );
		}

		$product->set_category_ids( $this->categories_to_set( $product_obj->webCategories ) );

		$this->meta_to_add( $product, $product_obj );

		// $product->set_slug( sanitize_title( $product_obj->description ) );
		$product->save();
		$product_id = $product->get_id();

		wp_set_post_terms( $product_id, $this->brand_to_set( $product_obj->brandId, $product_obj->brandDescription ), 'brand' );

		if ( $product_obj->availableOnWeb ) {
			wp_update_post(
				array(
					'ID'          => $product_id,
					'post_status' => 'publish',
				)
			);
		} else {
			wp_update_post(
				array(
					'ID'          => $product_id,
					'post_status' => 'private',
				)
			);
		}

		// Meta data for Yoast Plugin.
		if ( isset( $product_obj->seo ) ) {
			$keywords = str_replace(',', ' ', $product_obj->seo->keywords);

			update_post_meta( $product_id, '_yoast_wpseo_title', $product_obj->seo->pageTitle );
			update_post_meta( $product_id, '_yoast_wpseo_focuskw', $keywords );
			update_post_meta( $product_id, '_yoast_wpseo_metadesc', $product_obj->seo->metaDescription );
		}

		// Meta data for Cost of Goods Plugin.
		update_post_meta( $product_id, '_wc_cog_cost', $product_obj->replacementCost );

		update_option( 'product_' . $product_obj->id, $product_id );

		return $product;
	}
}

// new Wdm_Ebridge_Woocommerce_Sync_Products();
