<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.14
 */
$gravity_header_video = gravity_get_header_video();
if (!empty($gravity_header_video) && !gravity_is_from_uploads($gravity_header_video)) {
	global $wp_embed;
	if (is_object($wp_embed))
		$gravity_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($gravity_header_video) . '[/embed]' ));
	$gravity_embed_video = gravity_make_video_autoplay($gravity_embed_video);
	?><div id="background_video"><?php gravity_show_layout($gravity_embed_video); ?></div><?php
}
?>