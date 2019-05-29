<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the Visual Composer to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$gravity_content = '';
$gravity_blog_archive_mask = '%%CONTENT%%';
$gravity_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $gravity_blog_archive_mask);
if ( have_posts() ) {
	the_post(); 
	if (($gravity_content = apply_filters('the_content', get_the_content())) != '') {
		if (($gravity_pos = strpos($gravity_content, $gravity_blog_archive_mask)) !== false) {
			$gravity_content = preg_replace('/(\<p\>\s*)?'.$gravity_blog_archive_mask.'(\s*\<\/p\>)/i', $gravity_blog_archive_subst, $gravity_content);
		} else
			$gravity_content .= $gravity_blog_archive_subst;
		$gravity_content = explode($gravity_blog_archive_mask, $gravity_content);
	}
}

// Prepare args for a new query
$gravity_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$gravity_args = gravity_query_add_posts_and_cats($gravity_args, '', gravity_get_theme_option('post_type'), gravity_get_theme_option('parent_cat'));
$gravity_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($gravity_page_number > 1) {
	$gravity_args['paged'] = $gravity_page_number;
	$gravity_args['ignore_sticky_posts'] = true;
}
$gravity_ppp = gravity_get_theme_option('posts_per_page');
if ((int) $gravity_ppp != 0)
	$gravity_args['posts_per_page'] = (int) $gravity_ppp;
// Make a new query
query_posts( $gravity_args );
// Set a new query as main WP Query
$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];

// Set query vars in the new query!
if (is_array($gravity_content) && count($gravity_content) == 2) {
	set_query_var('blog_archive_start', $gravity_content[0]);
	set_query_var('blog_archive_end', $gravity_content[1]);
}

get_template_part('index');
?>