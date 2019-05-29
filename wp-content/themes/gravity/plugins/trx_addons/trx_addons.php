<?php
/* ThemeREX Addons support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('gravity_trx_addons_theme_setup1')) {
	add_action( 'after_setup_theme', 'gravity_trx_addons_theme_setup1', 1 );
	add_action( 'trx_addons_action_save_options', 'gravity_trx_addons_theme_setup1', 8 );
	function gravity_trx_addons_theme_setup1() {
		if (gravity_exists_trx_addons()) {
			add_filter( 'gravity_filter_list_posts_types',	'gravity_trx_addons_list_post_types');
			add_filter( 'gravity_filter_list_header_styles','gravity_trx_addons_list_header_styles');
			add_filter( 'gravity_filter_list_footer_styles','gravity_trx_addons_list_footer_styles');
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gravity_trx_addons_theme_setup9')) {
	add_action( 'after_setup_theme', 'gravity_trx_addons_theme_setup9', 9 );
	function gravity_trx_addons_theme_setup9() {
		if (gravity_exists_trx_addons()) {
			add_filter( 'trx_addons_sc_atts',							'gravity_trx_addons_sc_atts', 10, 2);
			add_filter( 'trx_addons_sc_output',							'gravity_trx_addons_sc_output', 10, 4);
			add_filter( 'trx_addons_filter_featured_image',				'gravity_trx_addons_featured_image', 10, 2);
			add_filter( 'trx_addons_filter_no_image',					'gravity_trx_addons_no_image' );
			add_filter( 'trx_addons_filter_get_list_icons',				'gravity_trx_addons_get_list_icons', 10, 2 );
			add_filter( 'trx_addons_filter_slider_title',				'gravity_trx_addons_slider_title', 10, 2 );
			add_filter( 'trx_addons_filter_override_options_fields',			'gravity_trx_addons_override_options_fields', 10, 2);
			add_action( 'wp_enqueue_scripts', 							'gravity_trx_addons_frontend_scripts', 1100 );
			add_filter( 'gravity_filter_query_sort_order',	 			'gravity_trx_addons_query_sort_order', 10, 3);
			add_filter( 'gravity_filter_merge_scripts',					'gravity_trx_addons_merge_scripts');
			add_filter( 'gravity_filter_get_css',						'gravity_trx_addons_get_css', 10, 4);
			add_filter( 'gravity_filter_prepare_css',					'gravity_trx_addons_prepare_css', 10, 2);
			add_filter( 'gravity_filter_prepare_js',					'gravity_trx_addons_prepare_js', 10, 2);
			add_filter( 'gravity_filter_get_post_categories',		 	'gravity_trx_addons_get_post_categories');
			add_filter( 'gravity_filter_get_post_date',		 			'gravity_trx_addons_get_post_date');
			add_filter( 'trx_addons_filter_get_post_date',		 		'gravity_trx_addons_get_post_date_wrap');
			add_filter( 'gravity_filter_post_type_taxonomy',			'gravity_trx_addons_post_type_taxonomy', 10, 2 );
			if (is_admin()) {
				add_filter( 'gravity_filter_allow_override_options', 			'gravity_trx_addons_allow_override_options', 10, 2);
				add_filter( 'gravity_filter_allow_theme_icons', 		'gravity_trx_addons_allow_theme_icons', 10, 2);
				add_filter( 'trx_addons_sc_map',						'gravity_trx_addons_sc_map', 10, 2);
			} else {
				add_filter( 'trx_addons_filter_theme_logo',				'gravity_trx_addons_theme_logo');
				add_filter( 'trx_addons_filter_post_meta',				'gravity_trx_addons_post_meta', 10, 2);
				add_filter( 'gravity_filter_get_mobile_menu',			'gravity_trx_addons_get_mobile_menu');
				add_filter( 'gravity_filter_detect_blog_mode',			'gravity_trx_addons_detect_blog_mode' );
				add_filter( 'gravity_filter_get_blog_title', 			'gravity_trx_addons_get_blog_title');
				add_action( 'gravity_action_login',						'gravity_trx_addons_action_login', 10, 2);
				add_action( 'gravity_action_search',					'gravity_trx_addons_action_search', 10, 3);
				add_action( 'gravity_action_breadcrumbs',				'gravity_trx_addons_action_breadcrumbs');
				add_action( 'gravity_action_show_layout',				'gravity_trx_addons_action_show_layout', 10, 1);
			}
		}
		
		// Add this filter any time: if plugin exists - load plugin's styles, if not exists - load layouts.css instead plugin's styles
		add_filter( 'gravity_filter_merge_styles',						'gravity_trx_addons_merge_styles');
		
		if (is_admin()) {
			add_filter( 'gravity_filter_tgmpa_required_plugins',		'gravity_trx_addons_tgmpa_required_plugins' );
			add_action( 'admin_enqueue_scripts', 						'gravity_trx_addons_editor_load_scripts_admin');
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gravity_trx_addons_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('gravity_filter_tgmpa_required_plugins',	'gravity_trx_addons_tgmpa_required_plugins');
	function gravity_trx_addons_tgmpa_required_plugins($list=array()) {
		if (in_array('trx_addons', gravity_storage_get('required_plugins'))) {
			$path = gravity_get_file_dir('plugins/trx_addons/trx_addons.zip');
			$list[] = array(
					'name' 		=> esc_html__('ThemeREX Addons', 'gravity'),
					'slug' 		=> 'trx_addons',
					'version'	=> '1.6.17',
					'source'	=> !empty($path) ? $path : 'upload://trx_addons.zip',
					'required' 	=> true
				);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gravity_exists_trx_addons' ) ) {
	function gravity_exists_trx_addons() {
		return defined('TRX_ADDONS_VERSION');
	}
}

// Return true if team is supported
if ( !function_exists( 'gravity_exists_team' ) ) {
	function gravity_exists_team() {
		return defined('TRX_ADDONS_CPT_TEAM_PT');
	}
}

// Return true if services is supported
if ( !function_exists( 'gravity_exists_services' ) ) {
	function gravity_exists_services() {
		return defined('TRX_ADDONS_CPT_SERVICES_PT');
	}
}

// Return true if portfolio is supported
if ( !function_exists( 'gravity_exists_portfolio' ) ) {
	function gravity_exists_portfolio() {
		return defined('TRX_ADDONS_CPT_PORTFOLIO_PT');
	}
}

// Return true if courses is supported
if ( !function_exists( 'gravity_exists_courses' ) ) {
	function gravity_exists_courses() {
		return defined('TRX_ADDONS_CPT_COURSES_PT');
	}
}

// Return true if layouts is supported
if ( !function_exists( 'gravity_exists_layouts' ) ) {
	function gravity_exists_layouts() {
		return defined('TRX_ADDONS_CPT_LAYOUTS_PT');
	}
}

// Return true if dishes is supported
if ( !function_exists( 'gravity_exists_dishes' ) ) {
	function gravity_exists_dishes() {
		return defined('TRX_ADDONS_CPT_DISHES_PT');
	}
}

// Return true if it's team page
if ( !function_exists( 'gravity_is_team_page' ) ) {
	function gravity_is_team_page() {
		return defined('TRX_ADDONS_CPT_TEAM_PT') 
					&& !is_search()
					&& (
						(is_single() && get_post_type()==TRX_ADDONS_CPT_TEAM_PT)
						|| is_post_type_archive(TRX_ADDONS_CPT_TEAM_PT)
						|| is_tax(TRX_ADDONS_CPT_TEAM_TAXONOMY)
						);
	}
}

// Return true if it's services page
if ( !function_exists( 'gravity_is_services_page' ) ) {
	function gravity_is_services_page() {
		return defined('TRX_ADDONS_CPT_SERVICES_PT') 
					&& !is_search()
					&& (
						(is_single() && get_post_type()==TRX_ADDONS_CPT_SERVICES_PT)
						|| is_post_type_archive(TRX_ADDONS_CPT_SERVICES_PT)
						|| is_tax(TRX_ADDONS_CPT_SERVICES_TAXONOMY)
						);
	}
}

// Return true if it's portfolio page
if ( !function_exists( 'gravity_is_portfolio_page' ) ) {
	function gravity_is_portfolio_page() {
		return defined('TRX_ADDONS_CPT_PORTFOLIO_PT') 
					&& !is_search()
					&& (
						(is_single() && get_post_type()==TRX_ADDONS_CPT_PORTFOLIO_PT)
						|| is_post_type_archive(TRX_ADDONS_CPT_PORTFOLIO_PT)
						|| is_tax(TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY)
						);
	}
}

// Return true if it's courses page
if ( !function_exists( 'gravity_is_courses_page' ) ) {
	function gravity_is_courses_page() {
		return defined('TRX_ADDONS_CPT_COURSES_PT') 
					&& !is_search()
					&& (
						(is_single() && get_post_type()==TRX_ADDONS_CPT_COURSES_PT)
						|| is_post_type_archive(TRX_ADDONS_CPT_COURSES_PT)
						|| is_tax(TRX_ADDONS_CPT_COURSES_TAXONOMY)
						);
	}
}

// Return true if it's dishes page
if ( !function_exists( 'gravity_is_dishes_page' ) ) {
	function gravity_is_dishes_page() {
		return defined('TRX_ADDONS_CPT_DISHES_PT') 
					&& !is_search()
					&& (
						(is_single() && get_post_type()==TRX_ADDONS_CPT_DISHES_PT)
						|| is_post_type_archive(TRX_ADDONS_CPT_DISHES_PT)
						|| is_tax(TRX_ADDONS_CPT_DISHES_TAXONOMY)
						);
	}
}


// Enqueue custom styles
if ( !function_exists( 'gravity_trx_addons_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'gravity_trx_addons_frontend_scripts', 1100 );
	function gravity_trx_addons_frontend_scripts() {
		if (gravity_exists_trx_addons()) {
			if (gravity_is_on(gravity_get_theme_option('debug_mode')) && gravity_get_file_dir('plugins/trx_addons/trx_addons.css')!='') {
				wp_enqueue_style( 'gravity-trx-addons',  gravity_get_file_url('plugins/trx_addons/trx_addons.css'), array(), null );
				wp_enqueue_style( 'gravity-trx-addons-editor',  gravity_get_file_url('plugins/trx_addons/trx_addons.editor.css'), array(), null );
			}
			if (gravity_is_on(gravity_get_theme_option('debug_mode')) && gravity_get_file_dir('plugins/trx_addons/trx_addons.js')!='')
				wp_enqueue_script( 'gravity-trx-addons', gravity_get_file_url('plugins/trx_addons/trx_addons.js'), array('jquery'), null, true );
		} else {
			// Load custom layouts from the theme if plugin not exists
			if ( gravity_is_on(gravity_get_theme_option('debug_mode')) ) {
				wp_enqueue_style( 'gravity-layouts', gravity_get_file_url('plugins/trx_addons/layouts/layouts.css') );
				wp_enqueue_style( 'gravity-layouts', gravity_get_file_url('plugins/trx_addons/layouts/logo.css') );
				wp_enqueue_style( 'gravity-layouts', gravity_get_file_url('plugins/trx_addons/layouts/menu.css') );
				wp_enqueue_style( 'gravity-layouts', gravity_get_file_url('plugins/trx_addons/layouts/search.css') );
				wp_enqueue_style( 'gravity-layouts', gravity_get_file_url('plugins/trx_addons/layouts/title.css') );
				wp_enqueue_style( 'gravity-layouts', gravity_get_file_url('plugins/trx_addons/layouts/featured.css') );
			}
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'gravity_trx_addons_merge_styles' ) ) {
	//Handler of the add_filter( 'gravity_filter_merge_styles', 'gravity_trx_addons_merge_styles');
	function gravity_trx_addons_merge_styles($list) {
		// ALWAYS merge custom layouts from the theme
		$list[] = 'plugins/trx_addons/layouts/layouts.css';
		$list[] = 'plugins/trx_addons/layouts/logo.css';
		$list[] = 'plugins/trx_addons/layouts/menu.css';
		$list[] = 'plugins/trx_addons/layouts/search.css';
		$list[] = 'plugins/trx_addons/layouts/title.css';
		$list[] = 'plugins/trx_addons/layouts/featured.css';
		if (gravity_exists_trx_addons()) {
			$list[] = 'plugins/trx_addons/trx_addons.css';
			$list[] = 'plugins/trx_addons/trx_addons.editor.css';
		}
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'gravity_trx_addons_merge_scripts' ) ) {
	//Handler of the add_filter('gravity_filter_merge_scripts', 'gravity_trx_addons_merge_scripts');
	function gravity_trx_addons_merge_scripts($list) {
		$list[] = 'plugins/trx_addons/trx_addons.js';
		return $list;
	}
}

// Detect current blog mode
if ( !function_exists( 'gravity_trx_addons_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'gravity_filter_detect_blog_mode', 'gravity_trx_addons_detect_blog_mode' );
	function gravity_trx_addons_detect_blog_mode($mode='') {
		if ( gravity_is_team_page() )
			$mode = 'team';
		else if ( gravity_is_services_page() )
			$mode = 'services';
		else if ( gravity_is_portfolio_page() )
			$mode = 'portfolio';
		else if ( gravity_is_courses_page() )
			$mode = 'courses';
		else if ( gravity_is_dishes_page() )
			$mode = 'dishes';
		return $mode;
	}
}

// Add team, courses, etc. to the supported posts list
if ( !function_exists( 'gravity_trx_addons_list_post_types' ) ) {
	//Handler of the add_filter( 'gravity_filter_list_posts_types', 'gravity_trx_addons_list_post_types');
	function gravity_trx_addons_list_post_types($list=array()) {
		if (function_exists('trx_addons_get_cpt_list')) {
			$cpt_list = trx_addons_get_cpt_list();
			foreach ($cpt_list as $cpt => $title) {
				if (   (defined('TRX_ADDONS_CPT_COURSES_PT') && $cpt == TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $cpt == TRX_ADDONS_CPT_PORTFOLIO_PT)
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $cpt == TRX_ADDONS_CPT_SERVICES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $cpt == TRX_ADDONS_CPT_DISHES_PT)
					)
					$list[$cpt] = $title;
			}
		}
		return $list;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'gravity_trx_addons_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'gravity_filter_post_type_taxonomy',	'gravity_trx_addons_post_type_taxonomy', 10, 2 );
	function gravity_trx_addons_post_type_taxonomy($tax='', $post_type='') {
		if ( defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type == TRX_ADDONS_CPT_TEAM_PT )
			$tax = TRX_ADDONS_CPT_TEAM_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type == TRX_ADDONS_CPT_SERVICES_PT )
			$tax = TRX_ADDONS_CPT_SERVICES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type == TRX_ADDONS_CPT_PORTFOLIO_PT )
			$tax = TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type == TRX_ADDONS_CPT_COURSES_PT )
			$tax = TRX_ADDONS_CPT_COURSES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type == TRX_ADDONS_CPT_DISHES_PT )
			$tax = TRX_ADDONS_CPT_DISHES_TAXONOMY;
		return $tax;
	}
}

// Show categories of the team, courses, etc.
if ( !function_exists( 'gravity_trx_addons_get_post_categories' ) ) {
	//Handler of the add_filter( 'gravity_filter_get_post_categories', 		'gravity_trx_addons_get_post_categories');
	function gravity_trx_addons_get_post_categories($cats='') {

		if ( defined('TRX_ADDONS_CPT_TEAM_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_TEAM_PT) {
				$cats = gravity_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_TEAM_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_SERVICES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_SERVICES_PT) {
				$cats = gravity_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PORTFOLIO_PT) {
				$cats = gravity_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COURSES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
				$cats = gravity_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COURSES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_DISHES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_DISHES_PT) {
				$cats = gravity_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_DISHES_TAXONOMY);
			}
		}
		return $cats;
	}
}

// Add layouts to the headers list
if ( !function_exists( 'gravity_trx_addons_list_header_styles' ) ) {
	//Handler of the add_filter( 'gravity_filter_list_header_styles', 'gravity_trx_addons_list_header_styles');
	function gravity_trx_addons_list_header_styles($list=array()) {
		if (gravity_exists_layouts()) {
			$layouts = gravity_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'header'
							)
						);
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $list['header-custom-'.intval($id)] = $title;
			}
		}
		return $list;
	}
}

// Add layouts to the footers list
if ( !function_exists( 'gravity_trx_addons_list_footer_styles' ) ) {
	//Handler of the add_filter( 'gravity_filter_list_footer_styles', 'gravity_trx_addons_list_footer_styles');
	function gravity_trx_addons_list_footer_styles($list=array()) {
		if (gravity_exists_layouts()) {
			$layouts = gravity_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'footer'
							)
						);
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $list['footer-custom-'.intval($id)] = $title;
			}
		}
		return $list;
	}
}

// Show post's date with the theme-specific format
if ( !function_exists( 'gravity_trx_addons_get_post_date_wrap' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_get_post_date', 'gravity_trx_addons_get_post_date_wrap');
	function gravity_trx_addons_get_post_date_wrap($dt='') {
		return apply_filters('gravity_filter_get_post_date', $dt);
	}
}

// Show date of the courses
if ( !function_exists( 'gravity_trx_addons_get_post_date' ) ) {
	//Handler of the add_filter( 'gravity_filter_get_post_date', 'gravity_trx_addons_get_post_date');
	function gravity_trx_addons_get_post_date($dt='') {

		if ( defined('TRX_ADDONS_CPT_COURSES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
				$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
				$dt = $meta['date'];
				$dt = sprintf($dt < date('Y-m-d') 
						? esc_html__('Started on %s', 'gravity') 
						: esc_html__('Starting %s', 'gravity'), 
						date(get_option('date_format'), strtotime($dt)));
			}
		}
		return $dt;
	}
}

// Check if override options is allowed
if (!function_exists('gravity_trx_addons_allow_override_options')) {
	function gravity_trx_addons_allow_override_options($allow, $post_type) {
		return $allow
					|| (defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type==TRX_ADDONS_CPT_TEAM_PT) 
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type==TRX_ADDONS_CPT_SERVICES_PT) 
					|| (defined('TRX_ADDONS_CPT_RESUME_PT') && $post_type==TRX_ADDONS_CPT_RESUME_PT) 
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type==TRX_ADDONS_CPT_PORTFOLIO_PT) 
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type==TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type==TRX_ADDONS_CPT_DISHES_PT);
	}
}

// Check if theme icons is allowed
if (!function_exists('gravity_trx_addons_allow_theme_icons')) {
	//Handler of the add_filter( 'gravity_filter_allow_theme_icons', 'gravity_trx_addons_allow_theme_icons', 10, 2);
	function gravity_trx_addons_allow_theme_icons($allow, $post_type) {
		return $allow
					|| (defined('TRX_ADDONS_CPT_LAYOUTS_PT') && $post_type==TRX_ADDONS_CPT_LAYOUTS_PT);
	}
}

// Add fields into override options
if (!function_exists('gravity_trx_addons_override_options_fields')) {
	function gravity_trx_addons_override_options_fields($mb, $post_type) {
		if (defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type==TRX_ADDONS_CPT_TEAM_PT) {
			$mb['email'] = array(
				"title" => esc_html__("E-mail",  'gravity'),
				"desc" => wp_kses_data( __("Team member's email", 'gravity') ),
				"std" => "",
				"details" => true,
				"type" => "text"
			);

		}
		return $mb;
	}
}



// WP Editor addons
//------------------------------------------------------------------------

// Load required styles and scripts for admin mode
if ( !function_exists( 'gravity_trx_addons_editor_load_scripts_admin' ) ) {
	//Handler of the add_action("admin_enqueue_scripts", 'gravity_trx_addons_editor_load_scripts_admin');
	function gravity_trx_addons_editor_load_scripts_admin() {
		// Add styles in the WP text editor
		add_editor_style( array(
							gravity_get_file_url('plugins/trx_addons/trx_addons.editor.css')
							)
						 );	
	}
}



// Shortcodes support
//------------------------------------------------------------------------

// Add params to the default shortcode's atts
if ( !function_exists( 'gravity_trx_addons_sc_atts' ) ) {
	//Handler of the add_filter( 'trx_addons_sc_atts', 'gravity_trx_addons_sc_atts', 10, 2);
	function gravity_trx_addons_sc_atts($atts, $sc) {
		
		// Param 'scheme'
		if (in_array($sc, array('trx_sc_action', 'trx_sc_blogger', 'trx_sc_courses', 'trx_sc_content', 'trx_sc_events', 'trx_sc_form', 'trx_sc_googlemap',
								'trx_sc_portfolio', 'trx_sc_price', 'trx_sc_promo', 'trx_sc_services', 'trx_sc_team', 'trx_sc_testimonials', 'trx_sc_title',
								'trx_widget_audio', 'trx_widget_twitter')))
			$atts['scheme'] = 'inherit';
		return $atts;
	}
}

// Add params into shortcodes VC map
if ( !function_exists( 'gravity_trx_addons_sc_map' ) ) {
	//Handler of the add_filter( 'trx_addons_sc_map', 'gravity_trx_addons_sc_map', 10, 2);
	function gravity_trx_addons_sc_map($params, $sc) {

		// Param 'scheme'
		if (in_array($sc, array('trx_sc_action', 'trx_sc_blogger', 'trx_sc_courses', 'trx_sc_content', 'trx_sc_events', 'trx_sc_form', 'trx_sc_googlemap',
								'trx_sc_portfolio', 'trx_sc_price', 'trx_sc_promo', 'trx_sc_services', 'trx_sc_team', 'trx_sc_testimonials', 'trx_sc_title',
								'trx_widget_audio', 'trx_widget_twitter'))) {
			$params['params'][] = array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'gravity'),
					"description" => wp_kses_data( __("Select color scheme to decorate this block", 'gravity') ),
					"group" => esc_html__('Colors', 'gravity'),
					"admin_label" => true,
					"value" => array_flip(gravity_get_list_schemes(true)),
					"type" => "dropdown"
				);
		}
		return $params;
	}
}

// Add params into shortcode's output
if ( !function_exists( 'gravity_trx_addons_sc_output' ) ) {
	//Handler of the add_filter( 'trx_addons_sc_output', 'gravity_trx_addons_sc_output', 10, 4);
	function gravity_trx_addons_sc_output($output, $sc, $atts, $content) {
		
		// Param 'scheme'
		if (in_array($sc, array('trx_sc_action'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_action ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_action ', $output);
		} else if (in_array($sc, array('trx_sc_blogger'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_blogger ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_blogger ', $output);
		} else if (in_array($sc, array('trx_sc_courses'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_courses ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_courses ', $output);
		} else if (in_array($sc, array('trx_sc_content'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_content ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_content ', $output);
		} else if (in_array($sc, array('trx_sc_form'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_form ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_form ', $output);
		} else if (in_array($sc, array('trx_sc_googlemap'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_googlemap_content', 'class="scheme_'.esc_attr($atts['scheme']).' sc_googlemap_content', $output);
		} else if (in_array($sc, array('trx_sc_portfolio'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_portfolio ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_portfolio ', $output);
		} else if (in_array($sc, array('trx_sc_price'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_price ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_price ', $output);
		} else if (in_array($sc, array('trx_sc_promo'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_promo ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_promo ', $output);
		} else if (in_array($sc, array('trx_sc_services'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_services ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_services ', $output);
		} else if (in_array($sc, array('trx_sc_team'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_team ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_team ', $output);
		} else if (in_array($sc, array('trx_sc_testimonials'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_testimonials ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_testimonials ', $output);
		} else if (in_array($sc, array('trx_sc_title'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_title ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_title ', $output);
		} else if (in_array($sc, array('trx_sc_events'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('class="sc_events ', 'class="scheme_'.esc_attr($atts['scheme']).' sc_events ', $output);
		} else if (in_array($sc, array('trx_widget_audio'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('sc_widget_audio', 'scheme_'.esc_attr($atts['scheme']).' sc_widget_audio', $output);
		} else if (in_array($sc, array('trx_widget_twitter'))) {
			if (!empty($atts['scheme']) && !gravity_is_inherit($atts['scheme']))
				$output = str_replace('sc_widget_twitter', 'scheme_'.esc_attr($atts['scheme']).' sc_widget_twitter', $output);
		}
		
		// Check if layouts components are showed
		if (in_array($sc, array('trx_sc_layouts_featured')))
			gravity_sc_layouts_showed('featured', is_singular() && in_array(get_post_type(), array('post', 'page')) && has_post_thumbnail());
		else if (in_array($sc, array('trx_sc_layouts_title')))
			gravity_sc_layouts_showed('title', true);
		else if (in_array($sc, array('trx_sc_layouts_cart')))
			gravity_sc_layouts_showed('cart', true);
		else if (in_array($sc, array('trx_sc_layouts_logo')))
			gravity_sc_layouts_showed('logo', true);
		else if (in_array($sc, array('trx_sc_layouts_menu')))
			gravity_sc_layouts_showed('menu', true);
		else if (in_array($sc, array('trx_sc_layouts_search')))
			gravity_sc_layouts_showed('search', true);
			
		
		return $output;
	}
}

// Return theme specific title layout for the slider
if ( !function_exists( 'gravity_trx_addons_slider_title' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_slider_title',	'gravity_trx_addons_slider_title', 10, 2 );
	function gravity_trx_addons_slider_title($title, $data) {
		$title = '';
		if (!empty($data['title'])) 
			$title .= '<h3 class="slide_title">'
						. (!empty($data['link']) ? '<a href="'.esc_url($data['link']).'">' : '')
						. esc_html($data['title'])
						. (!empty($data['link']) ? '</a>' : '')
						. '</h3>';
		if (!empty($data['cats']))
			$title .= sprintf('<div class="slide_cats">%s</div>', $data['cats']);
		return $title;
	}
}



// Plugin API - theme-specific wrappers for plugin functions
//------------------------------------------------------------------------

// Debug functions wrappers
if (!function_exists('ddo')) { function ddo($obj, $level=-1) { return var_dump($obj); } }
if (!function_exists('dco')) { function dco($obj, $level=-1) { print_r($obj); } }
if (!function_exists('dcl')) { function dcl($msg, $level=-1) { echo '<br><pre>' . esc_html($msg) . '</pre><br>'; } }
if (!function_exists('dfo')) { function dfo($obj, $level=-1) {} }
if (!function_exists('dfl')) { function dfl($msg, $level=-1) {} }

// Check if layouts components are showed or set new state
if (!function_exists('gravity_sc_layouts_showed')) {
	function gravity_sc_layouts_showed($name, $val=null) {
		if ($val!==null)
			return gravity_storage_set_array('sc_layouts_components', $name, $val);
		else
			return gravity_storage_get_array('sc_layouts_components', $name) != '';
	}
}

// Return image size multiplier
if (!function_exists('gravity_get_retina_multiplier')) {
	function gravity_get_retina_multiplier($force_retina=0) {
		static $mult = 0;
		if ($mult == 0) $mult = function_exists('trx_addons_get_retina_multiplier') ? trx_addons_get_retina_multiplier($force_retina) : 1;
		return max(1, $mult);
	}
}

// Return slider layout
if (!function_exists('gravity_build_slider_layout')) {
	function gravity_build_slider_layout($args) {
		return function_exists('trx_addons_build_slider_layout') 
					? trx_addons_build_slider_layout($args) 
					: '';
	}
}

// Return theme specific layout of the featured image block
if ( !function_exists( 'gravity_trx_addons_featured_image' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_featured_image', 'gravity_trx_addons_featured_image', 10, 2);
	function gravity_trx_addons_featured_image($processed=false, $args=array()) {
		$args['show_no_image'] = true;
		$args['singular'] = false;
		$args['hover'] = gravity_get_theme_option('image_hover');
		gravity_show_post_featured($args);
		return true;
	}
}

// Return theme specific 'no-image' picture
if ( !function_exists( 'gravity_trx_addons_no_image' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_no_image', 'gravity_trx_addons_no_image');
	function gravity_trx_addons_no_image($no_image='') {
		return gravity_get_no_image($no_image);
	}
}

// Return theme-specific icons
if ( !function_exists( 'gravity_trx_addons_get_list_icons' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_get_list_icons', 'gravity_trx_addons_get_list_icons', 10, 2 );
	function gravity_trx_addons_get_list_icons($list, $prepend_inherit) {
		return gravity_get_list_icons($prepend_inherit);
	}
}

// Return links to the social profiles
if (!function_exists('gravity_get_socials_links')) {
	function gravity_get_socials_links($style='icons') {
		return function_exists('trx_addons_get_socials_links') 
					? trx_addons_get_socials_links($style)
					: '';
	}
}

// Return links to share post
if (!function_exists('gravity_get_share_links')) {
	function gravity_get_share_links($args=array()) {
		return function_exists('trx_addons_get_share_links') 
					? trx_addons_get_share_links($args)
					: '';
	}
}

// Display links to share post
if (!function_exists('gravity_show_share_links')) {
	function gravity_show_share_links($args=array()) {
		if (function_exists('trx_addons_get_share_links')) {
			$args['echo'] = true;
			trx_addons_get_share_links($args);
		}
	}
}


// Return image from the category
if (!function_exists('gravity_get_category_image')) {
	function gravity_get_category_image($term_id=0) {
		return function_exists('trx_addons_get_category_image') 
					? trx_addons_get_category_image($term_id)
					: '';
	}
}

// Return small image (icon) from the category
if (!function_exists('gravity_get_category_icon')) {
	function gravity_get_category_icon($term_id=0) {
		return function_exists('trx_addons_get_category_icon') 
					? trx_addons_get_category_icon($term_id)
					: '';
	}
}

// Return string with counters items
if (!function_exists('gravity_get_post_counters')) {
	function gravity_get_post_counters($counters='views') {
		return function_exists('trx_addons_get_post_counters')
					? str_replace('post_counters_item', 'post_meta_item post_counters_item', trx_addons_get_post_counters($counters))
					: '';
	}
}

// Return list with animation effects
if (!function_exists('gravity_get_list_animations_in')) {
	function gravity_get_list_animations_in() {
		return function_exists('trx_addons_get_list_animations_in') 
					? trx_addons_get_list_animations_in()
					: array();
	}
}

// Return classes list for the specified animation
if (!function_exists('gravity_get_animation_classes')) {
	function gravity_get_animation_classes($animation, $speed='normal', $loop='none') {
		return function_exists('trx_addons_get_animation_classes') 
					? trx_addons_get_animation_classes($animation, $speed, $loop)
					: '';
	}
}

// Return string with the likes counter for the specified comment
if (!function_exists('gravity_get_comment_counters')) {
	function gravity_get_comment_counters($counters = 'likes') {
		return function_exists('trx_addons_get_comment_counters') 
					? trx_addons_get_comment_counters($counters)
					: '';
	}
}

// Display likes counter for the specified comment
if (!function_exists('gravity_show_comment_counters')) {
	function gravity_show_comment_counters($counters = 'likes') {
		if (function_exists('trx_addons_get_comment_counters'))
			trx_addons_get_comment_counters($counters, true);
	}
}

// Add query params to sort posts by views or likes
if (!function_exists('gravity_trx_addons_query_sort_order')) {
	//Handler of the add_filter('gravity_filter_query_sort_order', 'gravity_trx_addons_query_sort_order', 10, 3);
	function gravity_trx_addons_query_sort_order($q=array(), $orderby='date', $order='desc') {
		if ($orderby == 'views') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_views_count';
		} else if ($orderby == 'likes') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_likes_count';
		}
		return $q;
	}
}

// Return theme-specific logo to the plugin
if ( !function_exists( 'gravity_trx_addons_theme_logo' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_theme_logo', 'gravity_trx_addons_theme_logo');
	function gravity_trx_addons_theme_logo($logo) {
		return gravity_get_logo_image();
	}
}

// Return theme-specific post meta to the plugin
if ( !function_exists( 'gravity_trx_addons_post_meta' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_post_meta',	'gravity_trx_addons_post_meta', 10, 2);
	function gravity_trx_addons_post_meta($meta, $args=array()) {
		return gravity_show_post_meta($args);
	}
}
	
// Redirect action 'get_mobile_menu' to the plugin
// Return stored items as mobile menu
if ( !function_exists( 'gravity_trx_addons_get_mobile_menu' ) ) {
	//Handler of the add_filter("gravity_filter_get_mobile_menu", 'gravity_trx_addons_get_mobile_menu');
	function gravity_trx_addons_get_mobile_menu($menu) {
		return apply_filters('trx_addons_filter_get_mobile_menu', $menu);
	}
}

// Redirect action 'login' to the plugin
if (!function_exists('gravity_trx_addons_action_login')) {
	//Handler of the add_action( 'gravity_action_login',		'gravity_trx_addons_action_login', 10, 2);
	function gravity_trx_addons_action_login($link_text='', $link_title='') {
		do_action( 'trx_addons_action_login', $link_text, $link_title );
	}
}

// Redirect action 'search' to the plugin
if (!function_exists('gravity_trx_addons_action_search')) {
	//Handler of the add_action( 'gravity_action_search', 'gravity_trx_addons_action_search', 10, 3);
	function gravity_trx_addons_action_search($style, $class, $ajax) {
		do_action( 'trx_addons_action_search', $style, $class, $ajax );
	}
}

// Redirect action 'breadcrumbs' to the plugin
if (!function_exists('gravity_trx_addons_action_breadcrumbs')) {
	//Handler of the add_action( 'gravity_action_breadcrumbs',	'gravity_trx_addons_action_breadcrumbs');
	function gravity_trx_addons_action_breadcrumbs() {
		do_action( 'trx_addons_action_breadcrumbs' );
	}
}

// Redirect action 'show_layout' to the plugin
if (!function_exists('gravity_trx_addons_action_show_layout')) {
	//Handler of the add_action( 'gravity_action_show_layout', 'gravity_trx_addons_action_show_layout', 10, 1);
	function gravity_trx_addons_action_show_layout($layout_id='') {
		do_action( 'trx_addons_action_show_layout', $layout_id );
	}
}

// Redirect filter 'get_blog_title' to the plugin
if ( !function_exists( 'gravity_trx_addons_get_blog_title' ) ) {
	//Handler of the add_filter( 'gravity_filter_get_blog_title', 'gravity_trx_addons_get_blog_title');
	function gravity_trx_addons_get_blog_title($title='') {
		return apply_filters('trx_addons_filter_get_blog_title', $title);
	}
}

// Redirect filter 'prepare_css' to the plugin
if (!function_exists('gravity_trx_addons_prepare_css')) {
	//Handler of the add_filter( 'gravity_filter_prepare_css',	'gravity_trx_addons_prepare_css', 10, 2);
	function gravity_trx_addons_prepare_css($css='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_css', $css, $remove_spaces );
	}
}

// Redirect filter 'prepare_js' to the plugin
if (!function_exists('gravity_trx_addons_prepare_js')) {
	//Handler of the add_filter( 'gravity_filter_prepare_js',	'gravity_trx_addons_prepare_js', 10, 2);
	function gravity_trx_addons_prepare_js($js='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_js', $js, $remove_spaces );
	}
}

// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'gravity_trx_addons_privacy_text' ) ) {
	add_filter( 'trx_addons_filter_privacy_text', 'gravity_trx_addons_privacy_text' );
	function gravity_trx_addons_privacy_text( $text='' ) {
		return gravity_get_privacy_text();
	}
}


// Add plugin-specific rules into custom CSS
//------------------------------------------------------------------------

// Add css styles into global CSS stylesheet
if (!function_exists('gravity_trx_addons_get_css')) {
	//Handler of the add_filter('gravity_filter_get_css', 'gravity_trx_addons_get_css', 10, 4);
	function gravity_trx_addons_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

.sc_button.sc_button_size_small,
.sc_action_item.sc_action_item_shop_1 .sc_action_item_description b,
.sc_skills_pie.sc_skills_compact_off .sc_skills_item_title,
.sc_dishes_compact .sc_services_item_title,
.sc_action_item.sc_action_item_shop_2 .sc_action_item_subtitle,
.sc_services_iconed .sc_services_item_title {
	{$fonts['p_font-family']}
}
.sc_testimonials_item_content,
.widget_contacts .contacts_info .contacts_left .contacts_label,
.widget_calendar caption,
.sc_table table tr:first-child th, .sc_table table tr:first-child td,
.sc_form_field_title,
.trx_addons_dropcap,
.toc_menu_item .toc_menu_description,
.sc_recent_news .post_item .post_footer .post_counters .post_counters_item,
.sc_item_subtitle.sc_item_title_style_shadow,
.sc_item_button a,
.sc_form button,
.sc_button_simple,
.sc_icons_item_title,
.sc_price_title, .sc_price_link,
.sc_courses_default .sc_courses_item_price,
.sc_courses_default .trx_addons_hover_content .trx_addons_hover_links a,
.sc_promo_modern .sc_promo_link2 span+span,
.sc_skills_pie.sc_skills_compact_off .sc_skills_item_title,
.slider_swiper .slide_info.slide_info_large .slide_title,
.slider_style_modern .slider_controls_label span + span,
.slider_pagination_wrap,
.sc_services_large_button,
.sc_action_item_simple .sc_action_item_link,
.sc_skills_counter .sc_skills_total,
.sc_promo_modern .sc_promo_text2 b,
.sc_skills_counter .sc_skills_item_title,
.sc_action_item.sc_action_item_shop_1 .sc_action_item_description,
.sc_action_item.sc_action_item_shop_1 .sc_action_item_link,
.cq-tabs .cq-tabmenu.style1 a,
#sb_instagram #sbi_load .sbi_load_btn, #sb_instagram .sbi_follow_btn a,
.sc_slider_controller_info {
	{$fonts['h5_font-family']}
}
.trx_addons_audio_player .audio_author,
.sc_item_subtitle,
.sc_item_button .sc_button_simple,
.sc_recent_news .post_item .post_meta,
.sc_action_item_description,
.sc_price_description,
.sc_price_details,
.sc_courses_default .sc_courses_item_date,
.courses_single .courses_page_meta,
.sc_promo_modern .sc_promo_link2 span,
.slider_style_modern .slider_controls_label span,
.slider_titles_outside_wrap .slide_cats,
.slider_titles_outside_wrap .slide_subtitle,
.sc_team .sc_team_item_subtitle,
.sc_dishes .sc_dishes_item_subtitle,
.sc_services .sc_services_item_subtitle,
.team_member_page .team_member_brief_info_text,
.sc_testimonials_item_author_title,
.sc_testimonials_item_content:before {
	{$fonts['info_font-family']}
}
.sc_layouts_iconed_text,
.sc_price_link {
	{$fonts['p_font-family']}
}

CSS;

			$rad = gravity_get_border_radius();
			$rad50 = ' '.$rad != ' 0' ? '50%' : 0;
			$css['fonts'] .= <<<CSS

.sc_item_button a,
.sc_form button,
.sc_button,
.sc_price_link,
.sc_action_item_link {
	-webkit-border-radius: {$rad};
	   -moz-border-radius: {$rad};
	    -ms-border-radius: {$rad};
			border-radius: {$rad};
}
.trx_addons_scroll_to_top,
.socials_wrap .social_item a {
	-webkit-border-radius: {$rad50};
	   -moz-border-radius: {$rad50};
	    -ms-border-radius: {$rad50};
			border-radius: {$rad50};
}

CSS;
		}


		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Custom layouts
--------------------------------- */

