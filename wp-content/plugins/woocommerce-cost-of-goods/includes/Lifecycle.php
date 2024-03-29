<?php
/**
 * WooCommerce Cost of Goods
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Cost of Goods to newer
 * versions in the future. If you wish to customize WooCommerce Cost of Goods for your
 * needs please refer to http://docs.woocommerce.com/document/cost-of-goods/ for more information.
 *
 * @author      SkyVerge
 * @copyright   Copyright (c) 2013-2019, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\COG;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

/**
 * Plugin lifecycle handler.
 *
 * @since 2.8.0
 *
 * @method \WC_COG get_plugin()
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Constructs the class.
	 *
	 * @since 2.8.2
	 *
	 * @param \WC_COG $plugin plugin instance
	 */
	public function __construct( \WC_COG $plugin ) {

		parent::__construct( $plugin );

		$this->upgrade_versions = [
			'1.1.0',
			'1.3.3',
		];
	}


	/**
	 * Handles plugin installation routine.
	 *
	 * @since 2.8.0
	 */
	protected function install() {

		require_once( $this->get_plugin()->get_plugin_path() . '/includes/admin/class-wc-cog-admin.php' );

		$this->install_default_settings( \WC_COG_Admin::get_global_settings() );
	}


	/**
	 * Updates to v1.1.0.
	 *
	 * @since 2.8.2
	 */
	protected function upgrade_to_1_1_0() {

		// page through the variable products in blocks to avoid out of memory errors
		$offset         = (int) get_option( 'wc_cog_variable_product_offset', 0 );
		$posts_per_page = 500;

		do {

			// grab a set of variable product ids
			$product_ids = get_posts( [
				'post_type'      => 'product',
				'fields'         => 'ids',
				'offset'         => $offset,
				'posts_per_page' => $posts_per_page,
				'tax_query'      => [
					[
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => [ 'variable' ],
						'operator' => 'IN',
					],
				],
			] );

			// some sort of bad database error: deactivate the plugin and display an error
			if ( is_wp_error( $product_ids ) ) {

				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				deactivate_plugins( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' );

				/* @type \WP_Error $product_ids */
				/* translators: Placeholders: %s - error messages */
				$error_message = sprintf( __( 'Error upgrading <strong>WooCommerce Cost of Goods</strong>: %s', 'woocommerce-cost-of-goods' ), '<ul><li>' . implode( '</li><li>', $product_ids->get_error_messages() ) . '</li></ul>' );

				wp_die( $error_message . ' <a href="' . admin_url( 'plugins.php' ) . '">' . __( '&laquo; Go Back', 'woocommerce-cost-of-goods' ) . '</a>' );
			}

			// otherwise go through the results and set the min/max/cost
			if ( is_array( $product_ids ) ) {

				foreach ( $product_ids as $product_id ) {

					$cost = \WC_COG_Product::get_cost( $product_id );

					if ( '' === $cost && ( $product = wc_get_product( $product_id ) ) ) {

						// get the minimum and maximum costs associated with the product
						list( $min_variation_cost, $max_variation_cost ) = \WC_COG_Product::get_variable_product_min_max_costs( $product_id );

						Framework\SV_WC_Product_Compatibility::update_meta_data( $product, '_wc_cog_cost',               wc_format_decimal( $min_variation_cost ) );
						Framework\SV_WC_Product_Compatibility::update_meta_data( $product, '_wc_cog_min_variation_cost', wc_format_decimal( $min_variation_cost ) );
						Framework\SV_WC_Product_Compatibility::update_meta_data( $product, '_wc_cog_max_variation_cost', wc_format_decimal( $max_variation_cost ) );
					}
				}
			}

			// increment offset
			$offset += $posts_per_page;

			// and keep track of how far we made it in case we hit a script timeout
			update_option( 'wc_cog_variable_product_offset', $offset );

		} while ( count( $product_ids ) === $posts_per_page );  // while full set of results returned  (meaning there may be more results still to retrieve)
	}


	/**
	 * Updates to v1.3.3.
	 *
	 * In this version we are setting any variable product default costs, at the variation level with an indicator.
	 *
	 * @since 2.8.2
	 */
	protected function upgrade_to_1_3_3() {

		// page through the variable products in blocks to avoid out of memory errors
		$offset         = (int) get_option( 'wc_cog_variable_product_offset2', 0 );
		$posts_per_page = 500;

		do {

			// grab a set of variable product ids
			$product_ids = get_posts( [
				'post_type'      => 'product',
				'fields'         => 'ids',
				'offset'         => $offset,
				'posts_per_page' => $posts_per_page,
				'tax_query'      => [
					[
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => [ 'variable' ],
						'operator' => 'IN',
					],
				],
			] );

			// some sort of bad database error: deactivate the plugin and display an error.
			if ( is_wp_error( $product_ids ) ) {

				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				// hardcode the plugin path so that we can use symlinks in development.
				deactivate_plugins( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' );

				/* @type \WP_Error $product_ids */
				/* translators: Placeholders: %s - error messages */
				$error_message = sprintf( __( 'Error upgrading <strong>WooCommerce Cost of Goods</strong>: %s', 'woocommerce-cost-of-goods' ), '<ul><li>' . implode( '</li><li>', $product_ids->get_error_messages() ) . '</li></ul>' );

				wp_die( $error_message . ' <a href="' . admin_url( 'plugins.php' ) . '">' . __( '&laquo; Go Back', 'woocommerce-cost-of-goods' ) . '</a>' );

			// ...otherwise go through the results and set the min/max/cost.
			} elseif ( is_array( $product_ids ) ) {

				foreach ( $product_ids as $product_id ) {

					if ( $product = wc_get_product( $product_id ) ) {

						$default_cost = Framework\SV_WC_Product_Compatibility::get_meta( $product, '_wc_cog_cost_variable', true );

						// get all child variations
						$children = get_posts( [
							'post_parent'    => $product_id,
							'posts_per_page' => -1,
							'post_type'      => 'product_variation',
							'fields'         => 'ids',
							'post_status'    => 'publish',
						] );

						if ( $children ) {

							foreach ( $children as $child_product_id ) {

								if ( $child_product = wc_get_product( $child_product_id ) ) {

									// cost set at the child level?
									$cost = Framework\SV_WC_Product_Compatibility::get_meta( $child_product, '_wc_cog_cost', true );

									if ( '' === $cost && '' !== $default_cost ) {
										// using the default parent cost
										Framework\SV_WC_Product_Compatibility::update_meta_data( $child_product, '_wc_cog_cost', wc_format_decimal( $default_cost ) );
										Framework\SV_WC_Product_Compatibility::update_meta_data( $child_product, '_wc_cog_default_cost', 'yes' );
									} else {
										// otherwise no default cost
										Framework\SV_WC_Product_Compatibility::update_meta_data( $child_product, '_wc_cog_default_cost', 'no' );
									}
								}
							}
						}
					}
				}
			}

			// increment offset
			$offset += $posts_per_page;

			// and keep track of how far we made it in case we hit a script timeout
			update_option( 'wc_cog_variable_product_offset2', $offset );

		} while ( count( $product_ids ) === $posts_per_page );  // while full set of results returned  (meaning there may be more results still to retrieve)
	}


}
