<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_sidebar_position = gravity_get_theme_option('sidebar_position');
if (gravity_sidebar_present()) {
	ob_start();
	$gravity_sidebar_name = gravity_get_theme_option('sidebar_widgets');
	gravity_storage_set('current_sidebar', 'sidebar');
    if ( is_active_sidebar( $gravity_sidebar_name ) ) {
        dynamic_sidebar( $gravity_sidebar_name );
    }
	$gravity_out = trim(ob_get_contents());
	ob_end_clean();
	if (trim(strip_tags($gravity_out)) != '') {
		?>
		<div class="sidebar <?php echo esc_attr($gravity_sidebar_position); ?> widget_area<?php if (!gravity_is_inherit(gravity_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(gravity_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'gravity_action_before_sidebar' );
				gravity_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $gravity_out));
				do_action( 'gravity_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>