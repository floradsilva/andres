<?php
/**
 * Shortcode: Display post/page featured image
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.13
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_layouts_featured_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_layouts_featured_load_scripts_front');
	function trx_addons_sc_layouts_featured_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_layouts_featured', trx_addons_get_file_url('cpt/layouts/shortcodes/featured/featured.css'), array(), null );
		}
	}
}

	
// Merge shortcode specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_layouts_featured_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_layouts_featured_merge_styles');
	function trx_addons_sc_layouts_featured_merge_styles($list) {
		$list[] = 'cpt/layouts/shortcodes/featured/featured.css';
		return $list;
	}
}



// trx_sc_layouts_featured
//-------------------------------------------------------------
/*
[trx_sc_layouts_featured id="unique_id" height="40em"]
*/
if ( !function_exists( 'trx_addons_sc_layouts_featured' ) ) {
	function trx_addons_sc_layouts_featured($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_layouts_featured', $atts, array(
			// Individual params
			"type" => "default",
			"height" => "",
			"align" => '',
			"hide_on_mobile" => "0",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);

		$atts['content'] = do_shortcode($content);

		ob_start();
		set_query_var('trx_addons_args_sc_layouts_featured', $atts);
		if (($fdir = trx_addons_get_file_dir('cpt/layouts/shortcodes/featured/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		else if (($fdir = trx_addons_get_file_dir('cpt/layouts/shortcodes/featured/tpl.default.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_layouts_featured', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_layouts_featured", "trx_addons_sc_layouts_featured");
}


// Add [trx_sc_layouts_featured] in the VC shortcodes list
if (!function_exists('trx_addons_sc_layouts_featured_add_in_vc')) {
	function trx_addons_sc_layouts_featured_add_in_vc() {

		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_layouts_featured",
				"name" => esc_html__("Layouts: Featured image", 'trx_addons'),
				"description" => wp_kses_data( __("Insert post/page featured image", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_layouts_featured',
				"class" => "trx_sc_layouts_featured",
				"content_element" => true,
				'is_container' => true,
				"js_view" => 'VcColumnView',
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcodes's layout", 'trx_addons') ),
							"class" => "",
							"std" => "default",
							"value" => apply_filters('trx_addons_sc_type', array(
								esc_html__('Default', 'trx_addons') => 'default',
							), 'trx_sc_layouts_featured' ),
							"type" => "dropdown"
						),
						array(
							"param_name" => "height",
							"heading" => esc_html__("Height of the block", 'trx_addons'),
							"description" => wp_kses_data( __("Specify height of this block. If empty - use default height", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "align",
							"heading" => esc_html__("Content alignment", 'trx_addons'),
							"description" => wp_kses_data( __("Select alignment of the inner content in this block", 'trx_addons') ),
							"value" => array(
								esc_html__('Inherit', 'trx_addons') => 'inherit',
								esc_html__('Left', 'trx_addons') => 'left',
								esc_html__('Center', 'trx_addons') => 'center',
								esc_html__('Right', 'trx_addons') => 'right'
							),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_hide_param(),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_layouts_featured') );
			
		class WPBakeryShortCode_Trx_Sc_Layouts_Featured extends WPBakeryShortCodesContainer {}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_layouts_featured_add_in_vc', 15);
}
?>