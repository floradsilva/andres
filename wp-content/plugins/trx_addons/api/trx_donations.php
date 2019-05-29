<?php
/**
 * Plugin support: ThemeREX Donations
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

// Check if plugin installed and activated
if ( !function_exists( 'trx_addons_exists_trx_donations' ) ) {
	function trx_addons_exists_trx_donations() {
		return class_exists('TRX_DONATIONS');
	}
}

// Return true, if current page is any trx_donations page
if ( !function_exists( 'trx_addons_is_trx_donations_page' ) ) {
	function trx_addons_is_trx_donations_page() {
		$rez = false;
		if (trx_addons_exists_trx_donations()) {
			$rez = (is_single() && get_query_var('post_type') == TRX_DONATIONS::POST_TYPE) 
					|| is_post_type_archive(TRX_DONATIONS::POST_TYPE) 
					|| is_tax(TRX_DONATIONS::TAXONOMY);
		}
		return $rez;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'trx_addons_trx_donations_post_type_taxonomy' ) ) {
	add_filter( 'trx_addons_filter_post_type_taxonomy',	'trx_addons_trx_donations_post_type_taxonomy', 10, 2 );
	function trx_addons_trx_donations_post_type_taxonomy($tax='', $post_type='') {
		if (trx_addons_exists_trx_donations() && $post_type == TRX_DONATIONS::POST_TYPE)
			$tax = TRX_DONATIONS::TAXONOMY;
		return $tax;
	}
}

// Return link to the all donations page for the breadcrumbs
if ( !function_exists( 'trx_addons_trx_donations_get_blog_all_posts_link' ) ) {
	add_filter('trx_addons_filter_get_blog_all_posts_link', 'trx_addons_trx_donations_get_blog_all_posts_link');
	function trx_addons_trx_donations_get_blog_all_posts_link($link='') {
		if ($link=='') {
			if (trx_addons_is_trx_donations_page() && !is_post_type_archive(TRX_DONATIONS::POST_TYPE))
				$link = '<a href="'.esc_url(get_post_type_archive_link( TRX_DONATIONS::POST_TYPE )).'">'.esc_html__('All Donations', 'trx_addons').'</a>';
		}
		return $link;
	}
}

// Return current page title
if ( !function_exists( 'trx_addons_trx_donations_get_blog_title' ) ) {
	add_filter( 'trx_addons_filter_get_blog_title', 'trx_addons_trx_donations_get_blog_title');
	function trx_addons_trx_donations_get_blog_title($title='') {
		if ( trx_addons_exists_trx_donations() && is_post_type_archive(TRX_DONATIONS::POST_TYPE) )
			$title = esc_html__('All Donations', 'trx_addons');
		return $title;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_trx_donations_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_trx_donations_importer_required_plugins', 10, 2 );
	function trx_addons_trx_donations_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'trx_donations')!==false && !trx_addons_exists_trx_donations() )
			$not_installed .= '<br>' . esc_html__('trx_donations', 'trx_addons');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_trx_donations_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_trx_donations_importer_set_options' );
	function trx_addons_trx_donations_importer_set_options($options=array()) {
		if ( trx_addons_exists_trx_donations() && in_array('trx_donations', $options['required_plugins']) ) {
			$options['additional_options'][] = 'trx_donations_options';
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'trx_addons_trx_donations_importer_check_row' ) ) {
	if (is_admin()) add_filter('trx_addons_filter_importer_import_row', 'trx_addons_trx_donations_importer_check_row', 9, 4);
	function trx_addons_trx_donations_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'trx_donations')===false) return $flag;
		if ( trx_addons_exists_trx_donations() ) {
			if ($table == 'posts')
				$flag = $row['post_type']==TRX_DONATIONS::POST_TYPE;
		}
		return $flag;
	}
}
?>