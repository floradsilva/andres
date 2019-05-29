<?php
/**
 * The style "default" of the Widget "Video player"
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.10
 */

$args = get_query_var('trx_addons_args_widget_video');
extract($args);
		
// Before widget (defined by themes)
trx_addons_show_layout($before_widget);
			
// Widget title if one was input (before and after defined by themes)
trx_addons_show_layout($title, $before_title, $after_title);
	
// Widget body
?><div class="trx_addons_video_player <?php echo !empty($cover) ? 'with_cover hover_play' : 'without_cover'; ?>"><?php 

	if (!empty($link)) {
		global $wp_embed;
		if ( is_object($wp_embed) ) {
			$embed = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($link) . '[/embed]' ));
		}
	}
	if ( $cover ) {
		$embed = trx_addons_make_video_autoplay($embed);
		trx_addons_show_layout($cover);
		?><div class="video_mask"></div><div class="video_hover" data-video="<?php echo esc_attr($embed); ?>"></div><?php 
	}

	?><div class="video_embed video_frame"><?php 
		if ( !$cover ) {
			trx_addons_show_layout($embed);
		}
	?></div><?php

?></div><?php
	
// After widget (defined by themes)
trx_addons_show_layout($after_widget);
?>