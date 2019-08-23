<?php
/* Theme-specific action to configure ThemeREX Addons components
------------------------------------------------------------------------------- */


/* ThemeREX Addons components
------------------------------------------------------------------------------- */

if (!function_exists('gravity_trx_addons_theme_specific_setup1')) {
	add_action( 'after_setup_theme', 'gravity_trx_addons_theme_specific_setup1', 1 );
	add_action( 'trx_addons_action_save_options', 'gravity_trx_addons_theme_specific_setup1', 8 );
	function gravity_trx_addons_theme_specific_setup1() {
		if (gravity_exists_trx_addons()) {
            add_filter( 'trx_addons_sc_map',                'gravity_trx_addons_sc_map', 10, 2);
            add_filter( 'trx_addons_sc_atts',               'gravity_trx_addons_sc_atts', 10, 2);
            add_filter( 'trx_addons_sc_title_style',        'gravity_specific_sc_add_title_style', 10);
            add_filter( 'trx_addons_cv_enable',				'gravity_trx_addons_cv_enable');
			add_filter( 'trx_addons_cpt_list',				'gravity_trx_addons_cpt_list');
			add_filter( 'trx_addons_sc_list',				'gravity_trx_addons_sc_list');
			add_filter( 'trx_addons_widgets_list',			'gravity_trx_addons_widgets_list');
            add_filter( 'trx_addons_sc_output',             'gravity_trx_addons_sc_output', 10, 4);
		}
	}
}

// CV
if ( !function_exists( 'gravity_trx_addons_cv_enable' ) ) {
	//Handler of the add_filter( 'trx_addons_cv_enable', 'gravity_trx_addons_cv_enable');
	function gravity_trx_addons_cv_enable($enable=false) {
		// To do: return false if theme not use CV functionality
		return false;
	}
}

// CPT
if ( !function_exists( 'gravity_trx_addons_cpt_list' ) ) {
	//Handler of the add_filter('trx_addons_cpt_list',	'gravity_trx_addons_cpt_list');
	function gravity_trx_addons_cpt_list($list=array()) {
		// To do: Enable/Disable CPT via add/remove it in the list
        unset($list['portfolio']);
        unset($list['courses']);
        unset($list['certificates']);
        unset($list['resume']);
        unset($list['dishes']);
		return $list;
	}
}

// Shortcodes
if ( !function_exists( 'gravity_trx_addons_sc_list' ) ) {
	//Handler of the add_filter('trx_addons_sc_list',	'gravity_trx_addons_sc_list');
	function gravity_trx_addons_sc_list($list=array()) {

		// To do: Add/Remove shortcodes into list
		// If you add new shortcode - in the theme's folder must exists /trx_addons/shortcodes/new_sc_name/new_sc_name.php
		return $list;
	}
}

// Widgets
if ( !function_exists( 'gravity_trx_addons_widgets_list' ) ) {
	//Handler of the add_filter('trx_addons_widgets_list',	'gravity_trx_addons_widgets_list');
	function gravity_trx_addons_widgets_list($list=array()) {
        unset($list['aboutme']);
        unset($list['banner']);
        unset($list['categories_list']);
        unset($list['flickr']);
        unset($list['popular_posts']);
        unset($list['recent_news']);
        unset($list['twitter']);
		// To do: Add/Remove widgets into list
		// If you add widget - in the theme's folder must exists /trx_addons/widgets/new_widget_name/new_widget_name.php
		return $list;
	}
}


