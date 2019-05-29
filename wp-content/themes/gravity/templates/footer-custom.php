<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.10
 */

$gravity_footer_scheme =  gravity_is_inherit(gravity_get_theme_option('footer_scheme')) ? gravity_get_theme_option('color_scheme') : gravity_get_theme_option('footer_scheme');
$gravity_footer_id = str_replace('footer-custom-', '', gravity_get_theme_option("footer_style"));
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($gravity_footer_id); ?> scheme_<?php echo esc_attr($gravity_footer_scheme); ?>">
	<?php
    // Custom footer's layout
    do_action('gravity_action_show_layout', $gravity_footer_id);
	?>
</footer><!-- /.footer_wrap -->
