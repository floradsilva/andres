<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_booked_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_booked_theme_setup9', 9 );
	function gravity_booked_theme_setup9() {
		if (gravity_exists_booked()) {
			add_action( 'wp_enqueue_scripts', 							'gravity_booked_frontend_scripts', 1100 );
			add_filter( 'gravity_filter_merge_styles',					'gravity_booked_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',		'gravity_booked_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gravity_exists_booked' ) ) {
	function gravity_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_booked_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_booked_tgmpa_required_plugins');
	function gravity_booked_tgmpa_required_plugins($list=array()) {
		if (in_array('booked', gravity_storage_get('required_plugins'))) {
			$path = gravity_get_file_dir('plugins/booked/booked.zip');
			$list[] = array(
					'name' 		=> esc_html__('Booked Appointments', 'gravity'),
					'slug' 		=> 'booked',
					'source' 	=> !empty($path) ? $path : 'upload://booked.zip',
					'required' 	=> false
			);

			$path = gravity_get_file_dir( 'plugins/booked/booked-calendar-feeds.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'Booked Calendar Feeds', 'gravity' ),
					'slug'     => 'booked-calendar-feeds',
					'source'   => $path,
					'version'  => '1.1.5',
					'required' => false,
				);
			}
			$path = gravity_get_file_dir( 'plugins/booked/booked-frontend-agents.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'Booked Front-End Agents', 'gravity' ),
					'slug'     => 'booked-frontend-agents',
					'source'   => $path,
					'version'  => '1.1.15',
					'required' => false,
				);
			}
			$path = gravity_get_file_dir( 'plugins/booked/booked-woocommerce-payments.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'WooCommerce addons - Booked Payments with WooCommerce', 'gravity' ),
					'slug'     => 'booked-woocommerce-payments',
					'source'   => $path,
					'version'  => '1.5',
					'required' => false,
				);
			}


		}
		return $list;
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'gravity_booked_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'gravity_booked_frontend_scripts', 1100 );
	function gravity_booked_frontend_scripts() {
		if (gravity_is_on(gravity_get_theme_option('debug_mode')) && file_exists(gravity_get_file_dir('plugins/booked/booked.css')))
			wp_enqueue_style( 'gravity-booked',  gravity_get_file_url('plugins/booked/booked.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'gravity_booked_merge_styles' ) ) {
	//Handler of the add_filter('gravity_filter_merge_styles', 'gravity_booked_merge_styles');
	function gravity_booked_merge_styles($list) {
		$list[] = 'plugins/booked/booked.css';
		return $list;
	}
}
?>