/* Add options in the Theme Options Customizer
------------------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('gravity_trx_addons_theme_specific_setup3')) {
	add_action( 'after_setup_theme', 'gravity_trx_addons_theme_specific_setup3', 3 );
	function gravity_trx_addons_theme_specific_setup3() {
		
		// Section 'Courses' - settings to show 'Courses' blog archive and single posts
		if (gravity_exists_courses()) {
			gravity_storage_merge_array('options', '', array(
				'courses' => array(
					"title" => esc_html__('Courses', 'gravity'),
					"desc" => wp_kses_data( __('Select parameters to display the courses pages', 'gravity') ),
					"type" => "section"
					),
				'expand_content_courses' => array(
					"title" => esc_html__('Expand content', 'gravity'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'gravity') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'header_style_courses' => array(
					"title" => esc_html__('Header style', 'gravity'),
					"desc" => wp_kses_data( __('Select style to display the site header on the courses pages', 'gravity') ),
					"std" => 'inherit',
					"options" => gravity_get_list_header_styles(true),
					"type" => "select"
					),
				'header_position_courses' => array(
					"title" => esc_html__('Header position', 'gravity'),
					"desc" => wp_kses_data( __('Select position to display the site header on the courses pages', 'gravity') ),
					"std" => 'inherit',
					"options" => gravity_get_list_header_positions(true),
					"type" => "select"
					),
				'header_widgets_courses' => array(
					"title" => esc_html__('Header widgets', 'gravity'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the courses pages', 'gravity') ),
					"std" => 'hide',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'sidebar_widgets_courses' => array(
					"title" => esc_html__('Sidebar widgets', 'gravity'),
					"desc" => wp_kses_data( __('Select sidebar to show on the courses pages', 'gravity') ),
					"std" => 'courses_widgets',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'sidebar_position_courses' => array(
					"title" => esc_html__('Sidebar position', 'gravity'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the courses pages', 'gravity') ),
					"refresh" => false,
					"std" => 'left',
					"options" => gravity_get_list_sidebars_positions(true),
					"type" => "select"
					),
				'hide_sidebar_on_single_courses' => array(
					"title" => esc_html__('Hide sidebar on the single course', 'gravity'),
					"desc" => wp_kses_data( __("Hide sidebar on the single course's page", 'gravity') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_courses' => array(
					"title" => esc_html__('Widgets above the page', 'gravity'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'gravity') ),
					"std" => 'hide',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'widgets_above_content_courses' => array(
					"title" => esc_html__('Widgets above the content', 'gravity'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gravity') ),
					"std" => 'hide',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'widgets_below_content_courses' => array(
					"title" => esc_html__('Widgets below the content', 'gravity'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gravity') ),
					"std" => 'hide',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'widgets_below_page_courses' => array(
					"title" => esc_html__('Widgets below the page', 'gravity'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'gravity') ),
					"std" => 'hide',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'footer_scheme_courses' => array(
					"title" => esc_html__('Footer Color Scheme', 'gravity'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'gravity') ),
					"std" => 'dark',
					"options" => gravity_get_list_schemes(true),
					"type" => "select"
					),
				'footer_widgets_courses' => array(
					"title" => esc_html__('Footer widgets', 'gravity'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'gravity') ),
					"std" => 'footer_widgets',
					"options" => gravity_get_list_sidebars(true, true),
					"type" => "select"
					),
				'footer_columns_courses' => array(
					"title" => esc_html__('Footer columns', 'gravity'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'gravity') ),
					"dependency" => array(
						'footer_widgets_courses' => array('^hide')
					),
					"std" => 0,
					"options" => gravity_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_courses' => array(
					"title" => esc_html__('Footer fullwide', 'gravity'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'gravity') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
	}
}

// Add mobile menu to the plugin's cached menu list
if ( !function_exists( 'gravity_trx_addons_menu_cache' ) ) {
	add_filter( 'trx_addons_filter_menu_cache', 'gravity_trx_addons_menu_cache');
	function gravity_trx_addons_menu_cache($list=array()) {
		if (in_array('#menu_main', $list)) $list[] = '#menu_mobile';
		return $list;
	}
}

// Add vars into localize array
if (!function_exists('gravity_trx_addons_localize_script')) {
	add_filter( 'gravity_filter_localize_script','gravity_trx_addons_localize_script' );
	function gravity_trx_addons_localize_script($arr) {
		$arr['alter_link_color'] = gravity_get_scheme_color('alter_link');
		return $arr;
	}
}


// Add theme-specific layouts to the list
if (!function_exists('gravity_trx_addons_theme_specific_default_layouts')) {
	add_filter( 'trx_addons_filter_default_layouts',	'gravity_trx_addons_theme_specific_default_layouts');
	function gravity_trx_addons_theme_specific_default_layouts($default_layouts=array()) {
		require_once 'trx_addons.layouts.php';
		return isset($layouts) && is_array($layouts) && count($layouts) > 0
						? array_merge($default_layouts, $layouts)
						: $default_layouts;
	}
}

// Disable override header image on team pages
if ( !function_exists( 'gravity_trx_addons_allow_override_header_image' ) ) {
	add_filter( 'gravity_filter_allow_override_header_image', 'gravity_trx_addons_allow_override_header_image' );
	function gravity_trx_addons_allow_override_header_image($allow) {
		return gravity_is_team_page() || gravity_is_portfolio_page() ? false : $allow;
	}
}

// Hide sidebar on the team pages
if ( !function_exists( 'gravity_trx_addons_sidebar_present' ) ) {
	add_filter( 'gravity_filter_sidebar_present', 'gravity_trx_addons_sidebar_present' );
	function gravity_trx_addons_sidebar_present($present) {
		return !is_single() && (gravity_is_team_page() || gravity_is_portfolio_page()) ? false : $present;
	}
}


// WP Editor addons
//------------------------------------------------------------------------

// Theme-specific configure of the WP Editor
if ( !function_exists( 'gravity_trx_addons_editor_init' ) ) {
	if (is_admin()) add_filter( 'tiny_mce_before_init', 'gravity_trx_addons_editor_init', 11);
	function gravity_trx_addons_editor_init($opt) {
		if (gravity_exists_trx_addons()) {
			// Add style 'Arrow' to the 'List styles'
			// Remove 'false &&' from condition below to add new style to the list
			if (false && !empty($opt['style_formats'])) {
				$style_formats = json_decode($opt['style_formats'], true);
				if (is_array($style_formats) && count($style_formats)>0 ) {
					foreach ($style_formats as $k=>$v) {
						if ( $v['title'] == esc_html__('List styles', 'gravity') ) {
							$style_formats[$k]['items'][] = array(
										'title' => esc_html__('Arrow', 'gravity'),
										'selector' => 'ul',
										'classes' => 'trx_addons_list trx_addons_list_arrow'
									);
						}
					}
					$opt['style_formats'] = json_encode( $style_formats );		
				}
			}
		}
		return $opt;
	}
}


// Theme-specific thumb sizes
//------------------------------------------------------------------------

// Replace thumb sizes to the theme-specific
if ( !function_exists( 'gravity_trx_addons_add_thumb_sizes' ) ) {
	add_filter( 'trx_addons_filter_add_thumb_sizes', 'gravity_trx_addons_add_thumb_sizes');
	function gravity_trx_addons_add_thumb_sizes($list=array()) {
		if (is_array($list)) {
			foreach ($list as $k=>$v) {
				if (in_array($k, array(
								'trx_addons-thumb-huge',
								'trx_addons-thumb-big',
								'trx_addons-thumb-medium',
								'trx_addons-thumb-team',
								'trx_addons-thumb-tiny',
								'trx_addons-thumb-masonry-big',
								'trx_addons-thumb-masonry',
								)
							)
						) unset($list[$k]);
			}
		}
		return $list;
	}
}

// Return theme-specific thumb size instead removed plugin's thumb size
if ( !function_exists( 'gravity_trx_addons_get_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_get_thumb_size', 'gravity_trx_addons_get_thumb_size');
	function gravity_trx_addons_get_thumb_size($thumb_size='') {
		return str_replace(array(
							'trx_addons-thumb-huge',
							'trx_addons-thumb-huge-@retina',
							'trx_addons-thumb-big',
							'trx_addons-thumb-big-@retina',
							'trx_addons-thumb-medium',
							'trx_addons-thumb-medium-@retina',
                            'trx_addons-thumb-team',
                            'trx_addons-thumb-team-@retina',
                            'trx_addons-thumb-single_services_hot_spot',
                            'trx_addons-thumb-single_services_hot_spot-@retina',
                            'trx_addons-thumb-classic',
                            'trx_addons-thumb-classic-@retina',
                            'trx_addons-thumb-classic_wide',
                            'trx_addons-thumb-classic_wide-@retina',
							'trx_addons-thumb-tiny',
							'trx_addons-thumb-tiny-@retina',
							'trx_addons-thumb-masonry-big',
							'trx_addons-thumb-masonry-big-@retina',
							'trx_addons-thumb-masonry',
							'trx_addons-thumb-masonry-@retina',
                            'trx_addons-thumb-skills',
							'trx_addons-thumb-skills-@retina',
							),
							array(
							'gravity-thumb-huge',
							'gravity-thumb-huge-@retina',
							'gravity-thumb-big',
							'gravity-thumb-big-@retina',
							'gravity-thumb-med',
							'gravity-thumb-med-@retina',
                            'gravity-thumb-team',
                            'gravity-thumb-team-@retina',
                            'gravity-thumb-single_services_hot_spot',
                            'gravity-thumb-single_services_hot_spot-@retina',
                            'gravity-thumb-classic',
                            'gravity-thumb-classic-@retina',
                            'gravity-thumb-classic_wide',
                            'gravity-thumb-classic_wide-@retina',
							'gravity-thumb-tiny',
							'gravity-thumb-tiny-@retina',
							'gravity-thumb-masonry-big',
							'gravity-thumb-masonry-big-@retina',
							'gravity-thumb-masonry',
							'gravity-thumb-skills-@retina',
                            'gravity-thumb-skills',
							'gravity-thumb-masonry-@retina',
							),
							$thumb_size);
	}
}




// Shortcodes support
//------------------------------------------------------------------------

// Return tag for the item's title
if ( !function_exists( 'gravity_trx_addons_sc_item_title_tag' ) ) {
	add_filter( 'trx_addons_filter_sc_item_title_tag', 'gravity_trx_addons_sc_item_title_tag');
	function gravity_trx_addons_sc_item_title_tag($tag='') {
		return $tag=='h1' ? 'h2' : $tag;
	}
}

// Return args for the item's button
if ( !function_exists( 'gravity_trx_addons_sc_item_button_args' ) ) {
	add_filter( 'trx_addons_filter_sc_item_button_args', 'gravity_trx_addons_sc_item_button_args');
	function gravity_trx_addons_sc_item_button_args($args, $sc='') {
		if (false && $sc != 'sc_button') {
			$args['type'] = 'simple';
			$args['icon_type'] = 'fontawesome';
			$args['icon_fontawesome'] = 'icon-down-big';
			$args['icon_position'] = 'top';
		}
		return $args;
	}
}

// Add new types in the shortcodes
if ( !function_exists( 'gravity_trx_addons_sc_type' ) ) {
	add_filter( 'trx_addons_sc_type', 'gravity_trx_addons_sc_type', 10, 2);
	function gravity_trx_addons_sc_type($list, $sc) {
		if (in_array($sc, array('trx_sc_price')))
			$list[esc_html__('Light', 'gravity')] = 'light';
        if (in_array($sc, array('trx_sc_button')))
            $list[esc_html__('Inverse', 'gravity')] = 'inverse';
        if (in_array($sc, array('trx_sc_action')))
            $list[esc_html__('Light', 'gravity')] = 'light';
        if (in_array($sc, array('trx_sc_action')))
            $list[esc_html__('Shop 1', 'gravity')] = 'shop_1';
        if (in_array($sc, array('trx_sc_action')))
            $list[esc_html__('Shop 2', 'gravity')] = 'shop_2';
        if (in_array($sc, array('trx_sc_services')))
            $list[esc_html__('Inverse', 'gravity')] = 'inverse';
        if (in_array($sc, array('trx_sc_services')))
            $list[esc_html__('Large icon', 'gravity')] = 'large_icon';
        if (in_array($sc, array('trx_sc_services')))
            $list[esc_html__('Hot Spot', 'gravity')] = 'hot_spot';
		return $list;
	}
}

// Add new styles to the Google map
if ( !function_exists( 'gravity_trx_addons_sc_googlemap_styles' ) ) {
	add_filter( 'trx_addons_filter_sc_googlemap_styles',	'gravity_trx_addons_sc_googlemap_styles');
	function gravity_trx_addons_sc_googlemap_styles($list) {
		$list[esc_html__('Dark', 'gravity')] = 'dark';
		return $list;
	}
}
if ( !function_exists( 'gravity_trx_addons_sc_map' ) ) {
    //Handler of the add_filter( 'trx_addons_sc_map', 'gravity_trx_addons_sc_map', 10, 2);
    function gravity_trx_addons_sc_map($params, $sc) {

        if ($sc == 'trx_sc_promo') {
            $params['params'][] = array(
                "param_name" => "text2",
                "heading" => esc_html__("Text block", 'gravity'),
                "description" => wp_kses_data(__("Add another text block, under button", 'gravity')),
                'dependency' => array(
                    'element' => 'type',
                    'value' => 'modern'
                ),
                "type" => "textarea_safe"

            );
        }

        if ($sc == 'trx_sc_services') {
            $params['params'][] = array(
                "param_name" => "large_button",
                "heading" => esc_html__("Add large button", 'gravity'),
                "description" => wp_kses_data(__("Add large button", 'gravity')),
                'dependency' => array(
                    'element' => 'type',
                    'value' => 'inverse, large_icon'
                ),
                "std" => "0",
                "value" => array(esc_html__("Add large button", 'gravity') => "1" ),
				"type" => "checkbox"

            );
        }
        if ($sc == 'trx_sc_services') {
            $params['params'][] = array(
                "param_name" => "inverse_colors",
                "heading" => esc_html__("Inverse colors", 'gravity'),
                "description" => wp_kses_data(__("Inverse colors", 'gravity')),
                'dependency' => array(
                    'element' => 'type',
                    'value' => 'light'
                ),
                "std" => "0",
                "value" => array(esc_html__("Inverse colors", 'gravity') => "1" ),
                "type" => "checkbox"

            );
        }


        if ($sc == 'trx_sc_googlemap') {
            $params['params'][] = array(
                "param_name" => "large_content",
                "heading" => esc_html__("Large content", 'gravity'),
                "description" => wp_kses_data(__("Large content", 'gravity')),
                "std" => "0",
                "value" => array(esc_html__("Large content", 'gravity') => "1" ),
                "type" => "checkbox"

            );
        }
        if ($sc == 'trx_sc_team') {
            $params['params'][] = array(
                "param_name" => "inverse_colors",
                "heading" => esc_html__("Inverse colors", 'gravity'),
                "description" => wp_kses_data(__("Inverse colors", 'gravity')),
                'dependency' => array(
                    'element' => 'type',
                    'value' => 'short'
                ),
                "std" => "0",
                "value" => array(esc_html__("Inverse colors", 'gravity') => "1" ),
                "type" => "checkbox"

            );
        }

        if ($sc == 'trx_sc_skills') {
            $count = count($params['params']);

            for ($i=0; $i<$count;$i++) {
                if ($params['params'][$i]['param_name']=='values') {
                    $params['params'][$i]['params'][] =array(
                        "param_name" => "image2",
                        "heading" => esc_html__("Background image", 'gravity'),
                        "description" => wp_kses_data( __("Select background to decorate this block", 'gravity') ),
                        "type" => "attach_image"
                    );
                    break;
                }

            }
        }

        return $params;
    }
}
if ( !function_exists( 'gravity_trx_addons_sc_atts' ) ) {
    //Handler of the add_filter( 'trx_addons_sc_atts', 'gravity_trx_addons_sc_atts', 10, 2);
    function gravity_trx_addons_sc_atts($atts, $sc) {
        if ($sc == 'trx_sc_skills') {
            $atts['values'] = is_array($atts['values']) ? $atts['values'] : array();
            $atts['values']['image2'] = '';
        }

        if ($sc == 'trx_sc_googlemap') {
            $atts['large_content'] = '0';
        }

        if ($sc == 'trx_sc_services') {
            $atts['large_button'] = '0';
        }

        if ($sc == 'trx_sc_services') {
            $atts['inverse_colors'] = '0';
        }

        if ($sc == 'trx_sc_team') {
            $atts['inverse_colors'] = '0';
        }

        if ($sc == 'trx_sc_promo') {
            $atts['text2'] = '';
        }

        return $atts;
    }
}
if ( !function_exists('gravity_specific_sc_add_title_style') ) {
    function gravity_specific_sc_add_title_style($list) {
        $list[esc_html__('Inverse', 'gravity')] = 'inverse';
        return $list;
    }
}

if ( !function_exists( 'gravity_trx_addons_sc_output' ) ) {
    //Handler of the add_filter( 'trx_addons_sc_output', 'gravity_trx_addons_sc_output', 10, 4);
    function gravity_trx_addons_sc_output($output, $sc, $atts, $content) {

        if ($sc == 'top_rated_products') {
            $output = str_replace('products ', 'products related_products ', $output);
        }
        return $output;
    }
}