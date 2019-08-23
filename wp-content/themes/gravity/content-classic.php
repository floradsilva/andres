<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_blog_style = explode('_', gravity_get_theme_option('blog_style'));
$gravity_columns = empty($gravity_blog_style[1]) ? 2 : max(2, $gravity_blog_style[1]);
$gravity_expanded = !gravity_sidebar_present() && gravity_is_on(gravity_get_theme_option('expand_content'));
$gravity_post_format = get_post_format();
$gravity_post_format = empty($gravity_post_format) ? 'standard' : str_replace('post-format-', '', $gravity_post_format);
$gravity_animation = gravity_get_theme_option('blog_animation');

?><div class="<?php echo esc_html($gravity_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($gravity_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($gravity_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($gravity_columns)
					. ' post_layout_'.esc_attr($gravity_blog_style[0]) 
					. ' post_layout_'.esc_attr($gravity_blog_style[0]).'_'.esc_attr($gravity_columns)
					); ?>
	<?php echo (!gravity_is_off($gravity_animation) ? ' data-animation="'.esc_attr(gravity_get_animation_classes($gravity_animation)).'"' : ''); ?>
	>

	<?php

	// Featured image
	gravity_show_post_featured( array( 'thumb_size' => gravity_get_thumb_size($gravity_blog_style[0] == 'classic'
													? (strpos(gravity_get_theme_option('body_style'), 'full')!==false 
															? ( $gravity_columns > 2 ? 'big' : 'huge' )
															: (	$gravity_columns > 2
																? ($gravity_expanded ? 'med' : 'small')
																: ($gravity_expanded ? 'big' : 'med')
																)
														)
													: (strpos(gravity_get_theme_option('body_style'), 'full')!==false 
															? ( $gravity_columns > 2 ? 'masonry-big' : 'full' )
															: (	$gravity_columns <= 2 && $gravity_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	if ( !in_array($gravity_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('gravity_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('gravity_action_before_post_meta'); 

			// Post meta
			gravity_show_post_meta(array(
				'categories' => false,
				'date' => true,
				'edit' => false,
				'seo' => false,
				'share' => false,
				'counters' => 'comments',
				)
			);
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$gravity_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($gravity_post_format, array('link', 'aside', 'status', 'quote'))) {
				the_content();
			} else if (substr(get_the_content(), 0, 1)!='[') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($gravity_post_format, array('link', 'aside', 'status', 'quote'))) {
			gravity_show_post_meta(array(
				'share' => false,
				'counters' => 'comments'
				)
			);
		}
		// More button
		if ( $gravity_show_learn_more ) {
			?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'gravity'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>