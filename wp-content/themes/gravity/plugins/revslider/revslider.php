<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_revslider_theme_setup9', 9 );
	function gravity_revslider_theme_setup9() {
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',	'gravity_revslider_tgmpa_required_plugins' );
		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'gravity_exists_revslider' ) ) {
	function gravity_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_revslider_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_revslider_tgmpa_required_plugins');
	function gravity_revslider_tgmpa_required_plugins($list=array()) {
		if (in_array('revslider', gravity_storage_get('required_plugins'))) {
			$path = gravity_get_file_dir('plugins/revslider/revslider.zip');
			$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'gravity'),
					'slug' 		=> 'revslider',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}
?>