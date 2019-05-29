<?php
/**
 * Shortcode: Content container
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4.3
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_title_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_title_load_scripts_front');
	function trx_addons_sc_title_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_title', trx_addons_get_file_url('shortcodes/title/title.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_title_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_title_merge_styles');
	function trx_addons_sc_title_merge_styles($list) {
		$list[] = 'shortcodes/title/title.css';
		return $list;
	}
}


// trx_sc_title
//-------------------------------------------------------------
/*
[trx_sc_title id="unique_id" title="" subtitle="" description=""]
*/
if ( !function_exists( 'trx_addons_sc_title' ) ) {
	function trx_addons_sc_title($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_title', $atts, array(
			// Individual params
			'type' => 'default',
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_image" => '',
			"link_text" => esc_html__('Learn more', 'trx_addons'),
			"title_align" => "left",
			"title_style" => "default",
			"title_tag" => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		$output = '';
		
		if (!empty($atts['title']) || !empty($atts['subtitle']) || !empty($atts['description'])) {

			set_query_var('trx_addons_args_sc_title', $atts);

			ob_start();
			if (($fdir = trx_addons_get_file_dir('shortcodes/title/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
			else if (($fdir = trx_addons_get_file_dir('shortcodes/title/tpl.default.php')) != '') { include $fdir; }
			$output = ob_get_contents();
			ob_end_clean();

		}
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_title', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_title", "trx_addons_sc_title");
}


// Add [trx_sc_content] in the VC shortcodes list
if (!function_exists('trx_addons_sc_title_add_in_vc')) {
	function trx_addons_sc_title_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_title",
				"name" => esc_html__("Title", 'trx_addons'),
				"description" => wp_kses_data( __("Add title, subtitle and description", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_title',
				"class" => "trx_sc_title",
				'content_element' => true,
				'is_container' => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					trx_addons_vc_add_title_param(''),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_title' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Title extends WPBakeryShortCode {}
		}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_title_add_in_vc', 20);
}
?>