/* Menu hovers */

/* fade box */
.menu_hover_fade_box .sc_layouts_menu_nav > a:hover,
.menu_hover_fade_box .sc_layouts_menu_nav > li > a:hover,
.menu_hover_fade_box .sc_layouts_menu_nav > li.sfHover > a {
	color: {$colors['alter_link']};
	background-color: {$colors['alter_bg_color']};
}

/* slide_line */
.menu_hover_slide_line .sc_layouts_menu_nav > li#blob {
	background-color: {$colors['text_link']};
}

/* slide_box */
.menu_hover_slide_box .sc_layouts_menu_nav > li#blob {
	background-color: {$colors['alter_bg_color']};
}

/* zoom_line */
.menu_hover_zoom_line .sc_layouts_menu_nav > li > a:before {
	background-color: {$colors['text_link']};
}

/* path_line */
.menu_hover_path_line .sc_layouts_menu_nav > li:before,
.menu_hover_path_line .sc_layouts_menu_nav > li:after,
.menu_hover_path_line .sc_layouts_menu_nav > li > a:before,
.menu_hover_path_line .sc_layouts_menu_nav > li > a:after {
	background-color: {$colors['text_link']};
}

/* roll_down */
.menu_hover_roll_down .sc_layouts_menu_nav > li > a:before {
	background-color: {$colors['text_link']};
}

