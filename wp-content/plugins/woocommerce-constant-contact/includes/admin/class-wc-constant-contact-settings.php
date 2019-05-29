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

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

/**
 * Admin class.
 *
 * Loads / saves admin settings.
 *
 * @since 1.0
 */
class WC_Constant_Contact_Settings extends \WC_Settings_Page {


	/**
	 * Sets up the admin class.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->id    = 'constant_contact';
		$this->label = __( 'Constant Contact', 'woocommerce-constant-contact' );

		parent::__construct();

		// add a select box for available email lists
		add_action( 'woocommerce_admin_field_wc_constant_contact_list_select', array( $this, 'render_list_select' ) );

		// sanitize the email list option
		add_filter( 'woocommerce_admin_settings_sanitize_option_wc_constant_contact_email_list', array( $this, 'sanitize_option_wc_constant_contact_email_list' ), 10, 3 );
	}


	/**
	 * Returns settings array for use by render/save/install default settings methods.
	 *
	 * @internal
	 *
	 * @since 1.0
	 *
	 * @return array settings
	 */
	public function get_settings() {

		return apply_filters( 'wc_constant_contact_settings', array(

			// general settings
			array(
				'title' => __( 'General Settings', 'woocommerce-constant-contact' ),
				'type'  => 'title',
				'id'    => 'wc_constant_contact_general_settings_start',
			),

			// checkbox label
			array(
				'id'       => 'wc_constant_contact_subscribe_checkbox_label',
				'name'     => __( 'Subscribe Checkbox Label', 'woocommerce-constant-contact' ),
				'desc_tip' => __( 'Text displayed next to the opt-in checkbox on the Checkout page.', 'woocommerce-constant-contact' ),
				'css'      => 'min-width: 275px;',
				'default'  => __( 'Subscribe to our newsletter?', 'woocommerce-constant-contact' ),
				'type'     => 'text',
			),

			// checkbox default
			array(
				'id'       => 'wc_constant_contact_subscribe_checkbox_default',
				'name'     => __( 'Subscribe Checkbox Default', 'woocommerce-constant-contact' ),
				'desc_tip' => __( 'Default status for the Subscribe checkbox on the Checkout page.', 'woocommerce-constant-contact' ),
				'default'  => 'unchecked',
				'type'     => 'select',
				'options'  => array(
					'unchecked' => __( 'Unchecked', 'woocommerce-constant-contact' ),
					'checked'   => __( 'Checked', 'woocommerce-constant-contact' ),
				),
			),

			// list to subscribe customers to
			array(
				'id'       => 'wc_constant_contact_email_list',
				'name'     => __( 'Email List', 'woocommerce-constant-contact' ),
				'desc_tip' => __( "This is the email list that customer's will be added to when they opt-in at checkout", 'woocommerce-constant-contact' ),
				'css'      => 'min-width: 275px;',
				'type'     => 'wc_constant_contact_list_select',
			),

			array( 'type' => 'sectionend', 'id' => 'wc_constant_contact_general_settings_end' ),

			// API settings
			array(
				'title' => __( 'API Settings', 'woocommerce-constant-contact' ),
				'type'  => 'title',
				'id'    => 'wc_constant_contact_api_settings_start',
			),

			// username
			array(
				'id'       => 'wc_constant_contact_username',
				'name'     => __( 'ConstantContact.com Username', 'woocommerce-constant-contact' ),
				'desc_tip' => __( 'This is the username you use to log into your Constant Contact account.', 'woocommerce-constant-contact' ),
				'type'     => 'text',
			),

			// password
			array(
				'id'       => 'wc_constant_contact_password',
				'name'     => __( 'ConstantContact.com Password', 'woocommerce-constant-contact' ),
				'desc_tip' => __( 'This is the password you use to log into your Constant Contact account.', 'woocommerce-constant-contact' ),
				'type'     => 'password',
			),

			// API key
			array(
				'id'       => 'wc_constant_contact_api_key',
				'name'     => __( 'API Key', 'woocommerce-constant-contact' ),
				'desc_tip' => __( 'This is the API Key for your Constant Contact account. Read the documentation to learn how to get this.', 'woocommerce-constant-contact' ),
				'type'     => 'text',
			),

			// debug mode
			array(
				'id'      => 'wc_constant_contact_debug_mode',
				'name'    => __( 'Debug Mode', 'woocommerce-constant-contact' ),
				/* translators: Placeholder: %s - debug log file name and full path */
				'desc'    => sprintf( '<br>' . __( 'Save API requests/responses and Detailed Error Messages to the debug log: %s', 'woocommerce-constant-contact' ), '<br><strong class="nobr">' . wc_get_log_file_path( wc_constant_contact()->get_id() ) . '</strong>' ),
				'default' => 'off',
				'type'    => 'select',
				'options' => array(
					'off' => __( 'Off', 'woocommerce-constant-contact' ),
					'on'  => __( 'On', 'woocommerce-constant-contact' ),
				),
			),

			array( 'type' => 'sectionend', 'id' => 'wc_constant_contact_api_settings_end' ),

		) );
	}


	/**
	 * Renders the select box for the email lists available in the constant contact account.
	 *
	 * @internal
	 *
	 * @since 1.0
	 *
	 * @param array $field associative array of field parameters
	 */
	public function render_list_select( $field ) {

		if ( isset( $field['id'], $field['name'] ) ) :

			$selected_list = get_option( $field['id'] );

			// get lists
			if ( wc_constant_contact()->get_api() ) {

				try {

					$lists = array_merge( array( '' => __( 'Select a List', 'woocommerce-constant-contact' ) ), wc_constant_contact()->get_api()->get_lists() );

				} catch ( Framework\SV_WC_API_Exception $e ) {

					$lists = array( '' => sprintf( __( 'Oops, something went wrong: %s', 'woocommerce-constant-contact' ), $e->getMessage() ) );
				}

			} else {

				$lists = array( '' => __( 'Lists will appear after entering API info.', 'woocommerce-constant-contact' ) );
			}

			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for=""><?php echo wp_kses_post( $field['name'] ); ?></label>
					<?php wc_help_tip( $field['desc_tip'] ); ?>
				</th>
				<td class="forminp forminp-text">
					<fieldset>
						<select name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="wc-enhanced-select" style="<?php echo esc_attr( isset( $field['css'] ) ? $field['css'] : '' ); ?>">
							<?php foreach ( $lists as $list_id => $list_name ) : ?>
							<option value="<?php echo esc_attr( $list_id ); ?>" <?php selected( $selected_list, $list_id ); ?>><?php echo esc_html( $list_name ); ?></option>
							<?php endforeach; ?>
						</select>
					</fieldset>
				</td>
			</tr>
			<?php

		endif;

		// disable the select if API info is not populated
		wc_enqueue_js( '
			if ( ! $( "input#wc_constant_contact_username" ).val() || ! $( "input#wc_constant_contact_password" ).val() || ! $( "input#wc_constant_contact_api_key" ).val() ) {
				$( "select#wc_constant_contact_email_list" ).prop( "disabled", true );
			}
		' );
	}


	/**
	 * Sanitizes the Constant Contact email list option.
	 *
	 * WooCommerce wc_clean()'s the option value before saving it.
	 * Unfortunately, this removes the much needed `%40` chars from the email list.
	 *
	 * @internal
	 *
	 * @since 1.5.1
	 *
	 * @param string $value
	 * @param array $option
	 * @param string $raw_value
	 * @return string
	 */
	public function sanitize_option_wc_constant_contact_email_list( $value, $option, $raw_value ) {

		return esc_url_raw( $raw_value );
	}


}
