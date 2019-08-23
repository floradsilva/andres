<?php
/**
 * ThemeREX Addons Custom post type: Layouts
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.06
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// -----------------------------------------------------------------
// -- Custom post type registration
// -----------------------------------------------------------------

// Define Custom post type and taxonomy constants
if ( ! defined('TRX_ADDONS_CPT_LAYOUTS_PT') ) define('TRX_ADDONS_CPT_LAYOUTS_PT', trx_addons_cpt_param('layouts', 'post_type'));
if ( ! defined('TRX_ADDONS_CPT_LAYOUTS_TAXONOMY') ) define('TRX_ADDONS_CPT_LAYOUTS_TAXONOMY', trx_addons_cpt_param('layouts', 'taxonomy'));


// Register post type and taxonomy (if need)
if (!function_exists('trx_addons_cpt_layouts_init')) {
	if (trx_addons_exists_visual_composer()) add_action( 'init', 'trx_addons_cpt_layouts_init' );
	function trx_addons_cpt_layouts_init() {

		// Add Layouts parameters to the Meta Box support
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['post_types'][] = TRX_ADDONS_CPT_LAYOUTS_PT;
		$TRX_ADDONS_STORAGE['meta_box_'.TRX_ADDONS_CPT_LAYOUTS_PT] = array(
			"layout_type" => array(
				"title" => __('Type',  'trx_addons'),
				"desc" => __("Type of this layout", 'trx_addons'),
				"std" => 'header',
				"options" => array(
					'header' => esc_html__('Header', 'trx_addons'),
					'footer' => esc_html__('Footer', 'trx_addons'),
					'custom' => esc_html__('Custom', 'trx_addons')
				),
				"type" => "select")
		);

		// Register post type and taxonomy
		register_post_type( TRX_ADDONS_CPT_LAYOUTS_PT, array(
			'label'               => esc_html__( 'Layout', 'trx_addons' ),
			'description'         => esc_html__( 'Layout Description', 'trx_addons' ),
			'labels'              => array(
				'name'                => esc_html__( 'Layouts', 'trx_addons' ),
				'singular_name'       => esc_html__( 'Layout', 'trx_addons' ),
				'menu_name'           => esc_html__( 'Layouts', 'trx_addons' ),
				'parent_item_colon'   => esc_html__( 'Parent Item:', 'trx_addons' ),
				'all_items'           => esc_html__( 'All Layouts', 'trx_addons' ),
				'view_item'           => esc_html__( 'View Layout', 'trx_addons' ),
				'add_new_item'        => esc_html__( 'Add New Layout', 'trx_addons' ),
				'add_new'             => esc_html__( 'Add New', 'trx_addons' ),
				'edit_item'           => esc_html__( 'Edit Layout', 'trx_addons' ),
				'update_item'         => esc_html__( 'Update Layout', 'trx_addons' ),
				'search_items'        => esc_html__( 'Search Layout', 'trx_addons' ),
				'not_found'           => esc_html__( 'Not found', 'trx_addons' ),
				'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'trx_addons' ),
			),
			'taxonomies'          => array(TRX_ADDONS_CPT_LAYOUTS_TAXONOMY),
			'supports'            => trx_addons_cpt_param('layouts', 'supports'),
			'public'              => false,
			'hierarchical'        => false,
			'has_archive'         => false,
			'can_export'          => true,
			'show_in_admin_bar'   => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => '51.0',
			'menu_icon'			  => 'dashicons-editor-kitchensink',
			'capability_type'     => 'post',
			'rewrite'             => array( 'slug' => trx_addons_cpt_param('layouts', 'post_type_slug') )
			)
		);
		
		register_taxonomy( TRX_ADDONS_CPT_LAYOUTS_TAXONOMY, TRX_ADDONS_CPT_LAYOUTS_PT, array(
			'post_type' 		=> TRX_ADDONS_CPT_LAYOUTS_PT,
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => esc_html__( 'Layouts Group', 'trx_addons' ),
				'singular_name'     => esc_html__( 'Group', 'trx_addons' ),
				'search_items'      => esc_html__( 'Search Groups', 'trx_addons' ),
				'all_items'         => esc_html__( 'All Groups', 'trx_addons' ),
				'parent_item'       => esc_html__( 'Parent Group', 'trx_addons' ),
				'parent_item_colon' => esc_html__( 'Parent Group:', 'trx_addons' ),
				'edit_item'         => esc_html__( 'Edit Group', 'trx_addons' ),
				'update_item'       => esc_html__( 'Update Group', 'trx_addons' ),
				'add_new_item'      => esc_html__( 'Add New Group', 'trx_addons' ),
				'new_item_name'     => esc_html__( 'New Group Name', 'trx_addons' ),
				'menu_name'         => esc_html__( 'Layout Group', 'trx_addons' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => trx_addons_cpt_param('layouts', 'taxonomy_slug') )
			)
		);
		
		// Add cpt_layouts to the VC Editor post_types
		if (function_exists('vc_editor_set_post_types')) {
			$list = vc_editor_post_types();
			if (!in_array(TRX_ADDONS_CPT_LAYOUTS_PT, $list)) {
				$list[] = TRX_ADDONS_CPT_LAYOUTS_PT;
				vc_editor_set_post_types($list);
			}
		}

		// Create theme specific layouts on first load or after activate VC
		if (is_admin() && get_option('trx_addons_cpt_layouts_created', false)===false) {
			trx_addons_cpt_layouts_create(true);
			update_option('trx_addons_cpt_layouts_created', 1);
		}
	}
}

// Return translated or original post ID
if ( !function_exists( 'trx_addons_cpt_layouts_get_translated_layout' ) ) {
	add_filter('trx_addons_filter_get_translated_layout', 'trx_addons_cpt_layouts_get_translated_layout');
	function trx_addons_cpt_layouts_get_translated_layout($id) {
		global $sitepress;
		if (is_object($sitepress)) {
			$trid         = $sitepress->get_element_trid( $id, 'post_'.TRX_ADDONS_CPT_LAYOUTS_PT );
			$translations = $sitepress->get_element_translations( $trid, 'post_'.TRX_ADDONS_CPT_LAYOUTS_PT );
			if (!empty($translations[ICL_LANGUAGE_CODE]))
				$id = $translations[ICL_LANGUAGE_CODE]->element_id;
		}
		return $id;
	}
}

// Add 'Layouts' parameters in the ThemeREX Addons Options
if (!function_exists('trx_addons_cpt_layouts_options')) {
	add_action( 'trx_addons_filter_options', 'trx_addons_cpt_layouts_options');
	function trx_addons_cpt_layouts_options($options) {

		trx_addons_array_insert_after($options, 'theme_specific_section', array(
			// Layouts settings
			'layouts_info' => array(
				"title" => esc_html__('Custom Layouts', 'trx_addons'),
				"desc" => wp_kses_data( __('Create theme-specific custom layouts (headers, footers, etc.)', 'trx_addons') ),
				"type" => "info"
			),
			'layouts_create' => array(
				"title" => esc_html__('Create Layouts', 'trx_addons'),
				"desc" => wp_kses_data( __('Press button above to create set of layouts, prepared with this theme. Attention! If a post with the same name exist - it is skipped!', 'trx_addons') ),
				"std" => 'trx_addons_cpt_layouts_create',
				"type" => "button"
			)
		));
		return $options;
	}
}

// Callback for the 'Create Layouts' button
if ( !function_exists( 'trx_addons_callback_ajax_trx_addons_cpt_layouts_create' ) ) {
	add_action('wp_ajax_trx_addons_cpt_layouts_create', 'trx_addons_callback_ajax_trx_addons_cpt_layouts_create');
	function trx_addons_callback_ajax_trx_addons_cpt_layouts_create() {
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		$response = array('error'=>'');
		trx_addons_cpt_layouts_create(true);
		
		echo json_encode($response);
		die();
	}
}

// Create theme-specific layouts
if (!function_exists('trx_addons_cpt_layouts_create')) {
	function trx_addons_cpt_layouts_create($check = true) {
		$layouts = apply_filters('trx_addons_filter_default_layouts', array());
		if (count($layouts) > 0) {
			// Add in the user's VC layouts
			$vc_layouts = get_option('wpb_js_templates');
			if (!is_array($vc_layouts)) $vc_layouts = array();
			update_option('wpb_js_templates', array_merge($vc_layouts, $layouts));
			// Create 'layouts' posts
			foreach($layouts as $layout) {
				if ($check && get_page_by_title($layout['name'], OBJECT, TRX_ADDONS_CPT_LAYOUTS_PT) != null) continue;
				$post_id = wp_insert_post(array(
					'post_title' => $layout['name'],
					'post_content' => $layout['template'],
					'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
					'post_status' => 'publish'
				));
				if ( !is_wp_error($post_id) ) {
					if (!empty($layout['meta']) && is_array($layout['meta'])) {
						foreach ($layout['meta'] as $k=>$v)
							update_post_meta($post_id, $k, apply_filters('trx_addons_filter_save_post_options', $v, $post_id, TRX_ADDONS_CPT_LAYOUTS_PT));
					}
				}
			}
		}
	}
}
	
// Add layouts's specific vars into JS storage
if ( !function_exists( 'trx_addons_cpt_layouts_localize_script' ) ) {
	add_filter("trx_addons_localize_script_admin", 'trx_addons_cpt_layouts_localize_script');
	function trx_addons_cpt_layouts_localize_script($vars) {
		return array_merge($vars, array(
			'msg_cpt_layouts_created' => esc_html__('Custom Layouts created successfully!', 'trx_addons')
		));
	}
}


// Save data from meta box
if (!function_exists('trx_addons_cpt_layouts_meta_box_save')) {
	add_filter('trx_addons_filter_save_post_options', 'trx_addons_cpt_layouts_meta_box_save', 10, 3);
	function trx_addons_cpt_layouts_meta_box_save($options, $post_id, $post_type) {
		if ($post_type == TRX_ADDONS_CPT_LAYOUTS_PT && is_array($options) && !empty($options['layout_type']))
			update_post_meta($post_id, 'trx_addons_layout_type', $options['layout_type']);
		return $options;
	}
}

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_cpt_layouts_load_scripts_front' ) ) {
	if (trx_addons_exists_visual_composer()) add_action("wp_enqueue_scripts", 'trx_addons_cpt_layouts_load_scripts_front');
	function trx_addons_cpt_layouts_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-cpt_layouts', trx_addons_get_file_url('cpt/layouts/layouts.css'), array(), null );
			wp_enqueue_script( 'trx_addons-cpt_layouts', trx_addons_get_file_url('cpt/layouts/layouts.js'), array('jquery'), null, true );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_cpt_layouts_merge_styles' ) ) {
	if (trx_addons_exists_visual_composer()) add_action("trx_addons_filter_merge_styles", 'trx_addons_cpt_layouts_merge_styles');
	function trx_addons_cpt_layouts_merge_styles($list) {
		$list[] = 'cpt/layouts/layouts.css';
		return $list;
	}
}

	
// Merge shortcode's specific scripts into single file
if ( !function_exists( 'trx_addons_cpt_layouts_merge_scripts' ) ) {
	if (trx_addons_exists_visual_composer()) add_action("trx_addons_filter_merge_scripts", 'trx_addons_cpt_layouts_merge_scripts');
	function trx_addons_cpt_layouts_merge_scripts($list) {
		$list[] = 'cpt/layouts/layouts.js';
		return $list;
	}
}



// Admin utils
// -----------------------------------------------------------------

// Create additional column in the posts list
if (!function_exists('trx_addons_cpt_layouts_add_custom_column')) {
	add_filter('manage_edit-'.trx_addons_cpt_param('layouts', 'post_type').'_columns',	'trx_addons_cpt_layouts_add_custom_column', 9);
	function trx_addons_cpt_layouts_add_custom_column( $columns ){
		if (is_array($columns) && count($columns)>0) {
			$new_columns = array();
			foreach($columns as $k=>$v) {
				$new_columns[$k] = $v;
				if ($k=='title') {
					$new_columns['cpt_layouts_image'] = esc_html__('Image', 'trx_addons');
				}
			}
			$columns = $new_columns;
		}
		return $columns;
	}
}

// Fill image column in the posts list
if (!function_exists('trx_addons_cpt_layouts_fill_custom_column')) {
	add_action('manage_'.trx_addons_cpt_param('layouts', 'post_type').'_posts_custom_column',	'trx_addons_cpt_layouts_fill_custom_column', 9, 2);
	function trx_addons_cpt_layouts_fill_custom_column($column_name='', $post_id=0) {
		if ($column_name == 'cpt_layouts_image') {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'tiny' );
			if (!empty($image[0])) {
				?><img class="trx_addons_cpt_layouts_image_preview" src="<?php echo esc_url($image[0]); ?>" alt=""<?php if (!empty($image[1])) echo ' width="'.intval($image[1]).'"'; ?><?php if (!empty($image[2])) echo ' height="'.intval($image[2]).'"'; ?>><?php
			}
		}
	}
}

// Show <select> with layouts categories in the admin filters area
if (!function_exists('trx_addons_cpt_layouts_admin_filters')) {
	add_action( 'restrict_manage_posts', 'trx_addons_cpt_layouts_admin_filters' );
	function trx_addons_cpt_layouts_admin_filters() {
		if (get_query_var('post_type') != TRX_ADDONS_CPT_LAYOUTS_PT) return;

		$tax = TRX_ADDONS_CPT_LAYOUTS_TAXONOMY;

		if ( !($terms = get_transient("trx_addons_terms_filter_".trim($tax)))) {
			$terms = get_terms($tax);
			set_transient("trx_addons_terms_filter_".trim($tax), $terms, 24*60*60);
		}

		$list = '';
		if (is_array($terms) && count($terms) > 0) {
			$tax_obj = get_taxonomy($tax);
			$list .= '<select name="'.esc_attr($tax).'" id="'.esc_attr($tax).'" class="postform">'
					.  "<option value=''>" . esc_html($tax_obj->labels->all_items) . "</option>";
			foreach ($terms as $term) {
				$list .= '<option value='. esc_attr($term->slug) . (isset($_REQUEST[$tax]) && $_REQUEST[$tax] == $term->slug || (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == $tax && isset($_REQUEST['term']) && $_REQUEST['term'] == $term->slug) ? ' selected="selected"' : '') . '>' . esc_html($term->name) . '</option>';
			}
			$list .=  "</select>";
		}
		echo trim($list);
	}
}
  
// Clear terms cache on the taxonomy save
if (!function_exists('trx_addons_cpt_layouts_admin_clear_cache')) {
	add_action( 'edited_'.TRX_ADDONS_CPT_LAYOUTS_TAXONOMY, 'trx_addons_cpt_layouts_admin_clear_cache', 10, 1 );
	add_action( 'delete_'.TRX_ADDONS_CPT_LAYOUTS_TAXONOMY, 'trx_addons_cpt_layouts_admin_clear_cache', 10, 1 );
	add_action( 'created_'.TRX_ADDONS_CPT_LAYOUTS_TAXONOMY, 'trx_addons_cpt_layouts_admin_clear_cache', 10, 1 );
	function trx_addons_cpt_layouts_admin_clear_cache( $term_id=0 ) {  
		// verify nonce
		$ok = true;
		if (!empty($_REQUEST['_wpnonce_add-tag'])) {
			check_admin_referer( 'add-tag', '_wpnonce_add-tag' );
		} else if (!empty($_REQUEST['_wpnonce']) && !empty($_REQUEST['tag_ID'])) {
			$tag_ID = (int) $_REQUEST['tag_ID'];
			if ($_POST['action'] == 'editedtag')
				check_admin_referer( 'update-tag_' . $tag_ID );
			else if ($_POST['action'] == 'delete-tag')
				check_admin_referer( 'delete-tag_' . $tag_ID );
			else if ($_POST['action'] == 'delete')
				check_admin_referer( 'bulk-tags' );
			else if ($_POST['action'] == 'bulk-delete')
				check_admin_referer( 'bulk-tags' );
			else
				$ok = false;
		} else
			$ok = false;
		if ($ok) 
			set_transient("trx_addons_terms_filter_".TRX_ADDONS_CPT_LAYOUTS_TAXONOMY, '', 24*60*60);
	}
}


// Show layout with specified ID
if ( !function_exists( 'trx_addons_cpt_layouts_show_layout' ) ) {
	add_action( 'trx_addons_action_show_layout', 'trx_addons_cpt_layouts_show_layout', 10, 1 );
	function trx_addons_cpt_layouts_show_layout($id) {
		global $TRX_ADDONS_STORAGE;
		$layout = get_post($id);
		if (!empty($layout)) {
			$TRX_ADDONS_STORAGE['inside_cpt_layouts'] = true;
			$content = shortcode_unautop(trim($layout->post_content));
			if (strpos($content, '[vc_row') !== false) {
				// In WordPress 4.9 post content wrapped with <p>...</p>
				// and shortcode_unautop() not remove it - do it manual
				if (strpos($content, '<p>[vc_row') !== false || strpos($content, '<p>[vc_section') !== false) {
					$content = str_replace(
						array('<p>[vc_row', '[/vc_row]</p>', '<p>[vc_section', '[/vc_section]</p>'),
						array('[vc_row', '[/vc_row]', '[vc_section', '[/vc_section]'),
						$content);
				}
				trx_addons_show_layout(do_shortcode($content));
			} else {
				trx_addons_show_layout(apply_filters('trx_addons_filter_sc_layout_content', $content, $layout->ID));
			}
			$TRX_ADDONS_STORAGE['inside_cpt_layouts'] = false;
			// Add VC custom styles to the inline CSS
			$vc_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
			if ( !empty( $vc_custom_css ) ) trx_addons_add_inline_css(strip_tags(trx_addons_remove_protocol($vc_custom_css)));
		}
	}
}

if (!function_exists('trx_addons_remove_protocol')) {
	function trx_addons_remove_protocol($url, $complete=false) {
		$url = preg_replace('/http[s]?:'.($complete ? '\\/\\/' : '').'/', '', $url);
		return $url;
	}
}

// Wrap shortcode's output into .sc_layouts_item if shortcode inside custom layout
if ( !function_exists( 'trx_addons_cpt_layouts_sc_wrap' ) ) {
	add_filter( 'trx_addons_sc_output', 'trx_addons_cpt_layouts_sc_wrap', 1000, 4 );
	function trx_addons_cpt_layouts_sc_wrap($output, $sc, $atts, $content) {
		global $TRX_ADDONS_STORAGE;
		return !empty($TRX_ADDONS_STORAGE['inside_cpt_layouts']) && !in_array($sc, array('trx_sc_layouts', 'trx_sc_content'))
					? '<div class="sc_layouts_item'.(strpos($output, 'hide_on_mobile')!==false && strpos($output, 'sc_layouts_menu_mobile_button')===false ? ' sc_layouts_hide_on_mobile' : '').'">' . trim($output) . '</div>'
					: $output;
	}
}


// trx_sc_layouts
//-------------------------------------------------------------
/*
[trx_sc_layouts id="unique_id" layout="layout_id"]
*/
if ( !function_exists( 'trx_addons_sc_layouts' ) ) {
	function trx_addons_sc_layouts($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_layouts', $atts, array(
			// Individual params
			"type" => "default",
			"layout" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);

		if (!empty($atts['layout'])) $atts['layout'] = apply_filters('trx_addons_filter_get_translated_layout', $atts['layout']);

		ob_start();
		set_query_var('trx_addons_args_sc_layouts', $atts);
		if (($fdir = trx_addons_get_file_dir('cpt/layouts/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		else if (($fdir = trx_addons_get_file_dir('cpt/layouts/tpl.default.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_layouts', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_layouts", "trx_addons_sc_layouts");
}


// Add [trx_sc_layouts] in the VC shortcodes list
if (!function_exists('trx_addons_sc_layouts_add_in_vc')) {
	function trx_addons_sc_layouts_add_in_vc() {

		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_layouts",
				"name" => esc_html__("Layouts", 'trx_addons'),
				"description" => wp_kses_data( __("Display previously created layout (header, footer, etc.)", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_layouts',
				"class" => "trx_sc_layouts",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Type", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcodes's type", 'trx_addons') ),
							"std" => "default",
							"value" => apply_filters('trx_addons_sc_type', array(
								esc_html__('Default', 'trx_addons') => 'default',
							), 'trx_sc_layouts' ),
							"type" => "dropdown"
						),
						array(
							"param_name" => "layout",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Layout", 'trx_addons') ),
							"value" => array_flip(trx_addons_get_list_posts(false, TRX_ADDONS_CPT_LAYOUTS_PT)),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_layouts' ) );
			
		class WPBakeryShortCode_Trx_Sc_Layouts extends WPBakeryShortCode {}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_layouts_add_in_vc', 20);
}


// Visual Composer support utilities
if ( trx_addons_exists_visual_composer() && ($fdir = trx_addons_get_file_dir("cpt/layouts/layouts_vc.php")) != '') { 
	$trx_addons_need = true;
	$trx_addons_wp_action = trx_addons_get_value_gp('action');
	if (is_admin() && get_option('trx_addons_action')=='' && !in_array($trx_addons_wp_action, array('ajax_search', 'vc_load_template_preview'))) {
		$trx_addons_need = strpos($_SERVER['REQUEST_URI'], 'post-new.php')!==false && trx_addons_get_value_gp('post_type')==TRX_ADDONS_CPT_LAYOUTS_PT;
		if (!$trx_addons_need && (
						(strpos($_SERVER['REQUEST_URI'], 'post.php')!==false && ($trx_addons_id = (int) trx_addons_get_value_gp('post')) > 0)
						||
						($trx_addons_wp_action=='vc_edit_form' && ($trx_addons_id = (int) trx_addons_get_value_gp('post_id')) > 0)
						)) {
			$trx_addons_post = get_post($trx_addons_id);
			$trx_addons_need = is_object($trx_addons_post) && $trx_addons_post->post_type == TRX_ADDONS_CPT_LAYOUTS_PT;
		}
	}
	if ($trx_addons_need) {	include_once $fdir; }
}


// One-click import support
//------------------------------------------------------------------------

// Export posts
if ( !function_exists( 'trx_addons_cpt_layouts_importer_export' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_export',	'trx_addons_cpt_layouts_importer_export', 10, 1 );
	function trx_addons_cpt_layouts_importer_export($importer) {
		$posts = get_posts( array(
								'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
								'post_status' => 'publish',
								'posts_per_page' => -1,
								'ignore_sticky_posts' => true,
								'orderby'	=> 'post_title',
								'order'		=> 'ASC'
								)
							);
		$output = '';
		if (is_array($posts) && count($posts) > 0) {
			$output = "<?php"
						. "\n//" . esc_html__('Custom Layouts', 'trx_addons')
						. "\n\$layouts = array(";
			$counter = 0;
			foreach ($posts as $post) {
				$vc_custom_css = get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
				$layout_type = get_post_meta( $post->ID, 'trx_addons_layout_type', true );
				$output .= ($counter++ ? ',' : '') 
						. "\n\t\t'" . trim($layout_type) . '_' . $post->ID . "' => array("
						. "\n\t\t\t\t'name' => '" . addslashes($post->post_title) . "',"
						. "\n\t\t\t\t'template' => '" . addslashes(str_replace(array("\x0D\x0A", "©"), array("\x0A", "&copy;"), $post->post_content)) . "',"
						. "\n\t\t\t\t'meta' => array("
						. "\n\t\t\t\t\t\t'trx_addons_options' => array("
						. "\n\t\t\t\t\t\t\t\t'layout_type' => '{$layout_type}'"
						. "\n\t\t\t\t\t\t\t\t)"
						. (empty($vc_custom_css) ? '' : ",\n\t\t\t\t\t\t'_wpb_shortcodes_custom_css' => '{$vc_custom_css}'")
						. "\n\t\t\t\t\t\t)"
						. "\n\t\t\t\t)";
			}
			$output .= "\n\t\t);"
						. "\n?>";
		}
		trx_addons_fpc(trx_addons_get_file_dir('importer/export/layouts.txt'), $output);
	}
}

// Display exported data in the fields
if ( !function_exists( 'trx_addons_cpt_layouts_importer_export_fields' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_export_fields',	'trx_addons_cpt_layouts_importer_export_fields', 10, 1 );
	function trx_addons_cpt_layouts_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug'	=> 'layouts',
			'title' => esc_html__('Custom Layouts', 'trx_addons'),
			'download' => 'trx_addons.layouts.php'
			)
		);
	}
}
?>