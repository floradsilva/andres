<?php
/**
 * The style "default" of the Services
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4
 */

$args = get_query_var('trx_addons_args_sc_services');
$query_args = array(
	'suppress_filters' => true,
	'post_type' => TRX_ADDONS_CPT_SERVICES_PT,
	'post_status' => 'publish',
	'ignore_sticky_posts' => true
);
if (empty($args['ids'])) {
	$query_args['posts_per_page'] = $args['count'];
	$query_args['offset'] = $args['offset'];
}
$query_args = trx_addons_query_add_sort_order($query_args, $args['orderby'], $args['order']);
$query_args = trx_addons_query_add_posts_and_cats($query_args, $args['ids'], TRX_ADDONS_CPT_SERVICES_PT, $args['cat'], TRX_ADDONS_CPT_SERVICES_TAXONOMY);
$query = new WP_Query( $query_args );
if ($query->found_posts > 0) {
	if ($args['count'] > $query->found_posts) $args['count'] = $query->found_posts;
	if ($args['columns'] < 1) $args['columns'] = $args['count'];
	$args['columns'] = max(1, min(12, (int) $args['columns']));
	$args['slider'] = $args['slider'] > 0 && $args['count'] > $args['columns'];
	$args['slides_space'] = max(0, (int) $args['slides_space']);
	?><div class="sc_services sc_services_<?php 
			echo esc_attr($args['type']);
			if ($args['slider']) echo ' swiper-slider-container slider_swiper slider_noresize slider_nocontrols '.($args['slider_pagination'] > 0 ? 'slider_pagination' : 'slider_nopagination');
			if (!empty($args['class'])) echo ' '.esc_attr($args['class']); 
			?>"<?php
			echo (trim($args['columns'] > 1
					? ' data-slides-per-view="' . esc_attr($args['columns']) . '"' 
					: '')
				. ($args['slides_space'] > 0 
					? ' data-slides-space="' . esc_attr($args['slides_space']) . '"' 
					: '')
				. ' data-slides-min-width="' . ($args['type']=='iconed' ? 250 : 200) . '"');
				?>
		>
		<?php
		trx_addons_sc_show_titles('sc_services', $args);
		
		if ($args['slider']) {
			?><div class="sc_services_slider sc_item_slider slides swiper-wrapper sc_item_columns_<?php echo esc_attr($args['columns']); ?>"><?php
		} else if ($args['columns'] > 1) {
			?><div class="sc_services_columns sc_item_columns sc_item_columns_<?php
							echo esc_attr($args['columns']);
							echo ' '.esc_attr(trx_addons_get_columns_wrap_class()) . ($args['type']!='list' ? ' columns_padding_bottom' : '');
							if ($args['no_margin']==1) echo ' no_margin';
						?>"<?php							if ($args['large_button']==1) echo ' data-equal-height=".trx_addons_column-1_'.esc_attr($args['columns']).'"';
        ?>><?php
		} else {
			?><div class="sc_services_content sc_item_content sc_item_columns_1"><?php
		}

		set_query_var('trx_addons_args_sc_services', $args);
		$trx_addons_number = $args['offset'];
		while ( $query->have_posts() ) { $query->the_post();
			$trx_addons_number++;
			set_query_var('trx_addons_args_item_number', $trx_addons_number);
			if (($fdir = trx_addons_get_file_dir('cpt/services/tpl.'.trx_addons_esc($args['type']).'-item.php')) != '') { include $fdir; }
			else if (($fdir = trx_addons_get_file_dir('cpt/services/tpl.default-item.php')) != '') { include $fdir; }
		}
    if ($args['large_button'] == 1 && $args['columns'] > 1) {
        ?><div class="trx_addons_column-1_<?php
        echo esc_attr($args['columns']);
        ?>"><?php
        if (!empty($args['link']) && !empty($args['link_text'])) {
            ?><a class = "sc_services_large_button " href="<?php echo esc_url($args['link']); ?>"><span><?php
                echo esc_html($args['link_text']);
            ?></span></a><?php
    }

        ?></div><?php
    }


		wp_reset_postdata();
	
		?></div><?php

		if ($args['slider'] > 0 && $args['slider_pagination'] > 0) {
			?><div class="slider_pagination_wrap swiper-pagination"></div><?php
		}
		
		if ($args['large_button'] != 1) trx_addons_sc_show_links('sc_services', $args);



	?></div><!-- /.sc_services --><?php
}
?>