<?php
/**
 * The template to display the dishes archive
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.09
 */

get_header(); 

if (have_posts()) {

	do_action('trx_addons_action_start_archive');

	?><div class="sc_dishes sc_dishes_default">
		
		<div class="sc_dishes_columns_wrap <?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?> columns_padding_bottom"><?php

			$trx_addons_dishes_style   = explode('_', trx_addons_get_option('dishes_style'));
			$trx_addons_dishes_type    = $trx_addons_dishes_style[0];
			$trx_addons_dishes_columns = empty($trx_addons_dishes_style[1]) ? 1 : max(1, $trx_addons_dishes_style[1]);

			set_query_var('trx_addons_args_sc_dishes', array(
					'type' => $trx_addons_dishes_type,
					'columns' => $trx_addons_dishes_columns,
					'slider' => false
				)
			);
		
			while ( have_posts() ) { the_post(); 
				if (($fdir = trx_addons_get_file_dir('cpt/dishes/tpl.'.trim($trx_addons_dishes_type).'-item.php')) != '') { include $fdir; }
				else if (($fdir = trx_addons_get_file_dir('cpt/dishes/tpl.default-item.php')) != '') { include $fdir; }
			}
	
		?></div><!-- .trx_addons_dishes_columns_wrap --><?php

    ?></div><!-- .sc_dishes --><?php

	the_posts_pagination( array(
		'mid_size'  => 2,
		'prev_text' => esc_html__( '<', 'trx_addons' ),
		'next_text' => esc_html__( '>', 'trx_addons' ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'trx_addons' ) . ' </span>',
	) );

	do_action('trx_addons_action_end_archive');

} else {
	do_action('trx_addons_action_none_archive');
}

get_footer();
?>