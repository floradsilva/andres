<?php
/**
 * The style "default" of the Blogger
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_blogger');

$query_args = array(
	'suppress_filters' => true,
	'post_type' => 'post',
	'post_status' => 'publish',
	'ignore_sticky_posts' => true
);
if (empty($args['ids'])) {
	$query_args['posts_per_page'] = $args['count'];
	$query_args['offset'] = $args['offset'];
}
$query_args = trx_addons_query_add_sort_order($query_args, $args['orderby'], $args['order']);
$query_args = trx_addons_query_add_posts_and_cats($query_args, $args['ids'], 'post', $args['cat'], 'category');
$query = new WP_Query( $query_args );
if ($query->found_posts > 0) {
	if ($args['count'] > $query->found_posts) $args['count'] = $query->found_posts;
	if ($args['columns'] < 1) $args['columns'] = $args['count'];
	//$args['columns'] = min($args['columns'], $args['count']);
	$args['columns'] = max(1, min(12, (int) $args['columns']));
	$args['slider'] = $args['slider'] > 0 && $args['count'] > $args['columns'];
	$args['slides_space'] = max(0, (int) $args['slides_space']);
	?><div class="sc_blogger sc_blogger_<?php
			echo esc_attr($args['type']);
    		if ($args['slider']) echo ' swiper-slider-container slider_swiper slider_noresize slider_nocontrols '.($args['slider_pagination'] > 0 ? 'slider_pagination' : 'slider_nopagination');
			if (!empty($args['class'])) echo ' '.esc_attr($args['class']);
            echo (isset($args['our_projects_style']) && $args['our_projects_style']== 1) ? ' our_projects_style' : '';
            ?>"<?php
			echo ($args['columns'] > 1 
						? ' data-slides-per-view="' . esc_attr($args['columns']) . '"' 
						: '')
				. ($args['slides_space'] > 0 
						? ' data-slides-space="' . esc_attr($args['slides_space']) . '"' 
						: '')
				. ' data-slides-min-width="' . ($args['type']=='default' ? 480 : 150) . '"';
				?>
		>
		<?php
		trx_addons_sc_show_titles('sc_blogger', $args);
		
		if ($args['slider']) {
			?><div class="sc_blogger_slider sc_item_slider slides swiper-wrapper"><?php
		} else if ($args['columns'] > 1) {
			?><div class="sc_blogger_columns sc_item_columns <?php echo esc_attr(trx_addons_get_columns_wrap_class()) . ($args['type']!='plain' ? ' columns_padding_bottom' : ''); ?>"><?php
		} else {
			?><div class="sc_blogger_content sc_item_content"><?php
		}	

		set_query_var('trx_addons_args_sc_blogger', $args);
			
		while ( $query->have_posts() ) { $query->the_post();
			if (($fdir = trx_addons_get_file_dir('shortcodes/blogger/tpl.'.trx_addons_esc($args['type']).'-item.php')) != '') { include $fdir; }
			else if (($fdir = trx_addons_get_file_dir('shortcodes/blogger/tpl.default-item.php')) != '') { include $fdir; }
            $atts['count_number']++;
            $atts['columns_count']++;

        }

		wp_reset_postdata();
	
		?></div><?php

		if ($args['slider'] > 0 && $args['slider_pagination'] > 0) {
			?><div class="slider_pagination_wrap swiper-pagination"></div><?php
		}
		
		trx_addons_sc_show_links('sc_blogger', $args);

	?></div><!-- /.sc_blogger --><?php
}
?>