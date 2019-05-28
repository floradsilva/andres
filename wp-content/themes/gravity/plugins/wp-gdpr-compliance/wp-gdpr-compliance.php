<?php
/* gdpr-compliance support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_gdpr_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_gdpr_theme_setup9', 9 );
	function gravity_gdpr_theme_setup9() {
		if (gravity_exists_gdpr()) {
			add_filter( 'gravity_filter_merge_styles',					'gravity_gdpr_merge_styles');
		}
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',		'gravity_gdpr_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gravity_exists_gdpr' ) ) {
	function gravity_exists_gdpr() {
		return function_exists('__gdpr_load_plugin') || defined('GDPR_VERSION');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_gdpr_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_gdpr_tgmpa_required_plugins');
	function gravity_gdpr_tgmpa_required_plugins($list=array()) {
		if (in_array('wp-gdpr-compliance', gravity_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('WP GDPR Compliance', 'gravity'),
				'slug' 		=> 'wp-gdpr-compliance',
				'required' 	=> false
			);
		return $list;
	}
}



