<?php

class POS_Quote {

	public static function init() {
		add_filter( 'op_get_login_cashdrawer_data', __CLASS__ . '::custom_order_field', 20, 1 );
		// add_action( 'op_add_order_after', __CLASS__ . '::custom_order_field_data', 10, 2 );
		add_action( 'op_add_order_data_before', __CLASS__ . '::submit_quote', 10, 2 );
	}

	// order custom data
	public static function custom_order_field( $session_response_data ) {

		$addition_checkout_fields = array();

		// $addition_checkout_fields[] = array(
		// 	'code'        => 'po_quote',
		// 	'title' 	  => 'Quote',
		// 	'type'        => 'checkbox',
		// 	'label'       => 'Test Select',
		// 	'require'     => 'yes',
		// 	'default'     => 'no'
		// );

		$addition_checkout_fields[] = array(
            'code' => 'po_quote',
            'type' => 'select',
            'label' => 'Quote',
            'description' => '',
            'require' => 'yes',
            'default' => '',
            'options' => array(
                ['value' => '','label' => 'No'],
                ['value' => 'yes','label' => 'Yes'],
            )
        );
		$session_response_data['setting']['pos_addition_checkout_fields'] = $addition_checkout_fields;

		return $session_response_data;
	}

	// this function use to save or interactive with other system
	public static function custom_order_field_data( $order, $order_data ) {
		$order_id             = $order->get_id();
		$addition_information = isset( $order_data['addition_information'] ) ? $order_data['addition_information'] : array();
		// continue logic from here to save or interactive with other system
	}