/* color_line */
.menu_hover_color_line .sc_layouts_menu_nav > li > a:before {
	background-color: {$colors['text_dark']};
}
.menu_hover_color_line .sc_layouts_menu_nav > li > a:after,
.menu_hover_color_line .sc_layouts_menu_nav > li.menu-item-has-children > a:after {
	background-color: {$colors['text_link']};
}
.menu_hover_color_line .sc_layouts_menu_nav > li.sfHover > a,
.menu_hover_color_line .sc_layouts_menu_nav > li > a:hover,
.menu_hover_color_line .sc_layouts_menu_nav > li > a:focus {
	color: {$colors['text_link']};
}

/* VC Separator */
.scheme_self.sc_layouts_row .vc_separator.vc_sep_color_grey .vc_sep_line,
.sc_layouts_row .vc_separator.vc_sep_color_grey .vc_sep_line {
	border-color: {$colors['alter_bd_color']};
}

/* Widget: Cart */
.sc_layouts_cart_items_short {
	background-color: {$colors['text_dark']};
	color: {$colors['bg_color']};
}
.sc_layouts_cart_widget {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}
.sc_layouts_cart_widget:after {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
}
.sc_layouts_cart_widget .sc_layouts_cart_widget_close {
	color: {$colors['text_light']};
}
.sc_layouts_cart_widget .sc_layouts_cart_widget_close:hover {
	color: {$colors['text_dark']};
}

