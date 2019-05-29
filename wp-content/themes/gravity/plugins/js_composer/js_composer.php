<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_vc_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_vc_theme_setup9', 9 );
	function gravity_vc_theme_setup9() {
		if (gravity_exists_visual_composer()) {
			add_action( 'wp_enqueue_scripts', 								'gravity_vc_frontend_scripts', 1100 );
			add_filter( 'gravity_filter_merge_styles',						'gravity_vc_merge_styles' );
			add_filter( 'gravity_filter_merge_scripts',						'gravity_vc_merge_scripts' );
			add_filter( 'gravity_filter_get_css',							'gravity_vc_get_css', 10, 4 );
	
			// Add/Remove params in the standard VC shortcodes
			//-----------------------------------------------------
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,					'gravity_vc_add_params_classes', 10, 3 );
			
			// Color scheme
			$scheme = array(
				"param_name" => "scheme",
				"heading" => esc_html__("Color scheme", 'gravity'),
				"description" => wp_kses_data( __("Select color scheme to decorate this block", 'gravity') ),
				"group" => esc_html__('Colors', 'gravity'),
				"admin_label" => true,
				"value" => array_flip(gravity_get_list_schemes(true)),
				"type" => "dropdown"
			);
			vc_add_param("vc_row", $scheme);
			vc_add_param("vc_row_inner", $scheme);
			vc_add_param("vc_column", $scheme);
			vc_add_param("vc_column_inner", $scheme);
			vc_add_param("vc_column_text", $scheme);
			
			// Alter height and hide on mobile for Empty Space
			vc_add_param("vc_empty_space", array(
				"param_name" => "alter_height",
				"heading" => esc_html__("Alter height", 'gravity'),
				"description" => wp_kses_data( __("Select alternative height instead value from the field above", 'gravity') ),
				"admin_label" => true,
				"value" => array(
					esc_html__('Tiny', 'gravity') => 'tiny',
					esc_html__('Small -', 'gravity') => 'small_minus',
					esc_html__('Small', 'gravity') => 'small',
					esc_html__('Small +', 'gravity') => 'small_plus',
					esc_html__('Medium', 'gravity') => 'medium',
					esc_html__('Large -', 'gravity') => 'large_minus',
					esc_html__('Large', 'gravity') => 'large',
					esc_html__('Large +', 'gravity') => 'large_plus',
					esc_html__('Huge -', 'gravity') => 'huge_minus',
					esc_html__('Huge', 'gravity') => 'huge',
					esc_html__('Huge +', 'gravity') => 'huge_plus',
					esc_html__('From the value above', 'gravity') => 'none'
				),
				"type" => "dropdown"
			));
			vc_add_param("vc_empty_space", array(
				"param_name" => "hide_on_mobile",
				"heading" => esc_html__("Hide on mobile", 'gravity'),
				"description" => wp_kses_data( __("Hide this block on the mobile devices, when the columns are arranged one under another", 'gravity') ),
				"admin_label" => true,
				"std" => 0,
				"value" => array(
					esc_html__("Hide on mobile", 'gravity') => "1",
					esc_html__("Hide on tablet", 'gravity') => "3",
					esc_html__("Hide on notebook", 'gravity') => "2"
					),
				"type" => "checkbox"
			));
			
			// Add Narrow style to the Progress bars
			vc_add_param("vc_progress_bar", array(
				"param_name" => "narrow",
				"heading" => esc_html__("Narrow", 'gravity'),
				"description" => wp_kses_data( __("Use narrow style for the progress bar", 'gravity') ),
				"std" => 0,
				"value" => array(esc_html__("Narrow style", 'gravity') => "1" ),
				"type" => "checkbox"
			));

            // Add Inverse style to the Progress bars
            vc_add_param("vc_progress_bar", array(
                "param_name" => "inverse",
                "heading" => esc_html__("Inverse", 'gravity'),
                "description" => wp_kses_data( __("Use inverse style for the progress bar", 'gravity') ),
                "std" => 0,
                "value" => array(esc_html__("Inverse style", 'gravity') => "1" ),
                "type" => "checkbox"
            ));
			
			// Add param 'Closeable' to the Message Box
			vc_add_param("vc_message", array(
				"param_name" => "closeable",
				"heading" => esc_html__("Closeable", 'gravity'),
				"description" => wp_kses_data( __("Add 'Close' button to the message box", 'gravity') ),
				"std" => 0,
				"value" => array(esc_html__("Closeable", 'gravity') => "1" ),
				"type" => "checkbox"
			));
            // Add param 'No icon' to the Message Box
            vc_add_param("vc_message", array(
                "param_name" => "no_icon",
                "heading" => esc_html__("No icon", 'gravity'),
                "description" => wp_kses_data( __("Hide icon in the message box", 'gravity') ),
                "std" => 0,
                "value" => array(esc_html__("No icon", 'gravity') => "1" ),
                "type" => "checkbox"
            ));
		}
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',		'gravity_vc_tgmpa_required_plugins' );
			add_filter( 'vc_iconpicker-type-fontawesome',				'gravity_vc_iconpicker_type_fontawesome' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_vc_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_vc_tgmpa_required_plugins');
	function gravity_vc_tgmpa_required_plugins($list=array()) {
		if (in_array('js_composer', gravity_storage_get('required_plugins'))) {
			$path = gravity_get_file_dir('plugins/js_composer/js_composer.zip');
			$list[] = array(
					'name' 		=> esc_html__('Visual Composer', 'gravity'),
					'slug' 		=> 'js_composer',
					'source'	=> !empty($path) ? $path : 'upload://js_composer.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'gravity_exists_visual_composer' ) ) {
	function gravity_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'gravity_vc_is_frontend' ) ) {
	function gravity_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}
	
// Enqueue VC custom styles
if ( !function_exists( 'gravity_vc_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'gravity_vc_frontend_scripts', 1100 );
	function gravity_vc_frontend_scripts() {
		if (gravity_exists_visual_composer()) {
			if (gravity_is_on(gravity_get_theme_option('debug_mode')) && gravity_get_file_dir('plugins/js_composer/js_composer.css')!='')
				wp_enqueue_style( 'gravity-js-composer',  gravity_get_file_url('plugins/js_composer/js_composer.css'), array(), null );
			if (gravity_is_on(gravity_get_theme_option('debug_mode')) && gravity_get_file_dir('plugins/js_composer/js_composer.js')!='')
				wp_enqueue_script( 'gravity-js-composer', gravity_get_file_url('plugins/js_composer/js_composer.js'), array('jquery'), null, true );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'gravity_vc_merge_styles' ) ) {
	//Handler of the add_filter('gravity_filter_merge_styles', 'gravity_vc_merge_styles');
	function gravity_vc_merge_styles($list) {
		$list[] = 'plugins/js_composer/js_composer.css';
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'gravity_vc_merge_scripts' ) ) {
	//Handler of the add_filter('gravity_filter_merge_scripts', 'gravity_vc_merge_scripts');
	function gravity_vc_merge_scripts($list) {
		$list[] = 'plugins/js_composer/js_composer.js';
		return $list;
	}
}
	
// Add theme icons into VC iconpicker list
if ( !function_exists( 'gravity_vc_iconpicker_type_fontawesome' ) ) {
	//Handler of the add_filter( 'vc_iconpicker-type-fontawesome',	'gravity_vc_iconpicker_type_fontawesome' );
	function gravity_vc_iconpicker_type_fontawesome($icons) {
		$list = gravity_get_list_icons();
		if (!is_array($list) || count($list) == 0) return $icons;
		$rez = array();
		foreach ($list as $icon)
			$rez[] = array($icon => str_replace('icon-', '', $icon));
		return array_merge( $icons, array(esc_html__('Theme Icons', 'gravity') => $rez) );
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add params to the standard VC shortcodes
if ( !function_exists( 'gravity_vc_add_params_classes' ) ) {
	//Handler of the add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'gravity_vc_add_params_classes', 10, 3 );
	function gravity_vc_add_params_classes($classes, $sc, $atts) {
		if (in_array($sc, array('vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$classes .= ($classes ? ' ' : '') . 'scheme_' . $atts['scheme'];
		} else if (in_array($sc, array('vc_empty_space'))) {
			if (!empty($atts['alter_height']) && !gravity_is_off($atts['alter_height']))
				$classes .= ($classes ? ' ' : '') . 'height_' . $atts['alter_height'];
			if (!empty($atts['hide_on_mobile'])) {
				if (strpos($atts['hide_on_mobile'], '1')!==false)	$classes .= ($classes ? ' ' : '') . 'hide_on_mobile';
				if (strpos($atts['hide_on_mobile'], '2')!==false)	$classes .= ($classes ? ' ' : '') . 'hide_on_notebook';
				if (strpos($atts['hide_on_mobile'], '3')!==false)	$classes .= ($classes ? ' ' : '') . 'hide_on_tablet';
			}
		} else if (in_array($sc, array('vc_progress_bar'))) {
			if (!empty($atts['narrow']) && (int) $atts['narrow']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_progress_bar_narrow';
            if (!empty($atts['inverse']) && (int) $atts['inverse']==1)
                $classes .= ($classes ? ' ' : '') . 'vc_progress_bar_inverse';

		} else if (in_array($sc, array('vc_message'))) {
			if (!empty($atts['closeable']) && (int) $atts['closeable']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_message_box_closeable';
            if (!empty($atts['no_icon']) && (int) $atts['no_icon']==1)
                $classes .= ($classes ? ' ' : '') . 'vc_message_box_no_icon';
		}
		return $classes;
	}
}


// Add VC specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'gravity_vc_get_css' ) ) {
	//Handler of the add_filter( 'gravity_filter_get_css', 'gravity_vc_get_css', 10, 4 );
	function gravity_vc_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
.vc_tta.vc_general .vc_tta-tab > a,
.vc_message_box p,
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label,
.vc_tta.vc_tta-accordion .vc_tta-panel-title .vc_tta-title-text {
	{$fonts['h5_font-family']}
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	{$fonts['p_font-family']}
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Row and columns */
.scheme_self.wpb_row,
.scheme_self.wpb_column > .vc_column-inner > .wpb_wrapper,
.scheme_self.wpb_text_column {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}
.scheme_self.vc_row.vc_parallax[class*="scheme_"] .vc_parallax-inner:before {
	background-color: {$colors['bg_color_08']};
}

/* Accordion */
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon {
	color: {$colors['text_dark']};
}
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:before,
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:after {
	color: {$colors['text_dark']};
}
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a {
	color: {$colors['text_dark']};
}
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover {
	color: {$colors['text_link']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:before,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:before,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:after,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:after {
	border-color: {$colors['inverse_link']};
}
.vc_tta.vc_tta-accordion .vc_tta-panel {
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
.vc_tta.vc_general .vc_tta-panel.vc_active {
    background-color: transparent;
}

/* Tabs */
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab > a {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab > a:hover,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab > a:hover,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab.vc_active > a,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab.vc_active > a {
	color: {$colors['text_dark']};
	background-color: transparent;
}

/* Separator */
.vc_separator.vc_sep_color_grey .vc_sep_line {
	border-color: {$colors['bd_color']};
}

/* Progress bar */
.vc_progress_bar.vc_progress_bar_narrow.vc_progress_bar_inverse .vc_single_bar {
    background-color: {$colors['text_hover']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar {
	background-color: {$colors['alter_bg_color']};
}
.vc_progress_bar.vc_progress_bar_narrow.vc_progress-bar-color-bar_red .vc_single_bar .vc_bar {
	background-color: {$colors['alter_link']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label,
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	color: {$colors['text_dark']};
}
.vc_progress_bar.vc_progress_bar_narrow.vc_progress_bar_inverse .vc_single_bar .vc_label,
.vc_progress_bar.vc_progress_bar_narrow.vc_progress_bar_inverse .vc_single_bar .vc_label .vc_label_units {
	color: {$colors['inverse_text']};
}
.vc_message_box {
   	color: {$colors['text_dark']};
	background-color: {$colors['alter_bd_color']};
}
.vc_message_box .vc_message_box-icon {
    color: {$colors['text_dark_06']};
}
.vc_message_box_closeable:after {
   	color: {$colors['bg_color']};
	background-color: {$colors['bd_color']};
}
.vc_color-orange.vc_message_box {
	background-color: {$colors['text_hover']};
}
.vc_color-orange.vc_message_box.vc_message_box_closeable:after {
    background-color: #dfdc8b;
}
.vc_color-warning.vc_message_box.vc_message_box_closeable:after {
    background-color: #e38e77;
}
.vc_color-warning.vc_message_box {
	background-color: #f6b29f;
}
.vc_color-success.vc_message_box {
	background-color: #9fd8c6;
}
.vc_color-success.vc_message_box.vc_message_box_closeable:after {
    background-color: #6bc0a5;
}


CSS;
		}
		
		return $css;
	}
}
?>