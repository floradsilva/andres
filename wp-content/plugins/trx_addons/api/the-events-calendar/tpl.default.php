<?php
/**
 * The style "default" of the Tribe Events
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_events');

$query_args = array(
	'suppress_filters' => true,
	'post_type' => Tribe__Events__Main::POSTTYPE,
	'post_status' => 'publish',
	'ignore_sticky_posts' => true,
);
if (empty($args['ids'])) {
	$query_args['posts_per_page'] = $args['count'];
	$query_args['offset'] = $args['offset'];
	$query_args['meta_query'] = array(
		array(
			'key' => '_EventStartDate',
			'value' => date('Y-m-d'),
			'compare' => $args['past']==1 ? '<' : '>='
		)
	);
}
$query_args = trx_addons_query_add_sort_order($query_args, str_replace('post_date', 'event_date', $args['orderby']), $args['order']);
$query_args = trx_addons_query_add_posts_and_cats($query_args, $args['ids'], Tribe__Events__Main::POSTTYPE, $args['cat'], Tribe__Events__Main::TAXONOMY);
$query = new WP_Query( $query_args );
if ($query->found_posts > 0) {
	if ($args['count'] > $query->found_posts) $args['count'] = $query->found_posts;
	if ($args['columns'] < 1) $args['columns'] = $args['count'];
	//$args['columns'] = min($args['columns'], $args['count']);
	$args['columns'] = max(1, min(12, (int) $args['columns']));
	$args['slider'] = $args['slider'] > 0 && $args['count'] > $args['columns'];
	$args['slides_space'] = max(0, (int) $args['slides_space']);
	?><div class="sc_events sc_events_default<?php if ($args['slider']) echo ' swiper-slider-container slider_swiper slider_noresize slider_nocontrols '.($args['slider_pagination'] > 0 ? 'slider_pagination' : 'slider_nopagination'); ?>"<?php
			echo ($args['columns'] > 1 ? ' data-slides-per-view="' . esc_attr($args['columns']) . '"' : '')
				. ($args['slides_space'] > 0 ? ' data-slides-space="' . esc_attr($args['slides_space']) . '"' : '')
				. ' data-slides-min-width="150"';
				?>
		>
		<?php
		trx_addons_sc_show_titles('sc_events', $args);
		
		if ($args['slider']) {
			?><div class="sc_events_slider sc_item_slider slides swiper-wrapper"><?php
		} else if ($args['columns'] > 1) {
			?><div class="sc_events_columns sc_item_columns <?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?> columns_padding_bottom"><?php
		} else {
			?><div class="sc_events_content sc_item_content"><?php
		}	

		set_query_var('trx_addons_args_sc_events', $args);
			
		while ( $query->have_posts() ) { $query->the_post();
			if (($fdir = trx_addons_get_file_dir('api/the-events-calendar/tpl.default-item.php')) != '') { include $fdir; }
		}

		wp_reset_postdata();
	
		?></div><?php

		if ($args['slider'] > 0 && $args['slider_pagination'] > 0) {
			?><div class="slider_pagination_wrap swiper-pagination"></div><?php
		}
		
		trx_addons_sc_show_links('sc_events', $args);

	?></div><!-- /.sc_events --><?php
}
?>