<?php
/**
 * Shortcode: Popup container
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.10
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_popup_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_popup_load_scripts_front');
	function trx_addons_sc_popup_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_popup', trx_addons_get_file_url('shortcodes/popup/popup.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_popup_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_popup_merge_styles');
	function trx_addons_sc_popup_merge_styles($list) {
		$list[] = 'shortcodes/popup/popup.css';
		return $list;
	}
}

	
// Merge shortcode's specific scripts into single file
if ( !function_exists( 'trx_addons_sc_popup_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_popup_merge_scripts');
	function trx_addons_sc_popup_merge_scripts($list) {
		$list[] = 'shortcodes/popup/popup.js';
		return $list;
	}
}


// trx_sc_popup
//-------------------------------------------------------------
/*
[trx_sc_popup id="unique_id"]Popup content[/trx_sc_popup]
*/
if ( !function_exists( 'trx_addons_sc_popup' ) ) {
	function trx_addons_sc_popup($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_popup', $atts, array(
			// Individual params
			'type' => 'default',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		$output = '';

		$atts['content'] = do_shortcode($content);
		
		if (!empty($atts['content'])) {

			if (trx_addons_is_on(trx_addons_get_option('debug_mode')))
				wp_enqueue_script( 'trx_addons-sc_popup', trx_addons_get_file_url('shortcodes/popup/popup.js'), array('jquery'), null, true );
	
			set_query_var('trx_addons_args_sc_popup', $atts);

			ob_start();
			if (($fdir = trx_addons_get_file_dir('shortcodes/popup/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
			else if (($fdir = trx_addons_get_file_dir('shortcodes/popup/tpl.default.php')) != '') { include $fdir; }
			$output = ob_get_contents();
			ob_end_clean();

		}
		
		trx_addons_add_inline_html(apply_filters('trx_addons_sc_output', $output, 'trx_sc_popup', $atts, $content));
		return '';
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_popup", "trx_addons_sc_popup");
}


// Add [trx_sc_popup] in the VC shortcodes list
if (!function_exists('trx_addons_sc_popup_add_in_vc')) {
	function trx_addons_sc_popup_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_popup",
				"name" => esc_html__("Popup", 'trx_addons'),
				"description" => wp_kses_data( __("Create popup window with some content", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_popup',
				"class" => "trx_sc_popup",
				'content_element' => true,
				'is_container' => true,
				"js_view" => 'VcColumnView',
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcode's layout", 'trx_addons') ),
							"admin_label" => true,
							"std" => "default",
							"value" => apply_filters('trx_addons_sc_type', array(
								esc_html__('Default', 'trx_addons') => 'default',
							), 'trx_sc_popup' ),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_popup' ) );
			
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Trx_Sc_Popup extends WPBakeryShortCodesContainer {}
		}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_popup_add_in_vc', 20);
}
?>