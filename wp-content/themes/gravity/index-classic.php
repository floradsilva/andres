<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

gravity_storage_set('blog_archive', true);

// Load scripts for 'Masonry' layout
if (substr(gravity_get_theme_option('blog_style'), 0, 7) == 'masonry') {
	wp_enqueue_script( 'classie', gravity_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
	wp_enqueue_script( 'imagesloaded', gravity_get_file_url('js/theme.gallery/imagesloaded.min.js'), array(), null, true );
	wp_enqueue_script( 'masonry', gravity_get_file_url('js/theme.gallery/masonry.min.js'), array(), null, true );
	wp_enqueue_script( 'gravity-gallery-script', gravity_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );
}

get_header();

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$gravity_classes = 'posts_container '
						. (substr(gravity_get_theme_option('blog_style'), 0, 7) == 'classic' ? 'columns_wrap' : 'masonry_wrap');
	$gravity_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$gravity_sticky_out = is_array($gravity_stickies) && count($gravity_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($gravity_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$gravity_sticky_out) {
		if (gravity_get_theme_option('first_post_large') && !is_paged() && !in_array(gravity_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( 'content', 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($gravity_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($gravity_sticky_out && !is_sticky()) {
			$gravity_sticky_out = false;
			?></div><div class="<?php echo esc_attr($gravity_classes); ?>"><?php
		}
		get_template_part( 'content', $gravity_sticky_out && is_sticky() ? 'sticky' : 'classic' );
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