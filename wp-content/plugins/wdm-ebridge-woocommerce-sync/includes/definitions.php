<?php

define(
	'STATES_POSTAL_CODES',
	array(
		'Alabama'        => 'AL',
		'Alaska'         => 'AK',
		'Arizona'        => 'AZ',
		'Arkansas'       => 'AR',
		'California'     => 'CA',
		'Colorado'       => 'CO',
		'Connecticut'    => 'CT',
		'Delaware'       => 'DE',
		'Florida'        => 'FL',
		'Georgia'        => 'GA',
		'Hawaii'         => 'HI',
		'Idaho'          => 'ID',
		'Illinois'       => 'IL',
		'Indiana'        => 'IN',
		'Iowa'           => 'IA',
		'Kansas'         => 'KS',
		'Kentucky'       => 'KY',
		'Louisiana'      => 'LA',
		'Maine'          => 'ME',
		'Maryland'       => 'MD',
		'Massachusetts'  => 'MA',
		'Michigan'       => 'MI',
		'Minnesota'      => 'MN',
		'Mississippi'    => 'MS',
		'Missouri'       => 'MO',
		'Montana'        => 'MT',
		'Nebraska'       => 'NE',
		'Nevada'         => 'NV',
		'New Hampshire'  => 'NH',
		'New Jersey'     => 'NJ',
		'New Mexico'     => 'NM',
		'New York'       => 'NY',
		'North Carolina' => 'NC',
		'North Dakota'   => 'ND',
		'Ohio'           => 'OH',
		'Oklahoma'       => 'OK',
		'Oregon'         => 'OR',
		'Pennsylvania'   => 'PA',
		'Rhode Island'   => 'RI',
		'South Carolina' => 'SC',
		'South Dakota'   => 'SD',
		'Tennessee'      => 'TN',
		'Texas'          => 'TX',
		'Utah'           => 'UT',
		'Vermont'        => 'VT',
		'Virginia'       => 'VA',
		'Washington'     => 'WA',
		'West Virginia'  => 'WV',
		'Wisconsin'      => 'WI',
		'Wyoming'        => 'WY',
	)
);

define(
	'ORDER_STATUS',
	array(
		'Order'                => 1,
		'Invoice'              => 2,
		'ServiceOrder'         => 4,
		'Exchange'             => 5,
		'Layaway'              => 6,
		'SplitTicket'          => 7,
		'Quote'                => 8,
		'Delivery'             => 9,
		'Pickup'               => 10,
		'DirectShip'           => 11,
		'ReturnOrder'          => 12,
		'TakeWith'             => 13,
		'MultiShippingMaster'  => 14,
		'ManufacturerDelivery' => 15,
		'Void'                 => '',
	)
);

define(
	'ADDRESS_TYPE',
	array(
		'Undefined' => 0,
		'Customer'  => 1,
		'Billing'   => 2,
		'Shipping'  => 3,
	)
);


define(
	'DELIVERY_STATUS',
	array(
		'EST'  => 0,
		'SCD'  => 1,
		'CWC'  => 2,
		'ASAP' => 3,
	)
);


define(
	'ORDER_TYPE_ORDER_DETAILS',
	array(
		'Open Sales Order'      => 1,
		'Delivered Sales Order' => 2,
		'Gift Certificate'      => 3,
		'Void'                  => 4,
	)
);


define(
	'ORDER_TYPE',
	array(
		'Order'            => 1,
		'Gift Certificate' => 3,
	)
);


define(
	'ORDER_STATUS_TO_TYPE',
	array(
		'Order'                => 1,
		'Invoice'              => '',
		'ServiceOrder'         => '',
		'Exchange'             => '',
		'Layaway'              => '',
		'SplitTicket'          => '',
		'Quote'                => '',
		'Delivery'             => 1,
		'Pickup'               => 1,
		'DirectShip'           => '',
		'ReturnOrder'          => '',
		'TakeWith'             => '',
		'MultiShippingMaster'  => '',
		'ManufacturerDelivery' => '',
		'Void'                 => '',
	)
);


define(
	'VALID_ORDER_STATUSES',
	array(
		'Order',
		'Delivery',
		'Pickup',
	)
);
