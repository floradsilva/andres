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

namespace SkyVerge\WooCommerce\COG\Integrations;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

/**
 * The Subscriptions integration handler.
 *
 * @since 2.8.2
 */
class Subscriptions {


	/**
	 * Constructs the class.
	 *
	 * @since 2.8.2
	 */
	public function __construct() {

		add_filter( 'wc_cost_of_goods_previous_orders_query', [ $this, 'add_subscriptions_previous_orders_query' ] );
	}


	/**
	 * Adds subscriptions to the query when applying costs to previous orders.
	 *
	 * @internal
	 *
	 * @since 2.8.2
	 *
	 * @param array $query_args WP_Query args
	 * @return array
	 */
	public function add_subscriptions_previous_orders_query( $query_args ) {

		$query_args['post_type'] = isset( $query_args['post_type'] ) ? (array) $query_args['post_type'] : [];

		$query_args['post_type'][] = 'shop_subscription';

		return $query_args;
	}


}
