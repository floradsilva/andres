<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$gravity_post_format = get_post_format();
$gravity_post_format = empty($gravity_post_format) ? 'standard' : str_replace('post-format-', '', $gravity_post_format);
$gravity_animation = gravity_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($gravity_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($gravity_post_format) ); ?>
	<?php echo (!gravity_is_off($gravity_animation) ? ' data-animation="'.esc_attr(gravity_get_animation_classes($gravity_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	gravity_show_post_featured(array(
		'thumb_size' => gravity_get_thumb_size($gravity_columns==1 ? 'big' : ($gravity_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($gravity_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h1 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );
			// Post meta
            gravity_show_post_meta(array(
                    'categories' => false,
                    'date' => true,
                    'edit' => false,
                    'seo' => false,
                    'share' => false,
                    'counters' => 'comments'	//comments,likes,views - comma separated in any combination
                )
            );
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>