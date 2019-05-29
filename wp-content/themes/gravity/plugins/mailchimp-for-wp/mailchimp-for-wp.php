<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_mailchimp_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_mailchimp_theme_setup9', 9 );
	function gravity_mailchimp_theme_setup9() {
		if (gravity_exists_mailchimp()) {
			add_action( 'wp_enqueue_scripts',							'gravity_mailchimp_frontend_scripts', 1100 );
			add_filter( 'gravity_filter_merge_styles',					'gravity_mailchimp_merge_styles');
			add_filter( 'gravity_filter_get_css',						'gravity_mailchimp_get_css', 10, 4);
		}
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',		'gravity_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gravity_exists_mailchimp' ) ) {
	function gravity_exists_mailchimp() {
		return function_exists('__mc4wp_load_plugin') || defined('MC4WP_VERSION');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_mailchimp_tgmpa_required_plugins');
	function gravity_mailchimp_tgmpa_required_plugins($list=array()) {
		if (in_array('mailchimp-for-wp', gravity_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'gravity'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue custom styles
if ( !function_exists( 'gravity_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'gravity_mailchimp_frontend_scripts', 1100 );
	function gravity_mailchimp_frontend_scripts() {
		if (gravity_exists_mailchimp()) {
			if (gravity_is_on(gravity_get_theme_option('debug_mode')) && gravity_get_file_dir('plugins/mailchimp-for-wp/mailchimp-for-wp.css')!='')
				wp_enqueue_style( 'gravity-mailchimp-for-wp',  gravity_get_file_url('plugins/mailchimp-for-wp/mailchimp-for-wp.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'gravity_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'gravity_filter_merge_styles', 'gravity_mailchimp_merge_styles');
	function gravity_mailchimp_merge_styles($list) {
		$list[] = 'plugins/mailchimp-for-wp/mailchimp-for-wp.css';
		return $list;
	}
}

// Add css styles into global CSS stylesheet
if (!function_exists('gravity_mailchimp_get_css')) {
	//Handler of the add_filter('gravity_filter_get_css', 'gravity_mailchimp_get_css', 10, 4);
	function gravity_mailchimp_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

CSS;
		
			
			$rad = gravity_get_border_radius();
			$css['fonts'] .= <<<CSS

.mc4wp-form .mc4wp-form-fields input[type="email"],
.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	-webkit-border-radius: {$rad};
	   -moz-border-radius: {$rad};
	    -ms-border-radius: {$rad};
			border-radius: {$rad};
}

CSS;
		}

		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

.mc4wp-form input[type="email"] {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bg_color']};
	color: {$colors['text']};
}
.mc4wp-form button[type="submit"] {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}
.mc4wp-form button[type="submit"]:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}

CSS;
		}

		return $css;
	}
}
?>