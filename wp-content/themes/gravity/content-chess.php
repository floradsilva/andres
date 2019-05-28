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
$gravity_columns = empty($gravity_blog_style[1]) ? 1 : max(1, $gravity_blog_style[1]);
$gravity_expanded = !gravity_sidebar_present() && gravity_is_on(gravity_get_theme_option('expand_content'));
$gravity_post_format = get_post_format();
$gravity_post_format = empty($gravity_post_format) ? 'standard' : str_replace('post-format-', '', $gravity_post_format);
$gravity_animation = gravity_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($gravity_columns).' post_format_'.esc_attr($gravity_post_format) ); ?>
	<?php echo (!gravity_is_off($gravity_animation) ? ' data-animation="'.esc_attr(gravity_get_animation_classes($gravity_animation)).'"' : ''); ?>
	>

	<?php
	// Add anchor
	if ($gravity_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.esc_attr(get_the_title()).'"]');
	}

	// Featured image
	gravity_show_post_featured( array(
											'class' => $gravity_columns == 1 ? 'trx-stretch-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => gravity_get_thumb_size(
																	strpos(gravity_get_theme_option('body_style'), 'full')!==false
																		? ( $gravity_columns > 1 ? 'huge' : 'original' )
																		: (	$gravity_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('gravity_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			
			do_action('gravity_action_before_post_meta'); 

			// Post meta
			$gravity_post_meta = gravity_show_post_meta(array(
									'categories' => false,
									'date' => true,
									'edit' => false,
									'seo' => false,
									'share' => false,
									'counters' => $gravity_columns < 3 ? 'comments' : '',
									'echo' => false
									)
								);
			gravity_show_layout($gravity_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$gravity_show_learn_more = !in_array($gravity_post_format, array('link', 'aside', 'status', 'quote'));
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
				gravity_show_layout($gravity_post_meta);
			}
			// More button
			if ( $gravity_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'gravity'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>