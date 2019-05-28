<?php
/**
 * Shortcode: HotSpot
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

	
// trx_sc_hotspot
//-------------------------------------------------------------
/*
[trx_sc_hotspot id="unique_id" period="Monthly" price="89.25" currency="$" link="#" link_text="Buy now"]Description[/trx_sc_hotspot]
*/
if ( !function_exists( 'trx_addons_sc_hotspot' ) ) {
	function trx_addons_sc_hotspot($atts, $content=null){
		$atts = trx_addons_sc_prepare_atts('trx_sc_hotspot', $atts, array(
			// Individual params
			"type" => 'default',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		set_query_var('trx_addons_args_sc_hotspot', $atts);
        global $post_type;
		ob_start();
        if ( has_post_thumbnail() && ($post_type==TRX_ADDONS_CPT_SERVICES_PT) ) {
            get_template_part( 'templates/post-featured-hot-spot' );
        }
		$output = ob_get_contents();

		ob_end_clean();

		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_hotspot', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_hotspot", "trx_addons_sc_hotspot");
}


// Add [trx_sc_hotspot] in the VC shortcodes list
if (!function_exists('trx_addons_sc_hotspot_add_in_vc')) {
	function trx_addons_sc_hotspot_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_hotspot",
				"name" => esc_html__("HotSpot", 'trx_addons'),
				"description" => wp_kses_data( __("Add HotSpot into services item", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_hotspot',
				"class" => "trx_sc_hotspot",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcodes's layout", 'trx_addons') ),
							"admin_label" => true,
							"class" => "",
							"std" => "default",
							"value" => apply_filters('trx_addons_sc_type', array(
								esc_html__('Default', 'trx_addons') => 'default'
							), 'trx_sc_hotspot' ),
							"type" => "dropdown"
						),
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_hotspot' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_sc_hotspot extends WPBakeryShortCode {}
		}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_hotspot_add_in_vc', 20);
}
?>