	// this function use to save or interactive with other system
	public static function submit_quote( $order_parse_data, $session_data ) {
		$order_id = $order_parse_data['order_number'];
		$is_quote = strtolower($order_parse_data['addition_information']['po_quote']);
		update_post_meta($order_id, '_pos_order_data', $order_parse_data);

		$api_url      = get_option( 'ebridge_sync_api_url', false );
		$api_token    = get_option( 'ebridge_sync_api_token', false );
		
		if ($api_token && $api_url && $is_quote == 'yes') {
		// if ($api_token && $api_url) {

			$url = $api_url . '/' . $api_token . '/quotes/';
					
			$eb_sync_ord = new Wdm_Ebridge_Woocommerce_Sync_Orders();

			$data['billing_first_name']     = !empty( $order_parse_data['customer']['firstname'] ) ? $order_parse_data['customer']['firstname'] : '-';
			$data['billing_last_name']      = !empty( $order_parse_data['customer']['lastname'] ) ? $order_parse_data['customer']['lastname'] : $order_parse_data['customer']['firstname'];
			$data['billing_address_1']      = !empty( $order_parse_data['customer']['address_1'] ) ? $order_parse_data['customer']['address_1'] : '-';
			$data['billing_address_2']      = !empty( $order_parse_data['customer']['address_2'] ) ? $order_parse_data['customer']['address_2'] : '-';
			$data['billing_city']          	= !empty( $order_parse_data['customer']['city'] ) ? $order_parse_data['customer']['city'] : '-';
			$data['billing_state']         	= !empty( $order_parse_data['customer']['state'] ) ? $order_parse_data['customer']['state'] : '-';
			$data['billing_postcode']       = !empty( $order_parse_data['customer']['postcode'] ) ? $order_parse_data['customer']['postcode'] : '-';
			$data['billing_phone']     		= !empty( $order_parse_data['customer']['phone'] ) ? $order_parse_data['customer']['phone'] : '-';
			$data['billing_email']  		= !empty( $order_parse_data['customer']['email'] ) ? $order_parse_data['customer']['email'] : '-';

			$billing_address_data['firstName']     = !empty( $data['billing_first_name'] ) ? $data['billing_first_name'] : '-';
			$billing_address_data['middleInitial'] = '-';
			$billing_address_data['lastName']      = !empty( $data['billing_last_name'] ) ? $data['billing_last_name'] : $data['billing_first_name'];
			$billing_address_data['address1']      = !empty( $data['billing_address_1'] ) ? $data['billing_address_1'] : '-';
			$billing_address_data['address2']      = !empty( $data['billing_address_2'] ) ? $data['billing_address_2'] : '-';
			$billing_address_data['city']          = !empty( $data['billing_city'] ) ? $data['billing_city'] : '-';
			$billing_address_data['state']         = !empty( $data['billing_state'] ) ? $data['billing_state'] : '-';
			$billing_address_data['zipCode']       = !empty( $data['billing_postcode'] ) ? $data['billing_postcode'] : '-';
			$billing_address_data['cellPhone']     = !empty( $data['billing_phone'] ) ? $data['billing_phone'] : '-';
			$billing_address_data['emailAddress']  = !empty( $data['billing_email'] ) ? $data['billing_email'] : '-';

			$shipping_address_data['firstName']     = !empty( $order_parse_data['customer']['shipping_address'][0]['name'] ) ? $order_parse_data['customer']['shipping_address'][0]['name'] : '-';
			$shipping_address_data['lastName']     = !empty( $order_parse_data['customer']['shipping_address'][0]['name'] ) ? $order_parse_data['customer']['shipping_address'][0]['name'] : '-';
			$shipping_address_data['middleInitial'] = '-';
			$shipping_address_data['address1']      = !empty( $order_parse_data['customer']['shipping_address'][0]['address'] ) ? $order_parse_data['customer']['shipping_address'][0]['address'] : '-';
			$shipping_address_data['address2']      = !empty( $order_parse_data['customer']['shipping_address'][0]['address_2'] ) ? $order_parse_data['customer']['shipping_address'][0]['address_2'] : '-';
			$shipping_address_data['city']          = !empty( $order_parse_data['customer']['shipping_address'][0]['city'] ) ? $order_parse_data['customer']['shipping_address'][0]['city'] : '-';
			$shipping_address_data['state']         = !empty( $order_parse_data['customer']['shipping_address'][0]['state'] ) ? $order_parse_data['customer']['shipping_address'][0]['state'] : '-';
			$shipping_address_data['zipCode']       = !empty( $order_parse_data['customer']['shipping_address'][0]['postcode'] ) ? $order_parse_data['customer']['shipping_address'][0]['postcode'] : '-';
			$shipping_address_data['cellPhone']     = !empty( $order_parse_data['customer']['shipping_address'][0]['phone'] ) ? $order_parse_data['customer']['shipping_address'][0]['phone'] : '-';
			$shipping_address_data['emailAddress']  = !empty( $order_parse_data['customer']['email'] ) ? $order_parse_data['customer']['email'] : '-';

			$customer_obj = new Wdm_Ebridge_Woocommerce_Sync_Customer();
			$phone = $order_parse_data['customer']['phone'];
			$email = $order_parse_data['customer']['email'];
			
			$eb_cust_id = $customer_obj->find_ebridge_customer_by_phone_email( $phone, $email );

			if ( ! $eb_cust_id ) {
				$eb_cust_id = $customer_obj->create_ebridge_customer( $data, $order );
			}

			$quote_data                   = array();
			$quote_data['billingAddress'] = $eb_sync_ord->get_billing_address_data('', $data, '');
			$quote_data['cartItems']      = self::get_cart_data($order_parse_data['items']);
			$quote_data['cellPhone']      = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '-';
			$quote_data['charges']        = array();
			$quote_data['customerId']     = $eb_cust_id;
			$quote_data['emailAddress']   = isset( $email ) ? $email : '-';
			$quote_data['salesTax']       = 0;
			$quote_data['shippingAddress'] = $shipping_address_data;

			// print_r($quote_data);
			// echo json_encode( $quote_data );
			update_post_meta($order_id, '_pos_quote_data', $quote_data);

			$response = wp_remote_post(
				$url,
				array(
					'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
					'body'        => json_encode( $quote_data ),
					'method'      => 'POST',
					'data_format' => 'body',
					'timeout'     => 6000,
				)
			);
			$json_response = json_decode( wp_remote_retrieve_body( $response ) );

			update_post_meta($order_id, '_pos_quote_response', $json_response);
			// print_r($response);

			if ( ( 200 == wp_remote_retrieve_response_code( $response ) ) && ( 0 === $json_response->status ) ) {
				update_post_meta($order_id, 'is_pos_quote', 'yes');
				update_post_meta($order_id, 'ebridge_order_id', $json_response->salesOrderCreationResponse->createdSalesOrders[0]->orderId);
				update_post_meta($order_id, 'ebridge_order_type', $json_response->salesOrderCreationResponse->createdSalesOrders[0]->orderType);
			}
		}
	}

	public static function get_cart_data($items) {
		$cart_item_data = array();
		if ($items && is_array($items)) {
			foreach ( $items as $item ) {
				$product = $item['product'];
				$cart_item = array();
				$cart_item['description']          = $product['name'];
				$cart_item['id']                   = $product['sku'];
				$cart_item['lineItemDeliveryType'] = 2;
				$cart_item['price']                = $product['price'];
				$cart_item['quantity']             = $item['qty'];
				$cart_item['lineItemCommentData']  = 'POS QUOTE Order';

				$cart_item_data[] = $cart_item;
			}
		}

		return $cart_item_data;
	}
}

function wdm_add_quote_col( $columns ) {
    $columns['wdm_pos_quote'] = 'Quote';
	return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'wdm_add_quote_col' );

function wdm_add_quote_col_data($column) {
	global $post;

    if ( 'wdm_pos_quote' === $column ) {
		$order_id = $post->ID;
		$is_pos_quote = strtolower(get_post_meta($order_id, 'is_pos_quote', true));

		if ($is_pos_quote == "yes") {
			echo "<span class='dashicons dashicons-yes-alt'></span>";
		}
	}
}
add_action( 'manage_shop_order_posts_custom_column', 'wdm_add_quote_col_data' );

