<?php
/**
 * ThemeREX Widgets
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Include files with widgets
if (!function_exists('trx_addons_widgets_load')) {
	add_action( 'after_setup_theme', 'trx_addons_widgets_load', 6 );
	add_action( 'trx_addons_action_save_options', 'trx_addons_widgets_load', 6 );
	function trx_addons_widgets_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		$trx_addons_widgets = apply_filters('trx_addons_widgets_list', array(
			'aboutme' => 1,
			'audio' => 1,
			'banner' => 1,
			'calendar' => 1,
			'categories_list' => 1,
			'contacts' => 1,
			'flickr' => 1,
			'popular_posts' => 1,
			'recent_news' => 1,
			'recent_posts' => 1,
			'slider' => 1,
			'socials' => 1,
			'twitter' => 1,
			'video' => 1
			)
		);
		if (is_array($trx_addons_widgets) && count($trx_addons_widgets) > 0) {
			foreach ($trx_addons_widgets as $w=>$need) {
				if ($need && ($fdir = trx_addons_get_file_dir("widgets/{$w}/{$w}.php")) != '') { include_once $fdir; }
			}
		}
	}
}
?>