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

	private $default_attributes = array( 'beginningPromoDate', 'endingPromoDate', 'netQuantityAvailable', 'normalPrice', 'webMasterDescription', 'webMasterId', 'vendor', 'replacementCost', 'seo', 'images', 'kitComponents', 'description', 'benefits', 'id', 'msrp', 'weight', 'showAvailability', 'dimension', 'webCategories', 'brandId', 'brandDescription', 'availableOnWeb', 'webCategoryIds' );

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->api_url   = get_option( 'ebridge_sync_api_url', '' );
		$this->api_token = get_option( 'ebridge_sync_api_token', '' );
		$this->curl_args = array(
			'timeout' => 6000,
		);
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
				$url  = $this->api_url . '/' . $this->api_token . '/productsync?beginDate=' . $last_updated_date . '&beginTime=' . $last_updated_time . '&webServerTimeZoneOffset=' . $web_server_time_zone_offset;
			} else {
				$url = $this->api_url . '/' . $this->api_token . '/productsync?returnMode=2';
			}

			$response = wp_remote_get( $url, $this->curl_args );
			$products = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$update_product_ids = isset( $products->updatedProductIds ) ? $products->updatedProductIds : array();

				$no_updated_products = count( $update_product_ids );
				for ( $index = 0; $index < $no_updated_products; $index++ ) {
					$success = $this->create_product( $update_product_ids[ $index ] );
					$updated_products[ $update_product_ids[ $index ] ]       = $success;
					$this->updated_products[ $update_product_ids[ $index ] ] = $success;

					if ( $success ) {
						if ( $success ) {
							$success_count++;
						}
					}
				}

				$delete_product_ids = isset( $products->deletedProductIds ) ? $products->deletedProductIds : array();

				$no_deleted_products = count( $delete_product_ids );
				for ( $index = 0; $index < $no_deleted_products; $index++ ) {
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
		if ( get_transient( 'product_' . $id . '_' ) ) {
			return true;
		} else {
			set_transient( 'product_' . $id . '_', $id, 30 * MINUTE_IN_SECONDS );
		}

		$response = wp_remote_get( $this->api_url . '/' . $this->api_token . '/products/' . $id, $this->curl_args );
		$product  = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ( wp_remote_retrieve_response_code( $response ) == 200 ) && isset( $product->product ) ) {
			$product = $product->product;
			if ( ! empty( $product->kitComponents ) ) {
				return $this->create_product_bundle( $product );
			} else {
				return $this->create_simple_product( $product );
			}
		} elseif ( ( wp_remote_retrieve_response_code( $response ) == 200 ) && isset( $product->message ) && ( strpos( $product->message, 'Cannot locate' ) !== false ) ) {
			return $this->delete_product( $id );
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

		if ( ! $product_id ) {
			$product = new WC_Product_Grouped();
		} else {
			$product = new WC_Product_Grouped( $product_id );

			$last_sync_time = $product->get_meta( 'product_last_synced', true );

			$current_time        = current_time( 'timestamp', true );
			$fifteen_mins_before = $current_time - ( MINUTE_IN_SECONDS * 15 );

			if ( $last_sync_time > $fifteen_mins_before ) {
				return $product->get_id();
			}
		}

		$product = $this->set_product_common_data( $product, $product_obj );

		foreach ( $product_obj->kitComponents as $key => $value ) {
			$child_product_id = get_option( 'product_' . $value->id, '' );

			if ( ! $child_product_id ) {
				$success = $this->create_product( $value->id );

				if ( $success ) {
					$this->updated_products[ $value->id ] = $success;
					$child_products[]                     = $success;
				}
			} else {
				$child_products[] = $child_product_id;
			}
		}

		$product->set_children( $child_products );
		$product->save();

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
			$product        = new WC_Product_Simple( $product_id );
			$last_sync_time = $product->get_meta( 'product_last_synced', true );

			$current_time        = current_time( 'timestamp', true );
			$fifteen_mins_before = $current_time - ( MINUTE_IN_SECONDS * 15 );

			if ( $last_sync_time > $fifteen_mins_before ) {
				return $product->get_id();
			}
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
			$product->save();
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

		$product->update_meta_data( 'product_last_synced', current_time( 'timestamp', true ) );

		return $product;
	}


	public function set_product_common_data( $product, $product_obj ) {
		$product->set_name( $product_obj->description );
		$product->set_description( $product_obj->benefits );
		$product->set_sku( $product_obj->id );
		$product->set_regular_price( $product_obj->msrp );
		$product->set_weight( $product_obj->weight );

		if ( isset( $product_obj->dimension ) ) {
			$product->set_length( $product_obj->dimension->depth );
			$product->set_width( $product_obj->dimension->width );
			$product->set_height( $product_obj->dimension->height );
		}

		if ( isset( $product_obj->beginningPromoDate ) ) {
			$start_date = $this->get_date_time_object( $product_obj->beginningPromoDate );
			$product->set_date_on_sale_from( $start_date );
		}

		if ( isset( $product_obj->endingPromoDate ) ) {
			$end_date = $this->get_date_time_object( $product_obj->endingPromoDate );
			$product->set_date_on_sale_to( $end_date );
		}

		if ( $product->is_type( 'bundle' ) ) {
			$product = $this->set_product_availability( $product, $product_obj );
		} elseif ( isset( $product_obj->inventory ) ) {
			$net_quantity = $product_obj->inventory->netQuantityAvailable;

			if ( is_numeric( $net_quantity ) ) {
				$product->set_manage_stock( true );
				$product->set_stock_quantity( $net_quantity );
			}

			if ( isset( $product_obj->inventory->locations ) ) {
				$locations = $product_obj->inventory->locations;

				foreach ( $locations as $key => $location ) {
					if ( is_numeric( $location->leadDays ) && ( 0 === $net_quantity ) ) {
						$product->set_backorders( 'notify' );
					}
				}
			}
		}

		if ( ! $product_obj->availableOnWeb ) {
			$product->set_catalog_visibility( 'hidden' );
		}

		$product->set_sale_price( $product_obj->normalPrice );

		$product->set_category_ids( $this->categories_to_set( $product_obj->webCategories ) );

		$this->meta_to_add( $product, $product_obj );

		$product->set_image_id( $this->get_image_id( $product_obj->images ) );

		$product->set_tag_ids( $this->get_tag_ids( $product_obj ) );

		// $product->set_slug( sanitize_title( $product_obj->description ) );
		$product->save();
		$product_id = $product->get_id();

		wp_set_post_terms( $product_id, $this->brand_to_set( $product_obj->brandId, $product_obj->brandDescription ), 'brand' );

		wp_update_post(
			array(
				'ID'          => $product_id,
				'post_status' => 'publish',
			)
		);

		// Meta data for Yoast Plugin.
		if ( isset( $product_obj->seo ) ) {
			$keywords = str_replace( ',', ' ', $product_obj->seo->keywords );

			update_post_meta( $product_id, '_yoast_wpseo_title', $product_obj->seo->pageTitle );
			update_post_meta( $product_id, '_yoast_wpseo_focuskw', $keywords );
			update_post_meta( $product_id, '_yoast_wpseo_metadesc', $product_obj->seo->metaDescription );
		}

		// Meta data for Cost of Goods Plugin.
		update_post_meta( $product_id, '_wc_cog_cost', $product_obj->replacementCost );

		update_option( 'product_' . $product_obj->id, $product_id );

		return $product;
	}

	public function get_all_product_ids() {
		$api_url      = get_option( 'ebridge_sync_api_url', '' );
		$api_token    = get_option( 'ebridge_sync_api_token', '' );
		$all_products = array(
			'update_ids'       => array(),
			'delete_ids'       => array(),
			'update_ids_count' => 0,
			'delete_ids_count' => 0,
		);

		if ( $api_url && $api_token ) {
			$url      = $api_url . '/' . $api_token . '/productsync?returnMode=2';
			$response = wp_remote_get( $url, $this->curl_args );
			$products = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$all_products['update_ids']       = isset( $products->updatedProductIds ) ? $products->updatedProductIds : array();
				$all_products['update_ids_count'] = count( $all_products['update_ids'] );
				$$all_products['delete_ids']      = isset( $products->deletedProductIds ) ? $products->deletedProductIds : array();
				$all_products['delete_ids_count'] = count( $all_products['delete_ids'] );
			}
		}

		return $all_products;
	}

	public function get_last_updated_product_ids() {
		$api_url          = get_option( 'ebridge_sync_api_url', '' );
		$api_token        = get_option( 'ebridge_sync_api_token', '' );
		$updated_products = array(
			'update_ids'       => array(),
			'delete_ids'       => array(),
			'update_ids_count' => 0,
			'delete_ids_count' => 0,
		);

		if ( $api_url && $api_token ) {
			$last_updated_date           = get_option( 'ebridge_sync_last_updated_date', '' );
			$last_updated_time           = get_option( 'ebridge_sync_last_updated_time', '00:00' );
			$web_server_time_zone_offset = get_option( 'ebridge_sync_web_server_time_zone_offset', '0' );

			if ( $last_updated_date ) {
				$url = $api_url . '/' . $api_token . '/productsync?beginDate=' . $last_updated_date . '&beginTime=' . $last_updated_time . '&webServerTimeZoneOffset=' . $web_server_time_zone_offset;
			} else {
				$url = $api_url . '/' . $api_token . '/productsync?returnMode=2';
			}

			$response = wp_remote_get( $url, $this->curl_args );
			$products = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$updated_products['update_ids']       = isset( $products->updatedProductIds ) ? $products->updatedProductIds : array();
				$updated_products['update_ids_count'] = count( $updated_products['update_ids'] );
				$$updated_products['delete_ids']      = isset( $products->deletedProductIds ) ? $products->deletedProductIds : array();
				$updated_products['delete_ids_count'] = count( $updated_products['delete_ids'] );
			}

			$this->update_last_sync_date();
		}

		return $updated_products;
	}

	public function get_batched_product_ids() {
		$api_url      = get_option( 'ebridge_sync_api_url', '' );
		$api_token    = get_option( 'ebridge_sync_api_token', '' );
		$all_products = array(
			'update_ids'       => array(),
			'delete_ids'       => array(),
			'update_ids_count' => 0,
			'delete_ids_count' => 0,
		);

		if ( $api_url && $api_token ) {
			$url         = $api_url . '/' . $api_token . '/productsync?returnMode=2';
			$response    = wp_remote_get( $url, $this->curl_args );
			$product_ids = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$products                         = isset( $product_ids->updatedProductIds ) ? $product_ids->updatedProductIds : array();
				$product_ids                      = $this->get_batched_products( 'wews_product_all_update_start', $products );
				$all_products['update_ids_count'] = count( $product_ids );
				$all_products['update_ids']       = $product_ids;

				$products                         = isset( $product_ids->deletedProductIds ) ? $product_ids->deletedProductIds : array();
				$product_ids                      = $this->get_batched_products( 'wews_product_all_delete_start', $products );
				$all_products['delete_ids_count'] = count( $product_ids );
				$all_products['delete_ids']       = $product_ids;

				$this->update_last_sync_date();
			}
		}

		return $all_products;
	}

	public function get_batched_products( $option, $products ) {
		$start         = get_option( $option, 0 );
		$product_count = ( count( $products ) > ( $start + WEWS_FETCH_SIZE ) ) ? ( $start + WEWS_FETCH_SIZE ) : count( $products );
		$product_ids   = array();

		for ( $i = $start; $i < $product_count; $i++ ) {
			$product_ids[] = $products[ $i ];
		}

		if ( $product_count < count( $products ) ) {
			update_option( $option, $product_count );
		} else {
			update_option( $option, 0 );
		}

		return $product_ids;
	}


	public function update_last_sync_date() {
		$updated = get_option( 'wews_product_update_start', 0 );
		$deleted = get_option( 'wews_product_delete_start', 0 );

		if ( ( $updated == 0 ) && ( $deleted == 0 ) ) {
			update_option( 'ebridge_sync_last_updated_date', date( 'm-d-Y' ) );
			update_option( 'ebridge_sync_last_updated_time', date( 'H:i' ) );
		}
	}


	public function get_last_updated_batched_product_ids() {
		$api_url          = get_option( 'ebridge_sync_api_url', '' );
		$api_token        = get_option( 'ebridge_sync_api_token', '' );
		$updated_products = array(
			'update_ids'       => array(),
			'delete_ids'       => array(),
			'update_ids_count' => 0,
			'delete_ids_count' => 0,
		);

		if ( $api_url && $api_token ) {
			$last_updated_date           = get_option( 'ebridge_sync_last_updated_date', '' );
			$last_updated_time           = get_option( 'ebridge_sync_last_updated_time', '00:00' );
			$web_server_time_zone_offset = get_option( 'ebridge_sync_web_server_time_zone_offset', '0' );

			if ( $last_updated_date ) {
				$url = $api_url . '/' . $api_token . '/productsync?beginDate=' . $last_updated_date . '&beginTime=' . $last_updated_time . '&webServerTimeZoneOffset=' . $web_server_time_zone_offset;
			} else {
				$url = $api_url . '/' . $api_token . '/productsync?returnMode=2';
			}

			$response    = wp_remote_get( $url, $this->curl_args );
			$product_ids = json_decode( wp_remote_retrieve_body( $response ) );

			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$products                             = isset( $product_ids->updatedProductIds ) ? $product_ids->updatedProductIds : array();
				$product_ids                          = $this->get_batched_products( 'wews_product_update_start', $products );
				$updated_products['update_ids_count'] = count( $product_ids );
				$updated_products['update_ids']       = $product_ids;

				$products                             = isset( $product_ids->deletedProductIds ) ? $product_ids->deletedProductIds : array();
				$product_ids                          = $this->get_batched_products( 'wews_product_delete_start', $products );
				$updated_products['delete_ids_count'] = count( $product_ids );
				$updated_products['delete_ids']       = $product_ids;

				$this->update_last_sync_date();
			}
		}

		return $updated_products;
	}

	public function get_image_id( $images ) {
		if ( $images ) {
			$image_file_name = basename( $images[0]->url );

			// $info            = pathinfo( basename( $images[0]->url ) );
			// $image_file_name = $info['filename'];

			global $wpdb;
			$results = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_value like '%$image_file_name%'", OBJECT );
			if ( isset( $results ) && array_key_exists( 0, $results ) ) {
				return $results[0]->post_id;
			}
		}

		return null;
	}


	public function get_date_time_object( $date_str ) {
		if ( $date_str ) {
			preg_match( '#/Date\((\d{10})\d{3}(.*?)\)/#', $date_str, $date_obj );
			return $date_obj[1];
		}

		return null;
	}


	public function tag_to_set( $tag_id, $tag_name ) {

		$tag = get_term_by(
			'slug',
			sanitize_title( $tag_id ),
			'product_tag'
		);

		if ( ! $tag ) {
			wp_insert_term(
				sanitize_text_field( str_replace( '"', '', $tag_name ) ),
				'product_tag',
				array(
					'description' => $tag_id,
					'slug'        => sanitize_title( $tag_id ),
				)
			);

			$tag = get_term_by(
				'slug',
				sanitize_title( $tag_id ),
				'product_tag'
			);
		}

		return $tag->term_id;
	}


	public function get_tag_ids( $product_obj ) {
		$tags = array();

		if ( isset( $product_obj->seo->keywords ) ) {
			$keywords = $product_obj->seo->keywords;
			$keywords = str_replace( ' ', '', $keywords );
			$keywords = explode( ',', $keywords );

			foreach ( $keywords as $key => $keyword ) {
				$tags[] = $this->tag_to_set( $keyword, $keyword );
			}
		}

		if ( isset( $product_obj->webMasterId ) ) {
			$tags[] = $this->tag_to_set( $product_obj->webMasterId, $product_obj->webMasterDescription );
		}

		if ( isset( $product_obj->vendor ) ) {
			$tags[] = $this->tag_to_set( $product_obj->vendor->id, $product_obj->vendor->name );
		}

		if ( isset( $product_obj->description2 ) && ( $product_obj->description2 !== '' ) ) {
			$tags[] = $this->tag_to_set( $product_obj->description2, $product_obj->description2 );
		}

		return $tags;
	}


	/**
	 * Creates/Updates a WooCommerce Grouped Product.
	 *
	 * @since    1.0.0
	 * @param      object $product_obj       The Ebridge Grouped Product Object.
	 */
	public function create_product_bundle( $product_obj ) {
		$product_id     = get_option( 'product_' . $product_obj->id, '' );
		$child_products = array();

		if ( ! $product_id ) {
			$product = new WC_Product_Bundle( 0 );
		} else {

			$product = wc_get_product( $product_id );
			
			if( ! $product->is_type( 'bundle' ) ) {
				$product->delete(true);

				// Delete parent product transients.
				if ($parent_id = wp_get_post_parent_id($product_id)) {
					wc_delete_product_transients($parent_id);
				}

				delete_option( 'product_' . $product_id );

				$product = new WC_Product_Bundle( 0 );
			}

			$current_child_products = wc_pb_get_bundled_product_map( $product );

			$last_sync_time = $product->get_meta( 'product_last_synced', true );

			$current_time        = current_time( 'timestamp', true );
			$fifteen_mins_before = $current_time - ( MINUTE_IN_SECONDS * 15 );

			if ( $last_sync_time > $fifteen_mins_before ) {
				return $product->get_id();
			}
		}

		$current_child_product_ids = $product->get_bundled_item_ids();

		foreach ( $current_child_product_ids as $key => $current_child_product_id ) {
			$current_child_product = new WC_Bundled_Item_Data( $current_child_product_id );
			$current_child_product->delete();
		}

		$product = $this->set_product_common_data( $product, $product_obj );

		foreach ( $product_obj->kitComponents as $key => $value ) {
			$child_product_id = get_option( 'product_' . $value->id, '' );

			if ( ! $child_product_id ) {
				$child_product_id = $this->create_product( $value->id );

				if ( $child_product_id ) {
					$this->updated_products[ $value->id ] = $child_product_id;
					$child_products[]                     = $child_product_id;
				}
			} else {
				$child_products[] = $child_product_id;
			}

			$args = array(
				'product_id'   => $child_product_id,
				'bundle_id'    => $product->get_id(),
				'quantity_min' => $value->quantity,
				'quantity_max' => $value->quantity,
				// 'menu_order' => 0,
				// 'meta_data'  => array()
			);

			$result = WC_PB_DB::add_bundled_item( $args );
			$item   = new WC_Bundled_Item_Data( $result );
			$item->update_meta( 'single_product_visibility', 'hidden' );
			$item->save();
		}

		// $product->set_children( $child_products );
		$product->save();

		return $product->get_id();
	}

	public function set_product_availability( $product, $product_obj ) {
		if ( isset( $product_obj->kitComponents ) ) {
			$net_quantity   = WEWS_MAX_NET_QUANTITY;
			$product_to_set = null;
			$backorders     = false;

			foreach ( $product_obj->kitComponents as $key => $value ) {
				$response        = wp_remote_get( $this->api_url . '/' . $this->api_token . '/products/' . $value->id, $this->curl_args );
				$kit_product_obj = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ( wp_remote_retrieve_response_code( $response ) == 200 ) && isset( $kit_product_obj->product ) ) {
					$kit_product_obj = $kit_product_obj->product;

					if ( isset( $kit_product_obj->inventory ) ) {
						$kit_product_net_quantity = $kit_product_obj->inventory->netQuantityAvailable;

						if ( $kit_product_net_quantity < $net_quantity ) {
							$net_quantity   = $kit_product_net_quantity;
							$product_to_set = $kit_product_obj;

							if ( isset( $kit_product_obj->inventory->locations ) ) {
								$locations = $kit_product_obj->inventory->locations;

								$new_backorders = false;
								foreach ( $locations as $key => $location ) {
									if ( is_numeric( $location->leadDays ) && ( $net_quantity === 0 ) ) {
										// $product->set_backorders( 'notify' );
										$new_backorders = true;
									}
								}

								$backorders = $new_backorders;
							}

							$product_to_set = $kit_product_obj;
						} elseif ( $kit_product_net_quantity === $net_quantity ) {
							if ( isset( $kit_product_obj->inventory->locations ) ) {
								$locations = $kit_product_obj->inventory->locations;

								$new_backorders = false;
								foreach ( $locations as $key => $location ) {
									if ( is_numeric( $location->leadDays ) && ( 0 === $net_quantity ) ) {
										// $product->set_backorders( 'notify' );
										$new_backorders = true;
									}
								}

								if ( ( $backorders && $new_backorders ) || ( ! isset( $product_to_set ) && $new_backorders ) ) {
									$backorders     = true;
									$product_to_set = $kit_product_obj;
								}
							}
						}
					}
				}
			}

			if ( ( WEWS_MAX_NET_QUANTITY > $net_quantity ) && ( isset( $product_to_set ) && ( $net_quantity <= $product_obj->inventory->netQuantityAvailable ) ) ) {
				$product->set_manage_stock( true );
				$product->set_stock_quantity( $net_quantity );

				if ( $backorders ) {
					$product->set_backorders( 'notify' );
				}
			}
		}

		return $product;
	}
}

// new Wdm_Ebridge_Woocommerce_Sync_Products();
