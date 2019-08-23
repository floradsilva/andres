<?php
/**
 * The template to display the featured image in the single post
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

if ( get_query_var('gravity_header_image')=='' && is_singular() && has_post_thumbnail() && in_array(get_post_type(), array('post', 'page')) )  {
	$gravity_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
	if (!empty($gravity_src[0])) {
		gravity_sc_layouts_showed('featured', true);
		?><div class="sc_layouts_featured with_image <?php echo esc_attr(gravity_add_inline_style('background-image:url('.esc_url($gravity_src[0]).');')); ?>"></div><?php
	}
}
?>