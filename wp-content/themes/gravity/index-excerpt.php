<?php
/**
 * The template for homepage posts with "Excerpt" style
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

gravity_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	?><div class="posts_container"><?php
	
	$gravity_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$gravity_sticky_out = is_array($gravity_stickies) && count($gravity_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($gravity_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	while ( have_posts() ) { the_post(); 
		if ($gravity_sticky_out && !is_sticky()) {
			$gravity_sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', $gravity_sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
	}
	if ($gravity_sticky_out) {
		$gravity_sticky_out = false;
		?></div><?php
	}
	
	?></div><?php

	gravity_show_pagination();

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>