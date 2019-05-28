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
 * needs please refer to http://www.skyverge.com/contact/ for more information.
 *
 * @author      SkyVerge
 * @copyright   Copyright (c) 2013-2019, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_4_0 as Framework;

/**
 * Constant Contact API Request Class
 *
 * Parses XML received by Constant Contact API
 *
 * @since 1.3.1
 */
class WC_Constant_Contact_API_Response implements Framework\SV_WC_API_Response {


	/** @var string string representation of this response */
	private $raw_response_xml;

	/** @var \SimpleXMLElement response XML object */
	protected $response_xml;


	/**
	 * Builds a response object from the raw response xml.
	 *
	 * @since 1.3.1
	 *
	 * @param string $raw_response_xml the raw response XML
	 */
	public function __construct( $raw_response_xml ) {

		$this->raw_response_xml = $raw_response_xml;

		if ( $raw_response_xml ) {

			// LIBXML_NOCDATA ensures that any XML fields wrapped in [CDATA] will be included as text nodes
			$this->response_xml = new \SimpleXMLElement( $raw_response_xml, LIBXML_NOCDATA );
		}
	}


	/**
	 * Parses the GET /lists response into an array.
	 *
	 * $list[ $list_id ] => $list_name
	 *
	 * @since 1.1
	 *
	 * @return array
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function get_lists() {

		if ( empty( $this->response_xml->entry ) ) {
			throw new Framework\SV_WC_API_Exception( __( 'Get Lists - Entries are missing', 'woocommerce-constant-contact' ) );
		}

		$lists = array();

		foreach ( $this->response_xml->entry as $list ) {

			// exclude default lists
			if ( in_array( (string) $list->title, array( 'Active', 'Do Not Mail', 'Removed' ) ) ) {
				continue;
			}

			$contact_count = ( isset( $list->content->ContactList->ContactCount ) ) ? $list->content->ContactList->ContactCount : 0;

			// format each list like: "<list name> (<list count> contacts)"
			$lists[ (string) $list->id ] = sprintf( '%1$s (%2$d %3$s)',
				$list->title,
				$contact_count,
				( $contact_count > 0 ) ? _n( 'contact', 'contacts', $contact_count, 'woocommerce-constant-contact' ) : __( 'no contacts', 'woocommerce-constant-contact' )
			);
		}

		return $lists;
	}


	/**
	 * Checks if the response has a feed entry.
	 *
	 * Primarily used for checking if a contact exists.
	 *
	 * @since 1.3.1
	 *
	 * @return bool
	 */
	public function has_entry() {

		return ! empty( $this->response_xml->entry );
	}


	/**
	 * Gets the ID for a contact.
	 *
	 * @since 1.3.1
	 *
	 * @return string
	 * @throws Framework\SV_WC_API_Exception if contact ID is missing
	 */
	public function get_contact_id() {

		if ( empty( $this->response_xml->entry->id ) ) {
			throw new Framework\SV_WC_API_Exception( __( 'Contact ID is missing', 'woocommerce-constant-contact' ) );
		}

		return substr( strrchr( (string) $this->response_xml->entry->id, '/' ), 1 );
	}


	/**
	 * Gets the response ID.
	 *
	 * @since 1.3.1
	 *
	 * @return string
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function get_id() {

		if ( empty( $this->response_xml->id ) ) {
			throw new Framework\SV_WC_API_Exception( __( 'Created contact ID missing', 'woocommerce-constant-contact' ) );
		}

		return (string) $this->response_xml->id;
	}


	/**
	 * Get stats.
	 *
	 * @since 1.3.1
	 *
	 * @return array
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function get_stats() {

		if ( empty( $this->response_xml->id ) ) {
			throw new Framework\SV_WC_API_Exception( __( 'List ID is missing', 'woocommerce-constant-contact' ) );
		}

		if ( ! empty( $this->response_xml->title ) && ! empty( $this->response_xml->content->ContactList->ContactCount ) ) {

			return array(
				'list_name'        => (string) $this->response_xml->title,
				'list_subscribers' => (int) $this->response_xml->content->ContactList->ContactCount
			);

		} else {

			return array();
		}
	}


	/**
	 * Gets the parsed response.
	 *
	 * @return \SimpleXMLElement
	 */
	public function get_parsed_response() {

		return $this->response_xml;
	}


	/**
	 * Returns the string representation of this response.
	 *
	 * @see SV_WC_API_Response::to_string()
	 *
	 * @since 1.3.1
	 *
	 * @return string response
	 */
	public function to_string() {

		$string = $this->raw_response_xml;

		$dom = new \DOMDocument();

		// suppress errors for invalid XML syntax issues
		if ( @$dom->loadXML( $string ) ) {
			$dom->formatOutput = true;
			$string = $dom->saveXML();
		}

		return $string;
	}


	/**
	 * Returns the string representation of this response with any and all sensitive elements masked or removed
	 *
	 * @see SV_WC_API_Response::to_string_safe()
	 *
	 * @since 1.3.1
	 *
	 * @return string response safe for logging/displaying
	 */
	public function to_string_safe() {

		// no sensitive data to mask
		return $this->to_string();
	}


}
