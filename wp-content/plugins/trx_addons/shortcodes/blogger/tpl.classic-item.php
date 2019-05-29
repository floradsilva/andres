<?php
/**
 * The style "classic" of the Blogger
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_blogger');
$item_thumb_size = 'medium';

if ($args['slider']) {
	?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php
    if ($args['our_projects_style']!=1) {
	    echo esc_attr(trx_addons_get_column_class(1, $args['columns']));
    } else {
        $atts['flag']=floor($atts['count_number']/$args['columns'])%2==1?true:false;
        if ($atts['flag']) {
            if (floor($atts['count_number'])%2==1) {
                echo esc_attr(trx_addons_get_column_class(2, 3));
                $item_thumb_size = 'classic_wide';

            } else {
                echo esc_attr(trx_addons_get_column_class(1, 3));
                $item_thumb_size = 'classic';

            }
        } else {
            if (floor($atts['count_number'])%2==1) {
                echo esc_attr(trx_addons_get_column_class(1, 3));
                $item_thumb_size = 'classic';

            } else {
                echo esc_attr(trx_addons_get_column_class(2, 3));
                $item_thumb_size = 'classic_wide';

            }
        }



    } ?>"><?php
}

$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$post_link = get_permalink();
$post_title = get_the_title();

?><div id="post-<?php the_ID(); ?>"	<?php post_class( 'sc_blogger_item post_format_'.esc_attr($post_format).' sc_blogger_item_number_'.($atts['count_number']%2==1?'odd':'even').' sc_blogger_item_row_number_'.(floor($atts['count_number']/$args['columns'])%2==1?'odd':'even')); ?>><?php
//	// Featured image
//	set_query_var('trx_addons_args_featured', array(
//		'class' => 'sc_blogger_item_featured',
//		'hover' => 'zoomin',
//		'thumb_size' => $item_thumb_size
//	));
//	if (($fdir = trx_addons_get_file_dir('templates/tpl.featured.php')) != '') { include $fdir; }

if (function_exists('gravity_show_post_featured')) gravity_show_post_featured(array( 'thumb_size' => trx_addons_get_thumb_size($item_thumb_size) ));

	// Post content
	?><div class="sc_blogger_item_content entry-content"><?php
		// Post title
		if ( !in_array($post_format, array('link', 'aside', 'status', 'quote')) ) {
			?><div class="sc_blogger_item_header entry-header"><?php 
				// Post title
				the_title( sprintf( '<h4 class="sc_blogger_item_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				// Post meta
				trx_addons_sc_show_post_meta('sc_blogger', array(
					'categories' => true,
					)
				);
			?></div><!-- .entry-header --><?php
		}

		// Post content
		if (!isset($args['hide_excerpt']) || $args['hide_excerpt']==0) {
			?><div class="sc_blogger_item_excerpt">
				<div class="sc_blogger_item_excerpt_text">
					<?php
					$show_more = !in_array($post_format, array('link', 'aside', 'status', 'quote'));
					if (has_excerpt()) {
						the_excerpt();
					} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
						the_content( '' );
					} else if (!$show_more) {
						the_content();
					} else {
						the_excerpt();
					}
					?>
				</div>
				<?php
				// Post meta
				if (in_array($post_format, array('link', 'aside', 'status', 'quote'))) {
					trx_addons_sc_show_post_meta('sc_blogger', array(
						'date' => true
						)
					);
				}
				// More button
				if ( $show_more ) {
					?><div class="sc_blogger_item_button sc_item_button"><a href="<?php echo esc_url($post_link); ?>" class="sc_button sc_button_simple"><?php esc_html_e('Read more', 'trx_addons'); ?></a></div><?php
				}
			?></div><!-- .sc_blogger_item_excerpt --><?php
		}
		
	?></div><!-- .entry-content --><?php
	
?></div><!-- .sc_blogger_item --><?php

if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
?>