<?php
/**
 * The Portfolio template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_blog_style = explode('_', gravity_get_theme_option('blog_style'));
$gravity_columns = empty($gravity_blog_style[1]) ? 2 : max(2, $gravity_blog_style[1]);
$gravity_post_format = get_post_format();
$gravity_post_format = empty($gravity_post_format) ? 'standard' : str_replace('post-format-', '', $gravity_post_format);
$gravity_animation = gravity_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($gravity_columns).' post_format_'.esc_attr($gravity_post_format) ); ?>
	<?php echo (!gravity_is_off($gravity_animation) ? ' data-animation="'.esc_attr(gravity_get_animation_classes($gravity_animation)).'"' : ''); ?>
	>

	<?php
	$gravity_image_hover = gravity_get_theme_option('image_hover');
	// Featured image
	gravity_show_post_featured(array(
		'thumb_size' => gravity_get_thumb_size(strpos(gravity_get_theme_option('body_style'), 'full')!==false || $gravity_columns < 3 ? 'masonry-big' : 'masonry'),
		'show_no_image' => true,
		'class' => $gravity_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $gravity_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>