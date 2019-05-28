<?php
/**
 * The Gallery template to display posts
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
$gravity_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($gravity_columns).' post_format_'.esc_attr($gravity_post_format) ); ?>
	<?php echo (!gravity_is_off($gravity_animation) ? ' data-animation="'.esc_attr(gravity_get_animation_classes($gravity_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($gravity_image[1]) && !empty($gravity_image[2])) echo intval($gravity_image[1]) .'x' . intval($gravity_image[2]); ?>"
	data-src="<?php if (!empty($gravity_image[0])) echo esc_url($gravity_image[0]); ?>"
	>

	<?php
	$gravity_image_hover = 'icon';
	if (in_array($gravity_image_hover, array('icons', 'zoom'))) $gravity_image_hover = 'dots';
	// Featured image
	gravity_show_post_featured(array(
		'hover' => $gravity_image_hover,
		'thumb_size' => gravity_get_thumb_size( strpos(gravity_get_theme_option('body_style'), 'full')!==false || $gravity_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. gravity_show_post_meta(array(
									'categories' => true,
									'date' => true,
									'edit' => false,
									'seo' => false,
									'share' => true,
									'counters' => 'comments',
									'echo' => false
									))
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'gravity') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>