/* Currency Switcher */
.sc_layouts_currency .woocommerce-currency-switcher-form .wSelect-selected {
	color: {$colors['alter_text']};
}
.sc_layouts_currency .woocommerce-currency-switcher-form .wSelect-selected:hover {
	color: {$colors['alter_dark']};
}
.sc_layouts_currency .chosen-container .chosen-results,
.sc_layouts_currency .woocommerce-currency-switcher-form .wSelect-options-holder,
.sc_layouts_currency .woocommerce-currency-switcher-form .dd-options,
.sc_layouts_currency .woocommerce-currency-switcher-form .dd-option {
	background: {$colors['alter_bg_color']};
	color: {$colors['alter_dark']};
}
.sc_layouts_currency .chosen-container .chosen-results li,
.sc_layouts_currency .woocommerce-currency-switcher-form .wSelect-option {
	color: {$colors['alter_dark']};
}
.sc_layouts_currency .chosen-container .active-result.highlighted,
.sc_layouts_currency .chosen-container .active-result.result-selected,
.sc_layouts_currency .woocommerce-currency-switcher-form .wSelect-option:hover,
.sc_layouts_currency .woocommerce-currency-switcher-form .wSelect-options-holder .wSelect-option-selected,
.sc_layouts_currency .woocommerce-currency-switcher-form .dd-option:hover,
.sc_layouts_currency .woocommerce-currency-switcher-form .dd-option-selected {
	color: {$colors['alter_link']} !important;
}
.sc_layouts_currency .woocommerce-currency-switcher-form .dd-option-description {
	color: {$colors['alter_text']};
}

/* Socials */
.footer_wrap .sc_layouts_row_type_compact .socials_wrap .social_item a,
.scheme_self.footer_wrap .sc_layouts_row_type_compact .socials_wrap .social_item a {
	color: {$colors['text_dark']};
	background-color: transparent;
}
.footer_wrap .sc_layouts_row_type_compact .socials_wrap .social_item a:hover,
.scheme_self.footer_wrap .sc_layouts_row_type_compact .socials_wrap .social_item a:hover {
	color: {$colors['text_link']};
	background-color: transparent;
}



/* User styles
------------------------------------------ */
.trx_addons_accent,
.trx_addons_accent > a,
.trx_addons_accent > * {
	color: {$colors['text_link']};
}
.trx_addons_accent > a:hover {
	color: {$colors['text_dark']};
}
.sidebar .trx_addons_accent,
.scheme_self.sidebar .trx_addons_accent,
.sidebar .trx_addons_accent > a,
.scheme_self.sidebar .trx_addons_accent > a,
.sidebar .trx_addons_accent > *,
.scheme_self.sidebar .trx_addons_accent > *,
.footer_wrap .trx_addons_accent,
.scheme_self.footer_wrap .trx_addons_accent,
.footer_wrap .trx_addons_accent > a,
.scheme_self.footer_wrap .trx_addons_accent > a,
.footer_wrap .trx_addons_accent > *,
.scheme_self.footer_wrap .trx_addons_accent > * {
	color: {$colors['alter_link']};
}
.sidebar .trx_addons_accent > a:hover,
.scheme_self.sidebar .trx_addons_accent > a:hover,
.footer_wrap .trx_addons_accent > a:hover,
.scheme_self.footer_wrap .trx_addons_accent > a:hover {
	color: {$colors['alter_dark']};
}

