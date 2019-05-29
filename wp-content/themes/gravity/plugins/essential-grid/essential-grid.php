<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_essential_grid_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_essential_grid_theme_setup9', 9 );
	function gravity_essential_grid_theme_setup9() {
		if (gravity_exists_essential_grid()) {
			add_action( 'wp_enqueue_scripts', 							'gravity_essential_grid_frontend_scripts', 1100 );
			add_filter( 'gravity_filter_merge_styles',					'gravity_essential_grid_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',		'gravity_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gravity_exists_essential_grid' ) ) {
	function gravity_exists_essential_grid() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_essential_grid_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_essential_grid_tgmpa_required_plugins');
	function gravity_essential_grid_tgmpa_required_plugins($list=array()) {
		if (in_array('essential-grid', gravity_storage_get('required_plugins'))) {
			$path = gravity_get_file_dir('plugins/essential-grid/essential-grid.zip');
			$list[] = array(
						'name' 		=> esc_html__('Essential Grid', 'gravity'),
						'slug' 		=> 'essential-grid',
						'source'	=> !empty($path) ? $path : 'upload://essential-grid.zip',
						'required' 	=> false
			);
		}
		return $list;
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'gravity_essential_grid_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'gravity_essential_grid_frontend_scripts', 1100 );
	function gravity_essential_grid_frontend_scripts() {
		if (gravity_is_on(gravity_get_theme_option('debug_mode')) && file_exists(gravity_get_file_dir('plugins/essential-grid/essential-grid.css')))
			wp_enqueue_style( 'gravity-essential-grid',  gravity_get_file_url('plugins/essential-grid/essential-grid.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'gravity_essential_grid_merge_styles' ) ) {
	//Handler of the add_filter('gravity_filter_merge_styles', 'gravity_essential_grid_merge_styles');
	function gravity_essential_grid_merge_styles($list) {
		$list[] = 'plugins/essential-grid/essential-grid.css';
		return $list;
	}
}
?>