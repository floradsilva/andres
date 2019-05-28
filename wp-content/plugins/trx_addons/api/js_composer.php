<?php
/**
 * Plugin support: Visual Composer
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'trx_addons_exists_visual_composer' ) ) {
	function trx_addons_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'trx_addons_vc_is_frontend' ) ) {
	function trx_addons_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Add new param's option to the specified param
if ( !function_exists( 'trx_addons_vc_add_param_option' ) ) {
	function trx_addons_vc_add_param_option($params, $param_name, $option) {
		if (is_array($params)) {
			foreach($params as $k=>$v) {
				if (isset($v['param_name']) && $v['param_name']==$param_name) {
					$params[$k] = array_merge($v, $option);
					break;
				}
			}
		}
		return $params;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_vc_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_vc_importer_required_plugins', 10, 2 );
	function trx_addons_vc_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'js_composer')!==false && !trx_addons_exists_visual_composer())
			$not_installed .= '<br>' . esc_html__('Visual Composer', 'trx_addons');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_vc_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_vc_importer_set_options' );
	function trx_addons_vc_importer_set_options($options=array()) {
		if ( trx_addons_exists_visual_composer() && in_array('js_composer', $options['required_plugins']) ) {
			$options['additional_options'][] = 'wpb_js_templates';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'trx_addons_vc_importer_check_row' ) ) {
	if (is_admin()) add_filter('trx_addons_filter_importer_import_row', 'trx_addons_vc_importer_check_row', 9, 4);
	function trx_addons_vc_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'js_composer')===false) return $flag;
		if ( trx_addons_exists_visual_composer() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='vc_grid_item';
		}
		return $flag;
	}
}
?>