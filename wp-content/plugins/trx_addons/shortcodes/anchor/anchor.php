<?php
/**
 * Shortcode: Anchor
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_anchor_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_anchor_load_scripts_front');
	function trx_addons_sc_anchor_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_anchor', trx_addons_get_file_url('shortcodes/anchor/anchor.css'), array(), null );
		}
	}
}
	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_anchor_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_anchor_merge_styles');
	function trx_addons_sc_anchor_merge_styles($list) {
		$list[] = 'shortcodes/anchor/anchor.css';
		return $list;
	}
}

	
// Merge shortcode's specific scripts into single file
if ( !function_exists( 'trx_addons_sc_anchor_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_anchor_merge_scripts');
	function trx_addons_sc_anchor_merge_scripts($list) {
		$list[] = 'shortcodes/anchor/anchor.js';
		return $list;
	}
}
	
// Add shortcode's specific vars into JS storage
if ( !function_exists( 'trx_addons_sc_anchor_localize_script' ) ) {
	add_filter("trx_addons_localize_script", 'trx_addons_sc_anchor_localize_script');
	function trx_addons_sc_anchor_localize_script($vars) {
		return array_merge($vars, array(
			'scroll_to_anchor' => trx_addons_get_option('scroll_to_anchor'),
			'update_location_from_anchor' => trx_addons_get_option('update_location_from_anchor')
		));
	}
}



// trx_sc_anchor
//-------------------------------------------------------------
/*
[trx_sc_anchor id="unique_id" style="default"]
*/
if ( !function_exists( 'trx_addons_sc_anchor' ) ) {
	function trx_addons_sc_anchor($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_anchor', $atts, array(
			// Individual params
			"type" => "default",
			"title" => "",
			"url" => "",
			"icon_type" => 'fontawesome',
			"icon_fontawesome" => "",
			"icon_openiconic" => "",
			"icon_typicons" => "",
			"icon_entypo" => "",
			"icon_linecons" => "",
			// Common params
			"id" => ""
			)
		);

		if (trx_addons_is_on(trx_addons_get_option('debug_mode')))
			wp_enqueue_script( 'trx_addons-sc_anchor', trx_addons_get_file_url('shortcodes/anchor/anchor.js'), array('jquery'), null, true );

		$atts['icon'] = isset( $atts['icon_' . $atts['icon_type']] ) && $atts['icon_' . $atts['icon_type']] != 'empty' ? $atts['icon_' . $atts['icon_type']] : '';
		trx_addons_load_icons($atts['icon_type']);
	
		set_query_var('trx_addons_args_sc_anchor', $atts);
		
		ob_start();
		if (($fdir = trx_addons_get_file_dir('shortcodes/anchor/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		else if (($fdir = trx_addons_get_file_dir('shortcodes/anchor/tpl.default.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_anchor', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_anchor", "trx_addons_sc_anchor");
}


// Add [trx_sc_anchor] in the VC shortcodes list
if (!function_exists('trx_addons_sc_anchor_add_in_vc')) {
	function trx_addons_sc_anchor_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_anchor",
				"name" => esc_html__("Anchor", 'trx_addons'),
				"description" => wp_kses_data( __("Insert anchor for the inner page navigation", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_anchor',
				"class" => "trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge( array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Anchor ID", 'trx_addons'),
						"description" => wp_kses_data( __("ID for the anchor", 'trx_addons') ),
						"admin_label" => true,
						"type" => "textfield"
					), 
					array(
						'param_name' => 'title',
						'heading' => esc_html__( 'Title', 'trx_addons' ),
						'description' => esc_html__( 'Anchor title', 'trx_addons' ),
						'type' => 'textfield',
					),
					array(
						'param_name' => 'url',
						'heading' => esc_html__( 'URL to navigate', 'trx_addons' ),
						'description' => esc_html__( "URL to navigate. If empty - use id to create anchor", 'trx_addons' ),
						'type' => 'textfield',
					 ) ),

					trx_addons_vc_add_icon_param('')
				)
			), 'trx_sc_anchor' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Anchor extends WPBakeryShortCode {}
		}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_anchor_add_in_vc', 20);
}
?>