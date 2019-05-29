<?php
/**
 * Shortcode: Countdown
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4.3
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_countdown_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_countdown_load_scripts_front');
	function trx_addons_sc_countdown_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_countdown', trx_addons_get_file_url('shortcodes/countdown/countdown.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_countdown_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_countdown_merge_styles');
	function trx_addons_sc_countdown_merge_styles($list) {
		$list[] = 'shortcodes/countdown/countdown.css';
		return $list;
	}
}

	
// Merge countdown specific scripts into single file
if ( !function_exists( 'trx_addons_sc_countdown_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_countdown_merge_scripts');
	function trx_addons_sc_countdown_merge_scripts($list) {
		$list[] = 'shortcodes/countdown/jquery.plugin.js';
		$list[] = 'shortcodes/countdown/jquery.countdown.js';
		$list[] = 'shortcodes/countdown/countdown.js';
		return $list;
	}
}



// trx_sc_countdown
//-------------------------------------------------------------
/*
[trx_sc_countdown id="unique_id" date="2017-12-31" time="23:59:59"]
*/
if ( !function_exists( 'trx_addons_sc_countdown' ) ) {
	function trx_addons_sc_countdown($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_countdown', $atts, array(
			// Individual params
			"type" => "default",
			"date" => "",
			"time" => "",
			"align" => "center",
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

		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_script( 'jquery-plugin', trx_addons_get_file_url('shortcodes/countdown/jquery.plugin.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-countdown', trx_addons_get_file_url('shortcodes/countdown/jquery.countdown.js'), array('jquery'), null, true );
			wp_enqueue_script( 'trx_addons-sc_countdown', trx_addons_get_file_url('shortcodes/countdown/countdown.js'), array('jquery'), null, true );
		}

		set_query_var('trx_addons_args_sc_countdown', $atts);
		
		ob_start();
		if (($fdir = trx_addons_get_file_dir('shortcodes/countdown/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		else if (($fdir = trx_addons_get_file_dir('shortcodes/countdown/tpl.default.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_countdown', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_countdown", "trx_addons_sc_countdown");
}


// Add [trx_sc_countdown] in the VC shortcodes list
if (!function_exists('trx_addons_sc_countdown_add_in_vc')) {
	function trx_addons_sc_countdown_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_countdown",
				"name" => esc_html__("Countdown", 'trx_addons'),
				"description" => wp_kses_data( __("Put the countdown to the specified date and time", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_countdown',
				"class" => "trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Type", 'trx_addons'),
							"description" => wp_kses_data( __("Select counter's type", 'trx_addons') ),
							"admin_label" => true,
							"value" => apply_filters('trx_addons_sc_type', array(
								esc_html__('Default', 'trx_addons') => 'default',
								esc_html__('Circle', 'trx_addons') => 'circle'
							), 'trx_sc_countdown' ),
							"type" => "dropdown"
						),
						array(
							"param_name" => "date",
							"heading" => esc_html__("Date", 'trx_addons'),
							"description" => wp_kses_data( __("Target date. Attention! Write the date in the format: yyyy-mm-dd", 'trx_addons') ),
							'value' => '',
							"type" => "textfield"
						),
						array(
							'param_name' => 'time',
							'heading' => esc_html__( 'Time', 'trx_addons' ),
							'description' => esc_html__( 'Target time. Attention! Put the time in the 24-hours format: HH:mm:ss', 'trx_addons' ),
							'value' => '',
							'type' => 'textfield',
						),
						array(
							"param_name" => "align",
							"heading" => esc_html__("Alignment", 'trx_addons'),
							"description" => wp_kses_data( __("Select alignment of the countdown", 'trx_addons') ),
							"std" => "default",
							"value" => array(
								esc_html__('Default', 'trx_addons') => 'default',
								esc_html__('Left', 'trx_addons') => 'left',
								esc_html__('Center', 'trx_addons') => 'center',
								esc_html__('Right', 'trx_addons') => 'right'
							),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_title_param(),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_countdown' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Countdown extends WPBakeryShortCode {}
		}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_countdown_add_in_vc', 20);
}
?>