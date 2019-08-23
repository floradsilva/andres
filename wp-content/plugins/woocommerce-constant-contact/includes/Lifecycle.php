<?php
/**
 * WooCommerce Constant Contact
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
 * Do not edit or add to this file if you wish to upgrade WooCommerce Constant Contact to newer
 * versions in the future. If you wish to customize WooCommerce Constant Contact for your
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-constant-contact/ for more information.
 *
 * @author      SkyVerge
 * @copyright   Copyright (c) 2013-2019, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Constant_Contact;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

/**
 * Constant Contact lifecycle handler.
 *
 * @since 1.10.0
 *
 * @method \WC_Constant_Contact get_plugin()
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Performs installation tasks.
	 *
	 * @since 1.10.0
	 */
	protected function install() {

		$settings = $this->get_plugin()->get_settings_instance();

		if ( ! $settings instanceof \WC_Constant_Contact_Settings ) {

			// load settings so we can install defaults
			include_once( WC()->plugin_path() . '/includes/admin/settings/class-wc-settings-page.php' );

			$settings = $this->get_plugin()->load_class( '/includes/admin/class-wc-constant-contact-settings.php', 'WC_Constant_Contact_Settings' );
		}

		// default settings
		foreach ( $settings->get_settings() as $setting ) {

			if ( isset( $setting['id'], $setting['default'] ) ) {

				add_option( $setting['id'], $setting['default'] );
			}
		}
	}


}