.trx_addons_hover,
.trx_addons_hover > * {
	color: {$colors['text_hover']};
}
.trx_addons_accent_bg {
	background-color: {$colors['text_hover']};
	color: {$colors['text_dark']};
}
.trx_addons_inverse {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.trx_addons_dark,
.trx_addons_dark > a {
	color: {$colors['text_dark']};
}
.trx_addons_dark > a:hover {
	color: {$colors['text_link']};
}

.trx_addons_inverse,
.trx_addons_inverse > a {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.trx_addons_inverse > a:hover {
	color: {$colors['inverse_hover']};
}

.trx_addons_dropcap_style_1 {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}
.trx_addons_dropcap_style_2 {
	color: {$colors['text_dark']};
	background-color: transparent;
}

ul[class*="trx_addons_list"] > li:before {
	color: {$colors['text_link']};
}
ul[class*="trx_addons_list"][class*="_circled"] > li:before {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.trx_addons_list_parameters > li + li {
	border-color: {$colors['bd_color']};
}
ol > li:before {
	color: {$colors['text_link']};
}
ol.trx_addons_list_parameters > li:before {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}

.trx_addons_tooltip {
	color: {$colors['text']};
	border-color: {$colors['text']};
}
.trx_addons_tooltip:before {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.trx_addons_tooltip:after {
	border-top-color: {$colors['text_dark']};
}

blockquote.trx_addons_blockquote_style_1:before,
blockquote.trx_addons_blockquote_style_1 {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
blockquote.trx_addons_blockquote_style_1 b {
	color: {$colors['bg_color']};
}
blockquote.trx_addons_blockquote_style_1 a,
blockquote.trx_addons_blockquote_style_1 cite {
	color: {$colors['text_link']};
}
blockquote.trx_addons_blockquote_style_1 a:hover {
	color: {$colors['bg_color']};
}
blockquote.trx_addons_blockquote_style_2 {
}
blockquote.trx_addons_blockquote_style_2:before {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}

.trx_addons_hover_mask {
	background-color: {$colors['text_dark_07']};
}
.trx_addons_hover_title {
	color: {$colors['inverse_link']};
}
.trx_addons_hover_text {
	color: {$colors['text_light']};
}
.trx_addons_hover_icon,
.trx_addons_hover_links a {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}
.trx_addons_hover_icon:hover,
.trx_addons_hover_links a:hover {
	color: {$colors['alter_link']} !important;
	background-color: {$colors['alter_bg_color']};
}


/* Tabs */
.widget .trx_addons_tabs .trx_addons_tabs_titles li a {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bd_color']};
}
.widget .trx_addons_tabs .trx_addons_tabs_titles li.ui-state-active a,
.widget .trx_addons_tabs .trx_addons_tabs_titles li a:hover {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}


/* Posts slider */
.slider_swiper .slide_info.slide_info_large {
	background-color: {$colors['bg_color_07']};
}
.slider_swiper .slide_info.slide_info_large:hover {
	background-color: {$colors['bg_color']};
}
.slider_swiper .slide_info.slide_info_large .slide_cats a {
	color: {$colors['text_link']};
}
.slider_swiper .slide_info.slide_info_large .slide_title a {
	color: {$colors['text_dark']};
}
.slider_swiper .slide_info.slide_info_large .slide_date {
	color: {$colors['text']};
}
.slider_swiper .slide_info.slide_info_large:hover .slide_date {
	color: {$colors['text_light']};
}
.slider_swiper .slide_info.slide_info_large .slide_cats a:hover,
.slider_swiper .slide_info.slide_info_large .slide_title a:hover {
	color: {$colors['text_hover']};
}
.slider_swiper.slider_multi .slide_cats a:hover,
.slider_swiper.slider_multi .slide_title a:hover,
.slider_swiper.slider_multi a:hover .slide_title {
	color: {$colors['text_hover']};
}

.slider_swiper.slider_controls_side .slider_controls_wrap > a,
.slider_outer_controls_side .slider_controls_wrap > a {
	color: {$colors['text_dark']};
}
.sc_testimonials.slider_swiper.slider_controls_side .slider_controls_wrap > a,
.sc_testimonials.slider_outer_controls_side .slider_controls_wrap > a {
	color: {$colors['text_dark']};
	background-color: transparent;
	border-color: transparent;
}
.slider_swiper.slider_controls_side .slider_controls_wrap > a:hover,
.slider_outer_controls_side .slider_controls_wrap > a:hover {
	color: {$colors['text']};
}
.sc_testimonials.slider_swiper.slider_controls_side .slider_controls_wrap > a:hover,
.sc_testimonials.slider_outer_controls_side .slider_controls_wrap > a:hover {
	color: {$colors['text']};
	background-color: transparent;
	border-color: transparent;
}

.slider_swiper.slider_controls_bottom .slider_controls_wrap > a,
.slider_outer_controls_bottom .slider_controls_wrap > a {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}
.slider_swiper.slider_controls_bottom .slider_controls_wrap > a:hover,
.slider_outer_controls_bottom .slider_controls_wrap > a:hover {
	color: {$colors['bg_color']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.slider_swiper .slider_pagination_wrap .swiper-pagination-bullet,
.slider_swiper_outer .slider_pagination_wrap .swiper-pagination-bullet {
	background-color: {$colors['bg_color_02']};
}
.slider_swiper .slider_pagination_wrap .swiper-pagination-bullet.swiper-pagination-bullet-active,
.slider_swiper_outer .slider_pagination_wrap .swiper-pagination-bullet.swiper-pagination-bullet-active,
.slider_swiper .slider_pagination_wrap .swiper-pagination-bullet:hover,
.slider_swiper_outer .slider_pagination_wrap .swiper-pagination-bullet:hover {
	border-color: {$colors['bg_color']};
	background-color: {$colors['bg_color']};
}

.slider_titles_outside_wrap .slide_title a {
	color: {$colors['text_dark']};
}
.slider_titles_outside_wrap .slide_title a:hover {
	color: {$colors['text_link']};
}
.slider_titles_outside_wrap .slide_cats,
.slider_titles_outside_wrap .slide_subtitle {
	color: {$colors['text_link']};
}

.slider_style_modern .slider_controls_label {
	color: {$colors['text_dark']};
}
.slider_style_modern .slider_pagination_wrap {
	color: {$colors['text_light']};
}
.slider_style_modern .swiper-pagination-current {
	color: {$colors['text_dark']};
}

.sc_slider_controller .swiper-slide.swiper-slide-active {
	border-color: {$colors['text_link']};
}
.sc_slider_controller_titles .swiper-slide {
	background-color: {$colors['alter_bg_color']};
}
.sc_slider_controller_titles .swiper-slide:after {
	background-color: {$colors['alter_bd_color']};
}
.sc_slider_controller_titles .swiper-slide.swiper-slide-active {
	background-color: {$colors['bg_color']};
}
.sc_slider_controller_titles .sc_slider_controller_info_title {
	color: {$colors['alter_dark']};
}
.sc_slider_controller_titles .sc_slider_controller_info_number {
	color: {$colors['alter_light']};
}
.sc_slider_controller_titles .slider_controls_wrap > a {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.sc_slider_controller_titles .slider_controls_wrap > a:hover {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}


/* Widgets 
--------------------------------------------------- */

/* Audio */
.trx_addons_audio_player.without_cover {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.trx_addons_audio_player.with_cover .audio_caption {
	color: {$colors['inverse_link']};
}
.trx_addons_audio_player .audio_author {
	color: {$colors['text']};
}
.trx_addons_audio_player .mejs-container .mejs-controls .mejs-time {
	color: {$colors['text']};
}
.trx_addons_audio_player.with_cover .mejs-container .mejs-controls .mejs-time {
	color: {$colors['inverse_link']};
}

/* Categories list */
.widget_categories_list .categories_list_style_2 .categories_list_title a:hover {
	color: {$colors['text_dark']};
}

/* Contacts */
.widget_contacts .contacts_info .contacts_left .contacts_label {
    color: {$colors['inverse_text']};
}
.widget_contacts .contacts_info {
	color: {$colors['text']};
}
.widget_contacts .contacts_info span:before {
	color: {$colors['text']};
}
.widget_contacts .contacts_info span a {
	color: {$colors['text']};
}
.widget_contacts .contacts_info span a:hover {
	color: {$colors['text_hover']};
}

/* Recent News */
/* Attention! This widget placed in the content area and should use main text colors */
.sc_recent_news_header {
	border-color: {$colors['text_dark']};
}
.sc_recent_news_header_category_item_more {
	color: {$colors['text_link']};
}
.sc_recent_news_header_more_categories {
	border-color: {$colors['alter_bd_color']};
	background-color:{$colors['alter_bg_color']};
}
.sc_recent_news_header_more_categories > a {
	color:{$colors['alter_link']};
}
.sc_recent_news_header_more_categories > a:hover {
	color:{$colors['alter_hover']};
	background-color:{$colors['alter_bg_hover']};
}
.sc_recent_news .post_counters_item,
.sc_recent_news .post_counters .post_counters_edit a {
	color:{$colors['inverse_link']};
	background-color:{$colors['text_link']};
}
.sc_recent_news .post_counters_item:hover,
.sc_recent_news .post_counters .post_counters_edit a:hover {
	color:{$colors['bg_color']};
	background-color:{$colors['text_dark']};
}
.sidebar_inner .sc_recent_news .post_counters_item:hover,
.sidebar_inner .sc_recent_news .post_counters .post_counters_edit a:hover {
	color:{$colors['alter_dark']};
	background-color:{$colors['alter_bg_color']};
}
.sc_recent_news_style_news-magazine .post_accented_border {
	border-color: {$colors['bd_color']};
}
.sc_recent_news_style_news-excerpt .post_item {
	border-color: {$colors['bd_color']};
}

/* Twitter */
.widget_twitter .widget_content .sc_twitter_item,
.widget_twitter .widget_content li {
	color: {$colors['text']};
}
.widget_twitter .widget_content .sc_twitter_item .sc_twitter_item_icon {
	color: {$colors['text_link']} !important;
}
.widget_twitter .swiper-pagination-bullet {
	background-color: {$colors['text_light']};
}
.widget_twitter .swiper-pagination-bullet-active {
	background-color: {$colors['text_link']};
}

.widget_twitter .widget_content .sc_twitter_list li {
	color: {$colors['alter_text']};
}
.widget_twitter .widget_content .sc_twitter_list li:before {
	color: {$colors['alter_link']} !important;
}

/* Video */
.trx_addons_video_player.with_cover .video_hover {
	color: {$colors['inverse_text']};
	background-color: transparent;
	border-color: {$colors['inverse_text']};
}
.trx_addons_video_player.with_cover .video_hover:hover {
	color: {$colors['inverse_text']};
	background-color: transparent;
	border-color: {$colors['inverse_text']};
}
.sidebar_inner .trx_addons_video_player.with_cover .video_hover {
	color: {$colors['alter_link']};
}
.sidebar_inner .trx_addons_video_player.with_cover .video_hover:hover {
	color: {$colors['inverse_hover']};
	background-color: {$colors['alter_link']};
}
.widget_area .post_item .post_title a, .widget .post_item .post_title a {
    color: {$colors['text']};
}
.widget_area .post_item .post_info .post_info_item a, .widget .post_item .post_info .post_info_item a,
.widget_area .post_item .post_info .post_info_item, .widget .post_item .post_info .post_info_item,
.widget_area .post_item .post_title a:hover, .widget .post_item .post_title a:hover {
    color: {$colors['text_dark']};
}



/* Shortcodes
--------------------------------------------------- */

.sc_item_subtitle {
	/*color:{$colors['inverse_text']};*/
}
.sc_item_subtitle {
	color:{$colors['text']};
}

.sc_item_subtitle.sc_item_title_style_inverse,
.sc_item_title.sc_item_title_style_inverse {
	color:{$colors['inverse_text']};
}
.sc_item_subtitle.sc_item_title_style_shadow {
	color:{$colors['text_light']};
}
.sc_item_button a.sc_button_inverse:not(.sc_button_bg_image) {
	color:{$colors['text_dark']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
.sc_item_button a.sc_button_inverse:not(.sc_button_bg_image):hover {
	color:{$colors['inverse_text']};
	border-color: {$colors['inverse_text']};
	background-color: transparent;
}
.sc_item_button a:not(.sc_button_bg_image) {
	color:{$colors['inverse_link']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_item_button a:not(.sc_button_bg_image):hover {
	color:{$colors['text_link']};
	background-color:transparent;
	border-color: {$colors['text_link']};
}
.sc_item_button a:not(.sc_button_bg_image).sc_button_size_small:hover {
	color:{$colors['text_link']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
button.sc_button_simple:not(.sc_button_bg_image),
.sc_item_button button.sc_button_simple:not(.sc_button_bg_image),
button.sc_button_simple:not(.sc_button_bg_image):before,
.sc_item_button button.sc_button_simple:not(.sc_button_bg_image):before,
button.sc_button_simple:not(.sc_button_bg_image):after,
.sc_item_button button.sc_button_simple:not(.sc_button_bg_image):after,
a.sc_button_simple:not(.sc_button_bg_image),
.sc_item_button a.sc_button_simple:not(.sc_button_bg_image),
a.sc_button_simple:not(.sc_button_bg_image):before,
.sc_item_button a.sc_button_simple:not(.sc_button_bg_image):before,
a.sc_button_simple:not(.sc_button_bg_image):after,
.sc_item_button a.sc_button_simple:not(.sc_button_bg_image):after {
	color:{$colors['text_link']};
}
a.sc_button_simple:not(.sc_button_bg_image):hover,
.sc_item_button a.sc_button_simple:not(.sc_button_bg_image):hover,
a.sc_button_simple:not(.sc_button_bg_image):hover:before,
.sc_item_button a.sc_button_simple:not(.sc_button_bg_image):hover:before,
a.sc_button_simple:not(.sc_button_bg_image):hover:after,
.sc_item_button a.sc_button_simple:not(.sc_button_bg_image):hover:after,
button.sc_button_simple:not(.sc_button_bg_image):hover,
.sc_item_button button.sc_button_simple:not(.sc_button_bg_image):hover,
button.sc_button_simple:not(.sc_button_bg_image):hover:before,
.sc_item_button button.sc_button_simple:not(.sc_button_bg_image):hover:before,
button.sc_button_simple:not(.sc_button_bg_image):hover:after,
.sc_item_button button.sc_button_simple:not(.sc_button_bg_image):hover:after {
	color:{$colors['text']} !important;
}

.trx_addons_hover_content .trx_addons_hover_links a {
	color:{$colors['inverse_link']};
	background-color:{$colors['text_link']};
}
.trx_addons_hover_content .trx_addons_hover_links a:hover {
	color:{$colors['inverse_hover']} !important;
	background-color:{$colors['text_hover']};
}

/* Action */
.sc_action_item .sc_action_item_subtitle {
	color:{$colors['text_link']};
}
.sc_action_item_date,
.sc_action_item_info {
	color:{$colors['text_dark']};
	border-color:{$colors['text']};
}
.sc_action_item_description {
	color:{$colors['text']};
}
.vc_row-has-fill .sc_action_item_description {
	color:{$colors['inverse_text_08']};
}
.sc_action_item.sc_action_item_light .sc_action_item_link {
	color: {$colors['inverse_text']};
	background-color: transparent;
}
.sc_action_item.sc_action_item_light .sc_action_item_link:hover {
	color: {$colors['text_hover']};
	background-color: transparent;
}
.sc_action_item .sc_action_item_link {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.sc_action_item .sc_action_item_link:hover {
	color: {$colors['inverse_hover']};
	background-color: {$colors['text_link_blend']};
}
.sc_action_item_event.with_image .sc_action_item_inner {
	background-color:{$colors['bg_color']};
}
.sc_action_item.sc_action_item_light .sc_action_item_title {
	color: {$colors['inverse_text']};
}
.sc_action_item_simple .sc_action_item_link {
	color: {$colors['text_dark']};
	background-color: transparent;
	border-color: {$colors['text_hover']};
}
.sc_action_item_simple .sc_action_item_link:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_action_item.sc_action_item_shop_1 .sc_action_item_description b,
.sc_action_item.sc_action_item_shop_1 .sc_action_item_description,
.sc_action_item.sc_action_item_shop_1 .sc_action_item_title {
	color: {$colors['inverse_text']};
}
.sc_action_item.sc_action_item_shop_1 .sc_action_item_link {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_action_item.sc_action_item_shop_1 .sc_action_item_link:hover {
	color: {$colors['inverse_text']};
	background-color: transparent;
	border-color: {$colors['inverse_text']};
}
.sc_action_item.sc_action_item_shop_2 .sc_action_item_subtitle {
	background-color: {$colors['bg_color']};
}
.sc_action_item.sc_action_item_shop_2 .sc_action_item_link {
	color: {$colors['text_dark']};
}




/* Anchor */
.scheme_self.menu_side_icons .toc_menu_item .toc_menu_icon,
.menu_side_inner > .toc_menu_item .toc_menu_icon {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
	color: {$colors['text_link']};
}
.scheme_self.menu_side_icons .toc_menu_item:hover .toc_menu_icon,
.scheme_self.menu_side_icons .toc_menu_item_active .toc_menu_icon,
.menu_side_inner > .toc_menu_item:hover .toc_menu_icon,
.menu_side_inner > .toc_menu_item_active .toc_menu_icon {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_link']};
}
.scheme_self.menu_side_icons .toc_menu_icon_default:before,
.menu_side_inner > .toc_menu_icon_default:before {
	background-color: {$colors['text_link']};
}
.scheme_self.menu_side_icons .toc_menu_item:hover .toc_menu_icon_default:before,
.scheme_self.menu_side_icons .toc_menu_item_active .toc_menu_icon_default:before,
.menu_side_inner > .toc_menu_item:hover .toc_menu_icon_default:before,
.menu_side_inner > .toc_menu_item_active .toc_menu_icon_default:before {
	background-color: {$colors['text_dark']};
}
.scheme_self.menu_side_icons .toc_menu_item .toc_menu_description,
.menu_side_inner > .toc_menu_item .toc_menu_description {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}

.scheme_self.menu_side_dots #toc_menu .toc_menu_item .toc_menu_icon {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['alter_text']};
}
.scheme_self.menu_side_dots #toc_menu .toc_menu_item:hover .toc_menu_icon,
.scheme_self.menu_side_dots #toc_menu .toc_menu_item_active .toc_menu_icon {
	color: {$colors['alter_link']};
}
.scheme_self.menu_side_dots #toc_menu .toc_menu_item .toc_menu_icon:before {
	background-color: {$colors['alter_link']};
}
.scheme_self.menu_side_dots #toc_menu .toc_menu_item:hover .toc_menu_icon:before {
	background-color: {$colors['alter_hover']};
}

/* Blogger */
.sc_blogger.slider_swiper .swiper-pagination-bullet {
	border-color: {$colors['text_light']};
}

.sc_blogger_item {
	background-color: {$colors['bg_color']};
}
.sc_blogger_post_meta {
	color: {$colors['alter_light']};
}
.sc_blogger .post_meta_item.post_categories a {
	color: {$colors['text']};
}
.sc_blogger .post_meta_item.post_categories a:hover {
	color: {$colors['text_dark']};
}
.sc_blogger_item_title a {
	color: {$colors['alter_dark']};
}
.sc_blogger_item_title a:hover {
	color: {$colors['text']};
}
.sc_blogger_post_meta {
	color: {$colors['alter_light']};
}
.sc_blogger_item_content {
	color: {$colors['alter_text']};
}
.sc_blogger_item .more-link {
	color: {$colors['alter_link']};
}
.sc_blogger_item .more-link:hover {
	color: {$colors['alter_dark']};
}


/* Content area */
.sc_content_number {
	color: {$colors['alter_bg_hover']};
}


/* Countdown */
.sc_countdown_default .sc_countdown_digits span {
	color: {$colors['inverse_link']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_link']};
}
.sc_countdown_circle .sc_countdown_digits {
	color: {$colors['alter_link']};
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}

/* Courses */
.sc_courses.slider_swiper .swiper-pagination-bullet {
	border-color: {$colors['text_light']};
}

.sc_courses_default .sc_courses_item {
	background-color: {$colors['alter_bg_color']};
}
.sc_courses_default .sc_courses_item_categories {
	background-color: {$colors['alter_dark']};
}
.sc_courses_default .sc_courses_item_categories a {
	color: {$colors['bg_color']};
}
.sc_courses_default .sc_courses_item_categories a:hover {
	color: {$colors['alter_link']};
}
.sc_courses_default .sc_courses_item_meta {
	color: {$colors['alter_light']};
}
.sc_courses_default .sc_courses_item_date {
	color: {$colors['alter_dark']};
}
.sc_courses_default .sc_courses_item_price {
	color: {$colors['alter_link']};
}
.sc_courses_default .sc_courses_item_period {
	color: {$colors['alter_light']};
}
.courses_single .courses_page_meta {
	color: {$colors['text_light']};
}
.courses_single .courses_page_meta_item_date {
	color: {$colors['text_dark']};
}
.courses_single .courses_page_period {
	color: {$colors['text_light']};
}


/* Dishes */
.sc_dishes_default .sc_dishes_item {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
}
.sc_dishes_default .sc_dishes_item_subtitle {
	color: {$colors['alter_link']};
}
.sc_dishes_default .sc_dishes_item_featured_left,
.sc_dishes_default .sc_dishes_item_featured_right {
	color: {$colors['text']};
	background-color: transparent;
}
.sc_dishes_default .sc_dishes_item_featured_left .sc_dishes_item_subtitle,
.sc_dishes_default .sc_dishes_item_featured_right .sc_dishes_item_subtitle {
	color: {$colors['text_link']};
}

.sc_dishes_compact .sc_dishes_item {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
}
.sc_dishes_compact .sc_dishes_item_header {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.sc_dishes_compact .sc_dishes_item_price,
.sc_dishes_compact .sc_dishes_item_subtitle a {
	color: {$colors['bg_color']};
}
.sc_dishes_compact .sc_dishes_item_price:hover,
.sc_dishes_compact .sc_dishes_item:hover .sc_dishes_item_price,
.sc_dishes_compact .sc_dishes_item_subtitle a:hover,
.sc_dishes_compact .sc_dishes_item:hover .sc_dishes_item_subtitle a {
	color: {$colors['text_link']};
}
.sc_dishes_compact .sc_dishes_item_title a {
	color: {$colors['text_link']};
}
.sc_dishes_compact .sc_dishes_item_title a:hover,
.sc_dishes_compact .sc_dishes_item:hover .sc_dishes_item_title a {
	color: {$colors['bg_color']};
}
.sc_dishes.slider_swiper .swiper-pagination-bullet {
	border-color: {$colors['text_light']};
}


/* Events */
.sc_events.slider_swiper .swiper-pagination-bullet {
	border-color: {$colors['text_light']};
}

.sc_events_default .sc_events_item {
	background-color: {$colors['alter_bg_color']};
}
.sc_events_default .sc_events_item_date {
	background-color: {$colors['alter_link']};
	color: {$colors['inverse_link']};
}
.sc_events_default .sc_events_item:hover .sc_events_item_date {
	background-color: {$colors['alter_dark']};
}
.sc_events_default .sc_events_item_title {
	color: {$colors['alter_dark']};
}
.sc_events_default .sc_events_item:hover .sc_events_item_title {
	color: {$colors['alter_link']};
}
.sc_events_default .sc_events_item_button {
	color: {$colors['alter_link']};
}
.sc_events_default .sc_events_item:hover .sc_events_item_button {
	color: {$colors['alter_dark']};
}

.sc_events_detailed .sc_events_item,
.sc_events_detailed .sc_events_item_date_wrap,
.sc_events_detailed .sc_events_item_time_wrap:before,
.sc_events_detailed .sc_events_item_button_wrap:before {
	border-color: {$colors['text_link']};
}
.sc_events_detailed .sc_events_item_date,
.sc_events_detailed .sc_events_item_button {
	color: {$colors['text_link']};
}
.sc_events_detailed .sc_events_item_title {
	color: {$colors['text_dark']};
}
.sc_events_detailed .sc_events_item_time {
	color: {$colors['text']};
}
.sc_events_detailed .sc_events_item:hover {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_link']};
}
.sc_events_detailed .sc_events_item:hover,
.sc_events_detailed .sc_events_item:hover .sc_events_item_date,
.sc_events_detailed .sc_events_item:hover .sc_events_item_button,
.sc_events_detailed .sc_events_item:hover .sc_events_item_title,
.sc_events_detailed .sc_events_item:hover .sc_events_item_time {
	color: {$colors['inverse_hover']};
}
.sc_events_detailed .sc_events_item:hover,
.sc_events_detailed .sc_events_item:hover .sc_events_item_date_wrap,
.sc_events_detailed .sc_events_item:hover .sc_events_item_time_wrap:before,
.sc_events_detailed .sc_events_item:hover .sc_events_item_button_wrap:before {
	border-color: {$colors['inverse_hover']};
}

/* Form */
.scheme_self.sc_form {
	background-color: {$colors['bg_color']};
}
.sc_form_field_title {
	color: {$colors['text_dark']};
}
.sc_form .sc_form_field input[type="text"],
.sc_form .sc_form_field input[type="password"],
.sc_form .sc_form_field input[type="email"],
.sc_form .sc_form_field input[type="number"],
.sc_form .sc_form_field input[type="tel"],
.sc_form .sc_form_field input[type="search"],
.sc_form .sc_form_field textarea {
	color: {$colors['text']};
	border-color: {$colors['alter_bd_color']};
	background-color: transparent;
}
.sc_form.sc_form_modern .sc_form_field input[type="text"],
.sc_form.sc_form_modern .sc_form_field input[type="password"],
.sc_form.sc_form_modern .sc_form_field input[type="email"],
.sc_form.sc_form_modern .sc_form_field input[type="number"],
.sc_form.sc_form_modern .sc_form_field input[type="tel"],
.sc_form.sc_form_modern .sc_form_field input[type="search"],
.sc_form.sc_form_modern .sc_form_field textarea {
	background-color: {$colors['alter_bd_color']};
}
.sc_form .sc_form_field input[type="text"]:hover,
.sc_form .sc_form_field input[type="password"]:hover,
.sc_form .sc_form_field input[type="email"]:hover,
.sc_form .sc_form_field input[type="number"]:hover,
.sc_form .sc_form_field input[type="tel"]:hover,
.sc_form .sc_form_field input[type="search"]:hover,
.sc_form .sc_form_field textarea:hover {
	border-color: {$colors['bd_color']};
}
.sc_form .sc_form_field input[type="text"]:active,
.sc_form .sc_form_field input[type="password"]:active,
.sc_form .sc_form_field input[type="email"]:active,
.sc_form .sc_form_field input[type="number"]:active,
.sc_form .sc_form_field input[type="tel"]:active,
.sc_form .sc_form_field input[type="search"]:active,
.sc_form .sc_form_field textarea:active,
.sc_form .sc_form_field input[type="text"]:focus,
.sc_form .sc_form_field input[type="password"]:focus,
.sc_form .sc_form_field input[type="email"]:focus,
.sc_form .sc_form_field input[type="number"]:focus,
.sc_form .sc_form_field input[type="tel"]:focus,
.sc_form .sc_form_field input[type="search"]:focus,
.sc_form .sc_form_field textarea:focus {
	color: {$colors['text']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['bg_color']};
}
.sc_form .sc_form_info_icon {
	color: {$colors['text_link']};
}
.sc_form .sc_form_info_data > a,
.sc_form .sc_form_info_data > span {
	color: {$colors['text_dark']};
}
.sc_form .sc_form_info_data > a:hover {
	color: {$colors['text_link']};
}


/* input hovers */
[class*="sc_input_hover_"] .sc_form_field_content {
	color: {$colors['text_dark']};
}
.sc_input_hover_accent input[type="text"]:focus,
.sc_input_hover_accent input[type="number"]:focus,
.sc_input_hover_accent input[type="email"]:focus,
.sc_input_hover_accent input[type="password"]:focus,
.sc_input_hover_accent input[type="search"]:focus,
.sc_input_hover_accent select:focus,
.sc_input_hover_accent textarea:focus {
	/*box-shadow: 0px 0px 0px 2px {$colors['text_link']};*/
	border-color: {$colors['text_link']} !important;
}
.sc_input_hover_accent .sc_form_field_hover:before {
	color: {$colors['text_link_02']};
}

.sc_input_hover_path .sc_form_field_graphic {
	stroke: {$colors['input_bd_color']};
}

.sc_input_hover_jump .sc_form_field_content {
	color: {$colors['input_dark']};
}
.sc_input_hover_jump .sc_form_field_content:before {
	color: {$colors['text_link']};
}
.sc_input_hover_jump input[type="text"],
.sc_input_hover_jump input[type="number"],
.sc_input_hover_jump input[type="email"],
.sc_input_hover_jump input[type="password"],
.sc_input_hover_jump input[type="search"],
.sc_input_hover_jump textarea {
	border-color: {$colors['input_bd_color']};
}
.sc_input_hover_jump input[type="text"]:focus,
.sc_input_hover_jump input[type="number"]:focus,
.sc_input_hover_jump input[type="email"]:focus,
.sc_input_hover_jump input[type="password"]:focus,
.sc_input_hover_jump input[type="search"]:focus,
.sc_input_hover_jump textarea:focus {
	border-color: {$colors['text_link']} !important;
}

.sc_input_hover_underline .sc_form_field_hover:before {
	background-color: {$colors['input_bd_color']};
}
.sc_input_hover_underline input:focus + .sc_form_field_hover:before,
.sc_input_hover_underline textarea:focus + .sc_form_field_hover:before,
.sc_input_hover_underline input.filled + .sc_form_field_hover:before,
.sc_input_hover_underline textarea.filled + .sc_form_field_hover:before {
	background-color: {$colors['text_link']};
}
.sc_input_hover_underline .sc_form_field_content {
	color: {$colors['input_dark']};
}
.sc_input_hover_underline input:focus,
.sc_input_hover_underline textarea:focus,
.sc_input_hover_underline input.filled,
.sc_input_hover_underline textarea.filled,
.sc_input_hover_underline input:focus + .sc_form_field_hover > .sc_form_field_content,
.sc_input_hover_underline textarea:focus + .sc_form_field_hover > .sc_form_field_content,
.sc_input_hover_underline input.filled + .sc_form_field_hover > .sc_form_field_content,
.sc_input_hover_underline textarea.filled + .sc_form_field_hover > .sc_form_field_content {
	color: {$colors['text_link']} !important;
}

.sc_input_hover_iconed .sc_form_field_hover {
	color: {$colors['input_text']};
}
.sc_input_hover_iconed input:focus + .sc_form_field_hover,
.sc_input_hover_iconed textarea:focus + .sc_form_field_hover,
.sc_input_hover_iconed input.filled + .sc_form_field_hover,
.sc_input_hover_iconed textarea.filled + .sc_form_field_hover {
	color: {$colors['input_dark']};
}

/* Googlemap */
.sc_googlemap_content,
.scheme_self.sc_googlemap_content {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}
.sc_googlemap_content b,
.sc_googlemap_content strong,
.scheme_self.sc_googlemap_content b,
.scheme_self.sc_googlemap_content strong {
	color: {$colors['text_dark']};
}
.sc_googlemap_content_detailed:before {
	color: {$colors['text_link']};
}
.sc_googlemap_content_default {
	border-color: {$colors['bg_color']};
	background-color: {$colors['text_hover']};
}

/* Icons */
.sc_icons .sc_icons_icon {
	color: {$colors['text_link']};
}
.sc_icons .sc_icons_item_linked:hover .sc_icons_icon {
	color: {$colors['text_dark']};
}
.sc_icons .sc_icons_item_title {
	color: {$colors['text_link']};
}
.scheme_self.footer_wrap .sc_icons .sc_icons_item_title {
	color: {$colors['text_dark']};
}
.scheme_self.footer_wrap .sc_icons .sc_icons_item_description {
	color: {$colors['text']};
}
.sc_icons_item_description,
.sc_icons_modern .sc_icons_item_description {
	color: {$colors['text_dark']};
}

/* Price */
.sc_price.sc_price_light {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
	border-color: {$colors['alter_bd_color']};
}
.sc_price {
	color: {$colors['text']};
	background-color: {$colors['alter_bd_color']};
	border-color: {$colors['alter_bd_color']};
}
.sc_price .sc_price_info {
	background-color: {$colors['text_hover']};
}
.sc_price .sc_price_icon {
	color: {$colors['text_link']};
}
.sc_price .sc_price_icon:hover {
	color: {$colors['text_hover']};
}
.sc_price_info .sc_price_subtitle {
	color: {$colors['text_dark']};
}
.sc_price_info .sc_price_title,
.sc_price_info .sc_price_title a {
	color: {$colors['text_link']};
}
.sc_price_info .sc_price_price {
	color: {$colors['text']};
}
.sc_price_info .sc_price_description,
.sc_price_info .sc_price_details {
	color: {$colors['text_dark']};
}
.sc_price_link {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_price_link:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}

/* Promo */
.sc_promo_icon {
	color:{$colors['text_link']};
}
.sc_promo .sc_promo_title,
.sc_promo .sc_promo_descr {
	color:{$colors['text_dark']};
}
.sc_promo .sc_promo_content {
	color:{$colors['text']};
}
.sc_promo_modern .sc_promo_link2 {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']} !important;
}
.sc_promo_modern .sc_promo_link2:hover {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.scheme_self.sc_promo .sc_promo_text.trx_addons_stretch_height,
.scheme_self.sc_promo .sc_promo_text_inner {
	background-color: {$colors['alter_bg_color']};
}
.scheme_self.sc_promo .sc_promo_title {
	color:{$colors['alter_link']};
}
.scheme_self.sc_promo .sc_promo_subtitle {
	color:{$colors['alter_hover']};
}
.scheme_self.sc_promo .sc_promo_descr {
	color:{$colors['alter_dark']};
}
.scheme_self.sc_promo .sc_promo_content {
	color:{$colors['alter_text']};
}
.sc_promo_modern .sc_promo_text_inner {
	border-color: {$colors['bg_color']};
}

/* Services */
.sc_services .sc_services_item_number {
	color: {$colors['alter_bg_hover']};
}

.sc_services_default .sc_services_item {
	color: {$colors['text']};
	background-color: {$colors['alter_bd_color']};
}
.sc_services_default .sc_services_item_icon {
	color: {$colors['text_dark']};
	border-color: transparent;
}
.sc_services_default .sc_services_item:hover .sc_services_item_icon {
	border-color: {$colors['text_hover']};
}
.sc_services_default .sc_services_item.without_content.with_icon.sc_services_item_featured_top:hover {
	border-color: {$colors['text_hover']};
}
.sc_services_default .sc_services_item.without_content.with_icon.sc_services_item_featured_top:hover .sc_services_item_icon {
	border-color: transparent;
}
.sc_services_default .sc_services_item_subtitle {
	color: {$colors['alter_link']};
}
.sc_services_default .sc_services_item_featured_left,
.sc_services_default .sc_services_item_featured_right,
.sc_services_list .sc_services_item {
	color: {$colors['text']};
	background-color: transparent;
}
.sc_services_default .sc_services_item_featured_left .sc_services_item_icon,
.sc_services_default .sc_services_item_featured_right .sc_services_item_icon,
.sc_services_list .sc_services_item_icon {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_services_list .sc_services_item:hover .sc_services_item_icon {
	color: {$colors['text_hover']};
}
.sc_services_default .sc_services_item_featured_left:hover .sc_services_item_icon,
.sc_services_default .sc_services_item_featured_right:hover .sc_services_item_icon,
.sc_services_list .sc_services_item_featured_left:hover .sc_services_item_icon,
.sc_services_list .sc_services_item_featured_right:hover .sc_services_item_icon {
	color: {$colors['inverse_dark']};
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_services_default .sc_services_item_featured_left .sc_services_item_subtitle,
.sc_services_default .sc_services_item_featured_right .sc_services_item_subtitle {
	color: {$colors['text_link']};
}

.sc_services_light .sc_services_item_icon {
	color: {$colors['text_link']};
}
.sc_services_light .sc_services_item:hover .sc_services_item_icon {
	color: {$colors['text_hover']};
}

.sc_services_iconed .sc_services_item {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
}
.sc_services_iconed .sc_services_item_icon:hover,
.sc_services_iconed .sc_services_item:hover .sc_services_item_icon,
.sc_services_iconed .sc_services_item_header .sc_services_item_subtitle a:hover,
.sc_services_iconed .sc_services_item:hover .sc_services_item_header .sc_services_item_subtitle a {
	color: {$colors['text_link']};
}
.sc_services_iconed .sc_services_item_header .sc_services_item_title a {
	color: {$colors['text_link']};
}
.sc_services_iconed .sc_services_item_header .sc_services_item_title a:hover,
.sc_services_iconed .sc_services_item:hover .sc_services_item_header .sc_services_item_title a {
	color: #fff;
}
.sc_services_iconed .sc_services_item .sc_services_item_header .sc_services_item_subtitle a {
	color: #fff;
}
.sc_services_iconed .sc_services_item:hover .sc_services_item_header .sc_services_item_subtitle a,
.sc_services_iconed .sc_services_item .sc_services_item_header .sc_services_item_subtitle a:hover {
	color: {$colors['text_link']};
}
.sc_services_iconed .sc_services_item_content .sc_services_item_title a {
	color: {$colors['alter_dark']};
}
.sc_services_iconed .sc_services_item_content .sc_services_item_title a:hover,
.sc_services_iconed .sc_services_item:hover .sc_services_item_content .sc_services_item_title a {
	color: {$colors['alter_link']};
}
.sc_services.slider_swiper .swiper-pagination-bullet {
	border-color: {$colors['text_light']};
}

.sc_services_list .sc_services_item_featured_left .sc_services_item_number,
.sc_services_list .sc_services_item_featured_right .sc_services_item_number {
	color: {$colors['text_light']};
}

.sc_services_hover .sc_services_item_icon,
.sc_services_hover .sc_services_item_title a:hover,
.sc_services_hover .sc_services_item_subtitle a:hover {
	color: {$colors['text_link']};
}
.sc_services_hover [class*="column-"]:nth-child(2n) .sc_services_item.with_image .sc_services_item_header.without_image,
.sc_services_hover .swiper-slide:nth-child(2n) .sc_services_item.with_image .sc_services_item_header.without_image {
	background-color:{$colors['alter_bg_hover']};
}
.sc_services_hover [class*="column-"]:nth-child(2n+1) .sc_services_item.with_image .sc_services_item_header.without_image,
.sc_services_hover .swiper-slide:nth-child(2n+1) .sc_services_item.with_image .sc_services_item_header.without_image {
	background-color:{$colors['alter_bg_color']};
}
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_icon,
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_number {
	color: {$colors['alter_light']};
}
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_title a {
	color: {$colors['alter_dark']};
}
.sc_services_hover .sc_services_item.with_image:hover .sc_services_item_header.without_image .sc_services_item_title a,
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_title a:hover {
	color: {$colors['alter_link']};
}
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_subtitle a {
	color: {$colors['alter_link']};
}
.sc_services_hover .sc_services_item.with_image:hover .sc_services_item_header.without_image .sc_services_item_subtitle a,
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_subtitle a:hover {
	color: {$colors['alter_dark']};
}
.sc_services_hover .sc_services_item.with_image .sc_services_item_header.without_image .sc_services_item_text {
	color: {$colors['alter_text']};
}

.sc_services_chess .sc_services_item {
	color:{$colors['alter_text']};
	background-color:{$colors['alter_bg_color']};
}
.sc_services_chess .sc_services_item_title a {
	color:{$colors['alter_dark']};
}
.sc_services_chess .sc_services_item_title a:hover {
	color:{$colors['alter_link']};
}
.sc_services_chess .sc_services_item:hover {
	color:{$colors['text_light']};
	background-color:{$colors['text_dark']};
}
.sc_services_chess .sc_services_item:hover .sc_services_item_title a {
	color:{$colors['bg_color']};
}
.sc_services_chess .sc_services_item:hover .sc_services_item_title a:hover {
	color:{$colors['text_link']};
}


.sc_services_tabs_simple .sc_services_item_icon {
	color: {$colors['text_link']};
}
.sc_services_tabs_simple .sc_services_item:hover .sc_services_item_icon,
.sc_services_tabs_simple .sc_services_item:hover .sc_services_item_title,
.sc_services_tabs_simple .sc_services_item:hover .sc_services_item_subtitle,
.sc_services_tabs_simple .sc_services_tabs_list_item_active .sc_services_item_icon,
.sc_services_tabs_simple .sc_services_tabs_list_item_active .sc_services_item_title,
.sc_services_tabs_simple .sc_services_tabs_list_item_active .sc_services_item_subtitle {
	color: {$colors['text_hover']};
}

.sc_services_tabs .sc_services_item_content {
	color:{$colors['alter_text']};
	background-color:{$colors['alter_bg_color']};
}
.sc_services_tabs .sc_services_item_title a {
	color:{$colors['alter_dark']};
}
.sc_services_tabs .sc_services_item_title a:hover {
	color:{$colors['alter_link']};
}
.sc_services_tabs .sc_services_tabs_list_item .sc_services_item_icon {
	color: {$colors['alter_link']};
}
.sc_services_tabs .sc_services_tabs_list_item .sc_services_item_number {
	color: {$colors['alter_light']};
}
.sc_services_tabs .sc_services_tabs_list_item {
	background-color:{$colors['alter_bg_color']};
}
.sc_services_tabs .sc_services_tabs_list_item:nth-child(2n+2) {
	background-color:{$colors['alter_bg_hover']};
}
.sc_services_tabs .sc_services_tabs_list_item:hover,
.sc_services_tabs .sc_services_tabs_list_item:nth-child(2n+2):hover {
	background-color:{$colors['alter_bd_hover']};
}
.sc_services_tabs .sc_services_tabs_list_item .sc_services_item_title {
	color:{$colors['alter_dark']};
}
.sc_services_tabs .sc_services_tabs_list_item:hover .sc_services_item_title {
	color:{$colors['alter_link']};
}
.sc_services_tabs .sc_services_tabs_list_item:hover .sc_services_item_icon {
	color:{$colors['alter_hover']};
}
.sc_services_tabs .sc_services_tabs_list_item:hover .sc_services_item_number {
	color: {$colors['alter_text']};
}
.sc_services_tabs .sc_services_tabs_list_item.sc_services_tabs_list_item_active {
	background-color:{$colors['alter_dark']} !important;
}
.sc_services_tabs .sc_services_tabs_list_item.sc_services_tabs_list_item_active .sc_services_item_title {
	color: {$colors['bg_color']};
}
.sc_services_tabs .sc_services_tabs_list_item.sc_services_tabs_list_item_active .sc_services_item_icon {
	color: {$colors['alter_link']};
}
.sc_services_tabs .sc_services_tabs_list_item.sc_services_tabs_list_item_active .sc_services_item_number {
	color: {$colors['alter_link']};
}
.sc_services.sc_services_inverse .sc_services_item {
	color: {$colors['inverse_text']};
    background-color:{$colors['bg_color_03']};
}
.sc_services.sc_services_inverse .sc_services_item_icon {
	color: {$colors['inverse_text']};
}
.sc_services.sc_services_inverse .sc_services_item_icon:hover {
	color: {$colors['text_hover']};
}
.sc_services.sc_services_inverse .sc_services_item_title,
.sc_services.sc_services_inverse .sc_services_item_title a {
	color: {$colors['inverse_text']};
}
.sc_services.sc_services_inverse .sc_services_item_title a:hover {
	color: {$colors['text_hover']};
}
.sc_services .sc_services_large_button {
	color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_services .sc_services_large_button:hover {
	color: {$colors['inverse_text']};
	border-color: {$colors['inverse_text']};
}
.sc_services_light .sc_services_item_inverse_colors .sc_services_item_content {
	color: {$colors['inverse_text_08']};
}
.sc_services_light .sc_services_item_inverse_colors .sc_services_item_title,
.sc_services_light .sc_services_item_inverse_colors .sc_services_item_title a {
	color: {$colors['inverse_text']};
}
.sc_services_light .sc_services_item_inverse_colors .sc_services_item_title a:hover {
	color: {$colors['text_hover']};
}
.sc_services_light .sc_services_item_inverse_colors .sc_item_button a.sc_button_simple:not(.sc_button_bg_image),
.sc_services_light .sc_services_item_inverse_colors .sc_item_button a.sc_button_simple:not(.sc_button_bg_image):before,
.sc_services_light .sc_services_item_inverse_colors .sc_item_button a.sc_button_simple:not(.sc_button_bg_image):after {
	color: {$colors['inverse_text']};
}
.sc_services_light .sc_services_item_inverse_colors .sc_item_button a.sc_button_simple:not(.sc_button_bg_image):hover,
.sc_services_light .sc_services_item_inverse_colors .sc_item_button a.sc_button_simple:not(.sc_button_bg_image):hover:before,
.sc_services_light .sc_services_item_inverse_colors .sc_item_button a.sc_button_simple:not(.sc_button_bg_image):hover:after {
	color: {$colors['text_hover']}!important;
}
.sc_services.sc_services_large_icon .sc_services_item_icon:hover,
.sc_services.sc_services_large_icon .sc_services_item_title a:hover {
	color: {$colors['text']};
}
.sc_services.sc_services_large_icon .sc_services_large_button {
    color: {$colors['text_dark']};
    border-color: {$colors['text_hover']};
}
.sc_services.sc_services_large_icon .sc_services_large_button:hover {
    color: {$colors['text_dark']};
    border-color: {$colors['text_dark']};
}

/* Skills (Counters) */
.sc_skills_counter .sc_skills_icon {
	color:{$colors['text_dark']};
}
.sc_skills .sc_skills_total {
	color:{$colors['text_link']};
}
.sc_skills .sc_skills_item_title,
.sc_skills .sc_skills_legend_title,
.sc_skills .sc_skills_legend_value {
	color:{$colors['text_dark']};
}
.sc_skills_counter .sc_skills_column + .sc_skills_column:before {
	background-color: {$colors['bd_color']};
}
.sc_skills_counter .with_image .skills_info {
	border-color: {$colors['bg_color']};
	background-color: {$colors['text_hover']};
}


/* Socials */

.socials_wrap .social_item a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}
.socials_wrap .social_item a:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sidebar_inner .socials_wrap .social_item a {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}
.sidebar_inner .socials_wrap .social_item a:hover {
	color: {$colors['inverse_dark']};
	background-color: {$colors['alter_hover']};
}
.footer_wrap .socials_wrap .social_item a,
.scheme_self.footer_wrap .socials_wrap .social_item a {
	color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_dark']};
}
.footer_wrap .socials_wrap .social_item a:hover,
.scheme_self.footer_wrap .socials_wrap .social_item a:hover {
	color: {$colors['text_dark']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
.sc_layouts_title_caption {
	color: {$colors['inverse_text']};
}

/* Testimonials */
.sc_testimonials_item {
	background-color: {$colors['text_hover']};
}
.sc_testimonials_item_content {
	color: {$colors['text_dark']};
}
.sc_testimonials_item_content:before {
	color: {$colors['text_link']};
}
.sc_testimonials_item_author_title {
	color: {$colors['text_link']};
}
.sc_testimonials_item_author_subtitle {
	color: {$colors['text_light']};
}
.sc_testimonials_simple .sc_testimonials_item_author_data:before  {
	background-color: {$colors['text_light']};
}
.sc_testimonials_simple [class*="column"] .sc_testimonials_item_author_data {
	border-color: {$colors['text_light']};
}
.sc_testimonials .swiper-pagination-bullet {
	background-color: {$colors['text_light']};
}
.sc_testimonials .swiper-pagination-bullet-active {
	background-color: {$colors['text_link']};
}
.sc_testimonials_slider .sc_testimonials_item_author_avatar {
	border-color: {$colors['bg_color']};
}

/* Team */
.sc_team_default .sc_team_item {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
}
.sc_team_default .sc_team_item_subtitle {
	color: {$colors['alter_link']};
}
.sc_team_default .sc_team_item_socials .social_item a,
.team_member_page .team_member_socials .social_item a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}
.sc_team_default .sc_team_item_socials .social_item a:hover,
.team_member_page .team_member_socials .social_item a:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}
.sc_team .sc_team_item_thumb .sc_team_item_title a,
.sc_team .sc_team_item_thumb .sc_team_item_subtitle a,
.sc_team .sc_team_item_thumb .sc_team_item_content a {
	color: {$colors['inverse_link']};
}
.sc_team .sc_team_item_thumb .sc_team_item_title a:hover,
.sc_team .sc_team_item_thumb .sc_team_item_subtitle a:hover,
.sc_team .sc_team_item_thumb .sc_team_item_content a:hover {
	color: {$colors['inverse_hover']};
}
.sc_team .sc_team_item_thumb .sc_team_item_socials .social_item a {
	color: {$colors['inverse_link']};
	border-color: {$colors['inverse_link']};
}
.sc_team .sc_team_item_thumb .sc_team_item_socials .social_item a:hover {
	color: {$colors['text_link']};
	background-color: {$colors['inverse_link']};
}
.team_member_page .team_member_featured .team_member_avatar {
	border-color: {$colors['bd_color']};
}
.sc_team_short .sc_team_item_thumb {
	border-color: {$colors['text_link']};
}
.sc_team.slider_swiper .swiper-pagination-bullet {
	border-color: {$colors['text_light']};
}
.sc_team_short .sc_team_item_socials .social_icons {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}
.sc_team_short .sc_team_item_inverse_colors .sc_team_item_subtitle {
	color: {$colors['inverse_text_08']};
}
.sc_team_short .sc_team_item_inverse_colors .sc_team_item_title,
.sc_team_short .sc_team_item_inverse_colors .sc_team_item_title a {
	color: {$colors['inverse_text']};
}
.sc_team_short .sc_team_item_socials .social_icons:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}


/* Utils
--------------------------------------------------- */

/* Scroll to top */
.trx_addons_scroll_to_top,
.trx_addons_cv .trx_addons_scroll_to_top {
	color: {$colors['inverse_link']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.trx_addons_scroll_to_top:hover,
.trx_addons_cv .trx_addons_scroll_to_top:hover {
	color: {$colors['inverse_hover']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}


/* Login and Register */
.trx_addons_popup {
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
	color: {$colors['alter_text']};
}
.trx_addons_popup .mfp-close {
	background-color: {$colors['alter_bg_hover']};
	border-color: {$colors['alter_bd_hover']};
	color:{$colors['alter_text']};
}
.trx_addons_popup .mfp-close:hover {
	background-color: {$colors['alter_dark']};
	color: {$colors['alter_bg_color']};
}
.trx_addons_popup .trx_addons_tabs_title {
	background-color:{$colors['alter_bg_hover']};
	border-color: {$colors['alter_bd_hover']};
}
.trx_addons_popup .trx_addons_tabs_title.ui-tabs-active {
	background-color:{$colors['alter_bg_color']};
	border-bottom-color: transparent;
}
.trx_addons_popup .trx_addons_tabs_title a,
.trx_addons_popup .trx_addons_tabs_title a > i {
	color:{$colors['alter_text']};
}
.trx_addons_popup .trx_addons_tabs_title a:hover,
.trx_addons_popup .trx_addons_tabs_title a:hover > i {
	color:{$colors['alter_link']};
}
.trx_addons_popup .trx_addons_tabs_title[data-disabled="true"] a,
.trx_addons_popup .trx_addons_tabs_title[data-disabled="true"] a > i,
.trx_addons_popup .trx_addons_tabs_title[data-disabled="true"] a:hover,
.trx_addons_popup .trx_addons_tabs_title[data-disabled="true"] a:hover > i {
	color:{$colors['alter_light']};
}
.trx_addons_popup .trx_addons_tabs_title.ui-tabs-active a,
.trx_addons_popup .trx_addons_tabs_title.ui-tabs-active a > i,
.trx_addons_popup .trx_addons_tabs_title.ui-tabs-active a:hover,
.trx_addons_popup .trx_addons_tabs_title.ui-tabs-active a:hover > i {
	color:{$colors['alter_dark']};
}

/* Profiler */
.trx_addons_profiler {
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_hover']};
}
.trx_addons_profiler_title {
	color: {$colors['alter_dark']};
}
.trx_addons_profiler table td,
.trx_addons_profiler table th {
	border-color: {$colors['alter_bd_color']};
}
.trx_addons_profiler table td {
	color: {$colors['alter_text']};
}
.trx_addons_profiler table th {
	background-color: {$colors['alter_bg_hover']};
	color: {$colors['alter_dark']};
}


/* CV */
.trx_addons_cv,
.trx_addons_cv_body_wrap {
	color: {$colors['alter_text']};
	background-color:{$colors['alter_bg_color']};
}
.trx_addons_cv a {
	color: {$colors['alter_link']};
}
.trx_addons_cv a:hover {
	color: {$colors['alter_hover']};
}

.trx_addons_cv_header {
	background-color: {$colors['bg_color']};
}
.trx_addons_cv_header_image img {
	border-color: {$colors['text_dark']};
}
.trx_addons_cv_header .trx_addons_cv_header_letter,
.trx_addons_cv_header .trx_addons_cv_header_text {
	color: {$colors['text_dark']};
}
.trx_addons_cv_header .trx_addons_cv_header_socials .social_item > a {
	color: {$colors['text_dark_07']};	
}
.trx_addons_cv_header .trx_addons_cv_header_socials .social_item > a:hover {
	color: {$colors['text_dark']};	
}

.trx_addons_cv_header_letter,
.trx_addons_cv_header_text,
.trx_addons_cv_header_socials .social_item > a {
	text-shadow: 1px 1px 6px {$colors['bg_color']};
}

.trx_addons_cv_tint_dark .trx_addons_cv_header_letter,
.trx_addons_cv_tint_dark .trx_addons_cv_header_text,
.trx_addons_cv_tint_dark .trx_addons_cv_header_socials .social_item > a {
	color: {$colors['bg_color']};	
	text-shadow: 1px 1px 3px {$colors['text_dark']};
}
.trx_addons_cv_tint_dark .trx_addons_cv_header_socials .social_item > a:hover {
	color: {$colors['text_hover']};	
}

.trx_addons_cv_navi_buttons .trx_addons_cv_navi_buttons_area .trx_addons_cv_navi_buttons_item {
	color: {$colors['alter_light']};
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['bg_color']};
}
.trx_addons_cv_navi_buttons .trx_addons_cv_navi_buttons_area .trx_addons_cv_navi_buttons_item_active,
.trx_addons_cv_navi_buttons .trx_addons_cv_navi_buttons_area .trx_addons_cv_navi_buttons_item:hover {
	color: {$colors['alter_dark']};
	border-color: {$colors['alter_bg_color']};
}


.trx_addons_cv .trx_addons_cv_section_title,
.trx_addons_cv .trx_addons_cv_section_title a {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_section_title.ui-state-active {
	border-color: {$colors['alter_dark']};
}
.trx_addons_cv_section_content .trx_addons_tabs .trx_addons_tabs_titles li > a {
	color: {$colors['alter_light']};
}
.trx_addons_cv_section_content .trx_addons_tabs .trx_addons_tabs_titles li.ui-state-active > a,
.trx_addons_cv_section_content .trx_addons_tabs .trx_addons_tabs_titles li > a:hover {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_section .trx_addons_pagination > * {
	color:{$colors['alter_text']};
}
.trx_addons_cv_section .trx_addons_pagination > a:hover {
	color: {$colors['alter_dark']};
}
.trx_addons_pagination > span.active {
	color: {$colors['alter_dark']};
	border-color: {$colors['alter_dark']};
}
.trx_addons_cv_breadcrumbs .trx_addons_cv_breadcrumbs_item {
	color: {$colors['alter_light']};
}
.trx_addons_cv_breadcrumbs a.trx_addons_cv_breadcrumbs_item:hover {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_single .trx_addons_cv_single_title {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_single .trx_addons_cv_single_subtitle {
	color: {$colors['alter_light']};
}

.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_2 .trx_addons_cv_resume_column:nth-child(2n+2) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_3 .trx_addons_cv_resume_column:nth-child(3n+2) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_3 .trx_addons_cv_resume_column:nth-child(3n+3) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+2) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+3) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+4) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_2 .trx_addons_cv_resume_column:nth-child(2n+3) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_2 .trx_addons_cv_resume_column:nth-child(2n+4) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_3 .trx_addons_cv_resume_column:nth-child(3n+4) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_3 .trx_addons_cv_resume_column:nth-child(3n+5) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_3 .trx_addons_cv_resume_column:nth-child(3n+6) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+5) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+6) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+7) .trx_addons_cv_resume_item,
.trx_addons_tabs_content_delimiter .trx_addons_cv_resume_columns_4 .trx_addons_cv_resume_column:nth-child(4n+8) .trx_addons_cv_resume_item {
	border-color: {$colors['alter_bd_color']};
}
.trx_addons_cv_resume_item_meta {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_resume_item .trx_addons_cv_resume_item_title,
.trx_addons_cv_resume_item .trx_addons_cv_resume_item_title a {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_resume_item_subtitle {
	color: {$colors['alter_light']};
}
.trx_addons_cv_resume_style_skills .trx_addons_cv_resume_item_skills {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_resume_style_skills .trx_addons_cv_resume_item_skill:after {
	border-color: {$colors['alter_dark']};
}
.trx_addons_cv_resume_style_education .trx_addons_cv_resume_item_number {
	color: {$colors['alter_light']};
}
.trx_addons_cv_resume_style_services .trx_addons_cv_resume_item_icon {
	color: {$colors['alter_dark']};
}
.trx_addons_cv_resume_style_services .trx_addons_cv_resume_item_icon:hover,
.trx_addons_cv_resume_style_services .trx_addons_cv_resume_item_text a:hover {
	color: {$colors['text_hover']};
}
.trx_addons_cv_resume_style_services .trx_addons_cv_resume_item_title > a:hover:after {
	border-color: {$colors['text_hover']};
}
.trx_addons_cv_resume_style_services .trx_addons_cv_resume_item_title > a:after {
	border-top-color: {$colors['alter_dark']};
}
.trx_addons_cv_resume_style_services .trx_addons_cv_resume_item_text a {
	color: {$colors['alter_dark']};
}

.trx_addons_cv_portfolio_item .trx_addons_cv_portfolio_item_title,
.trx_addons_cv_portfolio_item .trx_addons_cv_portfolio_item_title a {
	color: {$colors['alter_dark']};
}

.trx_addons_cv_testimonials_item .trx_addons_cv_testimonials_item_title,
.trx_addons_cv_testimonials_item .trx_addons_cv_testimonials_item_title a {
	color: {$colors['alter_dark']};
}

.trx_addons_cv_certificates_item .trx_addons_cv_certificates_item_title,
.trx_addons_cv_certificates_item .trx_addons_cv_certificates_item_title a {
	color: {$colors['alter_dark']};
}

/* Contact form */
.trx_addons_cv .trx_addons_contact_form .trx_addons_contact_form_title {
	color: {$colors['alter_dark']};
}
.trx_addons_cv .trx_addons_contact_form_field_title {
	color: {$colors['alter_dark']};
}
.trx_addons_contact_form .trx_addons_contact_form_field input[type="text"],
.trx_addons_contact_form .trx_addons_contact_form_field textarea {
	border-color: {$colors['alter_bd_color']};
	color: {$colors['alter_text']};
}
.trx_addons_contact_form .trx_addons_contact_form_field input[type="text"]:focus,
.trx_addons_contact_form .trx_addons_contact_form_field textarea:focus {
	background-color: {$colors['alter_bg_hover']};
	color: {$colors['alter_dark']};
}
.trx_addons_contact_form_field button {
	background-color: {$colors['alter_dark']};
	border-color: {$colors['alter_dark']};
	color: {$colors['bg_color']};
}
.trx_addons_contact_form_field button:hover {
	color: {$colors['alter_dark']};
}
.trx_addons_contact_form_info_icon {
	color: {$colors['alter_light']};
}
.trx_addons_contact_form_info_area {
	color: {$colors['alter_dark']};
}
.trx_addons_contact_form_info_item_phone .trx_addons_contact_form_info_data {
	color: {$colors['alter_dark']} !important;
}

/* Page About Me */
.trx_addons_cv_about_page .trx_addons_cv_single_title {
	color: {$colors['alter_dark']};
}


/* WooCommerce Additional attributes for Variations */
.trx_addons_attrib_item.trx_addons_attrib_button,
.trx_addons_attrib_item.trx_addons_attrib_image,
.trx_addons_attrib_item.trx_addons_attrib_color {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.trx_addons_attrib_item.trx_addons_attrib_button:hover,
.trx_addons_attrib_item.trx_addons_attrib_image:hover,
.trx_addons_attrib_item.trx_addons_attrib_color:hover {
	border-color: {$colors['alter_bd_hover']};
	background-color: {$colors['alter_bg_hover']};
}
.trx_addons_attrib_item.trx_addons_attrib_selected {
	border-color: {$colors['alter_link']} !important;
	background-color: {$colors['alter_bg_hover']};
}
.trx_addons_attrib_item.trx_addons_attrib_disabled span:before,
.trx_addons_attrib_item.trx_addons_attrib_disabled span:after {
	background-color: {$colors['alter_hover']};
}
.esg-grid i[class*="eg-icon-"] {
    color: {$colors['text_dark']} !important;
}
.cq-tabs .cq-tabmenu.style1 a {
   	color: {$colors['text']};
	border-color: transparent;
	background-color: transparent;
}
.cq-tabs .cq-tabmenu.style1 .current a {
   	color: {$colors['text_dark']};
	border-color: {$colors['text_hover']};
	background-color: transparent;
}
.cq-tabs .cq-tabmenu.style1 li:hover a {
   	color: {$colors['text_dark']};
}
.sc_layouts_row_type_mid_panel .sc_item_button .sc_button {
   	color: {$colors['text_dark']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
.sc_layouts_row_type_mid_panel .sc_item_button .sc_button:hover {
   	color: {$colors['inverse_text']}!important;
	border-color: {$colors['text_dark']}!important;
	background-color: {$colors['text_dark']}!important;
}
.sc_layouts_row_type_bottom_panel .sc_layouts_menu_nav > li.sfHover > a {
   	color: {$colors['text']};
}
.sc_layouts_row_type_bottom_panel .sc_layouts_menu_nav > li > a:hover {
   	color: {$colors['text_hover']};
}
.sc_layouts_row_type_bottom_panel .sc_layouts_menu_nav > li.current-menu-item > a,
.sc_layouts_row_type_bottom_panel .sc_layouts_menu_nav > li.current-menu-parent > a,
.sc_layouts_row_type_bottom_panel .sc_layouts_menu_nav > li.current-menu-ancestor > a {
   	color: {$colors['text']};
}
.sc_layouts_row_type_bottom_panel .sc_layouts_menu_nav > li li > a {
   	color: {$colors['inverse_text']};
}
.post_item_none_search .search_wrap .search_submit, .post_item_none_archive .search_wrap .search_submit {
   	color: {$colors['text_dark']};
}

CSS;
		}

		return $css;
	}
}
?>