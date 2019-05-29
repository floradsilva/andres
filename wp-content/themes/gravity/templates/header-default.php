<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_header_css = $gravity_header_image = '';
$gravity_header_video = gravity_get_header_video();
if (true || empty($gravity_header_video)) {
	$gravity_header_image = get_header_image();
	if (gravity_is_on(gravity_get_theme_option('header_image_override')) && apply_filters('gravity_filter_allow_override_header_image', true)) {
		if (is_category()) {
			if (($gravity_cat_img = gravity_get_category_image()) != '')
				$gravity_header_image = $gravity_cat_img;
		} else if (is_singular() || gravity_storage_isset('blog_archive')) {
			if (has_post_thumbnail()) {
				$gravity_header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				if (is_array($gravity_header_image)) $gravity_header_image = $gravity_header_image[0];
			} else
				$gravity_header_image = '';
		}
	}
}

?><header class="top_panel top_panel_default<?php
					echo !empty($gravity_header_image) || !empty($gravity_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($gravity_header_video!='') echo ' with_bg_video';
					if ($gravity_header_image!='') echo ' '.esc_attr(gravity_add_inline_style('background-image: url('.esc_url($gravity_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (gravity_is_on(gravity_get_theme_option('header_fullheight'))) echo ' header_fullheight trx-stretch-height';
					?> scheme_<?php echo esc_attr(gravity_is_inherit(gravity_get_theme_option('header_scheme')) 
													? gravity_get_theme_option('color_scheme') 
													: gravity_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($gravity_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (gravity_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Header for single posts
	get_template_part( 'templates/header-single' );

?></header>