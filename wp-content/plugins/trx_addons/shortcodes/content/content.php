<?php
/**
 * Shortcode: Content container
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_content_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_content_load_scripts_front');
	function trx_addons_sc_content_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_content', trx_addons_get_file_url('shortcodes/content/content.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_content_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_content_merge_styles');
	function trx_addons_sc_content_merge_styles($list) {
		$list[] = 'shortcodes/content/content.css';
		return $list;
	}
}


// trx_sc_content
//-------------------------------------------------------------
/*
[trx_sc_content id="unique_id" width="1/2"]
*/
if ( !function_exists( 'trx_addons_sc_content' ) ) {
	function trx_addons_sc_content($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_content', $atts, array(
			// Individual params
			'type' => 'default',
			"width" => "",
			"size" => "none",
			"float" => 'center',
			"align" => "",
			"padding" => "",
			"number" => "",
			"number_position" => "br",
			"number_color" => "",
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

		if (empty($atts['width']) && !empty($atts['size'])) $atts['width'] = $atts['size'];
		
		$atts['content'] = do_shortcode($content);
		
		if (!empty($atts['content']) || !empty($atts['title']) || !empty($atts['subtitle']) || !empty($atts['description'])) {

			set_query_var('trx_addons_args_sc_content', $atts);

			ob_start();
			if (($fdir = trx_addons_get_file_dir('shortcodes/content/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
			else if (($fdir = trx_addons_get_file_dir('shortcodes/content/tpl.default.php')) != '') { include $fdir; }
			$output = ob_get_contents();
			ob_end_clean();

		}
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_content', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_content", "trx_addons_sc_content");
}


// Add [trx_sc_content] in the VC shortcodes list
if (!function_exists('trx_addons_sc_content_add_in_vc')) {
	function trx_addons_sc_content_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_content",
				"name" => esc_html__("Content area", 'trx_addons'),
				"description" => wp_kses_data( __("Limit content width inside the fullwide rows", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_content',
				"class" => "trx_sc_content",
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
							), 'trx_sc_content' ),
							"type" => "dropdown"
						),
						array(
							"param_name" => "size",	// Attention! Param 'width' is reserved by VC
							"heading" => esc_html__("Size", 'trx_addons'),
							"description" => wp_kses_data( __("Select size of the block", 'trx_addons') ),
							"admin_label" => true,
							"value" => apply_filters('trx_addons_sc_content_width', array(
								esc_html__('Default', 'trx_addons') => 'none',
								esc_html__('1/1', 'trx_addons') => '1_1',
								esc_html__('1/2', 'trx_addons') => '1_2',
								esc_html__('1/3', 'trx_addons') => '1_3',
								esc_html__('2/3', 'trx_addons') => '2_3',
								esc_html__('1/4', 'trx_addons') => '1_4',
								esc_html__('3/4', 'trx_addons') => '3_4'
							)),
							"type" => "dropdown"
						),
						array(
							"param_name" => "float",
							"heading" => esc_html__("Block alignment", 'trx_addons'),
							"description" => wp_kses_data( __("Select alignment (floating position) of the block", 'trx_addons') ),
							"admin_label" => true,
							"value" => array(
								esc_html__('None', 'trx_addons') => 'none',
								esc_html__('Left', 'trx_addons') => 'left',
								esc_html__('Center', 'trx_addons') => 'center',
								esc_html__('Right', 'trx_addons') => 'right'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "align",
							"heading" => esc_html__("Text alignment", 'trx_addons'),
							"description" => wp_kses_data( __("Select alignment of the inner text in the block", 'trx_addons') ),
							"admin_label" => true,
							"value" => array(
								esc_html__('None', 'trx_addons') => 'none',
								esc_html__('Left', 'trx_addons') => 'left',
								esc_html__('Center', 'trx_addons') => 'center',
								esc_html__('Right', 'trx_addons') => 'right',
								esc_html__('Justify', 'trx_addons') => 'justify'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "padding",
							"heading" => esc_html__("Inner paddings", 'trx_addons'),
							"description" => wp_kses_data( __("Select paddings around of the inner text in the block", 'trx_addons') ),
							"value" => array(
								esc_html__('None', 'trx_addons') => 'none',
								esc_html__('Small', 'trx_addons') => 'small',
								esc_html__('Medium', 'trx_addons') => 'medium',
								esc_html__('Large', 'trx_addons') => 'large'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "number",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => wp_kses_data( __("Number to display in the corner of this area", 'trx_addons') ),
							"group" => esc_html__('Number', 'trx_addons'),
							"type" => "textfield"
						),
						array(
							"param_name" => "number_position",
							"heading" => esc_html__("Number position", 'trx_addons'),
							"description" => wp_kses_data( __("Select position to display number", 'trx_addons') ),
							"group" => esc_html__('Number', 'trx_addons'),
							"std" => "br",
							"value" => array(
								esc_html__('Top Left', 'trx_addons') => 'tl',
								esc_html__('Top Center', 'trx_addons') => 'tc',
								esc_html__('Top Right', 'trx_addons') => 'tr',
								esc_html__('Middle Left', 'trx_addons') => 'ml',
								esc_html__('Middle Center', 'trx_addons') => 'mc',
								esc_html__('Middle Right', 'trx_addons') => 'mr',
								esc_html__('Bottom Left', 'trx_addons') => 'bl',
								esc_html__('Bottom Center', 'trx_addons') => 'bc',
								esc_html__('Bottom Right', 'trx_addons') => 'br'
							),
							"type" => "dropdown"
						),
						array(
							'param_name' => 'number_color',
							'heading' => esc_html__( 'Color of the number', 'trx_addons' ),
							'description' => esc_html__( 'Select custom color of the number', 'trx_addons' ),
							"group" => esc_html__('Number', 'trx_addons'),
							'type' => 'colorpicker'
						)
					),
					trx_addons_vc_add_title_param(),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_content' ) );
			
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Trx_Sc_Content extends WPBakeryShortCodesContainer {}
		}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_content_add_in_vc', 20);
}
?>