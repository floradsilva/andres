<?php

class POS_Quote {

	public static function init() {
		add_filter( 'op_get_login_cashdrawer_data', __CLASS__ . '::custom_order_field', 20, 1 );
		// add_action( 'op_add_order_after', __CLASS__ . '::custom_order_field_data', 10, 2 );
		add_action( 'op_add_order_data_before', __CLASS__ . '::submit_quote', 10, 2 );
	}

	// order custom data
	public function staticcustom_order_field( $session_response_data ) {

		$addition_checkout_fields = array();

		$addition_checkout_fields[]                                       = array(
			'code'        => 'po_select',
			'type'        => 'select',
			'label'       => 'Test Select',
			'description' => '',
			'require'     => 'yes',
			'default'     => 'no',
			'options'     => array(
				[
					'value' => 'no',
					'label' => 'No',
				],
				[
					'value' => 'yes',
					'label' => 'Yes',
				],
			),
		);
		$session_response_data['setting']['pos_addition_checkout_fields'] = $addition_checkout_fields;

		return $session_response_data;
	}

	// this function use to save or interactive with other system
	public function staticcustom_order_field_data( $order, $order_data ) {
		$order_id             = $order->get_id();
		$addition_information = isset( $order_data['addition_information'] ) ? $order_data['addition_information'] : array();
		// continue logic from here to save or interactive with other system
	}

	// this function use to save or interactive with other system
	public function staticsubmit_quote( $order_parse_data, $session_data ) {
		echo '<pre>';
		echo '===================order_parse_data=================<br>';
		var_dump( $order_parse_data );
		echo '===================session_data=================<br>';
		var_dump( $session_data );
		echo '================================================<br>';
		echo '</pre>';
		die;
	}
}
